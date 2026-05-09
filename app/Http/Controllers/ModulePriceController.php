<?php

namespace App\Http\Controllers;

use App\Models\ModulePrice;
use Illuminate\Http\Request;

class ModulePriceController extends Controller
{
    public function index()
    {
        $modulePrices = ModulePrice::where('company_id', auth()->user()->company_id)
            ->orderBy('catalog_code')
            ->orderBy('reference')
            ->orderBy('dx')
            ->orderBy('dy')
            ->orderBy('dz')
            ->get();

        return view('module_prices.index', [
            'modulePrices' => $modulePrices,
        ]);
    }

    public function update(Request $request, ModulePrice $modulePrice)
    {
        if ($modulePrice->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $validated = $request->validate([
            'complexity_level' => 'required|string|max:50',
            'complexity_factor' => 'required|numeric|min:0',
            'labor_cost' => 'required|numeric|min:0',
            'indirect_cost' => 'required|numeric|min:0',
            'default_waste_percent' => 'required|numeric|min:0|max:100',
            'is_active' => 'nullable|boolean',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $modulePrice->update($validated);

        return redirect()->route('module-prices.index')
            ->with('success', 'Costo de modulo actualizado correctamente.');
    }
}