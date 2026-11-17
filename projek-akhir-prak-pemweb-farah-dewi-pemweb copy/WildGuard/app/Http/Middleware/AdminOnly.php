<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

// Hanya admin yang boleh lewat. Belum login -> halaman masuk; user biasa -> 403.
class AdminOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        if (!$user) {
            return redirect()->guest(route('login'));
        }
        if ($user->role !== 'admin') {
            abort(403, 'Halaman ini khusus admin.');
        }
        return $next($request);
    }
}
