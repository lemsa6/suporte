<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class ResetUserPassword extends Command
{
    protected $signature = 'user:reset-password {email} {password}';
    protected $description = 'Reset user password';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');

        $user = User::where('email', $email)->first();

        if (!$user) {
            $this->error("Usuário com email {$email} não encontrado!");
            return 1;
        }

        $user->password = Hash::make($password);
        $user->is_active = true;
        $user->save();

        $this->info("Senha resetada com sucesso para {$user->name} ({$user->email})");
        $this->info("Usuário ativo: " . ($user->is_active ? 'Sim' : 'Não'));
        
        return 0;
    }
}
