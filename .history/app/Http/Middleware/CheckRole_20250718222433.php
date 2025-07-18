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
   public function handle(Request $request, Closure $next): Response
{
    // Kullanıcı giriş yapmamışsa login sayfasına yönlendir
    if (!auth()->check()) {
        return redirect()->route('login');
    }

    // Kullanıcı giriş yapmış ama yetkili değilse
    if (!in_array(auth()->user()->role, ['admin', 'instructor'])) {
        abort(403, 'Yetkisiz erişim');
    }

    return $next($request);
}
}
