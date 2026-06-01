<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CompanyController extends Controller
{
    public function index()
    {
        $companies = Company::latest()->get();

        return view('companies.index', compact('companies'));
    }

    public function create()
    {
        return view('companies.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies,code',
            'short_code' => 'required|string|max:10|unique:companies,short_code',
            'max_users' => 'required|integer|min:1',
            'logo' => 'nullable|image|max:2048',
        ]);

        $logoPath = null;

        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('companies', 'public');
        }

        Company::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'short_code' => strtoupper($request->short_code),
            'max_users' => $request->max_users,
            'logo' => $logoPath,
            'status' => 'active',
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Empresa creada correctamente');
    }

    public function edit(Company $company)
    {
        return view('companies.edit', compact('company'));
    }

    public function update(Request $request, Company $company)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:50|unique:companies,code,' . $company->id,
            'short_code' => 'required|string|max:10|unique:companies,short_code,' . $company->id,
            'max_users' => 'required|integer|min:1',
            'logo' => 'nullable|image|max:2048',
        ]);

        $logoPath = $company->logo;

        if ($request->hasFile('logo')) {

            if ($company->logo) {
                Storage::disk('public')->delete($company->logo);
            }

            $logoPath = $request->file('logo')->store('companies', 'public');
        }

        $company->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'short_code' => strtoupper($request->short_code),
            'max_users' => $request->max_users,
            'logo' => $logoPath,
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Empresa actualizada correctamente');
    }


    public function mySettings()
    {
        $company = auth()->user()->company;

        abort_if(!$company, 403, 'Usuario sin empresa asignada.');

        return view('companies.my-settings', compact('company'));
    }

    public function updateMySettings(Request $request)
    {
        $company = auth()->user()->company;

        abort_if(!$company, 403, 'Usuario sin empresa asignada.');

        $request->validate([
            'default_profit_percent' => 'required|numeric|min:0|max:100',
            'default_waste_percent' => 'required|numeric|min:0|max:100',
            'tax_percent' => 'required|numeric|min:0|max:100',
            'default_currency' => 'required|string|max:10',
            'require_validation_before_save' => 'nullable|boolean',
            'allow_project_replace' => 'nullable|boolean',
        ]);

        $settings = $company->settings ?? [];

        $settings['default_profit_percent'] = (float) $request->default_profit_percent;
        $settings['default_waste_percent'] = (float) $request->default_waste_percent;
        $settings['tax_percent'] = (float) $request->tax_percent;
        $settings['default_currency'] = strtoupper($request->default_currency);
        $settings['require_validation_before_save'] = $request->boolean('require_validation_before_save');
        $settings['allow_project_replace'] = $request->boolean('allow_project_replace');

        $company->settings = $settings;
        $company->save();

        return back()->with('success', 'Parametros de empresa actualizados correctamente');
    }
    public function destroy(Company $company)
    {
        $company->delete();

        return back()->with('success', 'Empresa eliminada');
    }

    public function toggle(Company $company)
    {
        $company->status = $company->status === 'active' ? 'inactive' : 'active';
        $company->save();

        return back()->with('success', 'Estado de empresa actualizado');
    }
}