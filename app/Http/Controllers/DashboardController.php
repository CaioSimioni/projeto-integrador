<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\Patient;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('dashboard', [
            'patientsNumber' => Patient::count(),
            'appointmentsQuantity' => Appointment::count(),
        ]);
    }
}
