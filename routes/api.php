<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KebunController;
use App\Http\Controllers\Api\PanenController;
use App\Http\Controllers\Api\TanamController;
use App\Http\Controllers\Api\ProfileController;
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

    Route::get('/penyiraman', [PenyiramanController::class, 'index']);
    Route::get('/tanam/{tanam}/penyiraman', [PenyiramanController::class, 'byTanam']);
    Route::post('/penyiraman', [PenyiramanController::class, 'store']);
    Route::put('/penyiraman/{id}', [PenyiramanController::class, 'update']);
    Route::delete('/penyiraman/{id}', [PenyiramanController::class, 'destroy']);

    Route::get('/pemupukan', [PemupukanController::class, 'index']);
    Route::post('/pemupukan', [PemupukanController::class, 'store'])->middleware('auth:api');
    Route::get('/tanam/{tanam}/pemupukan', [PemupukanController::class, 'byTanam'])->middleware('auth:api');
    Route::delete('/pemupukan/{id}', [PemupukanController::class, 'destroy']);

    Route::get('/panen', [PanenController::class, 'index']);
    Route::post('/panen', [PanenController::class, 'store']);
    Route::delete('/panen/{id}', [PanenController::class, 'destroy']);

    Route::get('/profile', [ProfileController::class, 'show']);
    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'updatePassword']);
});
