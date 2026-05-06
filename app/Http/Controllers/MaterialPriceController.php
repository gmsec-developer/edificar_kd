<?php

namespace App\Http\Controllers;

use App\Models\MaterialPrice;
use Illuminate\Http\Request;

class MaterialPriceController extends Controller
{
    public function index(Request $request)
    {
        $query = MaterialPrice::where('company_id', auth()->user()->company_id)
            ->orderBy('material_code')
            ->orderBy('color_code');

        if ($request->filled('search')) {
            $search = $request->input('search');

            $query->where(function ($q) use ($search) {
                $q->where('material_code', 'like', '%' . $search . '%')
                    ->orWhere('material_name', 'like', '%' . $search . '%')
                    ->orWhere('color_code', 'like', '%' . $search . '%')
                    ->orWhere('color_name', 'like', '%' . $search . '%');
            });
        }

        if ($request->input('status') === 'pending') {
            $query->where('unit_cost', 0);
        }

        if ($request->input('status') === 'priced') {
            $query->where('unit_cost', '>', 0);
        }

        $materials = $query->paginate(50)->withQueryString();

        $summary = [
            'total' => MaterialPrice::where('company_id', auth()->user()->company_id)->count(),
            'pending' => MaterialPrice::where('company_id', auth()->user()->company_id)->where('unit_cost', 0)->count(),
            'priced' => MaterialPrice::where('company_id', auth()->user()->company_id)->where('unit_cost', '>', 0)->count(),
        ];

        return view('material_prices.index', compact('materials', 'summary'));
    }

    public function edit(MaterialPrice $materialPrice)
    {
        if ($materialPrice->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        return view('material_prices.edit', compact('materialPrice'));
    }

    public function update(Request $request, MaterialPrice $materialPrice)
    {
        if ($materialPrice->company_id !== auth()->user()->company_id) {
            abort(403);
        }

        $data = $request->validate([
            'material_name' => 'required|string|max:255',
            'color_name' => 'nullable|string|max:255',
            'unit_type' => 'required|string|max:30',
            'unit_cost' => 'required|numeric|min:0',
            'is_active' => 'nullable',
        ]);

        $materialPrice->update([
            'material_name' => $data['material_name'],
            'color_name' => $data['color_name'] ?? null,
            'unit_type' => $data['unit_type'],
            'unit_cost' => $data['unit_cost'],
            'is_active' => $request->has('is_active'),
        ]);

        return redirect()
            ->route('material-prices.index')
            ->with('success', 'Precio de material actualizado correctamente.');
    }
}
