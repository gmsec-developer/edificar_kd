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
        $query = User::query()->with('roles');

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

        $users = $query->with('roles')->orderBy('name')->paginate(10)->withQueryString();
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

        return view('users.index', compact('users', 'roles'));
    }

    public function create()
    {
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

        return view('users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role' => ['required', 'exists:roles,name'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $avatarPath = null;

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_active' => true,
            'avatar' => $avatarPath,
        ]);

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
        $roles = \Spatie\Permission\Models\Role::orderBy('name')->get();

        return view('users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email,' . $user->id],
            'role' => ['required', 'exists:roles,name'],
            'avatar' => ['nullable', 'image', 'max:2048'],
        ]);

        $oldData = $user->only(['name', 'email', 'is_active', 'avatar']);
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
            'avatar' => $avatarPath,
        ]);

        $user->syncRoles([$request->role]);

        activity()
            ->causedBy(auth()->user())
            ->performedOn($user)
            ->withProperties([
                'old' => $oldData,
                'new' => array_merge(
                    $user->only(['name', 'email', 'is_active', 'avatar']),
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

        $user->is_active = !$user->is_active;
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