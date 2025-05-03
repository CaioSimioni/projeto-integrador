import { NavFooter } from '@/components/nav-footer';
import { NavMain } from '@/components/nav-main';
import { NavUser } from '@/components/nav-user';
import { Sidebar, SidebarContent, SidebarFooter, SidebarHeader, SidebarMenu, SidebarMenuButton, SidebarMenuItem } from '@/components/ui/sidebar';
import { type NavItem, type SharedData } from '@/types';
import { Link, usePage } from '@inertiajs/react';
import { CalendarDays, NotepadText, Shield, UserRound, Wallpaper, /* Warehouse */ } from 'lucide-react';
import AppLogo from './app-logo';

const mainNavItems: NavItem[] = [
    {
        title: 'Painel',
        href: '/dashboard',
        icon: Wallpaper,
    },
    {
        title: 'Pacientes',
        href: '/patients',
        icon: UserRound,
    },
    {
        title: 'Exames',
        href: '',
        icon: NotepadText,
    },
    {
        title: 'Consultas',
        href: '/appointments',
        icon: CalendarDays,
    },
    /* {
        title: 'Prontuários Eletrônicos',
        href: '',
        icon: FileText,
    },
    {
        title: 'Equipe Médica',
        href: '',
        icon: UsersRound,
    },
    {
        title: 'Faturamento',
        href: '',
        icon: ReceiptText,
    }, */
    /* {
        title: 'Inventário & Farmácia',
        href: '/inventory',
        icon: Warehouse,
    }, */
    /* {
        title: 'Exames & Testes Laboratoriais',
        href: '',
        icon: Stethoscope,
    },
    {
        title: 'Emergência & UTI',
        href: '',
        icon: HeartPulse,
    },
    {
        title: 'Relatórios & Análises',
        href: '',
        icon: ClipboardPen,
    }, */
];

const footerNavItems: NavItem[] = [
    {
        title: 'Painel de Administração',
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
