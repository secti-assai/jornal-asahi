<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $minLevel): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $userLevel = Auth::user()->role->level;
        
        if ($userLevel < $minLevel) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}