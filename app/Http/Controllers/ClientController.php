<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientContact;
use App\Models\Category;
use App\Services\AuditService;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;

class ClientController extends Controller
{
    protected $auditService;

    public function __construct(AuditService $auditService)
    {
        $this->auditService = $auditService;
    }

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
        
        // Aplicar filtros
        $this->applyFilters($query, $search, $status);
        
        $clients = $query->latest()->paginate(15);
        $stats = $this->getClientStats();
        
        return view('clients.index', compact('clients', 'search', 'status', 'stats'));
    }

    /**
     * Aplica filtros na consulta
     */
    private function applyFilters($query, ?string $search, string $status): void
    {
        if ($search) {
            $query->search($search);
        }
        
        if ($status !== 'all') {
            $query->where('is_active', $status === 'ativo');
        }
    }

    /**
     * Obtém estatísticas dos clientes
     */
    private function getClientStats(): array
    {
        return [
            'total' => Client::count(),
            'active' => Client::where('is_active', true)->count(),
            'companies' => Client::count(), // Todos os clientes são empresas neste sistema
            'with_tickets' => Client::whereHas('tickets')->count(),
        ];
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
        
        // Registrar auditoria
        $this->auditService->logCreated($client, auth()->user(), $request);
        
        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Cliente criado com sucesso!');
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
        
        $oldValues = $client->toArray();
        $client->update($validated);
        
        // Registrar auditoria
        $this->auditService->logUpdated($client, $oldValues, $client->toArray(), auth()->user(), $request);
        
        return redirect()
            ->route('clients.show', $client)
            ->with('success', 'Cliente atualizado com sucesso!');
    }

    /**
     * Executa ações em lote nos clientes selecionados
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|string|in:activate,deactivate,delete',
            'client_ids' => 'required|array|min:1',
            'client_ids.*' => 'integer|exists:clients,id',
        ]);

        $clientIds = $validated['client_ids'];
        $action = $validated['action'];
        $user = auth()->user();
        
        $results = [
            'success' => [],
            'errors' => [],
            'total' => count($clientIds)
        ];

        foreach ($clientIds as $clientId) {
            try {
                $client = Client::find($clientId);
                
                if (!$client) {
                    $results['errors'][] = "Cliente ID {$clientId} não encontrado";
                    continue;
                }

                $this->executeBulkActionOnClient($client, $action, $user, $request);
                $results['success'][] = $clientId;
                
            } catch (\Exception $e) {
                $results['errors'][] = "Erro no cliente ID {$clientId}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => count($results['success']) > 0,
            'message' => $this->getBulkActionMessage($action, $results),
            'results' => $results
        ]);
    }

    /**
     * Executa uma ação específica em um cliente
     */
    private function executeBulkActionOnClient(Client $client, string $action, $user, Request $request): void
    {
        $oldValues = $client->toArray();
        
        switch ($action) {
            case 'activate':
                $client->update(['is_active' => true]);
                $this->auditService->logUpdated($client, $oldValues, $client->toArray(), $user, $request);
                break;
                
            case 'deactivate':
                $client->update(['is_active' => false]);
                $this->auditService->logUpdated($client, $oldValues, $client->toArray(), $user, $request);
                break;
                
            case 'delete':
                // Verificar se há tickets ativos
                if ($client->tickets()->whereIn('status', ['aberto', 'em_andamento'])->exists()) {
                    throw new \Exception('Cliente possui tickets ativos e não pode ser excluído');
                }
                
                $client->delete();
                $this->auditService->logDeleted($client, $oldValues, $user, $request);
                break;
        }
    }

    /**
     * Gera mensagem de resultado das ações em lote
     */
    private function getBulkActionMessage(string $action, array $results): string
    {
        $actionNames = [
            'activate' => 'ativados',
            'deactivate' => 'desativados',
            'delete' => 'excluídos',
        ];
        
        $actionName = $actionNames[$action] ?? 'processados';
        $successCount = count($results['success']);
        $errorCount = count($results['errors']);
        
        if ($successCount > 0 && $errorCount === 0) {
            return "Todos os {$successCount} clientes foram {$actionName} com sucesso!";
        } elseif ($successCount > 0 && $errorCount > 0) {
            return "{$successCount} clientes foram {$actionName} com sucesso. {$errorCount} clientes apresentaram erro.";
        } else {
            return "Nenhum cliente pôde ser {$actionName}. Verifique as permissões e tente novamente.";
        }
    }

    /**
     * Remove cliente
     */
    public function destroy(Client $client): JsonResponse
    {
        // Verifica se há tickets ativos
        if ($client->tickets()->whereIn('status', ['aberto', 'em_andamento'])->exists()) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Não é possível excluir cliente com tickets ativos!'], 400);
            }
            return back()->with('error', 'Não é possível excluir cliente com tickets ativos!');
        }
        
        $oldValues = $client->toArray();
        $client->delete();
        
        // Registrar auditoria
        $this->auditService->logDeleted($client, $oldValues, auth()->user(), request());
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Cliente removido com sucesso!']);
        }
        
        return redirect()
            ->route('clients.index')
            ->with('success', 'Cliente removido com sucesso!');
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
