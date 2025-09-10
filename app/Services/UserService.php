<?php

namespace App\Services;

use App\Models\User;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserService
{
    /**
     * Create a new user with associated client if needed
     */
    public function createUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            // If user is a client, create associated client and contact
            if ($data['role'] === 'cliente') {
                $this->createClientAndContact($user, $data);
            }

            return $user;
        });
    }

    /**
     * Update an existing user
     */
    public function updateUser(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            $user->update([
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
            ]);

            // Update password if provided
            if (!empty($data['password'])) {
                $user->update(['password' => Hash::make($data['password'])]);
            }

            // Handle client data if role is cliente
            if ($data['role'] === 'cliente') {
                $this->updateClientData($user, $data);
            }

            return $user->fresh();
        });
    }

    /**
     * Delete a user and associated data
     */
    public function deleteUser(User $user): bool
    {
        return DB::transaction(function () use ($user) {
            // Delete associated client if exists
            if ($user->client) {
                $user->client->delete();
            }

            return $user->delete();
        });
    }

    /**
     * Toggle user active status
     */
    public function toggleUserStatus(User $user): User
    {
        $user->update(['is_active' => !$user->is_active]);
        return $user;
    }

    /**
     * Create client and contact for client user
     */
    protected function createClientAndContact(User $user, array $data): void
    {
        $client = Client::create([
            'cnpj' => $data['cnpj'],
            'company_name' => $data['company_name'],
            'trade_name' => $data['company_name'],
            'is_active' => true,
        ]);

        ClientContact::create([
            'client_id' => $client->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_primary' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Update client data for existing client user
     */
    protected function updateClientData(User $user, array $data): void
    {
        if ($user->client) {
            $user->client->update([
                'cnpj' => $data['cnpj'],
                'company_name' => $data['company_name'],
                'trade_name' => $data['company_name'],
            ]);
        } else {
            // Create client if it doesn't exist
            $this->createClientAndContact($user, $data);
        }
    }
}
