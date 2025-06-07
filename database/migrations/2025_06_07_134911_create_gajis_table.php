<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gaji', function (Blueprint $table) {
            $table->id();
            // Karena FK beda DB, pakai unsignedBigInteger biasa tanpa constraint
            $table->unsignedBigInteger('karyawan_id'); 
            $table->integer('bulan'); 
            $table->integer('tahun'); 
            $table->string('golongan'); // Ambil dari karyawan.golongan
            $table->double('gaji_utama');
            $table->double('potongan_gaji');
            $table->double('total_gaji');
            $table->timestamps();

            // Index supaya pencarian lebih cepat
            $table->index(['karyawan_id', 'bulan', 'tahun']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gaji');
    }
};
