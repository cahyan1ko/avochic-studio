<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemupukanRequest;
use App\Models\Pemupukan;
use App\Models\Tanam;
use Illuminate\Support\Facades\DB;

class PemupukanController extends Controller
{

    public function index($id)
    {
        try {
            $user = auth('api')->user();

            $tanam = Tanam::with('kebun')
                ->where('id', $id)
                ->firstOrFail();

            // 🔒 validasi kepemilikan kebun
            if ($tanam->kebun->user_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke tanaman ini',
                ], 403);
            }

            $pemupukan = Pemupukan::where('tanam_id', $id)
                ->orderBy('jam')
                ->get()
                ->map(function ($item) {
                    return [
                        'id' => $item->id,
                        'jenis_pupuk' => $item->jenis_pupuk,
                        'jam' => $item->jam,
                        'repeat' => $item->repeat,
                        'jumlah_pupuk' => (float) $item->jumlah_pupuk,
                        'created_at' => $item->created_at->toDateTimeString(),
                    ];
                });

            return response()->json([
                'status' => true,
                'message' => 'Jadwal pemupukan berhasil diambil',
                'data' => $pemupukan,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal mengambil jadwal pemupukan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function store(StorePemupukanRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();

            $tanam = Tanam::with('kebun')
                ->where('id', $request->tanam_id)
                ->firstOrFail();

            // 🔒 validasi kepemilikan kebun
            if ($tanam->kebun->user_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke tanaman ini',
                ], 403);
            }

            // 🌱 contoh perhitungan pupuk (bebas kamu atur)
            $jumlahPupuk = (float) $tanam->luas_tanam * 2; // kg

            $pemupukan = Pemupukan::create([
                'tanam_id' => $tanam->id,
                'jenis_pupuk' => $request->jenis_pupuk,
                'jam' => $request->jam,
                'repeat' => $request->repeat,
                'jumlah_pupuk' => $jumlahPupuk,
            ]);

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Jadwal pemupukan berhasil ditambahkan',
                'data' => [
                    'id' => $pemupukan->id,
                    'tanam_id' => $pemupukan->tanam_id,
                    'jenis_pupuk' => $pemupukan->jenis_pupuk,
                    'jam' => $pemupukan->jam,
                    'repeat' => $pemupukan->repeat,
                    'jumlah_pupuk' => (float) $pemupukan->jumlah_pupuk,
                    'created_at' => $pemupukan->created_at->toDateTimeString(),
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan jadwal pemupukan',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
