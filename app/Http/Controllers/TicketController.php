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
                return back()->withErrors(['client_id' => 'Não foi possível identificar o cliente associado ao seu usuário.']);
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
            return back()->withErrors(['category_id' => 'A categoria é obrigatória.']);
        }
        
        // Tratar campo client_id
        if (!$user->isCliente() && (!isset($validated['client_id']) || empty($validated['client_id']) || $validated['client_id'] === '')) {
            return back()->withErrors(['client_id' => 'O cliente é obrigatório.']);
        }
        
        // Tratar campo title
        if (isset($validated['title']) && (empty(trim($validated['title'])) || $validated['title'] === '')) {
            return back()->withErrors(['title' => 'O título é obrigatório.']);
        }
        
        // Tratar campo description
        if (isset($validated['description']) && empty(trim($validated['description']))) {
            return back()->withErrors(['description' => 'A descrição é obrigatória.']);
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
    public function changeStatus(Request $request, string $ticketNumber): JsonResponse
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }
        $this->authorize('update', $ticket);
        
        $validated = $request->validate([
            'status' => 'required|in:aberto,em_andamento,resolvido,fechado',
            'notes' => 'nullable|string|max:1000',
        ]);
        
        $oldStatus = $ticket->status;
        
        $updateData = ['status' => $validated['status']];
        
        // Atualizar timestamps baseado no status
        if ($validated['status'] === 'resolvido' && $oldStatus !== 'resolvido') {
            $updateData['resolved_at'] = now();
        } elseif ($validated['status'] === 'fechado' && $oldStatus !== 'fechado') {
            $updateData['closed_at'] = now();
        }
        
        $ticket->update($updateData);
        
        // Criar mensagem de mudança de status
        TicketMessage::create([
            'ticket_id' => $ticket->id,
            'user_id' => auth()->id(),
            'type' => 'status_change',
            'message' => "Status alterado de '{$oldStatus}' para '{$validated['status']}'" . 
                        ($validated['notes'] ? "\n\nNotas: {$validated['notes']}" : ''),
            'is_internal' => true,
            'metadata' => [
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
                'notes' => $validated['notes'],
            ],
        ]);
        
        return response()->json(['success' => true]);
    }

    /**
     * Reabre ticket fechado
     */
    public function reopen(string $ticketNumber): JsonResponse
    {
        $ticket = Ticket::findByNumber($ticketNumber);
        
        if (!$ticket) {
            abort(404, 'Ticket não encontrado.');
        }
        $this->authorize('reopen', $ticket);
        
        if (!$ticket->canBeReopened()) {
            return response()->json(['success' => false, 'message' => 'Ticket não pode ser reaberto']);
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
        
        return response()->json(['success' => true]);
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
