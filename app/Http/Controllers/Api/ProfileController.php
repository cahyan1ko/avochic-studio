<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use App\Models\User;

class ProfileController extends Controller
{
    /**
     * GET /api/profile
     * Ambil profil user yang sedang login
     */
    public function show()
    {
        $user = auth('api')->user();

        if (!$user) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthenticated',
            ], 401);
        }

        return response()->json([
            'status' => true,
            'message' => 'Profil berhasil diambil',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'is_verified' => (bool) $user->is_verified,
                'email_verified_at' => $user->email_verified_at,
                'created_at' => $user->created_at->toDateTimeString(),
            ],
        ]);
    }

    public function update(UpdateProfileRequest $request)
    {
        DB::beginTransaction();

        try {
            /** @var \App\Models\User $user */
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $otpRequired = false;
            $responseMessage = 'Profil berhasil diperbarui';
            $otpExpiresAt = null;

            // 🔹 update name
            if ($request->filled('name')) {
                $user->name = $request->name;
            }

            // 🔹 update password
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }

            // 🔹 update email → OTP
            if ($request->filled('email') && $request->email !== $user->email) {

                $user->email = $request->email;
                $user->save(); // penting sebelum kirim OTP

                $otpExpiresAt = $this->sendOtp($user);

                $otpRequired = true;
                $responseMessage = 'Email diperbarui. OTP berhasil dikirim ke email.';
            }

            $user->save();
            DB::commit();

            return response()->json([
                'status' => true,
                'message' => $responseMessage,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_verified' => (bool) $user->is_verified,
                    'otp_required' => $otpRequired,
                    'otp_expires_at' => $otpExpiresAt?->toDateTimeString(),
                ],
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal memperbarui profil',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function sendOtp(User $user)
    {
        $otp = rand(100000, 999999);
        $expiresAt = Carbon::now()->addMinutes(10);

        $user->update([
            'otp_code' => (string) $otp,
            'otp_expires_at' => $expiresAt,
            'is_verified' => false,
        ]);

        try {
            Mail::raw("Kode OTP Anda: $otp", function ($message) use ($user) {
                $message->to($user->email)
                    ->subject('OTP Verification');
            });
        } catch (\Exception $e) {
            Log::error('Failed to send OTP email', [
                'email' => $user->email,
                'error' => $e->getMessage(),
            ]);
        }

        return $expiresAt;
    }
}
