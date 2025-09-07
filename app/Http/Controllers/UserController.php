<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\ClientContact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    // Middleware aplicado nas rotas

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('assignedTickets')->paginate(15);
        return view('admin.settings.users', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'role' => ['required', 'in:admin,tecnico,cliente'],
            'company_name' => ['required_if:role,cliente', 'string', 'max:255'],
            'cnpj' => ['required_if:role,cliente', 'string', 'size:14', 'unique:clients,cnpj'],
        ], [
            'company_name.required_if' => 'O nome da empresa é obrigatório para clientes.',
            'cnpj.required_if' => 'O CNPJ é obrigatório para clientes.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Se for cliente, criar empresa e contato
        if ($request->role === 'cliente') {
            $this->createClientAndContact($user, $request->all());
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuário criado com sucesso!');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        $user->load(['assignedTickets', 'ticketMessages']);
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        return view('users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            'role' => ['required', 'in:admin,tecnico,cliente'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->email,
            'role' => $request->role,
        ]);

        if ($request->filled('password')) {
            $user->update(['password' => Hash::make($request->password)]);
        }

        return redirect()->route('users.index')
            ->with('success', 'Usuário atualizado com sucesso!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Não permitir deletar o próprio usuário
        if ($user->id === auth()->id()) {
            return redirect()->route('users.index')
                ->with('error', 'Você não pode deletar sua própria conta!');
        }

        $user->delete();

        return redirect()->route('users.index')
            ->with('success', 'Usuário removido com sucesso!');
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        $user->update(['is_active' => !$user->is_active]);

        $status = $user->is_active ? 'ativado' : 'desativado';
        return redirect()->route('users.index')
            ->with('success', "Usuário {$status} com sucesso!");
    }

    /**
     * Cria empresa e contato para usuário cliente
     */
    protected function createClientAndContact(User $user, array $data)
    {
        // Criar empresa
        $client = Client::create([
            'cnpj' => $data['cnpj'],
            'company_name' => $data['company_name'],
            'trade_name' => $data['company_name'],
            'is_active' => true,
        ]);

        // Criar contato principal
        ClientContact::create([
            'client_id' => $client->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_primary' => true,
            'is_active' => true,
        ]);
    }
}
