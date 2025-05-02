<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Http\Requests\StorePatientRequest;
use App\Http\Requests\UpdatePatientRequest;

class PatientController extends Controller
{
    public function index()
    {
        $dashboard_infos = [
            'total_patients' => Patient::count(),
            'active_patients' => Patient::where('is_active', true)->count(),
            'inactive_patients' => Patient::where('is_active', false)->count(),
        ];

        return Inertia::render('patients/index', [
            'dashboard_infos' => $dashboard_infos,
        ]);
    }

    public function create()
    {
        return Inertia::render('patients/patients-create');
    }

    public function list()
    {
        return Inertia::render('patients/patients-list', [
            'patients' => Patient::orderBy('full_name')->get(),
        ]);
    }

    public function store(StorePatientRequest $request)
    {
        try {
            $data = $request->validated();

            $patientData = [
                'full_name' => $data['full_name'],
                'cpf' => $data['cpf'],
                'birth_date' => $data['birth_date'],
                'gender' => $data['gender'],
                'mother_name' => $data['mother_name'],
                'father_name' => $data['father_name'] ?? null,
                'sus_number' => $data['sus_number'] ?? null,
                'medical_record' => $data['medical_record'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'birth_place' => $data['birth_place'] ?? null,
                'state' => $data['state'] ?? null,
                'cep' => $data['cep'] ?? null,
                'address' => $data['address'] ?? null,
                'number' => $data['number'] ?? null,
                'complement' => $data['complement'] ?? null,
                'neighborhood' => $data['neighborhood'] ?? null,
                'city' => $data['city'] ?? null,
                'state_address' => $data['state_address'] ?? null,
                'country' => $data['country'] ?? null,
                'phone' => $data['phone'] ?? null,
            ];

            Patient::create($patientData);

            return redirect()->route('patients.create')->with('success', 'Paciente cadastrado com sucesso!');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Erro ao cadastrar paciente: ' . $e->getMessage()]);
        }
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

    public function exams(Patient $patient)
    {
        return Inertia::render('patients/exams', [
            'patient' => $patient,
            'exams' => $patient->exams,
        ]);
    }
}
