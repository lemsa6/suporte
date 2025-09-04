<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Support\Facades\Log;

class TestNotifications extends Command
{
    protected $signature = 'test:notifications {ticket_id?}';
    protected $description = 'Testa o sistema de notificações';

    public function handle()
    {
        $this->info('🧪 Testando sistema de notificações...');

        // Verificar se há usuários admin/tecnico
        $users = User::whereIn('role', ['admin', 'tecnico'])
            ->where('is_active', true)
            ->get();

        if ($users->isEmpty()) {
            $this->error('❌ Nenhum usuário admin/tecnico encontrado!');
            $this->info('Crie usuários com role "admin" ou "tecnico" primeiro.');
            return 1;
        }

        $this->info("✅ Encontrados {$users->count()} usuários admin/tecnico:");
        foreach ($users as $user) {
            $this->line("  - {$user->name} ({$user->email}) - {$user->role}");
        }

        // Buscar um ticket para teste
        $ticketId = $this->argument('ticket_id');
        if ($ticketId) {
            $ticket = Ticket::find($ticketId);
        } else {
            $ticket = Ticket::first();
        }

        if (!$ticket) {
            $this->error('❌ Nenhum ticket encontrado!');
            $this->info('Crie um ticket primeiro.');
            return 1;
        }

        $this->info("✅ Ticket encontrado: #{$ticket->ticket_number} - {$ticket->title}");

        // Testar NotificationService
        try {
            $notificationService = app(NotificationService::class);
            
            $this->info('🔔 Testando notificação de mudança de status...');
            $notificationService->notifyStatusChange(
                $ticket, 
                'aberto', 
                'em_andamento', 
                $users->first()
            );

            $this->info('🔔 Testando notificação de mudança de prioridade...');
            $notificationService->notifyPriorityChange(
                $ticket, 
                'média', 
                'alta', 
                $users->first()
            );

            $this->info('✅ Notificações enviadas com sucesso!');
            
            // Verificar se o arquivo de log foi criado
            $logPath = storage_path('logs/mail.log');
            if (file_exists($logPath)) {
                $this->info("📝 Log de e-mails criado em: {$logPath}");
                $this->info("Últimas linhas do log:");
                $lines = file($logPath);
                $lastLines = array_slice($lines, -5);
                foreach ($lastLines as $line) {
                    $this->line("  " . trim($line));
                }
            } else {
                $this->warn("⚠️ Arquivo de log de e-mails não foi criado.");
                $this->info("Verifique se o MAIL_MAILER=log está configurado.");
            }

        } catch (\Exception $e) {
            $this->error("❌ Erro ao testar notificações: " . $e->getMessage());
            $this->error("Stack trace: " . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
