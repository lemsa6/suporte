<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CreateAdmin extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:create-admin';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cria usuário administrador';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        \App\Models\User::create([
            'name' => 'Administrador',
            'email' => 'contato@8bits.pro',
            'password' => bcrypt('AMESMASENHA2022*'),
            'role' => 'admin',
            'is_active' => true
        ]);
        
        $this->info('Usuário administrador criado com sucesso!');
        $this->info('Email: contato@8bits.pro');
        $this->info('Senha: AMESMASENHA2022*');
    }
}
