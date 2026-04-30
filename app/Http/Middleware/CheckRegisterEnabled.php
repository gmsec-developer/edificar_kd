<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

class CheckRegisterEnabled
{
    public function handle(Request $request, Closure $next): Response
    {
        $setting = DB::table('settings')->where('key', 'allow_register')->first();

        if ($setting && $setting->value === 'false') {
            abort(403, 'Registro deshabilitado');
        }

        return $next($request);
    }
}