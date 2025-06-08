<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;

class GajiSayaController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = auth()->user();

        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil semua presensi milik karyawan bulan ini (termasuk cuti)
        $presensis = \App\Models\Presensi::with(['jadwalKerja.shift'])
            ->where('karyawan_id', $karyawan->id)
            ->whereYear('tanggalPresensi', $tahun)
            ->whereMonth('tanggalPresensi', $bulan)
            ->get();

        $hariKerja = $presensis->count(); // semua data presensi, termasuk cuti
        $hariHadir = $presensis->whereNotNull('waktuMasuk')->count();

        // Inisialisasi potongan
        $potonganTidakPresensi = 0;
        $potonganTerlambat = 0;

        foreach ($presensis as $presensi) {
            $statusMasuk = $presensi->statusMasuk;

            // Lewati jika status cuti, tidak kena potongan apa pun
            if ($statusMasuk === 'Cuti') {
                continue;
            }

            // Potongan karena tidak presensi masuk
            if (!$presensi->waktuMasuk || $statusMasuk === 'Tidak Presensi Masuk') {
                $potonganTidakPresensi += 300000;
            }
            // Potongan keterlambatan
            else if ($statusMasuk === 'Terlambat' && $presensi->jadwalKerja && $presensi->jadwalKerja->shift) {
                $jamMasuk = \Carbon\Carbon::parse($presensi->waktuMasuk);
                $jamShift = \Carbon\Carbon::parse($presensi->jadwalKerja->shift->waktuMulai);

                if ($jamMasuk->gt($jamShift)) {
                    $selisihMenit = $jamMasuk->diffInMinutes($jamShift);
                    $potonganHariIni = ceil($selisihMenit / 10) * 50000;
                    $potonganTerlambat += min($potonganHariIni, 300000); // maksimal 300 ribu per hari
                }
            }
            // Kalau 'Tepat Waktu' otomatis aman, tidak ada potongan
        }

        // Total potongan
        $totalPotongan = $potonganTidakPresensi + $potonganTerlambat;

        // Gaji Pokok berdasarkan golongan
        $golongan = strtolower($karyawan->golongan);
        $gajiPokok = match ($golongan) {
            'staff' => 3000000,
            'asisten' => 6000000,
            'kepala subbagian' => 10000000,
            'kepala bagian' => 15000000,
            'direksi' => 20000000,
            default => 0,
        };

        $totalGaji = max(0, $gajiPokok - $totalPotongan);

        return Inertia::render('gajiSaya', [
            'gaji' => [
                'bulan' => $bulan,
                'tahun' => $tahun,
                'gaji_pokok' => $gajiPokok,
                'potongan_gaji' => $totalPotongan,
                'total_gaji' => $totalGaji,
                'hari_kerja' => $hariKerja,
                'hari_hadir' => $hariHadir,
                'potongan_tidak_presensi' => $potonganTidakPresensi,
                'potongan_terlambat' => $potonganTerlambat,
            ],
        ]);
    }
}
