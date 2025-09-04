<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRoleMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, $roles)
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $user = Auth::user();
        
        // Verificar se o usuário tem pelo menos um dos perfis necessários
        $allowedRoles = explode(',', $roles);
        $hasAccess = false;
        
        foreach ($allowedRoles as $role) {
            if ($this->hasRole($user, trim($role))) {
                $hasAccess = true;
                break;
            }
        }
        
        if (!$hasAccess) {
            if ($request->expectsJson()) {
                return response()->json(['error' => 'Acesso negado'], 403);
            }
            
            return redirect()->route('dashboard')
                ->with('error', 'Você não tem permissão para acessar esta área.');
        }

        return $next($request);
    }

    /**
     * Verifica se o usuário tem o perfil especificado
     */
    protected function hasRole($user, $role)
    {
        switch ($role) {
            case 'admin':
                return $user->isAdmin();
            case 'tecnico':
                return $user->isTecnico() || $user->isAdmin();
            case 'cliente':
                return $user->isCliente() || $user->isTecnico() || $user->isAdmin();
            case 'cliente_gestor':
                return $user->isClienteGestor() || $user->isTecnico() || $user->isAdmin();
            case 'cliente_funcionario':
                return $user->isClienteFuncionario() || $user->isClienteGestor() || $user->isTecnico() || $user->isAdmin();
            case 'staff':
                return $user->isTecnico() || $user->isAdmin();
            default:
                return false;
        }
    }
}
