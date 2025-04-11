import Heading from '@/components/heading';
import { Card, CardContent, CardHeader } from '@/components/ui/card';
import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem, type SharedData } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { Activity, BarChart, CalendarDays, PieChart, UserRound } from 'lucide-react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    const { analytics } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="px-4 py-6">
                <Heading title="Dashboard" description="Welcome to the dashboard" />
                <div className="grid grid-cols-1 gap-6 p-6 sm:grid-cols-2 lg:grid-cols-4">
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Total Patients <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.total_patients}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Active Patients <Activity />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.active_patients}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Inactive Patients <Activity />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.inactive_patients}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Total Appointments <CalendarDays />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.total_appointments}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Upcoming Appointments <CalendarDays />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.upcoming_appointments}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Appointments Last Month <BarChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.appointments_last_month}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Average Age <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.average_age ?? 'N/A'}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Min Age <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.min_age ?? 'N/A'}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Max Age <UserRound />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.max_age ?? 'N/A'}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Avg Appointments/Patient <BarChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.average_appointments_per_patient}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Active % <PieChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.active_percentage}%</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className="flex-row justify-between">
                            Inactive % <PieChart />
                        </CardHeader>
                        <CardContent className="text-3xl font-bold">{analytics.inactive_percentage}%</CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
