<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TerapisMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->canManagePatients()) {
            abort(403, 'Akses ditolak. Hanya admin dan terapis yang bisa mengakses halaman ini.');
        }

        return $next($request);
    }
}