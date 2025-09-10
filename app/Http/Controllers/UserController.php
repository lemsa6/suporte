<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Client;
use App\Models\ClientContact;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    protected UserService $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

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
        $validatedData = $this->validateUserData($request, isUpdate: false);

        try {
            $user = $this->userService->createUser($validatedData);

            return redirect()
                ->route('users.index')
                ->with('success', 'Usuário criado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao criar usuário: ' . $e->getMessage());
        }
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
        $validatedData = $this->validateUserData($request, $user, isUpdate: true);

        try {
            $this->userService->updateUser($user, $validatedData);

            return redirect()
                ->route('users.index')
                ->with('success', 'Usuário atualizado com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar usuário: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        if ($this->isCurrentUser($user)) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Você não pode deletar sua própria conta!');
        }

        try {
            $this->userService->deleteUser($user);

            return redirect()
                ->route('users.index')
                ->with('success', 'Usuário removido com sucesso!');
        } catch (\Exception $e) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Erro ao remover usuário: ' . $e->getMessage());
        }
    }

    /**
     * Toggle user status
     */
    public function toggleStatus(User $user)
    {
        try {
            $this->userService->toggleUserStatus($user);

            $status = $user->is_active ? 'ativado' : 'desativado';
            return redirect()
                ->route('users.index')
                ->with('success', "Usuário {$status} com sucesso!");
        } catch (\Exception $e) {
            return redirect()
                ->route('users.index')
                ->with('error', 'Erro ao alterar status: ' . $e->getMessage());
        }
    }

    /**
     * Validate user data based on operation type
     */
    protected function validateUserData(Request $request, ?User $user = null, bool $isUpdate = false): array
    {
        $rules = [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'role' => ['required', 'in:admin,tecnico,cliente'],
        ];

        // Email uniqueness rule
        if ($isUpdate) {
            $rules['email'][] = 'unique:users,email,' . $user->id;
        } else {
            $rules['email'][] = 'unique:users';
        }

        // Password rules
        if ($isUpdate) {
            $rules['password'] = ['nullable', 'confirmed', Password::defaults()];
        } else {
            $rules['password'] = ['required', 'confirmed', Password::defaults()];
        }

        // Client-specific rules
        if ($request->role === 'cliente') {
            $rules['company_name'] = ['required', 'string', 'max:255'];
            $rules['cnpj'] = ['required', 'string', 'size:14'];
            
            if ($isUpdate) {
                $rules['cnpj'][] = 'unique:clients,cnpj,' . ($user->client?->id ?? 'NULL');
            } else {
                $rules['cnpj'][] = 'unique:clients,cnpj';
            }
        }

        $messages = [
            'company_name.required' => 'O nome da empresa é obrigatório para clientes.',
            'cnpj.required' => 'O CNPJ é obrigatório para clientes.',
            'cnpj.unique' => 'Este CNPJ já está cadastrado no sistema.',
        ];

        return $request->validate($rules, $messages);
    }

    /**
     * Check if the user is the current authenticated user
     */
    protected function isCurrentUser(User $user): bool
    {
        return $user->id === auth()->id();
    }
}