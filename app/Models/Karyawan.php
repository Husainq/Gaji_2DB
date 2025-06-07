<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Karyawan extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $connection = 'pgsql2'; // koneksi database khusus karyawan
    protected $table = 'karyawan';

    protected $fillable = [
        'nama',
        'username',
        'divisi',
        'golongan',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    public function gajis()
    {
        return $this->hasMany(Gaji::class, 'karyawan_id');
    }
}
