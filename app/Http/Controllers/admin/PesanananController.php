<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PesanananController extends Controller
{
    public function index()
    {
        return view('admin.daftar.index');
    }
}
