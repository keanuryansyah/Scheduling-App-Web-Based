<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // 1. Cek apakah user login?
        if (! $request->user()) {
            return redirect('/');
        }

        // 2. Ambil role user saat ini (dari relasi users -> roles)
        // Pastikan di model User.php ada public function role()
        $userRole = $request->user()->role->name;

        // 3. Cek apakah role user ada di daftar yang dibolehkan
        // Contoh pemanggilan: middleware('role:boss,admin') -> $roles = ['boss', 'admin']
        if (in_array($userRole, $roles)) {
            return $next($request);
        }

        // 4. Jika tidak cocok, tolak akses
        abort(403, 'Anda tidak memiliki akses ke halaman ini.');
    }
}