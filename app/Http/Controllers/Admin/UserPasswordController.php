<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserPasswordController extends Controller
{
    /**
     * Mostrar formulário de alteração de senha
     */
    public function show(User $user)
    {
        // Verificar se é admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }

        return view('admin.users.change-password', compact('user'));
    }

    /**
     * Alterar senha do usuário
     */
    public function update(Request $request, User $user)
    {
        // Verificar se é admin
        if (!auth()->user()->isAdmin()) {
            abort(403, 'Acesso negado.');
        }

        $validated = $request->validate([
            'new_password' => 'required|string|min:8|confirmed',
            'admin_confirmation' => 'required|accepted',
            'reason' => 'nullable|string|max:500'
        ], [
            'new_password.required' => 'A nova senha é obrigatória.',
            'new_password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'new_password.confirmed' => 'A confirmação da senha não confere.',
            'admin_confirmation.accepted' => 'Você deve confirmar que está ciente da mudança de senha.',
        ]);

        try {
            $oldPasswordHash = $user->password;
            
            // Atualizar senha
            $user->update([
                'password' => Hash::make($validated['new_password'])
            ]);

            // Registrar no log de auditoria
            AuditLog::create([
                'event_type' => 'password_changed_by_admin',
                'auditable_type' => User::class,
                'auditable_id' => $user->id,
                'user_id' => auth()->id(),
                'user_type' => User::class,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'old_values' => [
                    'password_hash' => substr($oldPasswordHash, 0, 20) . '...' // Apenas parte do hash por segurança
                ],
                'new_values' => [
                    'password_changed' => true,
                    'changed_by_admin' => auth()->user()->name,
                    'admin_id' => auth()->id()
                ],
                'description' => 'Senha alterada pelo administrador: ' . auth()->user()->name,
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'metadata' => [
                    'reason' => $validated['reason'] ?? 'Não informado',
                    'admin_confirmation' => true,
                    'target_user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ]
                ]
            ]);

            // Log adicional para segurança
            Log::warning('Senha alterada pelo administrador', [
                'admin_id' => auth()->id(),
                'admin_name' => auth()->user()->name,
                'admin_email' => auth()->user()->email,
                'target_user_id' => $user->id,
                'target_user_name' => $user->name,
                'target_user_email' => $user->email,
                'target_user_role' => $user->role,
                'reason' => $validated['reason'] ?? 'Não informado',
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'timestamp' => now()->toISOString()
            ]);

            return redirect()
                ->route('admin.users.index')
                ->with('success', "Senha do usuário {$user->name} alterada com sucesso!");

        } catch (\Exception $e) {
            Log::error('Erro ao alterar senha pelo admin', [
                'admin_id' => auth()->id(),
                'target_user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()
                ->back()
                ->with('error', 'Erro ao alterar senha. Tente novamente.');
        }
    }
}
