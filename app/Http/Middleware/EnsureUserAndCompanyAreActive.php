<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureUserAndCompanyAreActive
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        if (!$user) {
            return $next($request);
        }

        if (isset($user->status) && $user->status !== 'active') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'Tu usuario aún no está activo o fue suspendido.']);
        }

        if ($user->company && $user->company->status !== 'active') {
            auth()->logout();

            return redirect()->route('login')
                ->withErrors(['email' => 'La empresa asociada no está activa.']);
        }

        return $next($request);
    }
}
