<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalKerja extends Model
{
    use HasFactory;

    protected $connection = 'pgsql2'; 
    protected $table = 'jadwal_kerja';

    protected $fillable = [
        'karyawan_id',
        'shift_id',
        'tanggalKerja',
        'statusKerja',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class, 'shift_id');
    }

    public function presensi()
    {
        return $this->hasOne(Presensi::class, 'jadwal_kerja_id');
    }
}
