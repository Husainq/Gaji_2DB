import React, { useState } from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, router } from '@inertiajs/react';
import {
  Table, TableBody, TableCaption, TableCell, TableHead, TableHeader, TableRow,
} from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Label } from "@/components/ui/label";
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from "@/components/ui/select";

const breadcrumbs: BreadcrumbItem[] = [
  { title: 'Gaji Saya', href: '/gajiSaya' },
];

// ✅ Fungsi format bulan dalam Bahasa Indonesia
const bulanNama = (bulan: number) => {
  const formatter = new Intl.DateTimeFormat('id-ID', { month: 'long' });
  const date = new Date(2023, bulan - 1); // Tahun bebas, hanya ambil bulan
  return formatter.format(date);
};

// ✅ Fungsi format tanggal lengkap (hari, tanggal, bulan, tahun)
const formatTanggalIndonesia = (tanggal: string) => {
  const tgl = new Date(tanggal);
  return tgl.toLocaleDateString('id-ID', {
    weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
  });
};

export default function GajiSaya({ gaji, selectedBulan, selectedTahun }: any) {
  const [bulan, setBulan] = useState(String(selectedBulan || new Date().getMonth() + 1));
  const [tahun, setTahun] = useState(String(selectedTahun || new Date().getFullYear()));

  const handleFilter = (e: React.FormEvent) => {
    e.preventDefault();
    router.get('/gajiSaya', { bulan, tahun }, { preserveScroll: true });
  };

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Gaji Anda" />

      <div className="p-6 bg-white rounded-md shadow-sm dark:bg-gray-800">
        {/* Filter */}
        <form onSubmit={handleFilter} className="flex flex-wrap gap-4 mb-6">
          <div>
            <Label>Bulan</Label>
            <Select value={bulan} onValueChange={setBulan}>
              <SelectTrigger className="w-[150px]">
                <SelectValue placeholder="Pilih Bulan" />
              </SelectTrigger>
              <SelectContent>
                {[...Array(12)].map((_, i) => (
                  <SelectItem key={i + 1} value={String(i + 1)}>
                    {bulanNama(i + 1)}
                  </SelectItem>
                ))}
              </SelectContent>
            </Select>
          </div>
          <div>
            <Label>Tahun</Label>
            <Input
              type="number"
              className="w-[150px]"
              value={tahun}
              onChange={(e) => setTahun(e.target.value)}
              min="2000"
              max="2100"
            />
          </div>
          <div className="self-end">
            <Button type="submit">Filter</Button>
          </div>
        </form>

        {/* Tabel Gaji */}
        {gaji ? (
          <>
            <Table>
              <TableCaption>Gaji bulan {bulanNama(Number(gaji.bulan))} tahun {gaji.tahun}</TableCaption>
              <TableHeader>
                <TableRow>
                  <TableHead>Bulan</TableHead>
                  <TableHead>Tahun</TableHead>
                  <TableHead>Gaji Pokok</TableHead>
                  <TableHead>Potongan Keterlambatan</TableHead>
                  <TableHead>Potongan Tidak Hadir</TableHead>
                  <TableHead>Total Potongan Gaji</TableHead>
                  <TableHead>Total Gaji</TableHead>
                </TableRow>
              </TableHeader>
              <TableBody>
                <TableRow>
                  <TableCell>{bulanNama(Number(gaji.bulan))}</TableCell>
                  <TableCell>{gaji.tahun}</TableCell>
                  <TableCell>{`Rp${Number(gaji.gaji_pokok).toLocaleString('id-ID')}`}</TableCell>
                  <TableCell>{`Rp${Number(gaji.potongan_terlambat).toLocaleString('id-ID')}`}</TableCell>
                  <TableCell>{`Rp${Number(gaji.potongan_tidak_presensi).toLocaleString('id-ID')}`}</TableCell>
                  <TableCell>{`Rp${Number(gaji.potongan_gaji).toLocaleString('id-ID')}`}</TableCell>
                  <TableCell>{`Rp${Number(gaji.total_gaji).toLocaleString('id-ID')}`}</TableCell>
                </TableRow>
              </TableBody>
            </Table>

            {/* Info Hari Kerja dan Hadir */}
            <div className="mt-6 p-4 rounded-md bg-gray-100 dark:bg-gray-900 text-sm text-gray-800 dark:text-gray-200 space-y-1">
              <p><strong>Jumlah Hari Kerja:</strong> {gaji.hari_kerja} Hari</p>
              <p><strong>Jumlah Hadir:</strong> {gaji.hari_hadir} Hari</p>
            </div>

            {/* Tabel Detail Keterlambatan */}
            {Array.isArray(gaji.terlambat_detail) && gaji.terlambat_detail.length > 0 && (
              <div className="mt-6">
                <h2 className="text-base font-semibold mb-2 text-gray-700 dark:text-gray-100">
                  Detail Keterlambatan
                </h2>
                <Table>
                  <TableHeader>
                    <TableRow>
                      <TableHead>Tanggal</TableHead>
                      <TableHead>Jam Shift</TableHead>
                      <TableHead>Jam Masuk</TableHead>
                      <TableHead>Keterlambatan</TableHead>
                      <TableHead>Potongan</TableHead>
                    </TableRow>
                  </TableHeader>
                  <TableBody>
                    {gaji.terlambat_detail.map((item: any, index: number) => (
                      <TableRow key={index}>
                        <TableCell>{item.tanggal}</TableCell>
                        <TableCell>{item.jam_shift}</TableCell>
                        <TableCell>{item.jam_masuk}</TableCell>
                        <TableCell>{Math.ceil(Number(item.selisih_menit))} menit</TableCell>
                        <TableCell>{`Rp${Number(item.potongan).toLocaleString('id-ID')}`}</TableCell>
                      </TableRow>
                    ))}
                  </TableBody>
                </Table>
              </div>
            )}
          </>
        ) : (
          <p className="text-gray-600 dark:text-gray-300">
            Data gaji tidak ditemukan untuk bulan dan tahun ini.
          </p>
        )}
      </div>
    </AppLayout>
  );
}
