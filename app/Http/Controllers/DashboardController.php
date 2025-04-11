<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function analytics()
    {
        $totalPatients = Patient::count();
        $activePatients = Patient::where('is_active', true)->count();
        $inactivePatients = Patient::where('is_active', false)->count();
        $totalAppointments = Appointment::count();
        $upcomingAppointments = Appointment::where('appointment_date', '>', now())->count();
        $appointmentsLastMonth = Appointment::whereBetween('appointment_date', [now()->subDays(30), now()])->count();

        // Calcula a média, idade mínima e máxima diretamente no banco de dados (compatível com SQLite)
        $ageStats = Patient::whereNotNull('birth_date')
            ->where('birth_date', '<=', now()) // Garante que a data de nascimento esteja no passado
            ->selectRaw(
                '
                AVG((julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25) as average_age,
                MIN((julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25) as min_age,
                MAX((julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25) as max_age
            '
            )
            ->first();

        $averageAge = $ageStats->average_age ? round($ageStats->average_age, 2) : null;
        $minAge = $ageStats->min_age ? round($ageStats->min_age, 2) : null;
        $maxAge = $ageStats->max_age ? round($ageStats->max_age, 2) : null;

        $averageAppointmentsPerPatient = $totalPatients > 0 ? round($totalAppointments / $totalPatients, 2) : 0;

        $activePercentage = $totalPatients > 0 ? round(($activePatients / $totalPatients) * 100, 2) : 0;
        $inactivePercentage = $totalPatients > 0 ? round(($inactivePatients / $totalPatients) * 100, 2) : 0;

        // Calcula grupos de idade
        $ageGroups = [
            '0-18' => Patient::whereNotNull('birth_date')
                ->where('birth_date', '<=', now())
                ->whereRaw('(julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25 <= 18')
                ->count(),
            '19-35' => Patient::whereNotNull('birth_date')
                ->where('birth_date', '<=', now())
                ->whereRaw('(julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25 BETWEEN 19 AND 35')
                ->count(),
            '36-60' => Patient::whereNotNull('birth_date')
                ->where('birth_date', '<=', now())
                ->whereRaw('(julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25 BETWEEN 36 AND 60')
                ->count(),
            '60+' => Patient::whereNotNull('birth_date')
                ->where('birth_date', '<=', now())
                ->whereRaw('(julianday(CURRENT_DATE) - julianday(birth_date)) / 365.25 > 60')
                ->count(),
        ];

        return [
            'total_patients' => $totalPatients,
            'active_patients' => $activePatients,
            'inactive_patients' => $inactivePatients,
            'total_appointments' => $totalAppointments,
            'upcoming_appointments' => $upcomingAppointments,
            'appointments_last_month' => $appointmentsLastMonth,
            'average_age' => $averageAge,
            'min_age' => $minAge,
            'max_age' => $maxAge,
            'average_appointments_per_patient' => $averageAppointmentsPerPatient,
            'active_percentage' => $activePercentage,
            'inactive_percentage' => $inactivePercentage,
            'age_groups' => $ageGroups,
        ];
    }

    public function index(Request $request)
    {
        $analytics = $this->analytics();

        return Inertia::render('dashboard', [
            'analytics' => $analytics,
        ]);
    }
}
