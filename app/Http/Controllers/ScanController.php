<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScanResult;
use App\Models\Disease;
use App\Services\MLService;

class ScanController extends Controller
{
    protected MLService $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        $path = $request->file('image')->store('scans', 'public');

        $prediction = $this->mlService->predict(storage_path('app/public/' . $path));

        $disease = Disease::where('slug', $prediction['disease'])->first();

        $scanResult = ScanResult::create([
            'user_id'              => Auth::id(),
            'disease_id'           => $disease?->id,
            'image_path'           => $path,
            'disease_label'        => $prediction['disease'],
            'disease_confidence'   => $prediction['disease_confidence'],
            'severity_label'       => $prediction['severity'],
            'severity_confidence'  => $prediction['severity_confidence'],
            'notes'                => $validated['notes'] ?? null,
            'is_shared'            => false,
        ]);

        $scanResult->load('disease.recommendations');

        return response()->json([
            'status'  => 'success',
            'message' => 'Scan berhasil',
            'data'    => $scanResult,
        ], 201);
    }
}