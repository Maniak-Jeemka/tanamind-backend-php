<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ScanController;

// Public routes — tidak perlu token
Route::prefix('auth')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login',    [AuthController::class, 'login']);
});

// Protected routes — wajib kirim Bearer Token
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user',         [AuthController::class, 'me']);

    // Scan
    Route::post('/scan',       [ScanController::class, 'store']);
    Route::get('/scan',        [ScanController::class, 'index']);
    Route::get('/scan/{id}',   [ScanController::class, 'show']);
});