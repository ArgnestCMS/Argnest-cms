<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureNotInstalled
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->installed()) {
            return $request->expectsJson()
                ? response()->json(['message' => 'Argnest kurulumu zaten tamamlanmis.'], 403)
                : redirect()->route('home');
        }

        return $next($request);
    }

    private function installed(): bool
    {
        return filter_var(env('APP_INSTALLED', false), FILTER_VALIDATE_BOOL)
            || file_exists(storage_path('framework/argnest-installed.lock'));
    }
}
