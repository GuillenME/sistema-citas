<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserActive
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next)
    {
        if (auth()->check() && !auth()->user()->activo) {
            auth()->logout();
            return redirect('/login')->withErrors([
                'email' => 'Tu cuenta ha sido desactivada.'
            ]);
        }

        return $next($request);
    }
}
