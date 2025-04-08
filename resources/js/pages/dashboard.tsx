import AppLayout from '@/layouts/app-layout';
import { type BreadcrumbItem } from '@/types';
import { Head, usePage } from '@inertiajs/react';
import { type SharedData } from '@/types';
import { Card, CardHeader, CardContent } from '@/components/ui/card';
import { CalendarDays, UserRound } from 'lucide-react';
import Heading from '@/components/heading';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: '/dashboard',
    },
];

export default function Dashboard() {
    const { patientsNumber, appointmentsQuantity } = usePage<SharedData>().props;

    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Dashboard" />
            <div className="px-4 py-6">
                <Heading title="Dashboard" description="Welcome to the dashboard" />
                <div className="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 p-6">
                    <Card>
                        <CardHeader className=' flex-row justify-between'>Patients <UserRound /></CardHeader>
                        <CardContent className='font-bold text-3xl'>{patientsNumber}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className=' flex-row justify-between'>Appointments <CalendarDays /></CardHeader>
                        <CardContent className='font-bold text-3xl'>{appointmentsQuantity}</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className=' flex-row justify-between'>Example <UserRound /></CardHeader>
                        <CardContent className='font-bold text-3xl'>0</CardContent>
                    </Card>
                    <Card>
                        <CardHeader className=' flex-row justify-between'>Example <UserRound /></CardHeader>
                        <CardContent className='font-bold text-3xl'>0</CardContent>
                    </Card>
                </div>
            </div>
        </AppLayout>
    );
}
