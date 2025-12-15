<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\VerifyOtpRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if ($user->is_verified) {
                return response()->json([
                    'status' => false,
                    'message' => 'Email sudah terdaftar',
                ], 409);
            }

            $user->update([
                'otp_code' => $otp,
                'otp_expires_at' => $expiresAt,
            ]);
        } else {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'otp_code' => $otp,
                'otp_expires_at' => $expiresAt,
                'is_verified' => false,
            ]);
        }

        try {
            Mail::raw("Kode OTP Anda: $otp", function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('OTP Verification');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);
        }

        return response()->json([
            'status' => true,
            'message' => 'OTP berhasil dikirim ke email',
            'data' => [
                'user_id' => $user->id,
                'email' => $user->email,
                'otp_expires_at' => $user->otp_expires_at->toDateTimeString(),
            ],
        ], 200);
    }


    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        $user = User::where('email', $credentials['email'])->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan',
                'data' => null
            ], 422);
        }

        if (!$user->is_verified) {
            return response()->json([
                'status' => false,
                'message' => 'User belum terverifikasi. Silakan lakukan verifikasi OTP.',
                'data' => null
            ], 422);
        }

        if (!Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status' => false,
                'message' => 'Password salah',
                'data' => null
            ], 422);
        }

        $token = JWTAuth::fromUser($user);

        return response()->json([
            'status' => true,
            'message' => 'Login berhasil',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_verified' => $user->is_verified,
                ],
                'token' => $token
            ]
        ], 200);
    }


    public function verifyOtp(VerifyOtpRequest $request)
    {
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'User tidak ditemukan',
                'data' => null
            ], 422);
        }

        if ($user->is_verified) {
            return response()->json([
                'status' => false,
                'message' => 'User sudah terverifikasi',
                'data' => null
            ], 422);
        }

        if ($user->otp_code !== (string) $request->otp) {
            return response()->json([
                'status' => false,
                'message' => 'OTP salah',
                'data' => null
            ], 422);
        }

        if (now()->gt($user->otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP sudah expired',
                'data' => null
            ], 422);
        }

        $user->is_verified = true;
        $user->otp_code = null;
        $user->otp_expires_at = null;
        $user->save();

        return response()->json([
            'status' => true,
            'message' => 'User verified',
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_verified' => $user->is_verified,
                ]
            ]
        ], 200);
    }
}
