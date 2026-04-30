<?php

namespace App\Http\Requests\Auth;

use App\Models\Company;
use App\Models\User;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class LoginRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'company_code' => ['required', 'string'],
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ];
    }

    public function authenticate(): void
    {
        $this->ensureIsNotRateLimited();

        $company = Company::where('code', Str::upper(trim($this->company_code)))->first();

        if (! $company) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'company_code' => 'La empresa ingresada no existe.',
            ]);
        }

        if ($company->status !== 'active') {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'company_code' => 'La empresa ingresada no está activa.',
            ]);
        }

        $user = User::withoutGlobalScopes()
            ->where('company_id', $company->id)
            ->where('email', $this->email)
            ->first();

        if ($user && isset($user->is_active) && ! $user->is_active) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Este usuario está inactivo. Contacte al administrador.',
            ]);
        }

        if ($user && isset($user->status) && $user->status !== 'active') {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => 'Este usuario aún no está activo o fue suspendido.',
            ]);
        }

        if (! Auth::attempt([
            'email' => $this->email,
            'password' => $this->password,
            'company_id' => $company->id,
        ], $this->boolean('remember'))) {
            RateLimiter::hit($this->throttleKey());

            throw ValidationException::withMessages([
                'email' => trans('auth.failed'),
            ]);
        }

        RateLimiter::clear($this->throttleKey());
    }

    public function ensureIsNotRateLimited(): void
    {
        if (! RateLimiter::tooManyAttempts($this->throttleKey(), 5)) {
            return;
        }

        event(new Lockout($this));

        $seconds = RateLimiter::availableIn($this->throttleKey());

        throw ValidationException::withMessages([
            'email' => trans('auth.throttle', [
                'seconds' => $seconds,
                'minutes' => ceil($seconds / 60),
            ]),
        ]);
    }

    public function throttleKey(): string
    {
        return Str::transliterate(
            Str::lower($this->string('company_code')).'|'.
            Str::lower($this->string('email')).'|'.
            $this->ip()
        );
    }
}