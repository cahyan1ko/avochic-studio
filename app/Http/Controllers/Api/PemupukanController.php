<?php

namespace App\Http\Controllers\Api;

use App\Models\Tanam;
use App\Models\Pemupukan;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePemupukanRequest;

class PemupukanController extends Controller
{
    /**
     * GET /pemupukan
     * List semua pemupukan milik user
     */
    public function index()
    {
        $user = auth('api')->user();

        $pemupukan = Pemupukan::with('tanam.kebun')
            ->whereHas('tanam.kebun', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->orderBy('jam')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanam_id' => $item->tanam_id,
                    'tanaman' => $item->tanam->jenis_tanaman ?? null,
                    'jenis_pupuk' => $item->jenis_pupuk,
                    'jam' => $item->jam,
                    'repeat' => $item->repeat,
                    'jumlah_pupuk' => (float) $item->jumlah_pupuk,
                    'created_at' => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'List pemupukan berhasil diambil',
            'data' => $pemupukan,
        ]);
    }

    /**
     * GET /tanam/{tanam}/pemupukan
     */
    public function byTanam(Tanam $tanam)
    {
        $user = auth('api')->user();

        // 🔒 validasi kepemilikan
        if ($tanam->kebun->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke tanaman ini',
            ], 403);
        }

        $pemupukan = $tanam->pemupukan()
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
    }

    /**
     * POST /pemupukan
     */
    public function store(StorePemupukanRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();

            $tanam = Tanam::with('kebun')
                ->where('id', $request->tanam_id)
                ->first();

            if (!$tanam) {
                return response()->json([
                    'status' => false,
                    'message' => 'Data tanaman tidak ditemukan',
                ], 404);
            }

            // 🔒 validasi kepemilikan
            if ($tanam->kebun->user_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke tanaman ini',
                ], 403);
            }

            // 🌱 contoh perhitungan pupuk
            $jumlahPupuk = (float) $tanam->luas_tanam * 2;

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
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
