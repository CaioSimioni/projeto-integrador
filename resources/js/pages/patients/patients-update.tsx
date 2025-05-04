import Heading from '@/components/heading';
import InputError from '@/components/input-error';
import { Button } from '@/components/ui/button';
import { Input } from '@/components/ui/input';
import { Label } from '@/components/ui/label';
import { Select, SelectContent, SelectItem, SelectTrigger, SelectValue } from '@/components/ui/select';
import { Separator } from '@/components/ui/separator';
import { Toast, ToastDescription, ToastProvider, ToastTitle, ToastViewport } from '@/components/ui/toaster';
import AppLayout from '@/layouts/app-layout';
import PatientLayout from '@/layouts/patients-layout';
import { BreadcrumbItem, Patient } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Editar Paciente', href: '#' }];

interface ProfileForm {
    fullName: string;
    cpf: string;
    birthDate: string;
    gender: string;
    motherName: string;
    fatherName?: string;
    susNumber?: string;
    medicalRecord?: string;
    nationality: string;
    birthPlace: string;
    state: string;
    cep: string;
    address: string;
    number: string;
    complement?: string;
    neighborhood: string;
    city: string;
    state_address: string;
    country: string;
    phone: string;
    [key: string]: string | undefined;
}

interface FormFieldProps {
    id: string;
    label: string;
    value: string | undefined;
    onChange: (e: React.ChangeEvent<HTMLInputElement>) => void;
    error?: string;
    required?: boolean;
    type?: string;
    placeholder?: string;
    maxLength?: number;
}

function FormField({ id, label, value, onChange, error, required = false, type = 'text', placeholder, maxLength }: FormFieldProps) {
    return (
        <div className="grid gap-2">
            <div className="flex items-center justify-between">
                <Label htmlFor={id}>{label}</Label>
                {/* maxLength && (
                    <span className="text-muted-foreground text-xs">
                        {value?.replace(/\D/g, '').length || 0}/{maxLength}
                    </span>
                ) */}
            </div>
            <Input
                id={id}
                type={type}
                value={value}
                required={required}
                onChange={onChange}
                placeholder={placeholder}
                maxLength={maxLength}
                aria-invalid={!!error}
                aria-describedby={`${id}-error`}
            />
            {error && <InputError id={`${id}-error`} message={error} />}
        </div>
    );
}

interface SelectFieldProps {
    id: string;
    label: string;
    value: string;
    onValueChange: (id: string, value: string) => void;
    options: { label: string; value: string }[];
    placeholder: string;
    error?: string;
}

function SelectField({ id, label, value, onValueChange, options, placeholder, error }: SelectFieldProps) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={id}>{label}</Label>
            <Select value={value} onValueChange={(val) => onValueChange(id, val)}>
                <SelectTrigger>
                    <SelectValue placeholder={placeholder} />
                </SelectTrigger>
                <SelectContent>
                    {options.map((opt) => (
                        <SelectItem key={`${id}-${opt.value}`} value={opt.value}>
                            {opt.label}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
            {error && <InputError id={`${id}-error`} message={error} />}
        </div>
    );
}

const formatCPF = (value: string) => {
    const numericValue = value.replace(/\D/g, '').slice(0, 11);
    return {
        raw: numericValue,
        formatted: numericValue
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})/, '$1-$2'),
    };
};

const formatSUSNumber = (value: string) => {
    const numericValue = value.replace(/\D/g, '').slice(0, 15);
    return {
        raw: numericValue,
        formatted: numericValue
            .replace(/(\d{3})(\d)/, '$1 $2')
            .replace(/(\d{4})(\d)/, '$1 $2')
            .replace(/(\d{4})(\d)/, '$1 $2'),
    };
};

const formatPhone = (value: string) => {
    const numericValue = value.replace(/\D/g, '').slice(0, 11);
    return numericValue.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
};

export default function PatientsUpdate({ patient }: { patient: Patient }) {
    const [isFetchingCEP, setIsFetchingCEP] = useState(false);
    const [submitError, setSubmitError] = useState<string | null>(null);
    const [rawCpf] = useState(patient.cpf || '');
    const [rawSusNumber] = useState(patient.sus_number || '');
    const [toastVisible, setToastVisible] = useState(false);
    const [toastType, setToastType] = useState<'success' | 'error'>('success');

    const initialData = {
        fullName: patient.full_name || '',
        cpf: patient.cpf ? formatCPF(patient.cpf).formatted : '',
        birthDate: patient.birth_date ? new Date(patient.birth_date).toISOString().split('T')[0] : '',
        gender: patient.gender || '',
        motherName: patient.mother_name || '',
        fatherName: patient.father_name || '',
        susNumber: patient.sus_number ? formatSUSNumber(patient.sus_number).formatted : '',
        medicalRecord: patient.medical_record || '',
        nationality: patient.nationality || 'Brasileiro',
        birthPlace: patient.birth_place || '',
        state: patient.state || '',
        cep: patient.cep || '',
        address: patient.address || '',
        number: patient.number || '',
        complement: patient.complement || '',
        neighborhood: patient.neighborhood || '',
        city: patient.city || '',
        state_address: patient.state_address || '',
        country: patient.country || 'Brasil',
        phone: patient.phone ? formatPhone(patient.phone) : '',
    };

    const { data, setData, errors, processing, recentlySuccessful } = useForm<ProfileForm>(initialData);

    const fetchAddressByCEP = async (cep: string) => {
        try {
            setIsFetchingCEP(true);
            const cleanedCEP = cep.replace(/\D/g, '');
            if (cleanedCEP.length !== 8) return;

            const response = await fetch(`https://viacep.com.br/ws/${cleanedCEP}/json/`);
            const data = await response.json();

            if (!data.erro) {
                setData((prev) => ({
                    ...prev,
                    address: data.logradouro || '',
                    neighborhood: data.bairro || '',
                    city: data.localidade || '',
                    state_address: data.uf || '',
                    complement: data.complemento || '',
                }));
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
        } finally {
            setIsFetchingCEP(false);
        }
    };

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { id, value } = e.target;
        const updateField = (newValue: string) => setData((prev) => ({ ...prev, [id]: newValue }));

        switch (id) {
            case 'cpf':
                updateField(formatCPF(value).formatted);
                break;

            case 'susNumber':
                updateField(formatSUSNumber(value).formatted);
                break;

            case 'phone':
                updateField(formatPhone(value));
                break;

            case 'cep': {
                const formatted = value.replace(/\D/g, '').slice(0, 8);
                updateField(formatted.replace(/(\d{5})(\d)/, '$1-$2'));
                if (formatted.length === 8) fetchAddressByCEP(formatted);
                break;
            }

            case 'state':
            case 'state_address':
                updateField(
                    value
                        .replace(/[^a-zA-Z]/g, '')
                        .slice(0, 2)
                        .toUpperCase(),
                );
                break;

            default:
                updateField(value);
        }
    };

    const validateForm = () => {
        const requiredFields = ['fullName', 'cpf', 'birthDate', 'gender', 'motherName'];
        const missingFields = requiredFields.filter((field) => !data[field as keyof ProfileForm]?.trim());

        if (missingFields.length > 0) {
            setSubmitError(`Preencha os campos obrigatórios: ${missingFields.join(', ')}`);
            return false;
        }

        if (data.susNumber && rawSusNumber.length !== 15) {
            setSubmitError('Nº SUS deve conter exatamente 15 dígitos');
            return false;
        }

        return true;
    };

    return (
        <ToastProvider>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Editar Paciente" />
                <PatientLayout>
                    {toastVisible && (
                        <Toast className={toastType === 'success' ? 'bg-green-500' : 'bg-red-500'} onOpenChange={setToastVisible} open={toastVisible}>
                            <ToastTitle>{toastType === 'success' ? 'Sucesso!' : 'Erro!'}</ToastTitle>
                            <ToastDescription>
                                {toastType === 'success' ? 'Paciente atualizado com sucesso!' : 'Ocorreu um erro. Verifique os campos destacados.'}
                            </ToastDescription>
                        </Toast>
                    )}

                    <Heading title="Editar Paciente" description="Atualize os dados do paciente nos campos abaixo:" />

                    <form
                        className="grid grid-cols-3 gap-4"
                        onSubmit={(e) => {
                            e.preventDefault();
                            setSubmitError(null);

                            if (!validateForm()) return;

                            router.patch(
                                route('patients.update', {
                                    patient: patient.id, // Certificar que patient.id existe e é numérico
                                }),
                                {
                                    full_name: data.fullName,
                                    cpf: rawCpf,
                                    birth_date: data.birthDate,
                                    gender: data.gender,
                                    mother_name: data.motherName,
                                    father_name: data.fatherName,
                                    sus_number: rawSusNumber,
                                    medical_record: data.medicalRecord,
                                    nationality: data.nationality,
                                    birth_place: data.birthPlace,
                                    state: data.state,
                                    cep: data.cep.replace(/\D/g, ''),
                                    address: data.address,
                                    number: data.number,
                                    complement: data.complement,
                                    neighborhood: data.neighborhood,
                                    city: data.city,
                                    state_address: data.state_address,
                                    country: data.country,
                                    phone: data.phone.replace(/\D/g, ''),
                                },
                                {
                                    onSuccess: () => {
                                        setToastType('success');
                                        setToastVisible(true);
                                        router.reload({ only: ['patient'] });
                                    },
                                    onError: (errors) => {
                                        setSubmitError(typeof errors === 'string' ? errors : 'Ocorreu um erro. Verifique os campos destacados.');
                                        setToastType('error');
                                        setToastVisible(true);
                                    },
                                },
                            );
                        }}
                    >
                        {/* Campos do formulário */}
                        <div className="col-span-3 grid gap-2">
                            <FormField
                                id="fullName"
                                label="Nome completo"
                                value={data.fullName}
                                onChange={handleChange}
                                error={errors.fullName}
                                required
                                placeholder="Ex: João da Silva"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="cpf"
                                label="CPF"
                                value={data.cpf}
                                onChange={handleChange}
                                error={errors.cpf}
                                required
                                placeholder="000.000.000-00"
                                maxLength={14}
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="birthDate"
                                label="Data de Nascimento"
                                type="date"
                                value={data.birthDate}
                                onChange={handleChange}
                                error={errors.birthDate}
                                required
                            />
                        </div>

                        <div className="grid gap-2">
                            <SelectField
                                id="gender"
                                label="Sexo"
                                value={data.gender}
                                onValueChange={(id, value) => setData(id as keyof ProfileForm, value)}
                                options={[
                                    { label: 'Masculino', value: 'male' },
                                    { label: 'Feminino', value: 'female' },
                                    { label: 'Outro', value: 'other' },
                                ]}
                                placeholder="Selecione o sexo"
                                error={errors.gender}
                            />
                        </div>

                        <div className="col-span-3 grid gap-2">
                            <FormField
                                id="motherName"
                                label="Nome da Mãe"
                                value={data.motherName}
                                onChange={handleChange}
                                error={errors.motherName}
                                required
                                placeholder="Ex: Maria da Silva"
                            />
                        </div>

                        <div className="col-span-3 grid gap-2">
                            <FormField
                                id="fatherName"
                                label="Nome do Pai"
                                value={data.fatherName}
                                onChange={handleChange}
                                error={errors.fatherName}
                                placeholder="Ex: José da Silva"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="susNumber"
                                label="Nº SUS"
                                value={data.susNumber}
                                onChange={handleChange}
                                error={errors.susNumber}
                                placeholder="000 0000 0000 0000"
                                maxLength={18}
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="medicalRecord"
                                label="Prontuário"
                                value={data.medicalRecord}
                                onChange={handleChange}
                                error={errors.medicalRecord}
                                placeholder="Ex: MR-1234-AB"
                                maxLength={50}
                            />
                        </div>

                        <div className="col-start-1 grid gap-2">
                            <FormField
                                id="nationality"
                                label="Nacionalidade"
                                value={data.nationality}
                                onChange={handleChange}
                                error={errors.nationality}
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="birthPlace"
                                label="Naturalidade"
                                value={data.birthPlace}
                                onChange={handleChange}
                                error={errors.birthPlace}
                                placeholder="Ex: São Paulo"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="state"
                                label="UF"
                                value={data.state}
                                onChange={handleChange}
                                error={errors.state}
                                placeholder="Ex: SP"
                                maxLength={2}
                            />
                        </div>

                        <Separator className="col-span-3 my-4" />

                        <div className="grid gap-2">
                            <FormField
                                id="cep"
                                label="CEP"
                                value={data.cep}
                                onChange={handleChange}
                                error={errors.cep}
                                placeholder="00000-000"
                                maxLength={9}
                            />
                            {isFetchingCEP && <p className="text-muted-foreground text-xs">Buscando endereço...</p>}
                        </div>

                        <div className="col-span-2 grid gap-2">
                            <FormField
                                id="address"
                                label="Endereço"
                                value={data.address}
                                onChange={handleChange}
                                error={errors.address}
                                placeholder="Ex: Rua das Flores, 123"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="number"
                                label="Número"
                                value={data.number}
                                onChange={handleChange}
                                error={errors.number}
                                placeholder="Ex: 123"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="complement"
                                label="Complemento"
                                value={data.complement}
                                onChange={handleChange}
                                error={errors.complement}
                                placeholder="Ex: Apto 101"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="neighborhood"
                                label="Bairro"
                                value={data.neighborhood}
                                onChange={handleChange}
                                error={errors.neighborhood}
                                placeholder="Ex: Centro"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="city"
                                label="Cidade"
                                value={data.city}
                                onChange={handleChange}
                                error={errors.city}
                                placeholder="Ex: São Paulo"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="state_address"
                                label="UF"
                                value={data.state_address}
                                onChange={handleChange}
                                error={errors.state_address}
                                placeholder="Ex: SP"
                                maxLength={2}
                            />
                        </div>

                        <div className="col-start-1 grid gap-2">
                            <FormField id="country" label="País" value={data.country} onChange={handleChange} error={errors.country} />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="phone"
                                label="Telefone"
                                value={data.phone}
                                onChange={handleChange}
                                error={errors.phone}
                                placeholder="(00) 00000-0000"
                                maxLength={15}
                            />
                        </div>

                        <div className="col-span-3 mt-6 flex items-center gap-4">
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Salvando...' : 'Salvar Alterações'}
                            </Button>
                            <Transition
                                show={recentlySuccessful}
                                enter="transition-opacity duration-150"
                                enterFrom="opacity-0"
                                enterTo="opacity-100"
                                leave="transition-opacity duration-150"
                                leaveFrom="opacity-100"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-green-600">Paciente atualizado com sucesso!</p>
                            </Transition>
                        </div>

                        {submitError && <div className="col-span-3 rounded-lg bg-red-50 p-4 text-red-700">{submitError}</div>}

                        {Object.keys(errors).length > 0 && (
                            <div className="col-span-3 rounded-lg bg-red-50 p-4 text-red-700">
                                <h3 className="mb-2 font-bold">Erros de validação:</h3>
                                <ul className="list-disc pl-5">
                                    {Object.entries(errors).map(([field, message], index) => (
                                        <li key={`error-${field}-${index}`}>
                                            <strong>{field}:</strong> {message}
                                        </li>
                                    ))}
                                </ul>
                            </div>
                        )}
                    </form>
                </PatientLayout>
                <ToastViewport />
            </AppLayout>
        </ToastProvider>
    );
}
