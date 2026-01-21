<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePenyiramanRequest;
use App\Models\Penyiraman;
use App\Models\Tanam;
use Illuminate\Support\Facades\DB;

class PenyiramanController extends Controller
{

    public function index()
    {
        $user = auth('api')->user();

        $penyiraman = Penyiraman::with('tanam.kebun')
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
                    'jam' => $item->jam,
                    'repeat' => $item->repeat,
                    'jumlah_air' => (float) $item->jumlah_air,
                    'created_at' => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'List penyiraman berhasil diambil',
            'data' => $penyiraman,
        ]);
    }

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

        $penyiraman = $tanam->penyiraman()
            ->orderBy('jam')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'jam' => $item->jam,
                    'repeat' => $item->repeat,
                    'jumlah_air' => (float) $item->jumlah_air,
                    'created_at' => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Jadwal penyiraman berhasil diambil',
            'data' => $penyiraman,
        ]);
    }

    public function store(StorePenyiramanRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $tanam = Tanam::with('kebun')
                ->where('id', $request->tanam_id)
                ->first();

            if (!$tanam) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Data tanaman tidak ditemukan',
                ], 404);
            }

            // 🔒 Validasi kepemilikan kebun
            if ($tanam->kebun->user_id !== $user->id) {
                return response()->json([
                    'status'  => false,
                    'message' => 'Anda tidak memiliki akses ke tanaman ini',
                ], 403);
            }

            // 💧 Hitung kebutuhan air (luas tanam x 23 liter)
            $jumlahAir = (float) $tanam->luas_tanam * 23;

            $penyiraman = Penyiraman::create([
                'tanam_id'   => $tanam->id,
                'jam'        => $request->jam,
                'repeat'     => $request->repeat,
                'jumlah_air' => $jumlahAir,
            ]);

            DB::commit();

            return response()->json([
                'status'  => true,
                'message' => 'Jadwal penyiraman berhasil ditambahkan',
                'data'    => [
                    'id'          => $penyiraman->id,
                    'tanam_id'    => $penyiraman->tanam_id,
                    'jam'         => $penyiraman->jam,
                    'repeat'      => $penyiraman->repeat,
                    'jumlah_air'  => (float) $penyiraman->jumlah_air,
                    'created_at'  => $penyiraman->created_at->toDateTimeString(),
                ],
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status'  => false,
                'message' => 'Gagal menambahkan jadwal penyiraman',
                'error'   => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $user = auth('api')->user();

            if (!$user) {
                return response()->json([
                    'status' => false,
                    'message' => 'Unauthenticated',
                ], 401);
            }

            $penyiraman = Penyiraman::with('tanam.kebun')
                ->where('id', $id)
                ->first();

            if (!$penyiraman) {
                return response()->json([
                    'status' => false,
                    'message' => 'Jadwal penyiraman tidak ditemukan',
                ], 404);
            }

            // 🔒 Validasi kepemilikan kebun
            if ($penyiraman->tanam->kebun->user_id !== $user->id) {
                return response()->json([
                    'status' => false,
                    'message' => 'Anda tidak memiliki akses ke jadwal ini',
                ], 403);
            }

            $penyiraman->delete();

            return response()->json([
                'status' => true,
                'message' => 'Jadwal penyiraman berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => 'Gagal menghapus jadwal penyiraman',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
