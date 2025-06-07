import React from 'react';
import AuthLayout from '@/layouts/auth/AuthLayout';
import { Head } from '@inertiajs/react';

export default function Dokumen() {
    return (
        <AuthLayout>
            <Head title="Dokumen Gaji" />
            <div className="p-6">
                <h1 className="text-2xl font-bold mb-4">Dokumen Gaji</h1>
                <p>Download slip gaji dan dokumen lainnya.</p>
            </div>
        </AuthLayout>
    );
}
