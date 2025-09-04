<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    // Middleware aplicado nas rotas

    /**
     * Lista de clientes
     */
    public function index(Request $request): View
    {
        $search = $request->get('search');
        $status = $request->get('status', 'all');
        
        $query = Client::with(['contacts', 'tickets'])
            ->withCount(['contacts', 'tickets'])
            ->withCount(['tickets as active_tickets_count' => function ($q) {
                $q->whereIn('status', ['aberto', 'em_andamento']);
            }]);
        
        // Filtro de busca
        if ($search) {
            $query->search($search);
        }
        
        // Filtro de status
        if ($status !== 'all') {
            $query->where('is_active', $status === 'active');
        }
        
        $clients = $query->latest()->paginate(15);
        
        // Estatísticas para o dashboard
        $stats = [
            'total' => Client::count(),
            'active' => Client::where('is_active', true)->count(),
            'inactive' => Client::where('is_active', false)->count(),
            'total_tickets' => \App\Models\Ticket::count(),
        ];
        
        return view('clients.index', compact('clients', 'search', 'status', 'stats'));
    }

    /**
     * Formulário de criação
     */
    public function create(): View
    {
        $categories = Category::active()->get();
        return view('clients.create', compact('categories'));
    }

    /**
     * Armazena novo cliente
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'cnpj' => 'required|string|unique:clients,cnpj|regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/',
            'company_name' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);
        
        // Limpar CNPJ antes de salvar (remover formatação)
        $validated['cnpj'] = preg_replace('/[^0-9]/', '', $validated['cnpj']);
        
        $client = Client::create($validated);
        
        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Empresa criada com sucesso!');
    }

    /**
     * Exibe detalhes do cliente
     */
    public function show(Client $client): View
    {
        $client->load([
            'contacts' => function ($q) {
                $q->orderBy('is_primary', 'desc')->orderBy('name');
            },
            'tickets' => function ($q) {
                $q->with(['category', 'contact'])->latest()->limit(10);
            }
        ]);
        
        $ticketsByStatus = [
            'aberto' => $client->tickets()->open()->count(),
            'em_andamento' => $client->tickets()->inProgress()->count(),
            'resolvido' => $client->tickets()->resolved()->count(),
            'fechado' => $client->tickets()->closed()->count(),
        ];
        
        $ticketsByCategory = $client->tickets()
            ->with('category')
            ->get()
            ->groupBy('category.name')
            ->map->count();
        
        // Estatísticas para o dashboard
        $stats = [
            'total_tickets' => $client->tickets()->count(),
            'open_tickets' => $client->tickets()->open()->count(),
            'resolved_tickets' => $client->tickets()->resolved()->count(),
            'urgent_tickets' => $client->tickets()->where('is_urgent', true)->count(),
        ];
        
        return view('clients.show', compact('client', 'ticketsByStatus', 'ticketsByCategory', 'stats'));
    }

    /**
     * Formulário de edição
     */
    public function edit(Client $client): View
    {
        $categories = Category::active()->get();
        return view('clients.edit', compact('client', 'categories'));
    }

    /**
     * Atualiza cliente
     */
    public function update(Request $request, Client $client): RedirectResponse
    {
        $validated = $request->validate([
            'cnpj' => 'required|string|unique:clients,cnpj,' . $client->id . '|regex:/^\d{2}\.\d{3}\.\d{3}\/\d{4}-\d{2}$/',
            'company_name' => 'required|string|max:255',
            'trade_name' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);
        
        // Limpar CNPJ antes de salvar (remover formatação)
        $validated['cnpj'] = preg_replace('/[^0-9]/', '', $validated['cnpj']);
        
        $client->update($validated);
        
        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Empresa atualizada com sucesso!');
    }

    /**
     * Remove cliente
     */
    public function destroy(Client $client): RedirectResponse
    {
        // Verifica se há tickets ativos
        if ($client->tickets()->active()->exists()) {
            return back()->with('error', 'Não é possível excluir empresa com tickets ativos!');
        }
        
        $client->delete();
        
        return redirect()
            ->route('clients.index')
            ->with('success', 'Empresa removida com sucesso!');
    }

    /**
     * API: Lista clientes para select
     */
    public function apiList(): JsonResponse
    {
        $clients = Client::active()
            ->select('id', 'company_name', 'trade_name')
            ->orderBy('company_name')
            ->get()
            ->map(function ($client) {
                return [
                    'id' => $client->id,
                    'text' => $client->company_name . ($client->trade_name ? " ({$client->trade_name})" : '')
                ];
            });
        
        return response()->json($clients);
    }

    /**
     * API: Busca cliente por CNPJ
     */
    public function apiSearchByCnpj(Request $request): JsonResponse
    {
        $cnpj = $request->get('cnpj');
        
        $client = Client::where('cnpj', $cnpj)->first();
        
        if (!$client) {
            return response()->json(['found' => false]);
        }
        
        return response()->json([
            'found' => true,
            'client' => [
                'id' => $client->id,
                'company_name' => $client->company_name,
                'trade_name' => $client->trade_name,
                'contacts' => $client->contacts()->active()->get(['id', 'name', 'email', 'position'])
            ]
        ]);
    }

    /**
     * Toggle status ativo/inativo
     */
    public function toggleStatus(Client $client): RedirectResponse
    {
        $client->update(['is_active' => !$client->is_active]);
        
        $status = $client->is_active ? 'ativada' : 'desativada';
        
        return back()->with('success', "Empresa {$status} com sucesso!");
    }

    /**
     * Armazena novo contato para o cliente
     */
    public function storeContact(Request $request, Client $client): JsonResponse
    {
        try {
            $validationRules = [
                'name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:20',
                'position' => 'nullable|string|max:255',
                'department' => 'nullable|string|max:255',
                'is_primary' => 'nullable|boolean',
                'user_type' => 'required|in:cliente_funcionario,cliente_gestor',
            ];
            
            // Adicionar regras de validação para senha se estiver redefinindo
            if ($request->has('reset_password') && $request->reset_password) {
                $validationRules['new_password'] = 'required|string|min:8';
                $validationRules['new_password_confirmation'] = 'required|string|same:new_password';
            }
            
            $validated = $request->validate($validationRules);
            
            // Garantir que is_primary seja boolean
            $validated['is_primary'] = $request->has('is_primary') && $request->boolean('is_primary');
            
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erro de validação',
                'errors' => $e->errors()
            ], 422);
        }

        // Se for contato principal, desmarcar outros
        if ($validated['is_primary']) {
            $client->contacts()->update(['is_primary' => false]);
        }
        
        // Remover campos de senha do array de criação
        if (isset($validated['new_password'])) {
            unset($validated['new_password']);
        }
        if (isset($validated['new_password_confirmation'])) {
            unset($validated['new_password_confirmation']);
        }

        $contact = $client->contacts()->create($validated);
        
        // Processar criação de usuário se solicitado
        if ($request->has('reset_password') && $request->reset_password) {
            // Verificar se existe um usuário com este email
            $user = \App\Models\User::where('email', $contact->email)->first();
            
            if ($user) {
                // Atualizar senha do usuário existente
                $user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
                ]);
                
                $passwordMessage = 'Senha do usuário atualizada com sucesso!';
            } else {
                // Criar um novo usuário com este email
                \App\Models\User::create([
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->new_password),
                    'role' => $contact->user_type, // Usar o user_type definido no contato
                    'is_active' => true
                ]);
                
                $passwordMessage = 'Novo usuário criado com sucesso! Este contato agora pode fazer login no sistema.';
            }
            
            return response()->json([
                'success' => true,
                'message' => 'Contato adicionado com sucesso! ' . $passwordMessage,
                'contact' => $contact->load('client'),
                'password_reset' => true
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Contato adicionado com sucesso!',
            'contact' => $contact->load('client')
        ]);
    }

    /**
     * Atualiza contato do cliente
     */
    public function updateContact(Request $request, Client $client, ClientContact $contact): RedirectResponse
    {
        $validationRules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:client_contacts,email,' . $contact->id . ',id',
            'phone' => 'nullable|string|max:20',
            'position' => 'nullable|string|max:255',
            'department' => 'nullable|string|max:255',
            'is_primary' => 'nullable|boolean',
            'user_type' => 'required|in:cliente_funcionario,cliente_gestor',
        ];
        
        // Adicionar regras de validação para senha se estiver sendo alterada
        if ($request->filled('new_password')) {
            $validationRules['new_password'] = 'required|string|min:8';
            $validationRules['new_password_confirmation'] = 'required|string|same:new_password';
        }
        
        $validated = $request->validate($validationRules);
        
        // Garantir que is_primary seja boolean
        $validated['is_primary'] = $request->has('is_primary') && $request->boolean('is_primary');

        // Se for contato principal, desmarcar outros
        if ($validated['is_primary']) {
            $client->contacts()->where('id', '!=', $contact->id)->update(['is_primary' => false]);
        }
        
        // Remover campos de senha do array de atualização
        if (isset($validated['new_password'])) {
            unset($validated['new_password']);
        }
        if (isset($validated['new_password_confirmation'])) {
            unset($validated['new_password_confirmation']);
        }

        $contact->update($validated);
        
        // Processar alteração de senha se fornecida
        if ($request->filled('new_password')) {
            // Verificar se existe um usuário com este email
            $user = \App\Models\User::where('email', $contact->email)->first();
            
            if ($user) {
                // Atualizar senha do usuário existente
                $user->update([
                    'password' => \Illuminate\Support\Facades\Hash::make($request->new_password)
                ]);
                
                $message = 'Contato atualizado com sucesso! Senha do usuário alterada.';
            } else {
                // Criar um novo usuário com este email
                \App\Models\User::create([
                    'name' => $contact->name,
                    'email' => $contact->email,
                    'password' => \Illuminate\Support\Facades\Hash::make($request->new_password),
                    'role' => $contact->user_type,
                    'is_active' => true
                ]);
                
                $message = 'Contato atualizado com sucesso! Novo usuário criado com a senha fornecida.';
            }
        } else {
            $message = 'Contato atualizado com sucesso!';
        }

        return redirect()->route('clients.show', $client)->with('success', $message);
    }

    /**
     * Remove contato do cliente
     */
    public function deleteContact(Client $client, ClientContact $contact): JsonResponse
    {
        // Não permitir excluir contato principal se for o único
        if ($contact->is_primary && $client->contacts()->count() === 1) {
            return response()->json([
                'success' => false,
                'message' => 'Não é possível excluir o único contato principal!'
            ], 400);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contato removido com sucesso!'
        ]);
    }

    /**
     * Buscar contatos de um cliente (para formulários de ticket)
     */
    public function getContacts(Client $client): JsonResponse
    {
        $contacts = $client->contacts()
            ->select('id', 'name', 'email', 'phone', 'position', 'department', 'is_primary')
            ->orderBy('is_primary', 'desc')
            ->orderBy('name')
            ->get();

        return response()->json($contacts);
    }

    /**
     * Retorna dados do contato para edição
     */
    public function editContact(Client $client, ClientContact $contact): View
    {
        return view('clients.contacts.edit', compact('client', 'contact'));
    }
}
