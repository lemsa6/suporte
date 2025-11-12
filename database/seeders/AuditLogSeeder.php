<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class AuditLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = User::all();
        
        if ($users->isEmpty()) {
            $this->command->warn('Nenhum usuário encontrado. Execute UserSeeder primeiro.');
            return;
        }

        // Gerar logins dos últimos 7 dias
        for ($i = 0; $i < 30; $i++) {
            $user = $users->random();
            $loginTime = Carbon::now()->subDays(rand(0, 7))->subHours(rand(0, 23))->subMinutes(rand(0, 59));
            
            // Login
            AuditLog::create([
                'event_type' => 'login_success',
                'auditable_type' => User::class,
                'auditable_id' => $user->id,
                'user_id' => $user->id,
                'user_type' => $user->role,
                'ip_address' => $this->generateRandomIP(),
                'user_agent' => $this->generateRandomUserAgent(),
                'old_values' => [],
                'new_values' => [
                    'email' => $user->email,
                    'role' => $user->role
                ],
                'description' => 'Login realizado com sucesso',
                'url' => '/login',
                'method' => 'POST',
                'created_at' => $loginTime,
                'updated_at' => $loginTime,
            ]);

            // Logout (algumas vezes)
            if (rand(1, 3) === 1) {
                $logoutTime = $loginTime->copy()->addHours(rand(1, 8));
                
                AuditLog::create([
                    'event_type' => 'logout',
                    'auditable_type' => User::class,
                    'auditable_id' => $user->id,
                    'user_id' => $user->id,
                    'user_type' => $user->role,
                    'ip_address' => $this->generateRandomIP(),
                    'user_agent' => $this->generateRandomUserAgent(),
                    'old_values' => [],
                    'new_values' => [
                        'email' => $user->email,
                        'role' => $user->role
                    ],
                    'description' => 'Logout realizado',
                    'url' => '/logout',
                    'method' => 'POST',
                    'created_at' => $logoutTime,
                    'updated_at' => $logoutTime,
                ]);
            }
        }

        $this->command->info('Dados de auditoria de login criados com sucesso!');
    }

    private function generateRandomIP(): string
    {
        return rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255) . '.' . rand(1, 255);
    }

    private function generateRandomUserAgent(): string
    {
        $userAgents = [
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36',
            'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:89.0) Gecko/20100101 Firefox/89.0',
            'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/605.1.15 (KHTML, like Gecko) Version/14.1.1 Safari/605.1.15',
        ];

        return $userAgents[array_rand($userAgents)];
    }
}