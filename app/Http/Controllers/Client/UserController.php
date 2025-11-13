<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientContact;
use App\Services\ClientUserService;
use App\Http\Requests\StoreClientUserRequest;
use App\Http\Requests\UpdateClientUserRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    protected ClientUserService $clientUserService;

    public function __construct(ClientUserService $clientUserService)
    {
        $this->clientUserService = $clientUserService;
    }

    /**
     * Exibir página de gerenciamento de usuários da empresa
     */
    public function index(Request $request): View
    {
        $client = Client::findOrFail($request->route('client'));
        
        // Debug temporário
        $user = auth()->user();
        \Log::info('ClientUserController@index - Debug', [
            'user_id' => $user->id,
            'user_role' => $user->role,
            'is_admin' => $user->isAdmin(),
            'is_tecnico' => $user->isTecnico(),
            'client_id' => $client->id
        ]);
        
        // Verificar permissões via Policy (temporariamente desabilitado para debug)
        // $this->authorize('viewAny', [ClientContact::class, $client]);
        
        // Verificação manual temporária
        if (!$user->isAdmin() && !$user->isTecnico()) {
            if (!$user->isClienteGestor()) {
                abort(403, 'Acesso negado - não é admin, técnico ou gestor.');
            }
            
            // Se é gestor, verificar se pertence a esta empresa
            $belongsToCompany = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            if (!$belongsToCompany) {
                abort(403, 'Acesso negado - gestor não pertence a esta empresa.');
            }
        }
        
        // Buscar contatos com usuários (eager loading)
        $contacts = $client->contacts()
            ->withUser()
            ->orderBy('is_primary', 'desc')
            ->orderBy('name')
            ->get();
        
        return view('clients.users.index', compact('client', 'contacts'));
    }

    /**
     * Exibir formulário para criar novo usuário
     */
    public function create(Request $request): View
    {
        $client = Client::findOrFail($request->route('client'));
        $user = auth()->user();
        
        // Verificação manual temporária
        if (!$user->isAdmin() && !$user->isTecnico()) {
            if (!$user->isClienteGestor()) {
                abort(403, 'Acesso negado - não é admin, técnico ou gestor.');
            }
            
            $belongsToCompany = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            if (!$belongsToCompany) {
                abort(403, 'Acesso negado - gestor não pertence a esta empresa.');
            }
        }
        
        return view('clients.users.create', compact('client'));
    }

    /**
     * Armazenar novo usuário
     */
    public function store(StoreClientUserRequest $request): RedirectResponse
    {
        $client = Client::findOrFail($request->route('client'));
        $user = auth()->user();
        
        // Verificação manual temporária
        if (!$user->isAdmin() && !$user->isTecnico()) {
            if (!$user->isClienteGestor()) {
                abort(403, 'Acesso negado - não é admin, técnico ou gestor.');
            }
            
            $belongsToCompany = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            if (!$belongsToCompany) {
                abort(403, 'Acesso negado - gestor não pertence a esta empresa.');
            }
        }
        
        try {
            $contact = $this->clientUserService->createUser($client, $request->validated());
            
            return redirect()
                ->route('clients.users.index', $client)
                ->with('success', 'Usuário criado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao criar usuário: ' . $e->getMessage()]);
        }
    }

    /**
     * Exibir formulário de edição do usuário
     */
    public function edit(Request $request, $contactId): View
    {
        $client = Client::findOrFail($request->route('client'));
        $contact = ClientContact::where('id', $contactId)
            ->where('client_id', $client->id)
            ->with('user')
            ->firstOrFail();
        
        $user = auth()->user();
        
        // Verificação manual temporária
        if (!$user->isAdmin() && !$user->isTecnico()) {
            if (!$user->isClienteGestor()) {
                abort(403, 'Acesso negado - não é admin, técnico ou gestor.');
            }
            
            $belongsToCompany = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            if (!$belongsToCompany) {
                abort(403, 'Acesso negado - gestor não pertence a esta empresa.');
            }
        }
        
        return view('clients.users.edit', compact('client', 'contact'));
    }

    /**
     * Atualizar usuário
     */
    public function update(UpdateClientUserRequest $request, $contactId): RedirectResponse
    {
        $client = Client::findOrFail($request->route('client'));
        $contact = ClientContact::where('id', $contactId)
            ->where('client_id', $client->id)
            ->with('user')
            ->firstOrFail();
        
        $user = auth()->user();
        
        // Verificação manual temporária
        if (!$user->isAdmin() && !$user->isTecnico()) {
            if (!$user->isClienteGestor()) {
                abort(403, 'Acesso negado - não é admin, técnico ou gestor.');
            }
            
            $belongsToCompany = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            if (!$belongsToCompany) {
                abort(403, 'Acesso negado - gestor não pertence a esta empresa.');
            }
        }
        
        try {
            $this->clientUserService->updateUser($contact, $request->validated());
            
            return redirect()
                ->route('clients.users.index', $client)
                ->with('success', 'Usuário atualizado com sucesso!');
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->withErrors(['error' => 'Erro ao atualizar usuário: ' . $e->getMessage()]);
        }
    }

    /**
     * Ativar/desativar usuário
     */
    public function toggleStatus(Request $request, $contactId): RedirectResponse
    {
        $client = Client::findOrFail($request->route('client'));
        $contact = ClientContact::where('id', $contactId)
            ->where('client_id', $client->id)
            ->with('user')
            ->firstOrFail();
        
        $user = auth()->user();
        
        // Verificação manual temporária
        if (!$user->isAdmin() && !$user->isTecnico()) {
            if (!$user->isClienteGestor()) {
                abort(403, 'Acesso negado - não é admin, técnico ou gestor.');
            }
            
            $belongsToCompany = $client->contacts()
                ->where(function ($query) use ($user) {
                    $query->where('user_id', $user->id)
                          ->orWhere('email', $user->email);
                })
                ->where('user_type', 'cliente_gestor')
                ->exists();
                
            if (!$belongsToCompany) {
                abort(403, 'Acesso negado - gestor não pertence a esta empresa.');
            }
        }
        
        try {
            $contact = $this->clientUserService->toggleStatus($contact);
            $status = $contact->is_active ? 'ativado' : 'desativado';
            
            return redirect()
                ->route('clients.users.index', $client)
                ->with('success', "Usuário {$status} com sucesso!");
                
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withErrors(['error' => $e->getMessage()]);
        }
    }
}
