<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pemesanan extends Model
{
    use HasFactory;
    protected $table = "pemesanan";
    protected $guarded = ["id"];

    public function jadwal_tiket()
    {
        return $this->belongsTo(Jadwal::class, 'jadwal_tiket_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function pembayaran()
    {
        return $this->hasOne(Pembayaran::class, 'pemesanan_id');
    }
}
