<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->with(['roles', 'company']);

        if (!auth()->user()->hasRole('superadmin')) {
            $query->where('company_id', auth()->user()->company_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active' ? 1 : 0);
        }

        if ($request->filled('role')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('name', $request->role);
            });
        }

        if (auth()->user()->hasRole('superadmin') && $request->filled('company')) {
            $query->where('company_id', $request->company);
        }

        $users = $query->orderBy('name')->paginate(10)->withQueryString();
        $rolesQuery = \Spatie\Permission\Models\Role::orderByRaw("CASE WHEN name = 'superadmin' THEN 1 WHEN name = 'Administrador empresa' THEN 2 WHEN name = 'Supervisor' THEN 3 WHEN name = 'Disenador' THEN 4 WHEN name = 'Costos' THEN 5 WHEN name = 'Auditor' THEN 6 WHEN name = 'Visitante' THEN 7 ELSE 99 END")->orderBy('name');

        if (!auth()->user()->hasRole('superadmin')) {
            $rolesQuery->whereIn('name', ['Administrador empresa', 'Supervisor', 'Visitante']);
        }

        $roles = $rolesQuery->get();
        $companies = \App\Models\Company::orderBy('name')->get();

        return view('users.index', compact('users', 'roles', 'companies'));
    }

    public function create()
    {
        $rolesQuery = \Spatie\Permission\Models\Role::orderByRaw("CASE WHEN name = 'superadmin' THEN 1 WHEN name = 'Administrador empresa' THEN 2 WHEN name = 'Supervisor' THEN 3 WHEN name = 'Disenador' THEN 4 WHEN name = 'Costos' THEN 5 WHEN name = 'Auditor' THEN 6 WHEN name = 'Visitante' THEN 7 ELSE 99 END")->orderBy('name');

        if (!auth()->user()->hasRole('superadmin')) {
            $rolesQuery->whereIn('name', ['Administrador empresa', 'Supervisor', 'Visitante']);
        }

        $roles = $rolesQuery->get();
        $companies = \App\Models\Company::orderBy('name')->get();

        return view('users.create', compact('roles', 'companies'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'company_id' => ['required', 'exists:companies,id'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'exists:roles,name'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if (!auth()->user()->hasRole('superadmin') && $request->role === 'superadmin') {
            return back()
                ->withInput()
                ->withErrors(['role' => 'No tienes permisos para asignar el rol Superadmin.']);
        }

        if (!auth()->user()->hasRole('superadmin') && (int) $request->company_id !== (int) auth()->user()->company_id) {
            return back()
                ->withInput()
                ->withErrors(['company_id' => 'No puedes crear usuarios en otra empresa.']);
        }

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'avatar' => $avatarPath,
        ]);

        $user->company_id = $request->company_id;
        $user->save();

        $user->assignRole($request->role);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Creo usuario: ' . $user->email);

        return redirect()->route('users.index')
            ->with('success', 'Usuario creado correctamente');
    }

    public function edit(User $user)
    {
        $rolesQuery = \Spatie\Permission\Models\Role::orderByRaw("CASE WHEN name = 'superadmin' THEN 1 WHEN name = 'Administrador empresa' THEN 2 WHEN name = 'Supervisor' THEN 3 WHEN name = 'Disenador' THEN 4 WHEN name = 'Costos' THEN 5 WHEN name = 'Auditor' THEN 6 WHEN name = 'Visitante' THEN 7 ELSE 99 END")->orderBy('name');

        if (!auth()->user()->hasRole('superadmin')) {
            $rolesQuery->whereIn('name', ['Administrador empresa', 'Supervisor', 'Visitante']);
        }

        $roles = $rolesQuery->get();
        $companies = \App\Models\Company::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles', 'companies'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'company_id' => ['required', 'exists:companies,id'],
            'role' => ['required', 'exists:roles,name'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        if (!auth()->user()->hasRole('superadmin') && $user->hasRole('superadmin')) {
            return back()
                ->withErrors(['user' => 'No tienes permisos para modificar un usuario Superadmin.']);
        }

        if (!auth()->user()->hasRole('superadmin') && $request->role === 'superadmin') {
            return back()
                ->withInput()
                ->withErrors(['role' => 'No tienes permisos para asignar el rol Superadmin.']);
        }

        if (!auth()->user()->hasRole('superadmin') && (int) $request->company_id !== (int) auth()->user()->company_id) {
            return back()
                ->withInput()
                ->withErrors(['company_id' => 'No puedes mover usuarios a otra empresa.']);
        }

        $oldData = $user->only(['name', 'email', 'company_id', 'is_active', 'avatar']);
        $oldData['role'] = $user->roles->pluck('name')->first();

        $avatarPath = $user->avatar;

        if ($request->hasFile('avatar')) {
            if ($user->avatar) {
                Storage::disk('public')->delete($user->avatar);
            }

            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'company_id' => $request->company_id,
            'avatar' => $avatarPath,
        ]);

        $user->company_id = $request->company_id;
        $user->save();

        $user->syncRoles([$request->role]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'old' => $oldData,
                'new' => array_merge(
                    $user->only(['name', 'email', 'company_id', 'is_active', 'avatar']),
                    ['role' => $request->role]
                ),
            ])
            ->log('Actualizo usuario: ' . $user->email);

        return redirect()->route('users.index')
            ->with('success', 'Usuario actualizado correctamente');
    }

    public function toggle($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('superadmin')) {
            return redirect()->route('users.index')
                ->with('success', 'No puedes desactivar un superadmin');
        }

        $newStatus = !$user->is_active;

        $user->is_active = $newStatus;
        $user->status = $newStatus ? 'active' : 'inactive';
        $user->save();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Cambio estado usuario: ' . $user->email);

        return redirect()->route('users.index')
            ->with('success', 'Estado actualizado correctamente');
    }

    public function resetPassword($id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('superadmin')) {
            return redirect()->route('users.index')
                ->with('success', 'No puedes resetear la clave de un superadmin');
        }

        $newPassword = \Illuminate\Support\Str::random(8);

        $user->password = Hash::make($newPassword);
        $user->save();

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Reseteo clave usuario: ' . $user->email);

        return redirect()->route('users.index')
            ->with('success', 'Nueva clave generada: ' . $newPassword);
    }

    public function destroy(string $id)
    {
        $user = User::findOrFail($id);

        if ($user->hasRole('superadmin')) {
            return redirect()->route('users.index')
                ->with('success', 'No puedes eliminar un superadmin');
        }

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->log('Elimino usuario: ' . $user->email);

        if ($user->avatar) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuario eliminado correctamente');
    }
}



