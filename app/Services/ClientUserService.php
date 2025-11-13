<?php

namespace App\Services;

use App\Models\Client;
use App\Models\User;
use App\Models\ClientContact;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ClientUserService
{
    /**
     * Criar novo usuário para uma empresa
     */
    public function createUser(Client $client, array $data): ClientContact
    {
        return DB::transaction(function () use ($client, $data) {
            // Criar usuário no sistema de autenticação
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['user_type'],
                'is_active' => true,
            ]);

            // Criar contato da empresa vinculado ao usuário
            $contact = ClientContact::create([
                'client_id' => $client->id,
                'user_id' => $user->id, // FK direta
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'position' => $data['position'] ?? null,
                'department' => $data['department'] ?? null,
                'user_type' => $data['user_type'],
                'receive_notifications' => $data['receive_notifications'] ?? false,
                'is_primary' => false,
                'is_active' => true,
            ]);

            Log::info('ClientUserService: Usuário criado com sucesso', [
                'client_id' => $client->id,
                'user_id' => $user->id,
                'contact_id' => $contact->id,
                'email' => $data['email']
            ]);

            return $contact->load('user');
        });
    }

    /**
     * Atualizar usuário existente
     */
    public function updateUser(ClientContact $contact, array $data): ClientContact
    {
        return DB::transaction(function () use ($contact, $data) {
            // Atualizar dados do contato
            $contact->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $data['phone'] ?? null,
                'position' => $data['position'] ?? null,
                'department' => $data['department'] ?? null,
                'user_type' => $data['user_type'],
                'receive_notifications' => $data['receive_notifications'] ?? false,
            ]);

            // Sincronizar com usuário se existir
            if ($contact->user) {
                $userUpdate = [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'role' => $data['user_type'],
                ];

                // Atualizar senha se fornecida
                if (!empty($data['new_password'])) {
                    $userUpdate['password'] = Hash::make($data['new_password']);
                }

                $contact->user->update($userUpdate);
            }

            Log::info('ClientUserService: Usuário atualizado com sucesso', [
                'contact_id' => $contact->id,
                'user_id' => $contact->user_id,
                'email' => $data['email']
            ]);

            return $contact->load('user');
        });
    }

    /**
     * Alternar status do usuário (ativo/inativo)
     */
    public function toggleStatus(ClientContact $contact): ClientContact
    {
        return DB::transaction(function () use ($contact) {
            // Não permitir desativar contato principal
            if ($contact->is_primary) {
                throw new \Exception('Não é possível desativar o contato principal.');
            }

            $newStatus = !$contact->is_active;

            // Atualizar status do contato
            $contact->update(['is_active' => $newStatus]);

            // Sincronizar status com usuário
            if ($contact->user) {
                $contact->user->update(['is_active' => $newStatus]);
            }

            Log::info('ClientUserService: Status alterado', [
                'contact_id' => $contact->id,
                'user_id' => $contact->user_id,
                'new_status' => $newStatus
            ]);

            return $contact->load('user');
        });
    }

    /**
     * Sincronizar dados entre ClientContact e User
     */
    public function syncUserData(ClientContact $contact): void
    {
        if (!$contact->user) {
            return;
        }

        $contact->user->update([
            'name' => $contact->name,
            'email' => $contact->email,
            'role' => $contact->user_type,
            'is_active' => $contact->is_active,
        ]);

        Log::info('ClientUserService: Dados sincronizados', [
            'contact_id' => $contact->id,
            'user_id' => $contact->user_id
        ]);
    }

    /**
     * Verificar se usuário pode gerenciar empresa
     */
    public function canManageClient(User $user, Client $client): bool
    {
        // Admin e técnico podem gerenciar qualquer empresa
        if ($user->isAdmin() || $user->isTecnico()) {
            return true;
        }

        // Gestor pode gerenciar apenas sua própria empresa
        if ($user->isClienteGestor()) {
            return $client->contacts()
                ->where('user_id', $user->id)
                ->where('user_type', 'cliente_gestor')
                ->exists();
        }

        return false;
    }

    /**
     * Obter estatísticas dos usuários da empresa
     */
    public function getClientUserStats(Client $client): array
    {
        $contacts = $client->contacts;

        return [
            'total' => $contacts->count(),
            'active' => $contacts->where('is_active', true)->count(),
            'managers' => $contacts->where('user_type', 'cliente_gestor')->count(),
            'employees' => $contacts->where('user_type', 'cliente_funcionario')->count(),
            'with_notifications' => $contacts->where('receive_notifications', true)->count(),
        ];
    }
}
