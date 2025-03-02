<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('jadwal_tiket', function (Blueprint $table) {
            $table->id();
            $table->string('tujuan'); // Tujuan travel
            $table->date('tanggal_keberangkatan'); // Tanggal keberangkatan
            $table->time('waktu_keberangkatan'); // Waktu keberangkatan
            $table->integer('kuota'); // Kuota penumpang
            $table->integer('jumlah_penumpang')->nullable();
            $table->decimal('harga_tiket', 10); // Harga tiket
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jadwal_tiket');
    }
};
