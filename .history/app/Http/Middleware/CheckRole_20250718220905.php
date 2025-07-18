<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    // Kullanıcının giriş yapıp yapmadığını kontrol et
    if (!Auth::check()) {
        return redirect()->route('login');
    }

    // Kullanıcı rolünü kontrol et
    $user = Auth::user();
    if (!in_array($user->role, ['admin', 'instructor'])) {
        abort(403, 'Unauthorized action.');
    }

    return $next($request);
}
}
