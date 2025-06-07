<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Models\Gaji;
use App\Models\JadwalKerja;
use Illuminate\Http\Request;
use App\Models\Karyawan;

class GajiSayaController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = auth()->user();

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        $jadwalKerja = JadwalKerja::with(['presensi.keterlambatan'])
            ->where('karyawan_id', $karyawan->id)
            ->whereYear('tanggalKerja', $tahun)
            ->whereMonth('tanggalKerja', $bulan)
            ->get();

        $hariKerja = $jadwalKerja->where('statusKerja', '!=', 'cuti')->count();
        $hariHadir = $jadwalKerja->filter(fn($jadwal) => $jadwal->statusKerja != 'cuti' && $jadwal->presensi != null)->count();

        $potonganPerHari = 100000;
        $jumlahPotongan = max(0, ($hariKerja - $hariHadir) * $potonganPerHari);

        $potonganPerTerlambat = 50000;
        $jumlahTerlambat = $jadwalKerja->reduce(function ($carry, $jadwal) {
            return $carry + (($jadwal->presensi && $jadwal->presensi->keterlambatan) ? 1 : 0);
        }, 0);

        $potonganTerlambat = $jumlahTerlambat * $potonganPerTerlambat;
        $totalPotongan = $jumlahPotongan + $potonganTerlambat;

        // Cek apakah sudah ada data gaji di tabel
        $gajiRecord = Gaji::where('karyawan_id', $karyawan->id)
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->first();

        // Jika ada, gunakan nilai dari database
        if ($gajiRecord) {
            $gajiPokok = $gajiRecord->gaji_utama;
        } else {
            // Jika belum ada, tentukan berdasarkan golongan
            $golongan = strtolower($karyawan->golongan); // pastikan lowercase agar lebih aman

            switch ($golongan) {
                case 'staff':
                    $gajiPokok = 3000000;
                    break;
                case 'asisten':
                    $gajiPokok = 6000000;
                    break;
                case 'kepala subbagian':
                    $gajiPokok = 10000000;
                    break;
                case 'kepala bagian':
                    $gajiPokok = 15000000;
                    break;
                case 'direksi':
                    $gajiPokok = 20000000;
                    break;
                default:
                    $gajiPokok = 0; // fallback
                    break;
            }
        }

        $totalGaji = max(0, $gajiPokok - $totalPotongan);

        return Inertia::render('gajiSaya', [
            'gaji' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'gaji_pokok' => $gajiPokok,
                'potongan_gaji' => $totalPotongan,
                'total_gaji' => $totalGaji,
                'jumlah_terlambat' => $jumlahTerlambat,
            ],
        ]);
    }


}
