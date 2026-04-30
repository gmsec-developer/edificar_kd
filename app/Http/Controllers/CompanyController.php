<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;

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
            'max_users' => 'required|integer|min:1',
        ]);

        Company::create([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'max_users' => $request->max_users,
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
            'max_users' => 'required|integer|min:1',
        ]);

        $company->update([
            'name' => $request->name,
            'code' => strtoupper($request->code),
            'max_users' => $request->max_users,
        ]);

        return redirect()->route('companies.index')
            ->with('success', 'Empresa actualizada correctamente');
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