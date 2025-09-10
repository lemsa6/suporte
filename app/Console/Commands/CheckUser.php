<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CheckUser extends Command
{
    protected $signature = 'user:check {email}';
    protected $description = 'Check user details';

    public function handle()
    {
        $email = $this->argument('email');
        
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado!");
            return 1;
        }
        
        $this->info("=== DADOS DO USUÁRIO ===");
        $this->info("Nome: {$user->name}");
        $this->info("Email: {$user->email}");
        $this->info("Ativo: " . ($user->is_active ? 'Sim' : 'Não'));
        $this->info("Role: {$user->role}");
        $this->info("Senha hash: " . substr($user->password, 0, 30) . "...");
        $this->info("Criado em: {$user->created_at}");
        
        // Testar senha
        $testPassword = 'AMESMASENHA2022*';
        $passwordMatch = Hash::check($testPassword, $user->password);
        $this->info("Senha '{$testPassword}' confere: " . ($passwordMatch ? 'SIM' : 'NÃO'));
        
        return 0;
    }
}
