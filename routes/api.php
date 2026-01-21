<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KebunController;
use App\Http\Controllers\Api\TanamController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\PemupukanController;
use App\Http\Controllers\Api\PenyiramanController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth.api')->group(function () {
    Route::get('/kebun', [KebunController::class, 'index']);
    Route::post('/kebun', [KebunController::class, 'store']);

    Route::post('/kebun/{kebun}/tanam', [TanamController::class, 'store']);

    Route::post('/penyiraman', [PenyiramanController::class, 'store']);
    Route::get('/penyiraman', [PenyiramanController::class, 'index']);

    Route::get('/tanam/{tanam}/penyiraman', [PenyiramanController::class, 'byTanam']);

    Route::post('/pemupukan', [PemupukanController::class, 'store'])
        ->middleware('auth:api');

    Route::get(
        '/tanam/{id}/pemupukan',
        [PemupukanController::class, 'index']
    )->middleware('auth:api');
});
