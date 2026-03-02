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

    private function authorizeClientAccess(Client $client): void
    {
        $user = auth()->user();

        if ($user->isAdmin() || $user->isTecnico()) {
            return;
        }

        if (!$user->isClienteGestor()) {
            abort(403, 'Acesso negado.');
        }

        $belongsToCompany = $client->contacts()
            ->where(function ($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->orWhere('email', $user->email);
            })
            ->where('user_type', 'cliente_gestor')
            ->exists();

        if (!$belongsToCompany) {
            abort(403, 'Acesso negado.');
        }
    }

    public function index(Request $request): View
    {
        $client = Client::findOrFail($request->route('client'));
        $this->authorizeClientAccess($client);
        
        $contacts = $client->contacts()
            ->withUser()
            ->orderBy('is_primary', 'desc')
            ->orderBy('name')
            ->get();
        
        return view('clients.users.index', compact('client', 'contacts'));
    }

    public function create(Request $request): View
    {
        $client = Client::findOrFail($request->route('client'));
        $this->authorizeClientAccess($client);
        
        return view('clients.users.create', compact('client'));
    }

    public function store(StoreClientUserRequest $request): RedirectResponse
    {
        $client = Client::findOrFail($request->route('client'));
        $this->authorizeClientAccess($client);
        
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

    public function edit(Request $request, $contactId): View
    {
        $client = Client::findOrFail($request->route('client'));
        $this->authorizeClientAccess($client);
        
        $contact = ClientContact::where('id', $contactId)
            ->where('client_id', $client->id)
            ->with('user')
            ->firstOrFail();
        
        return view('clients.users.edit', compact('client', 'contact'));
    }

    public function update(UpdateClientUserRequest $request, $contactId): RedirectResponse
    {
        $client = Client::findOrFail($request->route('client'));
        $this->authorizeClientAccess($client);
        
        $contact = ClientContact::where('id', $contactId)
            ->where('client_id', $client->id)
            ->with('user')
            ->firstOrFail();
        
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

    public function toggleStatus(Request $request, $contactId): RedirectResponse
    {
        $client = Client::findOrFail($request->route('client'));
        $this->authorizeClientAccess($client);
        
        $contact = ClientContact::where('id', $contactId)
            ->where('client_id', $client->id)
            ->with('user')
            ->firstOrFail();
        
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
