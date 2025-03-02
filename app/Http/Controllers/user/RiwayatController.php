<?php

namespace App\Http\Controllers\user;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;

class RiwayatController extends Controller
{
    public function index()
    {
        return view('user.riwayat.index');
    }
    public function show($id)
    {
        // Cari data pemesanan beserta relasi yang diperlukan, jika tidak ditemukan akan menghasilkan 404
        $pemesanan = Pemesanan::with(['jadwal_tiket', 'user', 'pembayaran'])->findOrFail($id);

        // Kirim data ke view invoice
        return view('user.riwayat.invoice', compact('pemesanan'));
    }
}
