<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\Presensi;

class GajiSayaController extends Controller
{
    public function index(Request $request)
    {
        $karyawan = auth()->user();

        $bulan = (int) $request->input('bulan', date('m'));
        $tahun = (int) $request->input('tahun', date('Y'));

        // Ambil semua presensi karyawan
        $semuaPresensi = Presensi::with(['jadwalKerja.shift'])
            ->where('karyawan_id', $karyawan->id)
            ->get();

        // Jika tidak ada data sama sekali, langsung return null
        if ($semuaPresensi->isEmpty()) {
            return Inertia::render('gajiSaya', [
                'gaji' => null,
                'selectedBulan' => $bulan,
                'selectedTahun' => $tahun,
            ]);
        }

        // Filter berdasarkan bulan & tahun
        $presensis = $semuaPresensi->filter(function ($item) use ($bulan, $tahun) {
            return Carbon::parse($item->tanggalPresensi)->month == $bulan &&
                   Carbon::parse($item->tanggalPresensi)->year == $tahun;
        });

        // Jika hasil filter kosong, juga return null
        if ($presensis->isEmpty()) {
            return Inertia::render('gajiSaya', [
                'gaji' => null,
                'selectedBulan' => $bulan,
                'selectedTahun' => $tahun,
            ]);
        }

        $hariKerja = $presensis->count();
        $hariHadir = $presensis->whereNotNull('waktuMasuk')->count();

        $potonganTidakPresensi = 0;
        $potonganTerlambat = 0;

        foreach ($presensis as $presensi) {
            $statusMasuk = $presensi->statusMasuk;

            if ($statusMasuk === 'Cuti') continue;

            if (!$presensi->waktuMasuk || $statusMasuk === 'Tidak Presensi Masuk') {
                $potonganTidakPresensi += 300000;
            } elseif ($statusMasuk === 'Terlambat' && $presensi->jadwalKerja && $presensi->jadwalKerja->shift) {
                $jamMasuk = Carbon::parse($presensi->waktuMasuk);
                $jamShift = Carbon::parse($presensi->jadwalKerja->shift->waktuMulai);
                if ($jamMasuk->gt($jamShift)) {
                    $selisihMenit = $jamMasuk->diffInMinutes($jamShift);
                    $potonganHariIni = ceil($selisihMenit / 10) * 50000;
                    $potonganTerlambat += min($potonganHariIni, 300000);
                }
            }
        }

        $totalPotongan = $potonganTidakPresensi + $potonganTerlambat;

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
                'potongan_terlambat' => $potonganTerlambat,
                'potongan_tidak_presensi' => $potonganTidakPresensi,
                'potongan_gaji' => $totalPotongan,
                'total_gaji' => $totalGaji,
                'hari_kerja' => $hariKerja,
                'hari_hadir' => $hariHadir,
            ],
            'selectedBulan' => $bulan,
            'selectedTahun' => $tahun,
        ]);
    }
}
