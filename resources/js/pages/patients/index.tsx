import PatientForm from '@/components/patient-form';
import PatientModal from '@/components/patient-modal';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import { Toast, ToastDescription, ToastProvider, ToastTitle, ToastViewport } from '@/components/ui/toaster';
import AppLayout from '@/layouts/app-layout';
import BasicLayout from '@/layouts/basic-layout';
import { BreadcrumbItem, type NavItem, type Patient } from '@/types';
import { Head, useForm } from '@inertiajs/react';
import { CirclePlus, Pencil, Trash2 } from 'lucide-react';
import { useState, type PropsWithChildren } from 'react';

export default function Patients({ patients }: PropsWithChildren<{ patients: Patient[] }>) {
    const {
        data,
        setData,
        post,
        patch,
        delete: destroy,
        processing,
        errors,
        reset,
    } = useForm({
        name: '',
        cpf: '',
        birth_date: '',
        insurance: 'Nenhum',
        is_active: true as boolean,
        phone: '',
        email: '',
        address: '',
    });

    const [modalType, setModalType] = useState<'create' | 'edit' | 'delete' | null>(null);
    const [selectedPatient, setSelectedPatient] = useState<Patient | null>(null);
    const [toastVisible, setToastVisible] = useState(false);

    const openModal = (type: 'create' | 'edit' | 'delete', patient: Patient | null = null) => {
        setSelectedPatient(patient);
        setModalType(type);
        if (type === 'edit' && patient) {
            setData({
                name: patient.name,
                cpf: patient.cpf,
                birth_date: patient.birth_date,
                phone: patient.phone || '',
                email: patient.email || '',
                address: patient.address || '',
                insurance: patient.insurance,
                is_active: patient.is_active === true,
            });
        }
    };

    const handleSubmit = () => {
        if (modalType === 'create') {
            post(route('patients.store'), {
                onSuccess: () => {
                    reset();
                    setToastVisible(true);
                },
                onError: () => {
                    setToastVisible(true);
                },
            });
        } else if (modalType === 'edit' && selectedPatient) {
            patch(route('patients.update', { patient: selectedPatient.id }), {
                onSuccess: () => {
                    reset();
                    setToastVisible(true);
                },
                onError: () => {
                    setToastVisible(true);
                },
            });
        } else if (modalType === 'delete' && selectedPatient) {
            destroy(route('patients.destroy', { patient: selectedPatient.id }), {
                onSuccess: () => {
                    setToastVisible(true);
                },
                onError: () => {
                    setToastVisible(true);
                },
            });
        }
        setModalType(null);
    };

    const breadcrumbs: BreadcrumbItem[] = [
        {
            title: 'Painel de Pacientes',
            href: '/patients',
        },
    ];

    const sidebarNavItems: NavItem[] = [
        {
            title: 'Geral',
            href: '/patients',
            icon: null,
        },
    ];

    return (
        <ToastProvider>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Pacientes" />
                <BasicLayout sidebarNavItems={sidebarNavItems}>
                    <div className="space-y-6">
                        <Button onClick={() => openModal('create')}>
                            Novo Paciente <CirclePlus />
                        </Button>

                        {toastVisible && (
                            <Toast className={toastVisible ? 'bg-green-500' : ''} onOpenChange={setToastVisible} open={toastVisible}>
                                <ToastTitle>Sucesso</ToastTitle>
                                <ToastDescription>Ação concluída com sucesso.</ToastDescription>
                            </Toast>
                        )}

                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>ID</TableHead>
                                    <TableHead>Nome</TableHead>
                                    <TableHead>CPF</TableHead>
                                    <TableHead>Ações</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {patients.map((patient) => (
                                    <TableRow key={patient.id}>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{patient.id}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{patient.name}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{patient.cpf}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            <Button className="mr-2" variant="outline" size="sm" onClick={() => openModal('edit', patient)}>
                                                Editar <Pencil />
                                            </Button>
                                            <Button variant="destructive" size="sm" onClick={() => openModal('delete', patient)}>
                                                Excluir <Trash2 />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>

                        {modalType && (
                            <PatientModal
                                open={!!modalType}
                                setOpen={() => setModalType(null)}
                                title={modalType === 'delete' ? 'Confirmar Exclusão' : modalType === 'edit' ? 'Editar Paciente' : 'Novo Paciente'}
                                description={
                                    modalType === 'delete' ? `Tem certeza que deseja excluir ${selectedPatient?.name}?` : 'Preencha os detalhes abaixo'
                                }
                                onConfirm={handleSubmit}
                                confirmText={modalType === 'delete' ? 'Excluir' : modalType === 'edit' ? 'Atualizar' : 'Criar'}
                                confirmVariant={modalType === 'delete' ? 'destructive' : 'default'}
                                processing={processing}
                            >
                                {modalType !== 'delete' && <PatientForm data={data} setData={setData} errors={errors} processing={processing} />}
                            </PatientModal>
                        )}
                    </div>
                </BasicLayout>
            </AppLayout>
            <ToastViewport />
        </ToastProvider>
    );
}