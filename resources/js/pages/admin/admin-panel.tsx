import AppLayout from "@/layouts/app-layout";
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { type NavItem, type SharedData } from '@/types';
import BasicLayout from '@/layouts/basic-layout';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Painel Admin',
        href: '/admin',
    },
];

const sidebarNavItems: NavItem[] = [
    {
        title: 'Geral',
        href: '/admin',
        icon: null,
    },
    {
        title: 'Usuários',
        href: '/admin/users',
        icon: null,
    },
];

export default function Dashboard() {
    const { auth, usersQuantity, patientsQuantity } = usePage<SharedData>().props;
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Admin" />
            <BasicLayout sidebarNavItems={sidebarNavItems}>
                <div className="space-y-6">
                    <h1><strong>{auth.user.name}</strong> é um administrador</h1>
                </div>
                <div>
                    <h1>Quantidade de Usuários: <strong>{usersQuantity}</strong></h1>
                    <h1>Quantidade de Pacientes: <strong>{patientsQuantity}</strong></h1>
                </div>
            </BasicLayout>
        </AppLayout>
    );
}
