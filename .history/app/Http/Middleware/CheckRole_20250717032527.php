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
    public function handle(Request $request, Closure $next, ...$roles)
    {
        // $user = $request->user();

        // if (!$user) {
        //     abort(401, 'Kullanıcı oturumu bulunamadı.');
        // }

        // // Rol karşılaştırmasını case-insensitive yapmak iyi olur
        // if (!in_array(strtolower($user->role), array_map('strtolower', $roles))) {
        //     abort(403, 'Yetkiniz yok.');
        // }

       return $next($request);
    }
}
