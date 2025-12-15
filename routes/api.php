<?php

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\KebunController;
use App\Http\Controllers\Api\TanamController;
use Illuminate\Support\Facades\Route;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);

Route::middleware('auth.api')->group(function () {
    Route::get('/kebun', [KebunController::class, 'index']);
    Route::post('/kebun', [KebunController::class, 'store']);
    
    Route::post('/kebun/{kebun}/tanam', [TanamController::class, 'store']);
});
