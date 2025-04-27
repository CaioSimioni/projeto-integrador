import { LucideIcon } from 'lucide-react';
import type { Config } from 'ziggy-js';

export interface Auth {
    user: User;
}

export interface BreadcrumbItem {
    title: string;
    href: string;
}

export interface NavGroup {
    title: string;
    items: NavItem[];
}

export interface NavItem {
    title: string;
    href: string;
    icon?: LucideIcon | null;
    isActive?: boolean;
}

export interface SharedData {
    name: string;
    quote: { message: string; author: string };
    auth: Auth;
    usersQuantity: number;
    patientsNumber: number;
    patientsQuantity: number;
    appointmentsQuantity: number;
    ziggy: Config & { location: string };
    analytics: Analytics;
    [key: string]: unknown;
}

export interface User {
    id: number;
    name: string;
    email: string;
    role: string;
    avatar?: string;
    email_verified_at: string | null;
    created_at: string;
    updated_at: string;
    [key: string]: unknown; // This allows for additional properties...
}

export interface Material {
    id: number;
    name: string;
    type: string;
    description: string | null;
    experation_date: string | null;
    quantity: number;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
}

export interface Patient {
    id: number;
    name: string;
    cpf: string;
    birth_date: string | Date; // Permite string ou Date
    phone: string;
    email: string;
    sus: string;
    medical_record: string;
    address: string;
    insurance: string;
    is_active: boolean;
    created_at: string;
    updated_at: string;
    [key: string]: unknown;
}

export interface Appointment {
    id: number;
    patient_id: number;
    appointment_date: string;
    notes: string | null;
    created_at: string;
    updated_at: string;
    patient?: Patient;
    [key: string]: unknown;
}

export interface Analytics {
    total_patients: number;
    active_patients: number;
    inactive_patients: number;
    total_appointments: number;

    upcoming_appointments: number;
    appointments_last_month: number;
    average_age: number | null;
    min_age: number | null;
    max_age: number | null;
    average_appointments_per_patient: number;
    active_percentage: number;
    inactive_percentage: number;
    age_groups: {
        '0-18': number;
        '19-35': number;
        '36-60': number;
        '60+': number;
    };
}
