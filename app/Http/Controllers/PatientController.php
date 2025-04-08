<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    public function index(Request $request) {
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

    public function destroy(Patient $patient) {
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente excluído com sucesso!');
    }
}
