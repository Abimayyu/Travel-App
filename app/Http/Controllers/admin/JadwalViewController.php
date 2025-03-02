<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Jadwal;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Illuminate\Http\Request;

class JadwalViewController extends Controller
{
    public function index()
    {
        return view('admin.jadwal.index');
    }
    public function create()
    {
        return view('admin.jadwal.form');
    }

    // Menampilkan halaman edit jadwal
    public function edit($id)
{
    $jadwal = Jadwal::findOrFail($id); 
    return view('admin.jadwal.form', compact('jadwal'));
}
}
