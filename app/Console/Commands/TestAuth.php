<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class TestAuth extends Command
{
    protected $signature = 'auth:test';
    protected $description = 'Test authentication system';

    public function handle()
    {
        $email = 'contato@8bits.pro';
        $password = 'AMESMASENHA2022*';
        
        $this->info("=== TESTE DE AUTENTICAÇÃO ===");
        
        // Verificar se usuário existe
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Usuário não encontrado!");
            return 1;
        }
        
        $this->info("Usuário encontrado: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Ativo: " . ($user->is_active ? 'Sim' : 'Não'));
        $this->info("Role: {$user->role}");
        
        // Testar hash da senha
        $this->info("\n=== TESTE DE SENHA ===");
        $this->info("Senha fornecida: {$password}");
        $this->info("Hash no banco: " . substr($user->password, 0, 30) . "...");
        
        // Testar verificação de senha
        $passwordCheck = Hash::check($password, $user->password);
        $this->info("Senha confere: " . ($passwordCheck ? 'SIM' : 'NÃO'));
        
        // Testar autenticação Laravel
        $this->info("\n=== TESTE DE AUTENTICAÇÃO LARAVEL ===");
        $authResult = Auth::attempt(['email' => $email, 'password' => $password]);
        $this->info("Auth::attempt(): " . ($authResult ? 'SUCESSO' : 'FALHA'));
        
        if ($authResult) {
            $this->info("Usuário autenticado: " . Auth::user()->name);
        }
        
        return 0;
    }
}
