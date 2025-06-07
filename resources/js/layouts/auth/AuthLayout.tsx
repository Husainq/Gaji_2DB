import { AppSidebar } from '@/components/app-sidebar';

export default function AuthLayout({ children }: { children: React.ReactNode }) {
    return (
        <div className="flex min-h-screen bg-background text-foreground">
            <AppSidebar />
            <main className="flex-1 overflow-y-auto p-4">{children}</main>
        </div>
    );
}
