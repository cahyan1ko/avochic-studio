<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreKebunRequest;
use App\Models\Kebun;
use Illuminate\Support\Facades\DB;

class KebunController extends Controller
{
    public function index()
    {
        $user = auth('api')->user();

        $kebun = Kebun::with('tanam')
            ->where('user_id', $user->id)
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'lokasi' => $item->lokasi,
                    'luas_kebun' => (float) $item->luas_kebun,
                    'luas_tertanam' => (float) $item->tanam->sum('luas_tanam'),
                    'sisa_lahan' => (float) ($item->luas_kebun - $item->tanam->sum('luas_tanam')),
                    'catatan' => $item->catatan,
                    'created_at' => $item->created_at->toDateTimeString(),
                    'tanam' => $item->tanam
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'List kebun berhasil diambil',
            'data' => $kebun
        ]);
    }

    public function store(StoreKebunRequest $request)
    {
        DB::beginTransaction();

        try {
            $user = auth('api')->user();

            $kebun = Kebun::create([
                'user_id' => $user->id,
                'lokasi' => $request->lokasi,
                'luas_kebun' => $request->luas_kebun,
                'catatan' => $request->catatan,
            ]);

            if ($request->filled('tanam')) {
                $kebun->tanam()->create([
                    'jenis_tanaman' => $request->tanam['jenis_tanaman'],
                    'jumlah_tanaman' => $request->tanam['jumlah_tanaman'],
                    'luas_tanam'     => $request->tanam['luas_tanam'],
                    'tanggal_tanam' => $request->tanam['tanggal_tanam'],
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => true,
                'message' => 'Kebun berhasil ditambahkan',
                'data' => $kebun->load('tanam'),
            ], 201);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Gagal menambahkan kebun',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
