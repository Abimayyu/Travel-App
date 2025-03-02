<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Pemesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RiwayatController extends Controller
{
    public function index()
{
    $userId = Auth::id();
    $data = Pemesanan::with([
        'jadwal_tiket:id,tujuan,tanggal_keberangkatan,waktu_keberangkatan',
        'user:id,name',
        'pembayaran:id,status_pembayaran,pemesanan_id'
    ])->where('user_id', $userId)->get();

    return response()->json($data, \Symfony\Component\HttpFoundation\Response::HTTP_OK);
}
}
