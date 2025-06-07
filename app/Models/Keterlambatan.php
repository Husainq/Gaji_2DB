<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Keterlambatan extends Model
{
    use HasFactory;

    protected $connection = 'pgsql2';
    protected $table = 'keterlambatan';
    protected $fillable = ['karyawan_id', 'presensi_id'];

    public function karyawan()
    {
        return $this->belongsTo(Karyawan::class);
    }

    public function presensi()
    {
        return $this->belongsTo(Presensi::class);
    }
}