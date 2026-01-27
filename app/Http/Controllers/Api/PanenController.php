<?php

namespace App\Http\Controllers\Api;

use App\Models\Panen;
use App\Models\Tanam;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class PanenController extends Controller
{
    /**
     * GET /panen
     */
    public function index()
    {
        $user = auth('api')->user();

        $panen = Panen::with('tanam.kebun')
            ->whereHas(
                'tanam.kebun',
                fn($q) =>
                $q->where('user_id', $user->id)
            )
            ->orderByDesc('tanggal_panen')
            ->get()
            ->map(function ($item) {
                return [
                    'id' => $item->id,
                    'tanam_id' => $item->tanam_id,
                    'tanaman' => $item->tanam->jenis_tanaman ?? null,
                    'tanggal_panen' => $item->tanggal_panen,
                    'hasil_panen' => (float) $item->hasil_panen,
                    'satuan' => $item->satuan,
                    'catatan' => $item->catatan,
                    'created_at' => $item->created_at->toDateTimeString(),
                ];
            });

        return response()->json([
            'status' => true,
            'message' => 'Data panen berhasil diambil',
            'data' => $panen,
        ]);
    }

    /**
     * POST /panen
     */
    public function store(Request $request)
    {
        $request->validate([
            'tanam_id' => 'required|exists:tanam,id',
            'tanggal_panen' => 'required|date',
            'hasil_panen' => 'required|numeric|min:1'
        ]);

        try {
            DB::transaction(function () use ($request) {

                $tanam = Tanam::lockForUpdate()->findOrFail($request->tanam_id);

                if ($tanam->jumlah_tanaman < $request->hasil_panen) {
                    throw new \Exception('Jumlah panen melebihi jumlah tanaman');
                }

                Panen::create([
                    'tanam_id' => $tanam->id,
                    'tanggal_panen' => $request->tanggal_panen,
                    'hasil_panen' => $request->hasil_panen,
                    'satuan' => 'kg',
                    'catatan' => $request->catatan
                ]);

                $tanam->decrement('jumlah_tanaman', $request->hasil_panen);
            });

            return response()->json([
                'status' => true,
                'message' => 'Data panen berhasil ditambahkan'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }


    /**
     * DELETE /panen/{id}
     */
    public function destroy($id)
    {
        $user = auth('api')->user();

        $panen = Panen::with('tanam.kebun')->find($id);

        if (!$panen) {
            return response()->json([
                'status' => false,
                'message' => 'Data panen tidak ditemukan',
            ], 404);
        }

        if ($panen->tanam->kebun->user_id !== $user->id) {
            return response()->json([
                'status' => false,
                'message' => 'Anda tidak memiliki akses ke data panen ini',
            ], 403);
        }

        $panen->delete();

        return response()->json([
            'status' => true,
            'message' => 'Data panen berhasil dihapus',
        ]);
    }
}
