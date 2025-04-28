<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Verifica se o usuário está autenticado e é admin
        if (Auth::check() && Auth::user()->isAdmin()) {
            return $next($request);
        }
        
        // Redireciona para a página inicial se não for admin
        return redirect('/')->with('error', 'Você não tem permissão para acessar esta página.');
    }
}