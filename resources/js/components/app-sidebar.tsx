import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import {
    Sidebar,
    SidebarContent,
    SidebarFooter,
    SidebarHeader,
    SidebarMenu,
    SidebarMenuButton,
    SidebarMenuItem,
} from '@/components/ui/sidebar';
import { type NavItem } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import {
    LayoutGrid,
    FileIcon,
    Folder,
    Settings,
    LogOut,
    User,
    Banknote,
} from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Gaji Anda',
        href: '/gajiSaya',
        icon: Banknote,
    },
    {
        title: 'Dokumen Gaji',
        href: '/dokumen',
        icon: Folder,
    },
];

const footerNavItems: NavItem[] = [
    
];

export function AppSidebar() {
    const { url } = usePage();

    return (
        <Sidebar collapsible="icon" variant="inset">
            {/* Logo */}
            <SidebarHeader>
                <SidebarMenu>
                    <SidebarMenuItem>
                        <SidebarMenuButton size="lg" asChild>
                            <Link href="/dashboard" prefetch>
                                <AppLogo />
                            </Link>
                        </SidebarMenuButton>
                    </SidebarMenuItem>
                </SidebarMenu>
            </SidebarHeader>

            {/* Menu */}
            <SidebarContent>
                <div className="px-4 pt-4 text-xs font-semibold text-muted-foreground uppercase tracking-wide">
                    Menu 
                </div>
                <NavMain items={mainNavItems} />

                {/* Tambahan untuk Admin atau Section lainnya jika mau */}
                {/* <div className="px-4 mt-6 text-xs font-semibold text-muted-foreground uppercase tracking-wide">
                    Admin
                </div>
                <NavMain items={adminNavItems} /> */}
            </SidebarContent>

            {/* Footer */}
            <SidebarFooter>
                <NavFooter items={footerNavItems} className="mt-auto" />
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
