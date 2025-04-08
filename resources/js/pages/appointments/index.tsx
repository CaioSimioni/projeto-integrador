import { useState, type PropsWithChildren } from "react";
import { Head, useForm } from "@inertiajs/react";
import { Table, TableHeader, TableRow, TableHead, TableBody, TableCell } from "@/components/ui/table";
import { Button } from "@/components/ui/button";
import { CirclePlus, Pencil, Trash2 } from "lucide-react";
import AppointmentForm from "@/components/appointment-form";
import AppointmentModal from "@/components/appointment-modal";
import AppLayout from "@/layouts/app-layout";
import BasicLayout from "@/layouts/basic-layout";
import { BreadcrumbItem, type NavItem, type Appointment, type Patient } from "@/types";
import { ToastProvider, Toast, ToastTitle, ToastDescription, ToastViewport } from "@/components/ui/toaster";

export default function Appointments({ appointments, patients }: PropsWithChildren<{ appointments: Appointment[], patients: Patient[] }>) {
    const { data, setData, post, patch, delete: destroy, processing, errors, reset } = useForm({
        patient_id: "",
        appointment_date: "",
        notes: "",
    });

    const [modalType, setModalType] = useState<"create" | "edit" | "delete" | null>(null);
    const [selectedAppointment, setSelectedAppointment] = useState<Appointment | null>(null);
    const [toastVisible, setToastVisible] = useState(false);

    const openModal = (type: "create" | "edit" | "delete", appointment: Appointment | null = null) => {
        setSelectedAppointment(appointment);
        setModalType(type);
        if (type === "edit" && appointment) {
            const formattedDate = new Date(appointment.appointment_date).toISOString().slice(0, 16);
            setData({
                patient_id: String(appointment.patient_id),
                appointment_date: formattedDate,
                notes: appointment.notes || "",
            });
        }
    };

    const handleSubmit = () => {
        if (modalType === "create") {
            post(route("appointments.store"), {
                onSuccess: () => {
                    reset();
                    setToastVisible(true);
                },
                onError: () => {
                    setToastVisible(true);
                },
            });
        } else if (modalType === "edit" && selectedAppointment) {
            patch(route("appointments.update", { appointment: selectedAppointment }), {
                onSuccess: () => {
                    reset();
                    setToastVisible(true);
                },
                onError: () => {
                    setToastVisible(true);
                },
            });
        } else if (modalType === "delete" && selectedAppointment) {
            destroy(route("appointments.destroy", { appointment: selectedAppointment.id }), {
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
            title: 'Appointments Painel',
            href: '/appointments',
        },
    ];

    const sidebarNavItems: NavItem[] = [
        {
            title: 'General',
            href: '/appointments',
            icon: null,
        },
    ];

    return (
        <ToastProvider>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Appointments" />
                <BasicLayout sidebarNavItems={sidebarNavItems}>
                    <div className="space-y-6">
                        <Button onClick={() => openModal("create")}>Create New Appointment <CirclePlus /></Button>

                        {toastVisible && (
                            <Toast className={toastVisible ? "bg-green-500" : ""} onOpenChange={setToastVisible} open={toastVisible}>
                                <ToastTitle>Success</ToastTitle>
                                <ToastDescription>Action completed successfully.</ToastDescription>
                            </Toast>
                        )}

                        <Table>
                            <TableHeader>
                                <TableRow>
                                    <TableHead>ID</TableHead>
                                    <TableHead>Patient</TableHead>
                                    <TableHead>Appointment Date</TableHead>
                                    <TableHead>Actions</TableHead>
                                </TableRow>
                            </TableHeader>
                            <TableBody>
                                {appointments.map((appointment) => (
                                    <TableRow key={appointment.id}>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{appointment.id}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">{appointment.patient?.name || "N/A"}</TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            {new Date(appointment.appointment_date).toLocaleString('UTC', { timeZone: 'UTC', hour: '2-digit', minute: '2-digit', day: '2-digit', month: '2-digit', year: 'numeric' })}
                                        </TableCell>
                                        <TableCell className="px-6 py-4 whitespace-nowrap">
                                            <Button className="mr-2" variant="outline" size="sm" onClick={() => openModal("edit", appointment)}>
                                                Edit <Pencil />
                                            </Button>
                                            <Button variant="destructive" size="sm" onClick={() => openModal("delete", appointment)}>
                                                Delete <Trash2 />
                                            </Button>
                                        </TableCell>
                                    </TableRow>
                                ))}
                            </TableBody>
                        </Table>

                        {modalType && (
                            <AppointmentModal
                                open={!!modalType}
                                setOpen={() => setModalType(null)}
                                title={modalType === "delete" ? "Confirm Deletion" : modalType === "edit" ? "Edit Appointment" : "Create Appointment"}
                                description={modalType === "delete" ? `Are you sure you want to delete this appointment?` : "Fill the details below"}
                                onConfirm={handleSubmit}
                                confirmText={modalType === "delete" ? "Delete" : modalType === "edit" ? "Update" : "Create"}
                                confirmVariant={modalType === "delete" ? "destructive" : "default"}
                                processing={processing}
                            >
                                {modalType !== "delete" && <AppointmentForm data={data} setData={setData} errors={errors} processing={processing} patients={patients} />}
                            </AppointmentModal>
                        )}
                    </div>
                </BasicLayout>
            </AppLayout>
            <ToastViewport />
        </ToastProvider>
    );
}
