import Heading from '@/components/heading';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Activity, BarChart, CalendarDays, PieChart, UserRound } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Painel de Controle',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    const { analytics } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Painel de Controle" />
            <div className="px-4 py-6">
                <Heading title="Painel de Controle" description="Bem-vindo ao painel de controle" />
                <div className="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Total de Pacientes <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.total_patients}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Pacientes Ativos <Activity />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.active_patients}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Pacientes Inativos <Activity />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.inactive_patients}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Total de Consultas <CalendarDays />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.total_appointments}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Próximas Consultas <CalendarDays />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.upcoming_appointments}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Consultas no Último Mês <BarChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.appointments_last_month}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Idade Média <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.average_age ?? 'N/D'}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Idade Mínima <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.min_age ?? 'N/D'}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Idade Máxima <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.max_age ?? 'N/D'}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Média de Consultas/Paciente <BarChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.average_appointments_per_patient}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            % Ativos <PieChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.active_percentage}%</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            % Inativos <PieChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.inactive_percentage}%</CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
