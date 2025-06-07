<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gaji extends Model
{
    protected $connection = 'pgsql'; 
    protected $table = 'gaji';

    protected $fillable = [
        'karyawan_id',
        'bulan',
        'tahun',
        'golongan',
        'gaji_utama',
        'potongan_gaji',
        'total_gaji',
    ];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class, 'karyawan_id');
    }
}
