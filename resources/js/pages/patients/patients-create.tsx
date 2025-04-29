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
import { BreadcrumbItem } from '@/types';
import { Transition } from '@headlessui/react';
import { Head, router, useForm } from '@inertiajs/react';
import { useState } from 'react';

const breadcrumbs: BreadcrumbItem[] = [{ title: 'Adicionar Paciente', href: '/patients/create' }];

interface ProfileForm {
    name: string;
    email: string;
    fullName: string;
    cpf: string;
    birthDate: string;
    gender: string;
    motherName: string;
    fatherName?: string;
    susNumber?: string;
    medicalRecord?: string;
    nationality?: string;
    birthPlace?: string;
    state?: string;
    cep?: string;
    address?: string;
    number?: string;
    complement?: string;
    neighborhood?: string;
    state_address?: string;
    city?: string;
    country?: string;
    phone?: string;
}

// Componentes auxiliares
function FormField({ id, label, value, onChange, error, required = false, type = 'text', placeholder, maxLength }: any) {
    return (
        <div className="grid gap-2">
            <div className="flex items-center justify-between">
                <Label htmlFor={id}>{label}</Label>
                {maxLength && (
                    <span className="text-muted-foreground text-xs">
                        {value?.length || 0}/{maxLength}
                    </span>
                )}
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

function SelectField({ id, label, value, onValueChange, options, placeholder, error }: any) {
    return (
        <div className="grid gap-2">
            <Label htmlFor={id}>{label}</Label>
            <Select value={value} onValueChange={(val) => onValueChange(id, val)}>
                <SelectTrigger>
                    <SelectValue placeholder={placeholder} />
                </SelectTrigger>
                <SelectContent>
                    {options.map((opt: { label: string; value: string }) => (
                        <SelectItem key={opt.value} value={opt.value}>
                            {opt.label}
                        </SelectItem>
                    ))}
                </SelectContent>
            </Select>
            {error && <InputError id={`${id}-error`} message={error} />}
        </div>
    );
}

export default function PatientCreate() {
    const [isFetchingCEP, setIsFetchingCEP] = useState(false);
    const [submitError, setSubmitError] = useState<string | null>(null);
    const [rawCpf, setRawCpf] = useState('');
    const [toastVisible, setToastVisible] = useState(false);

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
                    country: prev.country || 'Brasil',
                }));
            } else {
                console.warn('CEP não encontrado');
            }
        } catch (error) {
            console.error('Erro ao buscar CEP:', error);
        } finally {
            setIsFetchingCEP(false);
        }
    };

    const formatCPF = (value: string) => {
        const numericValue = value.replace(/\D/g, '').slice(0, 11);
        setRawCpf(numericValue); // Armazena o CPF sem formatação

        return numericValue
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d)/, '$1.$2')
            .replace(/(\d{3})(\d{1,2})/, '$1-$2')
            .replace(/(-\d{2})\d+?$/, '$1');
    };

    const formatCEP = (value: string) => {
        const numericValue = value.replace(/\D/g, '').slice(0, 8);
        return numericValue.replace(/(\d{5})(\d)/, '$1-$2').replace(/(-\d{3})\d+?$/, '$1');
    };

    const formatPhone = (value: string) => {
        const numericValue = value.replace(/\D/g, '');
        if (numericValue.length <= 10) {
            return numericValue.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{4})(\d)/, '$1-$2');
        } else {
            return numericValue.replace(/(\d{2})(\d)/, '($1) $2').replace(/(\d{5})(\d)/, '$1-$2');
        }
    };

    const isValidDate = (dateString: string) => {
        const regEx = /^\d{4}-\d{2}-\d{2}$/;
        return dateString.match(regEx) !== null;
    };

    const { data, setData, errors, processing, recentlySuccessful } = useForm<Required<ProfileForm>>({
        name: '',
        email: '',
        fullName: '',
        cpf: '',
        birthDate: '',
        gender: '',
        motherName: '',
        fatherName: '',
        susNumber: '',
        medicalRecord: '',
        nationality: '',
        birthPlace: '',
        state: '',
        cep: '',
        address: '',
        number: '',
        complement: '',
        neighborhood: '',
        city: '',
        state_address: '',
        country: '',
        phone: '',
    });

    const handleChange = (e: React.ChangeEvent<HTMLInputElement>) => {
        const { id, value } = e.target;

        if (!(id in data)) {
            console.warn(`Campo não reconhecido: ${id}`);
            return;
        }

        const updateField = (newValue: string) => {
            setData((prev) => ({ ...prev, [id]: newValue }));
        };

        switch (id) {
            case 'cpf':
                updateField(formatCPF(value));
                break;

            case 'cep':
                const formattedCEP = formatCEP(value);
                updateField(formattedCEP);

                // Quando o CEP estiver completo (8 dígitos), busca o endereço
                if (value.replace(/\D/g, '').length === 8) {
                    fetchAddressByCEP(formattedCEP);
                }
                break;

            case 'state':
            case 'state_address':
                updateField(
                    value
                        .replace(/[^a-zA-Z]/g, '')
                        .slice(0, 2)
                        .toUpperCase(),
                );
                break;

            case 'phone':
                updateField(formatPhone(value));
                break;

            case 'birthDate':
                if (value === '' || isValidDate(value)) {
                    updateField(value);
                }
                break;

            default:
                updateField(value);
        }
    };

    const handleSelectChange = (id: keyof ProfileForm, value: string) => {
        setData(id, value);
    };

    return (
        <ToastProvider>
            <AppLayout breadcrumbs={breadcrumbs}>
                <Head title="Criar Paciente" />
                <PatientLayout>
                    <Heading title="Cadastro de Pacientes" description="Informe os dados do paciente nos campos abaixo:" />

                    <form
                        className="grid grid-cols-3 gap-4"
                        onSubmit={(e) => {
                            e.preventDefault();
                            setSubmitError(null);

                            // Prepara os dados para envio
                            const formData = {
                                ...data,
                                cpf: rawCpf, // Envia o CPF sem formatação
                                cep: data.cep?.replace(/\D/g, ''), // Remove formatação do CEP
                            };

                            router.post(route('patients.store'), formData, {
                                onSuccess: () => {
                                    setToastVisible(true);
                                    // Limpa após 3 segundos
                                    setTimeout(() => setToastVisible(false), 3000);
                                    setData({
                                        name: '',
                                        email: '',
                                        fullName: '',
                                        cpf: '',
                                        birthDate: '',
                                        gender: '',
                                        motherName: '',
                                        fatherName: '',
                                        susNumber: '',
                                        medicalRecord: '',
                                        nationality: '',
                                        birthPlace: '',
                                        state: '',
                                        cep: '',
                                        address: '',
                                        number: '',
                                        complement: '',
                                        neighborhood: '',
                                        city: '',
                                        state_address: '',
                                        country: '',
                                        phone: '',
                                    });
                                    setRawCpf('');
                                },
                                onError: (errors) => {
                                    if (errors.error) {
                                        setSubmitError(errors.error);
                                    } else {
                                        setSubmitError('Por favor, corrija os erros no formulário.');
                                    }
                                },
                            });
                        }}
                    >
                        {/* Campos do formulário (mantidos iguais) */}
                        {/* Nome completo */}
                        <div className="col-span-3 grid gap-2">
                            <FormField
                                id="fullName"
                                label="Nome completo"
                                value={data.fullName}
                                onChange={handleChange}
                                error={errors.fullName}
                                required
                                placeholder="Nome completo"
                            />
                        </div>

                        {/* Linha: CPF, Data de nascimento, Sexo */}
                        <div className="grid gap-2">
                            <FormField id="cpf" label="CPF" value={data.cpf} onChange={handleChange} error={errors.cpf} required placeholder="CPF" />
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
                                onValueChange={handleSelectChange}
                                placeholder="Selecione o sexo"
                                options={[
                                    { label: 'Masculino', value: 'male' },
                                    { label: 'Feminino', value: 'female' },
                                    { label: 'Outro', value: 'other' },
                                ]}
                                error={errors.gender}
                            />
                        </div>

                        {/* Nome da mãe */}
                        <div className="col-span-3 grid gap-2">
                            <FormField
                                id="motherName"
                                label="Nome da Mãe"
                                value={data.motherName}
                                onChange={handleChange}
                                error={errors.motherName}
                                required
                                placeholder="Nome da Mãe"
                            />
                        </div>

                        {/* Nome do pai */}
                        <div className="col-span-3 grid gap-2">
                            <FormField
                                id="fatherName"
                                label="Nome do Pai"
                                value={data.fatherName}
                                onChange={handleChange}
                                error={errors.fatherName}
                                placeholder="Nome do Pai"
                            />
                        </div>

                        {/* SUS, Prontuário */}
                        <div className="grid gap-2">
                            <FormField
                                id="susNumber"
                                label="Nº SUS"
                                value={data.susNumber}
                                onChange={handleChange}
                                error={errors.susNumber}
                                placeholder="Nº SUS"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="medicalRecord"
                                label="Nº Prontuário"
                                value={data.medicalRecord}
                                onChange={handleChange}
                                error={errors.medicalRecord}
                                placeholder="Nº Prontuário"
                            />
                        </div>

                        {/* Nacionalidade, Naturalidade, Estado */}
                        <div className="col-start-1 grid gap-2">
                            <FormField
                                id="nationality"
                                label="Nacionalidade"
                                value={data.nationality}
                                onChange={handleChange}
                                error={errors.nationality}
                                placeholder="Nacionalidade"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="birthPlace"
                                label="Naturalidade"
                                value={data.birthPlace}
                                onChange={handleChange}
                                error={errors.birthPlace}
                                placeholder="Naturalidade"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="state"
                                label="UF"
                                value={data.state}
                                onChange={handleChange}
                                error={errors.state}
                                placeholder="Digite o estado"
                            />
                        </div>

                        <Separator className="col-span-3 my-1" />

                        {/* Informações de endereço */}

                        <div className="grid gap-2">
                            <FormField id="cep" label="CEP" value={data.cep} onChange={handleChange} error={errors.cep} placeholder="CEP" />
                            {isFetchingCEP && <p className="text-muted-foreground text-xs">Buscando endereço...</p>}
                        </div>

                        <div className="col-span-2 grid gap-2">
                            <FormField
                                id="address"
                                label="Endereço"
                                value={data.address}
                                onChange={handleChange}
                                error={errors.address}
                                placeholder="Endereço"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="number"
                                label="Número"
                                value={data.number}
                                onChange={handleChange}
                                error={errors.number}
                                placeholder="Número"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="complement"
                                label="Complemento"
                                value={data.complement}
                                onChange={handleChange}
                                error={errors.complement}
                                placeholder="Complemento"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="neighborhood"
                                label="Bairro"
                                value={data.neighborhood}
                                onChange={handleChange}
                                error={errors.neighborhood}
                                placeholder="Bairro"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField id="city" label="Cidade" value={data.city} onChange={handleChange} error={errors.city} placeholder="Cidade" />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="state_address"
                                label="Estado"
                                value={data.state_address}
                                onChange={handleChange}
                                error={errors.state_address}
                                placeholder="Estado"
                            />
                        </div>

                        <div className="col-start-1 grid gap-2">
                            <FormField
                                id="country"
                                label="País"
                                value={data.country}
                                onChange={handleChange}
                                error={errors.country}
                                placeholder="País"
                            />
                        </div>

                        <div className="grid gap-2">
                            <FormField
                                id="phone"
                                label="Telefone"
                                value={data.phone}
                                onChange={handleChange}
                                error={errors.phone}
                                placeholder="Telefone"
                            />
                        </div>

                        {/* Botão */}
                        <div className="col-span-3 mt-4 flex items-center gap-4">
                            <Button type="submit" disabled={processing}>
                                {processing ? 'Salvando...' : 'Salvar'}
                            </Button>
                            <Transition
                                show={recentlySuccessful}
                                enter="transition-opacity duration-500"
                                enterFrom="opacity-0"
                                leave="transition-opacity duration-500"
                                leaveTo="opacity-0"
                            >
                                <p className="text-sm text-green-600">Salvo com sucesso!</p>
                            </Transition>
                        </div>

                        {toastVisible && (
                            <Toast open={toastVisible} onOpenChange={setToastVisible} className="bg-green-500 text-white">
                                <ToastTitle>Sucesso</ToastTitle>
                                <ToastDescription>Paciente cadastrado com sucesso!</ToastDescription>
                            </Toast>
                        )}

                        {/* Mensagens de erro */}
                        <div className="col-span-3">
                            {submitError && <div className="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">{submitError}</div>}

                            {Object.keys(errors).length > 0 && (
                                <div className="mb-4 rounded-lg bg-red-100 p-4 text-sm text-red-700">
                                    <h3 className="font-bold">Erros no formulário:</h3>
                                    <ul className="mt-2 list-disc pl-5">
                                        {Object.entries(errors).map(([field, message]) => (
                                            <li key={field}>{message}</li>
                                        ))}
                                    </ul>
                                </div>
                            )}
                        </div>
                    </form>
                </PatientLayout>
            </AppLayout>
            <ToastViewport />
        </ToastProvider>
    );
}
