import React from 'react';
import AuthLayout from '@/layouts/auth/AuthLayout';
import { Head } from '@inertiajs/react';

export default function GajiSaya() {
    return (
        <AuthLayout>
            <Head title="Gaji Anda" />
            <div className="p-6">
                <h1 className="text-2xl font-bold mb-4">Gaji Anda</h1>
                <p>Menampilkan data gaji pribadi.</p>
            </div>
        </AuthLayout>
    );
}
