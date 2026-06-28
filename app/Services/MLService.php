<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Exception;

class MLService
{
    public function predict(string $imagePath): array
    {
        // DUMMY — hapus blok ini setelah FastAPI siap
        return [
            "disease"             => "bercak",
            "disease_confidence"  => 0.92,
            "severity"            => "sedang",
            "severity_confidence" => 0.87,
        ];
    }
}