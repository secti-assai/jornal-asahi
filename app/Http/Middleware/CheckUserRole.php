<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckUserRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check()) {
            return redirect('login');
        }

        $user = Auth::user();
        
        switch ($role) {
            case 'admin':
                if (!$user->isAdmin()) {
                    abort(403, 'Acesso não autorizado.');
                }
                break;
            case 'approver':
                if (!$user->isApprover() && !$user->isAdmin()) {
                    abort(403, 'Acesso não autorizado.');
                }
                break;
            case 'reporter':
                if (!$user->isReporter() && !$user->isApprover() && !$user->isAdmin()) {
                    abort(403, 'Acesso não autorizado.');
                }
                break;
        }

        return $next($request);
    }
}