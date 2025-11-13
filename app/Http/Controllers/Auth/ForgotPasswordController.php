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
        // Verificar limite de tentativas por IP (5 tentativas por hora)
        $key = 'password_reset_attempts:' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 5) {
            return back()->withErrors([
                'email' => 'Muitas tentativas de recuperação de senha. Tente novamente em 1 hora.'
            ]);
        }

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
            // Incrementar tentativas mesmo para emails inválidos (segurança)
            cache()->put($key, $attempts + 1, now()->addHour());
            
            throw ValidationException::withMessages([
                'email' => 'Este email não está cadastrado em nosso sistema.'
            ]);
        }

        // Verificar se já existe um token recente (evitar spam)
        $recentReset = DB::table('password_resets')
            ->where('email', $email)
            ->where('created_at', '>', now()->subMinutes(5))
            ->first();

        if ($recentReset) {
            return back()->withErrors([
                'email' => 'Já enviamos um link de recuperação recentemente. Verifique seu email ou aguarde 5 minutos.'
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
            
            // Incrementar contador de tentativas
            cache()->put($key, $attempts + 1, now()->addHour());
            
            // Log da tentativa de recuperação
            \Log::info('Password reset requested', [
                'email' => $email,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'attempts' => $attempts + 1
            ]);
            
            return back()->with('status', 'Enviamos um link de recuperação de senha para seu email.');
        } catch (\Exception $e) {
            \Log::error('Failed to send password reset email', [
                'email' => $email,
                'error' => $e->getMessage()
            ]);
            
            return back()->withErrors([
                'email' => 'Erro ao enviar email. Tente novamente em alguns minutos.'
            ]);
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
        // Verificar limite de tentativas de reset por IP (10 tentativas por hora)
        $key = 'password_reset_attempts:' . $request->ip();
        $attempts = cache()->get($key, 0);
        
        if ($attempts >= 10) {
            return back()->withErrors([
                'email' => 'Muitas tentativas de redefinição de senha. Tente novamente em 1 hora.'
            ]);
        }

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
            // Incrementar tentativas para tokens inválidos
            cache()->put($key, $attempts + 1, now()->addHour());
            
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

        // Log da redefinição bem-sucedida
        \Log::info('Password reset completed', [
            'email' => $email,
            'user_id' => $user->id,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Limpar tentativas após sucesso
        cache()->forget($key);

        return redirect()->route('login')->with('status', 'Senha redefinida com sucesso! Faça login com sua nova senha.');
    }
}