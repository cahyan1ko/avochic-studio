<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTanamRequest;
use App\Models\Kebun;
use Illuminate\Support\Facades\DB;

class TanamController extends Controller
{
    public function store(StoreTanamRequest $request, Kebun $kebun)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();

            // 🔒 Pastikan kebun milik user
            if ($kebun->user_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke kebun ini',
                ], 403);
            }

            if ($request->luas_tanam > $kebun->sisa_lahan) {
                return response()->json([
                    'status' => false,
                    'message' => 'Lahan tidak mencukupi',
                    'sisa_lahan' => $kebun->sisa_lahan,
                ], 422);
            }

            // 🌱 SIMPAN TANAM
            $tanam = $kebun->tanam()->create([
                'jenis_tanaman' => $request->jenis_tanaman,
                'jumlah_tanaman' => $request->jumlah_tanaman,
                'luas_tanam' => $request->luas_tanam,
                'tanggal_tanam' => $request->tanggal_tanam,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Data tanam berhasil ditambahkan',
                'data' => [
                    'tanam' => $tanam,
                    'sisa_lahan' => $kebun->fresh()->sisa_lahan,
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan data tanam',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
