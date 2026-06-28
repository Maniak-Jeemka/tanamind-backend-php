<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class MLService
{
    /**
     * Kirim gambar ke FastAPI untuk prediksi penyakit tanaman.
     *
     * @param string $imagePath  Absolute path ke file gambar di storage
     * @return array  ['disease', 'disease_confidence', 'severity', 'severity_confidence']
     * @throws Exception  Jika FastAPI tidak bisa dihubungi
     */
    public function predict(string $imagePath): array
    {
        // ====================================================================
        // DUMMY — hapus blok ini setelah FastAPI siap, lalu uncomment blok di bawah
        // ====================================================================
        return [
            'disease'             => 'bercak',
            'disease_confidence'  => 0.92,
            'severity'            => 'sedang',
            'severity_confidence' => 0.87,
        ];

        // ====================================================================
        // REAL — uncomment blok ini setelah FastAPI siap
        // ====================================================================
        // $url = rtrim(config('services.ml.url', env('ML_SERVICE_URL', 'http://localhost:8001')), '/');
        //
        // try {
        //     $response = Http::attach(
        //         'image',
        //         file_get_contents($imagePath),
        //         basename($imagePath)
        //     )->post("{$url}/predict");
        //
        //     if ($response->failed()) {
        //         throw new Exception('ML Service mengembalikan error: ' . $response->status());
        //     }
        //
        //     return $response->json();
        // } catch (\Illuminate\Http\Client\ConnectionException $e) {
        //     throw new Exception('ML Service tidak tersedia');
        // }
    }
}