<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use App\Models\Pembayaran;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Response;


class PesanController extends Controller
{
    public function index() {
        return response()->json(Pemesanan::with(['jadwal_tiket:id,tujuan,tanggal_keberangkatan,waktu_keberangkatan', 'user:id,name', 'pembayaran:id,status_pembayaran,pemesanan_id'])->get(),Response::HTTP_OK
        );
    }
    public function store($id)
    {
        $userId = Auth::id();

        $jadwal = Jadwal::find($id);
        if (!$jadwal) {
            return response()->json(['message' => 'Jadwal tidak ditemukan'], 404);
        }

        try {
            $pemesanan = Pemesanan::create([
                'jadwal_tiket_id' => $id,
                'user_id'         => $userId,
            ]);

            $jadwal->increment('jumlah_penumpang');

            return response()->json([
                'message'   => 'Tiket berhasil dipesan',
                'pemesanan' => $pemesanan
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat memproses pemesanan',
                'error'   => $e->getMessage()
            ], 500);
        }
    }
    public function updateStatusLunas($pemesananId)
    {
        // Cari data pemesanan berdasarkan ID
        $pemesanan = Pemesanan::find($pemesananId);
        if (!$pemesanan) {
            return response()->json([
                'message' => 'Pemesanan tidak ditemukan'
            ], Response::HTTP_NOT_FOUND);
        }

        // Karena belum ada data pembayaran, buat data baru dengan status "lunas"
        $pembayaran = Pembayaran::create([
            'pemesanan_id'     => $pemesananId,
            'status_pembayaran' => 'lunas'
        ]);

        return response()->json([
            'message'     => 'Pembayaran telah dibuat dan diset menjadi lunas',
            'pembayaran'  => $pembayaran
        ], Response::HTTP_OK);
    }
}


