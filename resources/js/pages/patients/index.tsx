import { Card, CardContent, CardHeader } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import PatientsLayout from '@/layouts/patients-layout';
import { BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Activity, UserRound } from 'lucide-react';

export default function Patients() {
    const { dashboard_infos } = usePage<{
        dashboard_infos: {
            totalPatients: number;
            activePatients: number;
            inactivePatients: number;
        };
    }>().props;

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Painel de Pacientes',
            href: '/patients',
        },
    ];

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Pacientes" />
            <PatientsLayout>
                <div className="space-y-6">
                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <Card>
                            <CardHeader className="flex-row justify-between">
                                Total de Pacientes <UserRound />
                            </CardHeader>
                            <CardContent className="text-3xl font-bold">{dashboard_infos.totalPatients}</CardContent>
                        </Card>
                        <Card>
                            <CardHeader className="flex-row justify-between">
                                Pacientes Ativos <Activity />
                            </CardHeader>
                            <CardContent className="text-3xl font-bold">{dashboard_infos.activePatients}</CardContent>
                        </Card>
                        <Card>
                            <CardHeader className="flex-row justify-between">
                                Pacientes Inativos <Activity />
                            </CardHeader>
                            <CardContent className="text-3xl font-bold">{dashboard_infos.inactivePatients}</CardContent>
                        </Card>
                    </div>
                </div>
            </PatientsLayout>
        </AppLayout>
    );
}
