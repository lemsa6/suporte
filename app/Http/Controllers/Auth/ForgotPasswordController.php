<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class ForgotPasswordController extends Controller
{
    /**
     * Exibe o formulário de solicitação de recuperação de senha
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Envia o email de recuperação de senha
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.exists' => 'Este email não está cadastrado em nosso sistema.'
        ]);

        $email = $request->email;
        $user = User::where('email', $email)->first();

        if (!$user) {
            throw ValidationException::withMessages([
                'email' => 'Este email não está cadastrado em nosso sistema.'
            ]);
        }

        // Gerar token único
        $token = Str::random(64);
        
        // Salvar token no banco de dados
        DB::table('password_resets')->updateOrInsert(
            ['email' => $email],
            [
                'email' => $email,
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // Enviar email
        try {
            Mail::to($user->email)->send(new PasswordResetMail($user, $token));
            
            return back()->with('status', 'Enviamos um link de recuperação de senha para seu email.');
        } catch (\Exception $e) {
            return back()->withErrors(['email' => 'Erro ao enviar email. Tente novamente mais tarde.']);
        }
    }

    /**
     * Exibe o formulário de redefinição de senha
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $request->email
        ]);
    }

    /**
     * Processa a redefinição de senha
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'O campo email é obrigatório.',
            'email.email' => 'Digite um email válido.',
            'email.exists' => 'Este email não está cadastrado em nosso sistema.',
            'password.required' => 'O campo senha é obrigatório.',
            'password.min' => 'A senha deve ter pelo menos 8 caracteres.',
            'password.confirmed' => 'A confirmação de senha não confere.',
        ]);

        $email = $request->email;
        $token = $request->token;
        $password = $request->password;

        // Verificar se o token é válido
        $passwordReset = DB::table('password_resets')
            ->where('email', $email)
            ->first();

        if (!$passwordReset || !Hash::check($token, $passwordReset->token)) {
            return back()->withErrors(['email' => 'Token inválido ou expirado.']);
        }

        // Verificar se o token não expirou (24 horas)
        if (now()->diffInHours($passwordReset->created_at) > 24) {
            DB::table('password_resets')->where('email', $email)->delete();
            return back()->withErrors(['email' => 'Token expirado. Solicite uma nova recuperação de senha.']);
        }

        // Atualizar senha do usuário
        $user = User::where('email', $email)->first();
        $user->password = Hash::make($password);
        $user->save();

        // Remover token do banco
        DB::table('password_resets')->where('email', $email)->delete();

        return redirect()->route('login')->with('status', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
    }
}