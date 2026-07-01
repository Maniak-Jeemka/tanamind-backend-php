<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ScanResult;
use App\Models\Disease;
use Illuminate\Support\Facades\Storage;
use App\Services\MLService;

class ScanController extends Controller
{
    protected MLService $mlService;

    public function __construct(MLService $mlService)
    {
        $this->mlService = $mlService;
    }

    /**
     * Upload gambar dan jalankan prediksi ML.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'image' => 'required|file|mimes:jpg,jpeg,png|max:5120',
            'notes' => 'nullable|string|max:500',
        ]);

        $path = $request->file('image')->store('scans', 'public');

        $prediction = $this->mlService->predict(storage_path('app/public/' . $path));

        // Cari disease berdasarkan slug, null jika "healthy" atau tidak ditemukan
        $disease = null;
        if ($prediction['disease'] !== 'healthy') {
            $disease = Disease::where('slug', $prediction['disease'])->first();
        }

        $scanResult = ScanResult::create([
            'user_id'             => Auth::id(),
            'disease_id'          => $disease?->id,
            'image_path'          => $path,
            'disease_label'       => $prediction['disease'],
            'disease_confidence'  => $prediction['disease_confidence'],
            'severity_label'      => $prediction['severity'],
            'severity_confidence' => $prediction['severity_confidence'],
            'notes'               => $validated['notes'] ?? null,
            'is_shared'           => false,
        ]);

        $scanResult->load('disease.recommendations');

        return response()->json([
            'status'  => 'success',
            'message' => 'Scan berhasil',
            'data'    => $scanResult,
        ], 201);
    }

    /**
     * Ambil semua riwayat scan milik user yang login.
     */
    public function index(Request $request)
    {
        $scans = ScanResult::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->with('disease')
            ->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Riwayat scan berhasil diambil',
            'data'    => $scans,
        ], 200);
    }

    /**
     * Lihat detail scan berdasarkan ID (hanya milik user yang login).
     */
    public function show(Request $request, $id)
    {
        $scanResult = ScanResult::where('id', $id)
            ->where('user_id', Auth::id())
            ->with('disease.recommendations')
            ->first();

        if (!$scanResult) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Scan tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        return response()->json([
            'status'  => 'success',
            'message' => 'Detail scan berhasil diambil',
            'data'    => $scanResult,
        ], 200);
    }

    /**
     * Hapus scan result milik user yang login.
     */
    public function destroy(Request $request, $id)
    {
        $scanResult = ScanResult::where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$scanResult) {
            return response()->json([
                'status'  => 'error',
                'message' => 'Scan tidak ditemukan',
                'data'    => null,
            ], 404);
        }

        // Hapus file gambar dari storage
        if ($scanResult->image_path) {
            Storage::disk('public')->delete($scanResult->image_path);
        }

        $scanResult->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Scan berhasil dihapus',
            'data'    => null,
        ], 200);
    }
}