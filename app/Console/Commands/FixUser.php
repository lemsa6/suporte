<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class FixUser extends Command
{
    protected $signature = 'user:fix';
    protected $description = 'Fix user login';

    public function handle()
    {
        $email = 'contato@8bits.pro';
        $password = 'AMESMASENHA2022*';
        
        // Verificar se usuário existe
        $user = User::where('email', $email)->first();
        
        if (!$user) {
            $this->info("Criando usuário...");
            $user = User::create([
                'name' => 'Administrador',
                'email' => $email,
                'password' => Hash::make($password),
                'role' => 'admin',
                'is_active' => true
            ]);
            $this->info("Usuário criado com sucesso!");
        } else {
            $this->info("Usuário encontrado, atualizando senha...");
            $user->password = Hash::make($password);
            $user->is_active = true;
            $user->save();
            $this->info("Usuário atualizado com sucesso!");
        }
        
        // Verificar senha
        $passwordCheck = Hash::check($password, $user->password);
        $this->info("Senha confere: " . ($passwordCheck ? 'SIM' : 'NÃO'));
        
        $this->info("=== CREDENCIAIS ===");
        $this->info("Email: {$user->email}");
        $this->info("Senha: {$password}");
        $this->info("Ativo: " . ($user->is_active ? 'Sim' : 'Não'));
        
        return 0;
    }
}