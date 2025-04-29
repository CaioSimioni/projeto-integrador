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
        // Alterações para incluir contagem e dados dos pacientes
        $dashboard_infos = [
            'totalPatients' => Patient::count(),
            'activePatients' => Patient::where('is_active', true)->count(),
            'inactivePatients' => Patient::where('is_active', false)->count(),
        ];

        return Inertia::render('patients/index', [
            'dashboard_infos' => $dashboard_infos,
        ]);
    }

    public function create()
    {
        // Renderiza o formulário de criação de paciente
        return Inertia::render('patients/patients-create');
    }

    public function list()
    {
        // Lista todos os pacientes
        return Inertia::render('patients/patients-list', [
            'patients' => Patient::orderBy('name')->get(),
        ]);
    }

    public function store(StorePatientRequest $request)
    {
        try {
            $data = $request->validated();

            // Converter camelCase para snake_case
            $patientData = [
                'full_name' => $data['fullName'],
                'cpf' => preg_replace('/[^0-9]/', '', $data['cpf']), // Remove formatação
                'birth_date' => $data['birthDate'],
                'gender' => $data['gender'],
                'mother_name' => $data['motherName'],
                'father_name' => $data['fatherName'] ?? null,
                'sus_number' => $data['susNumber'] ?? null,
                'medical_record' => $data['medicalRecord'] ?? null,
                'nationality' => $data['nationality'] ?? null,
                'birth_place' => $data['birthPlace'] ?? null,
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
        // Atualiza as informações do paciente, incluindo os novos campos
        $patient->update($request->validated());

        return redirect()->route('patients.index')->with('success', 'Paciente atualizado com sucesso!');
    }

    public function destroy(Patient $patient)
    {
        // Exclui o paciente
        $patient->delete();

        return redirect()->route('patients.index')->with('success', 'Paciente excluído com sucesso!');
    }

    // Aqui podemos adicionar o método para listar os exames do paciente
    public function exams(Patient $patient)
    {
        // Exibe os exames do paciente (exemplo de implementação)
        return Inertia::render('patients/exams', [
            'patient' => $patient,
            'exams' => $patient->exams, // Relacionamento com os exames
        ]);
    }
}
