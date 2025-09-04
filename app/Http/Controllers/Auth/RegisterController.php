<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;

class RegisterController extends Controller
{
    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\View\View
     */
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
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
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\Models\User
     */
    protected function create(array $data)
    {
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'role' => $data['role'],
        ]);

        // Se for cliente, criar empresa e contato
        if ($data['role'] === 'cliente') {
            $this->createClientAndContact($user, $data);
        }

        return $user;
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        $this->guard()->login($user);

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $message = 'Conta criada com sucesso! ';
        
        if ($user->isCliente()) {
            $message .= 'Sua empresa foi cadastrada automaticamente.';
        }
        
        return redirect()->route('dashboard')->with('success', $message);
    }

    /**
     * Cria empresa e contato para usuário cliente
     */
    protected function createClientAndContact(User $user, array $data)
    {
        // Criar empresa
        $client = \App\Models\Client::create([
            'cnpj' => $data['cnpj'],
            'company_name' => $data['company_name'],
            'trade_name' => $data['company_name'],
            'is_active' => true,
        ]);

        // Criar contato principal
        \App\Models\ClientContact::create([
            'client_id' => $client->id,
            'name' => $user->name,
            'email' => $user->email,
            'is_primary' => true,
            'is_active' => true,
        ]);
    }

    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * Get the post-registration redirect path.
     *
     * @return string
     */
    protected function redirectPath()
    {
        return $this->redirectTo;
    }
}
