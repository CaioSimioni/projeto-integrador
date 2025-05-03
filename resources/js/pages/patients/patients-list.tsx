import { MapModal } from '@/components/modal-mapa';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import AppLayout from '@/layouts/app-layout';
import PatientLayout from '@/layouts/patients-layout';
import { BreadcrumbItem, Patient } from '@/types';
import { Head } from '@inertiajs/react';
import { Eye, Trash2 } from 'lucide-react';
import { PropsWithChildren, useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Lista de Pacientes',
        href: '/patients/list',
    },
];

export default function PatientsList({ patients }: PropsWithChildren<{ patients: Patient[] }>) {
    const [modalOpen, setModalOpen] = useState(false);
    const [endereco, setEndereco] = useState('');

    // Função para buscar o endereço do paciente (ajuste conforme sua API)
    async function handleVerNoMapa(patient: Patient) {
        const cepFormatado = patient.cep.replace(/(\d{5})(\d{3})/, '$1-$2');

        const fullEndereco = `${patient.address}, ${patient.number}, ${patient.city} - ${patient.state}, ${cepFormatado}`;
        setEndereco(fullEndereco);
        setModalOpen(true);
    }

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
                                    <TableRow key={patient.id}>
                                        <TableCell className="whitespace-nowrap">{patient.full_name}</TableCell>
                                        <TableCell className="whitespace-nowrap">{patient.cpf}</TableCell>
                                        <TableCell className="whitespace-nowrap">{patient.sus_number}</TableCell>
                                        <TableCell className="whitespace-nowrap">{patient.medical_record}</TableCell>
                                        <TableCell className="whitespace-nowrap">
                                            <Button variant={'link'} size={'sm'} className="cursor-pointer" onClick={() => handleVerNoMapa(patient)}>
                                                Ver no mapa
                                            </Button>
                                        </TableCell>
                                        <TableCell className="whitespace-nowrap">
                                            <Button asChild variant={'outline'} className="mr-1">
                                                <a href={route('patients.edit', { patient: patient.id })}>
                                                    Visualizar <Eye />
                                                </a>
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
                <MapModal open={modalOpen} onClose={() => setModalOpen(false)} endereco={endereco} />
            </PatientLayout>
        </AppLayout>
    );
}
