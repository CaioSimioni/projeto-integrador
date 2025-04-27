import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import PatientLayout from '@/layouts/patients-layout';
import { BreadcrumbItem, Patient } from '@/types';
import { Head } from '@inertiajs/react';
import { Eye, Trash2 } from 'lucide-react';
import { PropsWithChildren } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Painel de Pacientes',
        href: '/patients',
    },
];

export default function PatientsList({ patients }: PropsWithChildren<{ patients: Patient[] }>) {
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title="Buscar Paciente" />
            <PatientLayout>
                <div className="space-y-6">
                    <div className="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead className="text-center">Nome</TableHead>
                                    <TableHead className="text-center">CPF</TableHead>
                                    <TableHead className="text-center">SUS</TableHead>
                                    <TableHead className="text-center">Prontuário</TableHead>
                                    <TableHead className="text-center">Mapa</TableHead>
                                    <TableHead className="text-center">Ações</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {patients.map((patient) => (
                                    <TableRow key={patient.name}>
                                        <TableCell className="whitespace-nowrap">{patient.name}</TableCell>
                                        <TableCell className="whitespace-nowrap">{patient.cpf}</TableCell>
                                        <TableCell className="whitespace-nowrap">{patient.sus}</TableCell>
                                        <TableCell className="whitespace-nowrap">{patient.medical_record}</TableCell>
                                        <TableCell className="whitespace-nowrap">
                                            <Button variant={'link'} size={'sm'} className="cursor-pointer">
                                                Ver no mapa
                                            </Button>
                                        </TableCell>
                                        <TableCell className="whitespace-nowrap">
                                            <Button variant={'outline'} className="mr-1">
                                                Visualizar <Eye />
                                            </Button>
                                            <Button variant={'destructive'}>
                                                Excluir <Trash2 />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>
                    </div>
                </div>
            </PatientLayout>
        </AppLayout>
    );
}
