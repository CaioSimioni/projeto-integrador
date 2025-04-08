import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import {
    CalendarDays,
    ClipboardPen,
    FileText,
    HeartPulse,
    LayoutGrid,
    ReceiptText,
    Shield,
    Stethoscope,
    UserRound,
    UsersRound,
    Warehouse,
} from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
        icon: LayoutGrid,
    },
    {
        title: 'Patients',
        href: '/patients',
        icon: UserRound,
    },
    {
        title: 'Appointments',
        href: '/appointments',
        icon: CalendarDays,
    },
    {
        title: 'Electronic Health Records',
        href: '',
        icon: FileText,
    },
    {
        title: 'Medical Staff',
        href: '',
        icon: UsersRound,
    },
    {
        title: 'Billing',
        href: '',
        icon: ReceiptText,
    },
    {
        title: 'Inventory & Pharmacy',
        href: '/inventory',
        icon: Warehouse,
    },
    {
        title: 'Exams & Lab Tests',
        href: '',
        icon: Stethoscope,
    },
    {
        title: 'Emergency & ICU',
        href: '',
        icon: HeartPulse,
    },
    {
        title: 'Reports & Analytics',
        href: '',
        icon: ClipboardPen,
    },
];

const footerNavItems: NavItem[] = [
    {
        title: 'Admin Panel',
        href: '/admin',
        icon: Shield,
    },
];

export function AppSidebar() {
    const { auth } = usePage<SharedData>().props;

    return (
        <Sidebar collapsible="icon" variant="inset">
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

            <SidebarContent>
                <NavMain items={mainNavItems} />
            </SidebarContent>

            <SidebarFooter>
                {auth.user.role === 'adm' && <NavFooter items={footerNavItems} className="mt-auto" />}
                <NavUser />
            </SidebarFooter>
        </Sidebar>
    );
}
