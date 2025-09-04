<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class CompanyUserController extends Controller
{
    /**
     * Exibe a lista de usuários da empresa do gestor
     */
    public function index(): View
    {
        $user = auth()->user();
        
        // Buscar a empresa do gestor
        $client = Client::whereHas('contacts', function ($query) use ($user) {
            $query->where('email', $user->email);
        })->firstOrFail();
        
        // Buscar todos os usuários da empresa
        $companyUsers = User::whereHas('clientContacts', function ($query) use ($client) {
            $query->where('client_id', $client->id);
        })->with('clientContacts')->get();
        
        return view('company.users.index', compact('companyUsers', 'client'));
    }

    /**
     * Exibe o formulário para criar novo usuário
     */
    public function create(): View
    {
        $user = auth()->user();
        
        // Buscar a empresa do gestor
        $client = Client::whereHas('contacts', function ($query) use ($user) {
            $query->where('email', $user->email);
        })->firstOrFail();
        
        return view('company.users.create', compact('client'));
    }

    /**
     * Armazena um novo usuário da empresa
     */
    public function store(Request $request): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Buscar a empresa do gestor
            $client = Client::whereHas('contacts', function ($query) use ($user) {
                $query->where('email', $user->email);
            })->firstOrFail();
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
                'position' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
            ]);
            
            DB::beginTransaction();
            
            // Criar o usuário
            $newUser = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => 'cliente_funcionario',
                'is_active' => true,
            ]);
            
            // Criar o contato na empresa
            $contact = ClientContact::create([
                'client_id' => $client->id,
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'position' => $validated['position'],
                'department' => $validated['department'],
                'is_primary' => false,
            ]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Funcionário adicionado com sucesso!',
                'user' => $newUser->load('clientContacts'),
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao criar funcionário: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Exibe o formulário para editar usuário
     */
    public function edit(User $companyUser): View
    {
        $user = auth()->user();
        
        // Verificar se o usuário pertence à empresa do gestor
        $client = Client::whereHas('contacts', function ($query) use ($user) {
            $query->where('email', $user->email);
        })->firstOrFail();
        
        // Verificar se o usuário a ser editado pertence à mesma empresa
        $contact = $companyUser->clientContacts()->where('client_id', $client->id)->first();
        
        if (!$contact) {
            abort(403, 'Usuário não pertence à sua empresa.');
        }
        
        return view('company.users.edit', compact('companyUser', 'client', 'contact'));
    }

    /**
     * Atualiza um usuário da empresa
     */
    public function update(Request $request, User $companyUser): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Buscar a empresa do gestor
            $client = Client::whereHas('contacts', function ($query) use ($user) {
                $query->where('email', $user->email);
            })->firstOrFail();
            
            // Verificar se o usuário pertence à empresa do gestor
            $contact = $companyUser->clientContacts()->where('client_id', $client->id)->first();
            
            if (!$contact) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não pertence à sua empresa.',
                ], 403);
            }
            
            $validated = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255|unique:users,email,' . $companyUser->id,
                'position' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'phone' => 'nullable|string|max:20',
                'is_active' => 'boolean',
            ]);
            
            // Atualizar o usuário
            $companyUser->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'is_active' => $validated['is_active'] ?? $companyUser->is_active,
            ]);
            
            // Atualizar o contato
            $contact->update([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'phone' => $validated['phone'],
                'position' => $validated['position'],
                'department' => $validated['department'],
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Funcionário atualizado com sucesso!',
                'user' => $companyUser->load('clientContacts'),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao atualizar funcionário: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove um usuário da empresa
     */
    public function destroy(User $companyUser): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Buscar a empresa do gestor
            $client = Client::whereHas('contacts', function ($query) use ($user) {
                $query->where('email', $user->email);
            })->firstOrFail();
            
            // Verificar se o usuário pertence à empresa do gestor
            $contact = $companyUser->clientContacts()->where('client_id', $client->id)->first();
            
            if (!$contact) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não pertence à sua empresa.',
                ], 403);
            }
            
            // Não permitir remover o próprio gestor
            if ($companyUser->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não pode remover a si mesmo.',
                ], 403);
            }
            
            DB::beginTransaction();
            
            // Remover o contato
            $contact->delete();
            
            // Desativar o usuário (soft delete)
            $companyUser->update(['is_active' => false]);
            
            DB::commit();
            
            return response()->json([
                'success' => true,
                'message' => 'Funcionário removido com sucesso!',
            ]);
            
        } catch (\Exception $e) {
            DB::rollBack();
            
            return response()->json([
                'success' => false,
                'message' => 'Erro ao remover funcionário: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Altera o status ativo/inativo de um usuário
     */
    public function toggleStatus(User $companyUser): JsonResponse
    {
        try {
            $user = auth()->user();
            
            // Buscar a empresa do gestor
            $client = Client::whereHas('contacts', function ($query) use ($user) {
                $query->where('email', $user->email);
            })->firstOrFail();
            
            // Verificar se o usuário pertence à empresa do gestor
            $contact = $companyUser->clientContacts()->where('client_id', $client->id)->first();
            
            if (!$contact) {
                return response()->json([
                    'success' => false,
                    'message' => 'Usuário não pertence à sua empresa.',
                ], 403);
            }
            
            // Não permitir desativar o próprio gestor
            if ($companyUser->id === $user->id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Você não pode desativar a si mesmo.',
                ], 403);
            }
            
            $companyUser->update([
                'is_active' => !$companyUser->is_active
            ]);
            
            $status = $companyUser->is_active ? 'ativado' : 'desativado';
            
            return response()->json([
                'success' => true,
                'message' => "Funcionário {$status} com sucesso!",
                'user' => $companyUser->load('clientContacts'),
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro ao alterar status: ' . $e->getMessage(),
            ], 500);
        }
    }
}
