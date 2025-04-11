<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    /* private function analitics()
    {
        $totalPatients = Patient::count();
        $activePatients = Patient::where('is_active', true)->count();
        $inactivePatients = Patient::where('is_active', false)->count();
        $totalAppointments = \App\Models\Appointment::count();
        $patientsWithoutAppointments = Patient::doesntHave('appointments')->count();
        $upcomingAppointments = \App\Models\Appointment::where('appointment_date', '>', now())->count();
        $appointmentsLastMonth = \App\Models\Appointment::whereBetween('appointment_date', [now()->subDays(30), now()])->count();

        // Calcula a média de idade
        $averageAge = Patient::whereNotNull('birth_date')
            ->get()
            ->map(function ($patient) {
                return now()->diffInYears($patient->birth_date);
            })
            ->average();

        $minAge = Patient::whereNotNull('birth_date')
            ->get()
            ->map(function ($patient) {
                return now()->diffInYears($patient->birth_date);
            })
            ->min();

        $maxAge = Patient::whereNotNull('birth_date')
            ->get()
            ->map(function ($patient) {
                return now()->diffInYears($patient->birth_date);
            })
            ->max();

        $averageAppointmentsPerPatient = $totalPatients > 0 ? round($totalAppointments / $totalPatients, 2) : 0;

        $activePercentage = $totalPatients > 0 ? round(($activePatients / $totalPatients) * 100, 2) : 0;
        $inactivePercentage = $totalPatients > 0 ? round(($inactivePatients / $totalPatients) * 100, 2) : 0;

        $ageGroups = [
            '0-18' => Patient::whereNotNull('birth_date')->get()->filter(fn($p) => now()->diffInYears($p->birth_date) <= 18)->count(),
            '19-35' => Patient::whereNotNull('birth_date')
                ->get()
                ->filter(fn($p) => now()->diffInYears($p->birth_date) >= 19 && now()->diffInYears($p->birth_date) <= 35)
                ->count(),
            '36-60' => Patient::whereNotNull('birth_date')
                ->get()
                ->filter(fn($p) => now()->diffInYears($p->birth_date) >= 36 && now()->diffInYears($p->birth_date) <= 60)
                ->count(),
            '60+' => Patient::whereNotNull('birth_date')->get()->filter(fn($p) => now()->diffInYears($p->birth_date) > 60)->count(),
        ];

        return [
            'total_patients' => $totalPatients,
            'active_patients' => $activePatients,
            'inactive_patients' => $inactivePatients,
            'total_appointments' => $totalAppointments,
            'patients_without_appointments' => $patientsWithoutAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'appointments_last_month' => $appointmentsLastMonth,
            'average_age' => $averageAge ? round($averageAge, 2) : null, // Arredonda para 2 casas decimais
            'min_age' => $minAge,
            'max_age' => $maxAge,
            'average_appointments_per_patient' => $averageAppointmentsPerPatient,
            'active_percentage' => $activePercentage,
            'inactive_percentage' => $inactivePercentage,
            'age_groups' => $ageGroups,
        ];
    } */

    public function index(Request $request)
    {
        $patients = Patient::orderBy('created_at', 'desc')->get();

        return Inertia::render('patients/index', [
            'patients' => $patients,
        ]);
    }

    public function store(StorePatientRequest $request)
    {
        $patient = Patient::create($request->validated());

        return redirect()->route('patients.index')->with('success', 'Paciente cadastrado com sucesso!');
    }

    public function update(UpdatePatientRequest $request, Patient $patient)
    {
        $patient->update($request->validated());

        return redirect()->route('patients.index')->with('success', 'Paciente atualizado com sucesso!');
    }

    public function destroy(Patient $patient)
    {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente excluído com sucesso!');
    }
}
