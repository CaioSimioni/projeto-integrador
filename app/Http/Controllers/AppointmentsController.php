<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StoreAppointmentRequest;
use App\Http\Requests\UpdateAppointmentRequest;

class AppointmentsController extends Controller
{
    public function index(Request $request) {
    $appointments = Appointment::with('patient')->orderBy('appointment_date', 'desc')->get();
    $patients = Patient::all();

    return Inertia::render('appointments/index', [
        'appointments' => $appointments,
        'patients' => $patients,
    ]);
}

    public function store(StoreAppointmentRequest $request)
    {
        $appointment = Appointment::create($request->validated());

        return redirect()->route('appointments.index')->with('success', 'Consulta agendada com sucesso!');
    }

    public function update(UpdateAppointmentRequest $request, Appointment $appointment)
    {
        $appointment->update($request->validated());

        return redirect()->route('appointments.index')->with('success', 'Consulta atualizada com sucesso!');
    }

    public function destroy(Appointment $appointment)
    {
        $appointment->delete();

        return redirect()->route('appointments.index')->with('success', 'Consulta excluída com sucesso!');
    }
}
