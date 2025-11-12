<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Client;
use App\Models\Category;
use App\Models\User;
use App\Models\TicketMessage;
use App\Models\Attachment;
use App\Services\NotificationService;
use App\Services\AuditService;
use App\Http\Requests\StoreTicketRequest;
use App\Http\Requests\UpdateTicketRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{
    protected $notificationService;
    protected $auditService;

    public function __construct(NotificationService $notificationService, AuditService $auditService)
    {
        $this->notificationService = $notificationService;
        $this->auditService = $auditService;
    }

    // Middleware aplicado nas rotas

    /**
     * Lista de tickets
     */
    public function index(Request $request): View
    {
        $user = auth()->user();
        
        $query = Ticket::with(['client', 'category', 'contact', 'assignedTo'])
            ->withCount(['messages', 'attachments']);
        
        // Filtros baseados no perfil do usuário
        if ($user->isCliente()) {
            $query->whereHas('contact', function ($q) use ($user) {
                $q->where('email', $user->email);
            });
        } elseif ($user->isTecnico()) {
            $query->where('assigned_to', $user->id);
        }
        // Admin vê todos os tickets
        
        // Aplicar filtros da requisição
        $this->applyFilters($query, $request);
        
        $tickets = $query->latest('updated_at')->paginate(20);
        
        // Estatísticas para o dashboard (apenas tickets ativos)
        $stats = [
            'total' => Ticket::count(),
            'open' => Ticket::where('status', 'aberto')->count(),
            'in_progress' => Ticket::where('status', 'em_andamento')->count(),
            'resolved' => Ticket::where('status', 'resolvido')->count(),
            'closed' => Ticket::where('status', 'fechado')->count(),
            'urgent' => Ticket::where('is_urgent', true)->count(),
        ];
        
        // Dados para filtros
        $clients = Client::active()->orderBy('company_name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $technicians = User::tecnicos()->orderBy('name')->get();
        
        return view('tickets.index', compact('tickets', 'clients', 'categories', 'technicians', 'stats'));
    }

    /**
     * Formulário de criação
     */
    public function create(): View
    {
        $clients = Client::active()->orderBy('company_name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $priorities = ['baixa', 'média', 'alta'];
        $technicians = User::tecnicos()->orderBy('name')->get();
        
        // Gerar próximo número do ticket
        $nextTicketNumber = 'TKT-' . date('Y') . '-' . str_pad(Ticket::count() + 1, 4, '0', STR_PAD_LEFT);
        
        return view('tickets.create', compact('clients', 'categories', 'priorities', 'technicians', 'nextTicketNumber'));
    }

    /**
     * Armazena novo ticket
     */
    public function store(StoreTicketRequest $request): RedirectResponse
    {
        \Log::info('Store ticket called', [
            'has_files' => $request->hasFile('attachments'),
            'files_count' => $request->hasFile('attachments') ? count($request->file('attachments')) : 0,
            'all_data' => $request->all()
        ]);
        
        $validated = $request->validated();
        
        // Definir valores padrão
        $validated['is_urgent'] = $validated['is_urgent'] ?? false;
        
        // Se o usuário for cliente, definir automaticamente o client_id
        $user = auth()->user();
        if ($user->isCliente() && !isset($validated['client_id'])) {
            // Buscar o cliente associado ao usuário
            $client = Client::whereHas('contacts', function($query) use ($user) {
                $query->where('email', $user->email);
            })->first();
            
            if ($client) {
                $validated['client_id'] = $client->id;
            } else {
                return back()->withInput()->withErrors(['client_id' => 'Não foi possível identificar o cliente associado ao seu usuário.']);
            }
        }
        
        // Tratar campo assigned_to
        if (isset($validated['assigned_to']) && (empty($validated['assigned_to']) || $validated['assigned_to'] === '')) {
            $validated['assigned_to'] = null;
        }
        
        // Tratar campo priority
        if (isset($validated['priority']) && (empty($validated['priority']) || $validated['priority'] === '')) {
            $validated['priority'] = 'média';
        }
        
        // Tratar campo category_id
        if (isset($validated['category_id']) && (empty($validated['category_id']) || $validated['category_id'] === '')) {
            return back()->withInput()->withErrors(['category_id' => 'A categoria é obrigatória.']);
        }
        
        // Tratar campo client_id
        if (!$user->isCliente() && (!isset($validated['client_id']) || empty($validated['client_id']) || $validated['client_id'] === '')) {
            return back()->withInput()->withErrors(['client_id' => 'O cliente é obrigatório.']);
        }
        
        // Tratar campo title
        if (isset($validated['title']) && (empty(trim($validated['title'])) || $validated['title'] === '')) {
            return back()->withInput()->withErrors(['title' => 'O título é obrigatório.']);
        }
        
        // Tratar campo description
        if (isset($validated['description']) && empty(trim($validated['description']))) {
            return back()->withInput()->withErrors(['description' => 'A descrição é obrigatória.']);
        }
        
        // Se não houver contact_id, usar o contato primário do cliente
        if (!isset($validated['contact_id'])) {
            $client = Client::find($validated['client_id']);
            $primaryContact = $client->contacts()->primary()->first();
            if ($primaryContact) {
                $validated['contact_id'] = $primaryContact->id;
            } else {
                // Se não houver contato primário, usar o primeiro contato ativo
                $firstContact = $client->contacts()->active()->first();
                if ($firstContact) {
                    $validated['contact_id'] = $firstContact->id;
                } else {
                    return back()->withErrors(['client_id' => 'Este cliente não possui contatos cadastrados.']);
                }
            }
        }
        
        $validated['opened_at'] = now();
        
        // Adicionar informações de auditoria
        $validated['created_ip'] = $request->get('audit_ip') ?? $request->ip();
        $validated['created_user_agent'] = $request->get('audit_user_agent') ?? $request->userAgent();
        
        $ticket = Ticket::create($validated);
        
        // Criar mensagem inicial
        $ticketMessage = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'contact_id' => $validated['contact_id'],
            'type' => 'reply',
            'message' => $validated['description'],
            'is_internal' => false,
            'ip_address' => $request->get('audit_ip') ?? $request->ip(),
            'user_agent' => $request->get('audit_user_agent') ?? $request->userAgent(),
        ]);

        // Processar anexos
        if ($request->hasFile('attachments')) {
            try {
                foreach ($request->file('attachments') as $file) {
                    if ($file->isValid()) {
                        $path = $file->store('tickets/' . $ticket->id, 'public');
                        
                        Attachment::create([
                            'ticket_message_id' => $ticketMessage->id,
                            'filename' => $file->getClientOriginalName(),
                            'file_path' => $path,
                            'file_type' => $file->getMimeType(),
                            'file_size' => $file->getSize(),
                            'disk' => 'public',
                        ]);
                    }
                }
            } catch (\Exception $e) {
                \Log::error('Erro ao processar anexos: ' . $e->getMessage());
                return back()->withInput()->withErrors(['attachments' => 'Erro ao processar anexos. Tente novamente.']);
            }
        }

        // Registrar auditoria
        $this->auditService->logCreated($ticket, auth()->user(), $request);
        $this->auditService->logTicketReply($ticket, $ticketMessage, auth()->user(), $request);

        // Enviar notificações
        $this->notificationService->notifyNewTicket($ticket, auth()->user());
        
        return redirect()->route('tickets.show', $ticket->ticket_number)
            ->with('success', 'Ticket criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $ticketNumber): View
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }

        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            abort(403, 'Acesso negado a este ticket.');
        }

        $ticket->load([
            'client',
            'category', 
            'contact',
            'assignedTo',
            'messages' => function ($q) {
                $q->with(['user', 'contact', 'attachments'])->orderBy('created_at');
            }
        ]);
        
        // Filtrar mensagens baseado no role do usuário
        $user = auth()->user();
        if ($user->isCliente()) {
            $ticket->setRelation('messages', $ticket->messages->filter(function ($message) {
                return !$message->is_internal;
            }));
        }
        
        // Registrar visualização do ticket
        $this->auditService->logTicketViewed($ticket, auth()->user(), request());

        // Dados para formulários
        $technicians = User::tecnicos()->orderBy('name')->get();
        $priorities = ['baixa', 'média', 'alta'];
        $statuses = ['aberto', 'em_andamento', 'resolvido', 'fechado'];
        
        return view('tickets.show', compact('ticket', 'technicians', 'priorities', 'statuses'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $ticketNumber): View
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }

        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            abort(403, 'Acesso negado a este ticket.');
        }

        $clients = Client::active()->orderBy('company_name')->get();
        $categories = Category::active()->orderBy('name')->get();
        $technicians = User::tecnicos()->orderBy('name')->get();
        $priorities = ['baixa', 'média', 'alta'];
        $statuses = ['aberto', 'em_andamento', 'resolvido', 'fechado'];
        
        return view('tickets.edit', compact('ticket', 'clients', 'categories', 'technicians', 'priorities', 'statuses'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $ticketNumber): RedirectResponse
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }

        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            abort(403, 'Acesso negado a este ticket.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'category_id' => 'required|exists:categories,id',
            'priority' => 'required|in:baixa,média,alta',
            'status' => 'required|in:aberto,em_andamento,resolvido,fechado',
            'assigned_to' => 'nullable|exists:users,id',
            'is_urgent' => 'boolean',
        ]);
        
        // Capturar valores antigos para notificações e auditoria
        $oldValues = $ticket->toArray();
        $oldStatus = $ticket->status;
        $oldPriority = $ticket->priority;
        $oldAssignedTo = $ticket->assigned_to;
        
        // Adicionar informações de auditoria
        $validated['updated_ip'] = $request->get('audit_ip') ?? $request->ip();
        $validated['updated_user_agent'] = $request->get('audit_user_agent') ?? $request->userAgent();
        
        // Atualizar timestamps baseado no status
        if ($validated['status'] === 'resolvido' && $ticket->status !== 'resolvido') {
            $validated['resolved_at'] = now();
        } elseif ($validated['status'] === 'fechado' && $ticket->status !== 'fechado') {
            $validated['closed_at'] = now();
            $validated['closed_ip'] = $validated['updated_ip'];
            $validated['closed_user_agent'] = $validated['updated_user_agent'];
        }
        
        $ticket->update($validated);
        
        // Enviar notificações baseadas nas mudanças
        if ($oldStatus !== $validated['status']) {
            $this->notificationService->notifyStatusChange($ticket, $oldStatus, $validated['status'], auth()->user());
        }
        
        if ($oldPriority !== $validated['priority']) {
            $this->notificationService->notifyPriorityChange($ticket, $oldPriority, $validated['priority'], auth()->user());
        }
        
        if ($oldAssignedTo !== ($validated['assigned_to'] ?? null) && !empty($validated['assigned_to'])) {
            $assignedUser = User::find($validated['assigned_to']);
            if ($assignedUser) {
                $this->notificationService->notifyTicketAssigned($ticket, $assignedUser);
            }
        }

        // Registrar auditoria
        $this->auditService->logUpdated($ticket, $oldValues, $ticket->toArray(), auth()->user(), $request);
        
        // Logs específicos para mudanças
        if ($oldStatus !== $validated['status']) {
            $this->auditService->logStatusChange($ticket, $oldStatus, $validated['status'], auth()->user(), $request);
        }
        
        if ($oldPriority !== $validated['priority']) {
            $this->auditService->logPriorityChange($ticket, $oldPriority, $validated['priority'], auth()->user(), $request);
        }
        
        if ($oldAssignedTo !== ($validated['assigned_to'] ?? null)) {
            $assignedUser = User::find($validated['assigned_to']);
            $this->auditService->logTicketAssigned($ticket, $assignedUser, auth()->user(), $request);
        }
        
        if ($validated['status'] === 'fechado' && $oldStatus !== 'fechado') {
            $this->auditService->logTicketClosed($ticket, auth()->user(), $request);
        }
        
        return redirect()
            ->route('tickets.show', $ticket->ticket_number)
            ->with('success', 'Ticket atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $ticketNumber)
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Ticket não encontrado.'], 404);
            }
            abort(404, 'Ticket não encontrado.');
        }

        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            if (request()->expectsJson()) {
                return response()->json(['success' => false, 'message' => 'Acesso negado a este ticket.'], 403);
            }
            abort(403, 'Acesso negado a este ticket.');
        }

        $ticket->delete();
        
        if (request()->expectsJson()) {
            return response()->json(['success' => true, 'message' => 'Ticket removido com sucesso!']);
        }
        
        return redirect()
            ->route('tickets.index')
            ->with('success', 'Ticket removido com sucesso!');
    }

    /**
     * Adiciona uma mensagem ao ticket
     */
    public function addMessage(Request $request, string $ticketNumber): RedirectResponse
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }

        // Verificar se o usuário tem acesso ao ticket
        $user = auth()->user();
        if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
            abort(403, 'Acesso negado a este ticket.');
        }

        $validated = $request->validate([
            'message' => 'required|string|max:5000',
            'type' => 'required|in:reply,note,status_change',
            'is_internal' => 'boolean',
            'attachments.*' => 'nullable|file|max:25000|mimes:pdf,jpg,jpeg,png,zip,txt,log,doc,docx,xls,xlsx',
        ]);
        
        $ticketMessage = TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => $user->isCliente() ? null : $user->id,
            'contact_id' => $user->isCliente() ? $ticket->contact_id : null,
            'type' => $validated['type'],
            'message' => $validated['message'],
            'is_internal' => $user->isCliente() ? false : ($validated['is_internal'] ?? false),
            'ip_address' => $request->get('audit_ip') ?? $request->ip(),
            'user_agent' => $request->get('audit_user_agent') ?? $request->userAgent(),
        ]);
        
        // Processar anexos
        if ($request->hasFile('attachments')) {
            foreach ($request->file('attachments') as $file) {
                $path = $file->store('tickets/' . $ticket->id, 'public');
                
                Attachment::create([
                    'ticket_message_id' => $ticketMessage->id,
                    'filename' => $file->getClientOriginalName(),
                    'file_path' => $path,
                    'file_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'disk' => 'public',
                ]);
            }
        }
        
        // Registrar auditoria
        $this->auditService->logTicketReply($ticket, $ticketMessage, $user, $request);
        
        // Notificar sobre a resposta
        if ($validated['type'] === 'reply') {
            $this->notificationService->notifyTicketReply($ticket, $ticketMessage, $user);
        }
        
        return back()->with('success', 'Mensagem adicionada com sucesso!');
    }

    /**
     * Atribui ticket a um técnico
     */
    public function assign(Request $request, string $ticketNumber): JsonResponse
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }
        $this->authorize('assign', $ticket);
        
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
        ]);
        
        $ticket->update(['assigned_to' => $validated['assigned_to']]);
        
        // Criar mensagem de atribuição
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'assignment',
            'message' => 'Ticket atribuído a ' . User::find($validated['assigned_to'])->name,
            'is_internal' => true,
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Altera status do ticket
     */
    public function changeStatus(Request $request, string $ticketNumber)
    {
        try {
            $ticket = Ticket::findByNumber($ticketNumber);
            
            if (!$ticket) {
                return redirect()->back()->with('error', 'Ticket não encontrado.');
            }
            
            $oldStatus = $ticket->status;
            $newStatus = $request->input('status', 'fechado');
            
            // Atualizar status
            $updateData = ['status' => $newStatus];
            
            if ($newStatus === 'fechado' && $oldStatus !== 'fechado') {
                $updateData['closed_at'] = now();
            }
            
            $ticket->update($updateData);
            
            // Criar mensagem no histórico SEMPRE (mesmo se status for igual)
            if (auth()->check()) {
                $message = $oldStatus !== $newStatus 
                    ? "Status alterado de '{$oldStatus}' para '{$newStatus}'"
                    : "Ticket {$newStatus}";
                
                \Log::info("Criando mensagem no histórico: {$message}");
                    
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => auth()->id(),
                    'type' => 'status_change',
                    'message' => $message,
                    'is_internal' => true,
                ]);
                
                \Log::info("Mensagem criada com sucesso");
            } else {
                \Log::warning("Usuário não autenticado - não criou mensagem");
            }
            
            return redirect()->route('tickets.show', $ticket->ticket_number)
                            ->with('success', 'Ticket fechado com sucesso!');
                            
        } catch (\Exception $e) {
            \Log::error('Erro ao fechar ticket: ' . $e->getMessage());
            return redirect()->back()->with('error', 'ERRO ESPECÍFICO: ' . $e->getMessage());
        }
    }

    /**
     * Reabre ticket fechado
     */
    public function reopen(string $ticketNumber)
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }
        $this->authorize('reopen', $ticket);
        
        if (!$ticket->canBeReopened()) {
            return redirect()->route('tickets.show', $ticket->ticket_number)
                            ->with('error', 'Ticket não pode ser reaberto');
        }
        
        $ticket->update([
            'status' => 'aberto',
            'closed_at' => null,
        ]);
        
        // Criar mensagem de reabertura
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'status_change',
            'message' => 'Ticket reaberto',
            'is_internal' => true,
            'metadata' => [
                'old_status' => 'fechado',
                'new_status' => 'aberto',
                'action' => 'reopen',
            ],
        ]);
        
        return redirect()->route('tickets.show', $ticket->ticket_number)
                        ->with('success', 'Ticket reaberto com sucesso!');
    }

    /**
     * Executa ações em lote nos tickets selecionados
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'action' => 'required|string|in:delete,change_status,change_priority,assign,mark_urgent,unmark_urgent',
            'ticket_ids' => 'required|array|min:1',
            'ticket_ids.*' => 'string',
            'status' => 'required_if:action,change_status|in:aberto,em_andamento,resolvido,fechado',
            'priority' => 'required_if:action,change_priority|in:baixa,média,alta',
            'assigned_to' => 'required_if:action,assign|exists:users,id',
        ]);

        $ticketNumbers = $validated['ticket_ids'];
        $action = $validated['action'];
        $user = auth()->user();
        
        $results = [
            'success' => [],
            'errors' => [],
            'total' => count($ticketNumbers)
        ];

        foreach ($ticketNumbers as $ticketNumber) {
            try {
                $ticket = Ticket::findByNumber($ticketNumber);
                
                if (!$ticket) {
                    $results['errors'][] = "Ticket {$ticketNumber} não encontrado";
                    continue;
                }

                // Verificar permissões
                if ($user->isCliente() && $ticket->client_id !== $user->client_id) {
                    $results['errors'][] = "Sem permissão para modificar ticket {$ticketNumber}";
                    continue;
                }

                $this->executeBulkActionOnTicket($ticket, $action, $validated, $user, $request);
                $results['success'][] = $ticketNumber;
                
            } catch (\Exception $e) {
                $results['errors'][] = "Erro no ticket {$ticketNumber}: " . $e->getMessage();
            }
        }

        return response()->json([
            'success' => count($results['success']) > 0,
            'message' => $this->getBulkActionMessage($action, $results),
            'results' => $results
        ]);
    }

    /**
     * Executa uma ação específica em um ticket
     */
    private function executeBulkActionOnTicket(Ticket $ticket, string $action, array $validated, $user, Request $request): void
    {
        $oldValues = $ticket->toArray();
        
        switch ($action) {
            case 'delete':
                $ticket->delete();
                $this->auditService->logDeleted($ticket, $oldValues, $user, $request);
                break;
                
            case 'change_status':
                $oldStatus = $ticket->status;
                $newStatus = $validated['status'];
                
                $updateData = ['status' => $newStatus];
                
                if ($newStatus === 'resolvido' && $oldStatus !== 'resolvido') {
                    $updateData['resolved_at'] = now();
                } elseif ($newStatus === 'fechado' && $oldStatus !== 'fechado') {
                    $updateData['closed_at'] = now();
                }
                
                $ticket->update($updateData);
                
                // Criar mensagem de mudança de status
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => 'status_change',
                    'message' => "Status alterado de '{$oldStatus}' para '{$newStatus}' (ação em lote)",
                    'is_internal' => true,
                ]);
                
                $this->auditService->logStatusChange($ticket, $oldStatus, $newStatus, $user, $request);
                $this->notificationService->notifyStatusChange($ticket, $oldStatus, $newStatus, $user);
                break;
                
            case 'change_priority':
                $oldPriority = $ticket->priority;
                $newPriority = $validated['priority'];
                
                $ticket->update(['priority' => $newPriority]);
                
                // Criar mensagem de mudança de prioridade
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => 'priority_change',
                    'message' => "Prioridade alterada de '{$oldPriority}' para '{$newPriority}' (ação em lote)",
                    'is_internal' => true,
                ]);
                
                $this->auditService->logPriorityChange($ticket, $oldPriority, $newPriority, $user, $request);
                $this->notificationService->notifyPriorityChange($ticket, $oldPriority, $newPriority, $user);
                break;
                
            case 'assign':
                $assignedUserId = $validated['assigned_to'];
                $assignedUser = User::find($assignedUserId);
                $oldAssignedTo = $ticket->assigned_to;
                
                $ticket->update(['assigned_to' => $assignedUserId]);
                
                // Criar mensagem de atribuição
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => 'assignment',
                    'message' => "Ticket atribuído a {$assignedUser->name} (ação em lote)",
                    'is_internal' => true,
                ]);
                
                $this->auditService->logTicketAssigned($ticket, $assignedUser, $user, $request);
                $this->notificationService->notifyTicketAssigned($ticket, $assignedUser);
                break;
                
            case 'mark_urgent':
                $ticket->update(['is_urgent' => true]);
                
                // Criar mensagem de urgência
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => 'priority_change',
                    'message' => 'Ticket marcado como urgente (ação em lote)',
                    'is_internal' => true,
                ]);
                break;
                
            case 'unmark_urgent':
                $ticket->update(['is_urgent' => false]);
                
                // Criar mensagem de urgência
                TicketMessage::create([
                    'ticket_id' => $ticket->id,
                    'user_id' => $user->id,
                    'type' => 'priority_change',
                    'message' => 'Ticket desmarcado como urgente (ação em lote)',
                    'is_internal' => true,
                ]);
                break;
        }
    }

    /**
     * Gera mensagem de resultado das ações em lote
     */
    private function getBulkActionMessage(string $action, array $results): string
    {
        $actionNames = [
            'delete' => 'excluídos',
            'change_status' => 'status alterado',
            'change_priority' => 'prioridade alterada',
            'assign' => 'atribuídos',
            'mark_urgent' => 'marcados como urgentes',
            'unmark_urgent' => 'desmarcados como urgentes',
        ];
        
        $actionName = $actionNames[$action] ?? 'processados';
        $successCount = count($results['success']);
        $errorCount = count($results['errors']);
        
        if ($successCount > 0 && $errorCount === 0) {
            return "Todos os {$successCount} tickets foram {$actionName} com sucesso!";
        } elseif ($successCount > 0 && $errorCount > 0) {
            return "{$successCount} tickets foram {$actionName} com sucesso. {$errorCount} tickets apresentaram erro.";
        } else {
            return "Nenhum ticket pôde ser {$actionName}. Verifique as permissões e tente novamente.";
        }
    }

    /**
     * Aplica filtros na query
     */
    private function applyFilters($query, Request $request): void
    {
        if ($request->filled('search')) {
            $query->search($request->search);
        }
        
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('priority') && $request->priority !== 'all') {
            $query->byPriority($request->priority);
        }
        
        if ($request->filled('category_id') && $request->category_id !== 'all') {
            $query->byCategory($request->category_id);
        }
        
        if ($request->filled('client_id') && $request->client_id !== 'all') {
            $query->byClient($request->client_id);
        }
        
        if ($request->filled('assigned_to') && $request->assigned_to !== 'all') {
            if ($request->assigned_to === 'unassigned') {
                $query->unassigned();
            } else {
                $query->assignedTo($request->assigned_to);
            }
        }
        
        if ($request->filled('urgent')) {
            $query->urgent();
        }
    }
}
