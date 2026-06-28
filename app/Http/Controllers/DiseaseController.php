<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Disease;

class DiseaseController extends Controller
{
    /**
     * Ambil semua data penyakit beserta rekomendasi.
     */
    public function index(Request $request)
    {
        $diseases = Disease::with('recommendations')->get();

        return response()->json([
            'status'  => 'success',
            'message' => 'Daftar penyakit berhasil diambil',
            'data'    => $diseases,
        ], 200);
    }
}
