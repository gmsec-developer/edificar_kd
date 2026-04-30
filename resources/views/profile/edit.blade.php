@extends('layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Mi perfil</h1>
    <p class="text-sm text-gray-500">Administra tu información personal, contraseña y cuenta.</p>
</div>

<div class="space-y-6">
    <div class="bg-white shadow rounded-lg p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-profile-information-form')
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="max-w-xl">
            @include('profile.partials.update-password-form')
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <div class="max-w-xl">
            @include('profile.partials.delete-user-form')
        </div>
    </div>
</div>
@endsection