<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Material;
use Inertia\Inertia;
use phpDocumentor\Reflection\PseudoTypes\Numeric_;

class MaterialController extends Controller
{
    public function index()
    {
        $materials = Material::All();
        return Inertia::render('inventory/materials', [
            'materials' => $materials,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        Material::create($validated);

        return redirect()->back()->with('success', 'Material created successfully!');
    }

    public function update(Request $request, Material $material)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'quantity' => 'required|integer|min:0',
            'expiration_date' => 'nullable|date',
            'description' => 'nullable|string',
        ]);

        $material->update($validated);

        return redirect()->back()->with('success', 'Material updated successfully!');
    }

    public function destroy(Material $material)
    {
        $material->delete();

        return redirect()->back()->with('success', 'Material deleted successfully!');
    }

    public function materialsQuantity()
    {
        $materials = Material::all();
        $totalQuantity = 0;

        foreach ($materials as $material) {
            $totalQuantity += $material->quantity;
        }

        return response()->json(['totalQuantity' => $totalQuantity]);
    }
}
