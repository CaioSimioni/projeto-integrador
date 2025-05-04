import { MapModal } from '@/components/modal-mapa';
import { Button } from '@/components/ui/button';
import { Table, TableBody, TableCell, TableHead, TableHeader, TableRow } from '@/components/ui/table';
import {
  AlertDialog,
  AlertDialogAction,
  AlertDialogCancel,
  AlertDialogContent,
  AlertDialogDescription,
  AlertDialogFooter,
  AlertDialogHeader,
  AlertDialogTitle,
} from '@/components/ui/alert-dialog';
import { Toast, ToastDescription, ToastProvider, ToastTitle, ToastViewport } from '@/components/ui/toaster';
import AppLayout from '@/layouts/app-layout';
import PatientLayout from '@/layouts/patients-layout';
import { BreadcrumbItem, Patient } from '@/types';
import { Head, router } from '@inertiajs/react';
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
  const [deleteDialogOpen, setDeleteDialogOpen] = useState(false);
  const [patientToDelete, setPatientToDelete] = useState<Patient | null>(null);
  const [toastOpen, setToastOpen] = useState(false);
  const [toastMessage, setToastMessage] = useState('');

  // Função para buscar o endereço do paciente
  async function handleVerNoMapa(patient: Patient) {
    const cepFormatado = patient.cep.replace(/(\d{5})(\d{3})/, '$1-$2');
    const fullEndereco = `${patient.address}, ${patient.number}, ${patient.city} - ${patient.state}, ${cepFormatado}`;
    setEndereco(fullEndereco);
    setModalOpen(true);
  }

  // Função para abrir o diálogo de confirmação
  function handleDeleteClick(patient: Patient) {
    setPatientToDelete(patient);
    setDeleteDialogOpen(true);
  }

  // Função para confirmar a exclusão
  function confirmDelete() {
    if (!patientToDelete) return;

    router.delete(route('patients.destroy', { patient: patientToDelete.id }), {
      onSuccess: () => {
        setToastMessage('Paciente excluído com sucesso!');
        setToastOpen(true);
      },
      onError: () => {
        setToastMessage('Erro ao excluir paciente');
        setToastOpen(true);
      },
    });

    setDeleteDialogOpen(false);
  }

  return (
    <ToastProvider>
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
                            Visualizar <Eye className="ml-2 h-4 w-4" />
                          </a>
                        </Button>
                        <Button variant={'destructive'} onClick={() => handleDeleteClick(patient)}>
                          Excluir <Trash2 className="ml-2 h-4 w-4" />
                        </Button>
                      </TableCell>
                    </TableRow>
                  ))}
                </TableBody>
              </Table>
            </div>
          </div>
          <MapModal open={modalOpen} onClose={() => setModalOpen(false)} endereco={endereco} />

          {/* Diálogo de confirmação de exclusão */}
          <AlertDialog open={deleteDialogOpen} onOpenChange={setDeleteDialogOpen}>
            <AlertDialogContent>
              <AlertDialogHeader>
                <AlertDialogTitle>Confirmar exclusão</AlertDialogTitle>
                <AlertDialogDescription>
                  Tem certeza que deseja excluir o paciente {patientToDelete?.full_name}? Esta ação não pode ser desfeita.
                </AlertDialogDescription>
              </AlertDialogHeader>
              <AlertDialogFooter>
                <AlertDialogCancel>Cancelar</AlertDialogCancel>
                <AlertDialogAction onClick={confirmDelete}>Confirmar</AlertDialogAction>
              </AlertDialogFooter>
            </AlertDialogContent>
          </AlertDialog>

          {/* Toast de confirmação */}
          {toastOpen && (
            <Toast className="bg-green-500" onOpenChange={setToastOpen} open={toastOpen}>
              <ToastTitle>{toastMessage.includes('sucesso') ? 'Sucesso!' : 'Erro!'}</ToastTitle>
              <ToastDescription>{toastMessage}</ToastDescription>
            </Toast>
          )}
        </PatientLayout>
        <ToastViewport />
      </AppLayout>
    </ToastProvider>
  );
}
