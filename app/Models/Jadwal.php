<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jadwal extends Model
{
    use HasFactory;
    protected $table = "jadwal_tiket";
    protected $guarded = ["id"];

    public function pemesanan()
    {
        return $this->hasMany(Pemesanan::class, 'jadwal_tiket_id');
    }
    public function updateKuota($jumlah_tiket)
    {
        if ($this->kuota >= $jumlah_tiket) {
            $this->kuota -= $jumlah_tiket;
            $this->save();
            return true;
        }
        return false; 
    }
}
