<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string  $roles  (bisa lebih dari satu, pisahkan dengan koma)
     * @return mixed
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        // Jika user belum login, redirect ke login
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();

        // Dukung multi-role (misal 'admin,siswa')
        $allowedRoles = explode(',', $roles);

        if (!in_array($user->role, $allowedRoles)) {
            // Bisa redirect ke dashboard sesuai role jika mau
            // return redirect()->route($user->role . '.dashboard');

            // Atau abort 403
            abort(403, 'Akses ditolak karena role tidak sesuai.');
        }

        return $next($request);
    }
}
