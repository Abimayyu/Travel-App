<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use Illuminate\Http\Response;
use Illuminate\Http\Request;

class JadwalController extends Controller
{
    public function index()
    {
        return response()->json(Jadwal::all(), Response::HTTP_OK);
    }

    // Menyimpan jadwal tiket baru
    public function store(Request $request)
    {
        $request->validate([
            'tujuan' => 'required|string',
            'tanggal_keberangkatan' => 'required|date',
            'waktu_keberangkatan' => 'required',
            'kuota' => 'required|integer',
            'harga_tiket' => 'required|numeric'
        ]);

        $jadwal = Jadwal::create($request->all());
        return response()->json($jadwal, Response::HTTP_CREATED);
    }

    // Menampilkan detail jadwal tiket
    public function show($id)
    {
        // Mengambil data jadwal berdasarkan id, beserta relasi pemesanan, user, dan pembayaran
        $jadwal = Jadwal::with(['pemesanan.user', 'pemesanan.pembayaran'])
            ->where('id', $id)
            ->first();
    
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }
    
        $response = [
            'jadwal' => $jadwal->only([
                'id', 
                'tujuan', 
                'tanggal_keberangkatan', 
                'waktu_keberangkatan', 
                'kuota', 
                'harga_tiket'
            ])
        ];
    
        if ($jadwal->pemesanan->isNotEmpty()) {
            $response['pemesan'] = $jadwal->pemesanan->map(function ($pemesanan) {
                return [
                    'id'                => $pemesanan->id,
                    'nama_pemesan'      => $pemesanan->user->name ?? 'Guest',
                    'status_pembayaran' => $pemesanan->pembayaran->status_pembayaran ?? null,
                ];
            });
        }
    
        return response()->json($response, Response::HTTP_OK);
    }
    

    // Mengupdate jadwal tiket
    public function update(Request $request, $id)
    {
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }
        
        $request->validate([
            'tujuan' => 'sometimes|string',
            'tanggal_keberangkatan' => 'sometimes|date',
            'waktu_keberangkatan' => 'sometimes',
            'kuota' => 'sometimes|integer',
            'harga_tiket' => 'sometimes|numeric'
        ]);
        
        $jadwal->update($request->all());
        return response()->json($jadwal, Response::HTTP_OK);
    }

    // Menghapus jadwal tiket
    public function destroy($id)
    {
        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], Response::HTTP_NOT_FOUND);
        }
        
        $jadwal->delete();
        return response()->json(['message' => 'Jadwal berhasil dihapus'], Response::HTTP_OK);
    }
}
