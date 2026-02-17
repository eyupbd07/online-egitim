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
    public function handle(Request $request, Closure $next, string $role): Response
    {
        // 1. Kullanıcı giriş yapmamışsa login sayfasına at
        if (! $request->user()) {
            return redirect()->route('login');
        }

        // 2. Kullanıcının rolü, istenen rolle eşleşmiyor mu?
        // Örn: Sayfa 'admin' istiyor ama kullanıcı 'student' ise
        if ($request->user()->role !== $role) {
            
            // Yetkisiz ise Dashboard'a geri gönderelim veya 403 hatası verelim
            abort(403, 'Bu sayfaya erişim yetkiniz yok.');
        }

        return $next($request);
    }
}   