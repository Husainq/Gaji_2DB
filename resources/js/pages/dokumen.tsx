import React from 'react';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head } from '@inertiajs/react';

const breadcrumbs: BreadcrumbItem[] = [
  {
    title: 'Dokumen',
    href: '/dokumen',
  },
];

export default function Dokumen() {
     return (
    <AppLayout breadcrumbs={breadcrumbs}>
      <Head title="Gaji Anda" />
      <div className="p-6 bg-white rounded-md shadow-sm dark:bg-gray-800">
        
      </div>
    </AppLayout>
  );
}
