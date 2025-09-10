<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateOrUpdateUser extends Command
{
    protected $signature = 'user:create-or-update {email} {password} {name}';
    protected $description = 'Create or update user';

    public function handle()
    {
        $email = $this->argument('email');
        $password = $this->argument('password');
        $name = $this->argument('name');

        $user = User::where('email', $email)->first();

        if ($user) {
            $user->password = Hash::make($password);
            $user->is_active = true;
            $user->save();
            $this->info("Usuário atualizado: {$user->name} ({$user->email})");
        } else {
            $user = User::create([
                'name' => $name,
                'email' => $email,
                'password' => Hash::make($password),
                'is_active' => true,
                'role' => 'admin'
            ]);
            $this->info("Usuário criado: {$user->name} ({$user->email})");
        }

        $this->info("Senha: {$password}");
        $this->info("Ativo: " . ($user->is_active ? 'Sim' : 'Não'));
        
        return 0;
    }
}
