import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import {
  Table,
  TableBody,
  TableCaption,
  TableCell,
  TableHead,
  TableHeader,
  TableRow,
} from "@/components/ui/table";

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Gaji Saya',
    href: '/gajiSaya',
  },
];

export default function GajiSaya({ gaji }: { gaji: any }) {

  const bulanNama = (bulan: number) => {
    const bulanList = [
      'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
      'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];
    return bulanList[bulan - 1] || 'Invalid';
  };

  // Kalau data belum tersedia, tampil loading atau kosong
  if (!gaji) {
    return <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Gaji Anda" />
      <div className="p-6 bg-white rounded-md shadow-sm dark:bg-gray-800">
        <p>Data gaji tidak ditemukan.</p>
      </div>
    </AppLayout>;
  }

  return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Gaji Anda" />
      <div className="p-6 bg-white rounded-md shadow-sm dark:bg-gray-800">
        <Table>
          <TableCaption>Gaji bulan {bulanNama(Number(gaji.bulan))} tahun {gaji.tahun}</TableCaption>
          <TableHeader>
            <TableRow>
              <TableHead>Bulan</TableHead>
              <TableHead>Tahun</TableHead>
              <TableHead>Gaji Pokok</TableHead>
              <TableHead>Potongan Gaji</TableHead>
              <TableHead>Total Gaji</TableHead>
            </TableRow>
          </TableHeader>
          <TableBody>
            <TableRow>
              <TableCell>{bulanNama(Number(gaji.bulan))}</TableCell>
              <TableCell>{gaji.tahun}</TableCell>
              <TableCell>{`Rp${Number(gaji.gaji_pokok).toLocaleString('id-ID')}`}</TableCell>
              <TableCell>{`Rp${Number(gaji.potongan_gaji).toLocaleString('id-ID')}`}</TableCell>
              <TableCell>{`Rp${Number(gaji.total_gaji).toLocaleString('id-ID')}`}</TableCell>
            </TableRow>
          </TableBody>
        </Table>
      </div>
    </AppLayout>
  );
}
