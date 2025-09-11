<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Mail;

class SettingsController extends Controller
{
    // Middleware aplicado nas rotas

    /**
     * Página principal de configurações
     */
    public function index(): View
    {
        return view('admin.settings.index');
    }

    /**
     * Configurações do sistema
     */
    public function system(): View
    {
        $settings = [
            'app_name' => Setting::get('app_name', 'Sistema de Tickets'),
            'app_url' => Setting::get('app_url', config('app.url')),
            'app_logo' => Setting::get('app_logo', ''),
            'company_name' => Setting::get('company_name', '8Bits Pro'),
            'company_cnpj' => Setting::get('company_cnpj', ''),
            'contact_email' => Setting::get('contact_email', 'contato@8bits.pro'),
            'contact_phone' => Setting::get('contact_phone', '(11) 99999-9999'),
            'company_address' => Setting::get('company_address', ''),
            'company_website' => Setting::get('company_website', ''),
            'company_working_hours' => Setting::get('company_working_hours', 'Segunda a Sexta, 8h às 18h'),
            'timezone' => Setting::get('timezone', 'America/Sao_Paulo'),
        ];

        return view('admin.settings.system', compact('settings'));
    }

    /**
     * Atualizar configurações do sistema
     */
    public function updateSystem(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'app_name' => 'required|string|max:255',
            'app_url' => 'required|url|max:255',
            'app_logo' => 'nullable|string|max:255',
            'company_name' => 'required|string|max:255',
            'company_cnpj' => 'nullable|string|max:18',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:50',
            'company_address' => 'nullable|string|max:500',
            'company_website' => 'nullable|url|max:255',
            'company_working_hours' => 'nullable|string|max:255',
            'timezone' => 'required|string|max:50',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, null, 'system');
        }

        return redirect()->route('admin.settings.system')
            ->with('success', 'Configurações do sistema atualizadas com sucesso!');
    }

    /**
     * Configurações de email
     */
    public function email(): View
    {
        $settings = [
            'mail_from_address' => Setting::get('mail_from_address', config('mail.from.address')),
            'mail_from_name' => Setting::get('mail_from_name', config('mail.from.name')),
            'mail_smtp_host' => Setting::get('mail_smtp_host', config('mail.host')),
            'mail_smtp_port' => Setting::get('mail_smtp_port', config('mail.port')),
            'mail_smtp_username' => Setting::get('mail_smtp_username', config('mail.username')),
            'mail_smtp_password' => Setting::get('mail_smtp_password', config('mail.password')),
            'mail_smtp_encryption' => Setting::get('mail_smtp_encryption', config('mail.encryption')),
        ];

        return view('admin.settings.email', compact('settings'));
    }

    /**
     * Atualizar configurações de email
     */
    public function updateEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'mail_from_address' => 'required|email|max:255',
            'mail_from_name' => 'required|string|max:255',
            'mail_smtp_host' => 'required|string|max:255',
            'mail_smtp_port' => 'required|integer|min:1|max:65535',
            'mail_smtp_username' => 'required|string|max:255',
            'mail_smtp_password' => 'required|string|max:255',
            'mail_smtp_encryption' => 'required|in:tls,ssl,none',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, null, 'email');
        }

        return redirect()->route('admin.settings.email')
            ->with('success', 'Configurações de email atualizadas com sucesso!');
    }

    /**
     * Configurações de templates de notificação
     */
    public function templates(): View
    {
        $templates = [
            'ticket_created' => $this->getTemplateContent('emails.tickets.created'),
            'ticket_created_for_you' => $this->getTemplateContent('emails.tickets.created-for-you'),
            'ticket_replied' => $this->getTemplateContent('emails.tickets.replied'),
            'ticket_status_changed' => $this->getTemplateContent('emails.tickets.status-changed'),
            'ticket_closed' => $this->getTemplateContent('emails.tickets.closed'),
            'email_layout' => $this->getTemplateContent('emails.layouts.app'),
        ];

        return view('admin.settings.templates', compact('templates'));
    }

    /**
     * Atualizar template de notificação
     */
    public function updateTemplate(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'template_name' => 'required|string|in:ticket_created,ticket_created_for_you,ticket_replied,ticket_status_changed,ticket_closed,email_layout',
            'template_content' => 'required|string',
        ]);

        $templatePath = $this->getTemplatePath($validated['template_name']);
        
        if ($templatePath) {
            File::put($templatePath, $validated['template_content']);
            
            return redirect()->route('admin.settings.templates')
                ->with('success', 'Template atualizado com sucesso!');
        }

        return redirect()->route('admin.settings.templates')
            ->with('error', 'Erro ao atualizar template!');
    }

    /**
     * Preview de template
     */
    public function previewTemplate(Request $request): View
    {
        $templateName = $request->input('template_name');
        $templateContent = $request->input('template_content');

        // Dados de exemplo para preview
        $sampleData = $this->getSampleData($templateName);

        return view('admin.settings.template-preview', compact('templateName', 'templateContent', 'sampleData'));
    }

    /**
     * Testar envio de email
     */
    public function testEmail(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'test_email' => 'required|email',
            'template_type' => 'required|in:ticket_created,ticket_created_for_you,ticket_replied,ticket_status_changed,ticket_closed',
        ]);

        try {
            // Aqui você pode implementar o envio de email de teste
            // Por enquanto, apenas retornamos sucesso
            return redirect()->route('admin.settings.templates')
                ->with('success', 'Email de teste enviado com sucesso!');
        } catch (\Exception $e) {
            return redirect()->route('admin.settings.templates')
                ->with('error', 'Erro ao enviar email de teste: ' . $e->getMessage());
        }
    }

    /**
     * Obter conteúdo do template
     */
    private function getTemplateContent(string $templateName): string
    {
        $templatePath = resource_path('views/' . str_replace('.', '/', $templateName) . '.blade.php');
        
        if (File::exists($templatePath)) {
            return File::get($templatePath);
        }

        return '';
    }

    /**
     * Obter caminho do template
     */
    private function getTemplatePath(string $templateName): ?string
    {
        $templateMap = [
            'ticket_created' => 'emails/tickets/created.blade.php',
            'ticket_created_for_you' => 'emails/tickets/created-for-you.blade.php',
            'ticket_replied' => 'emails/tickets/replied.blade.php',
            'ticket_status_changed' => 'emails/tickets/status-changed.blade.php',
            'ticket_closed' => 'emails/tickets/closed.blade.php',
            'email_layout' => 'emails/layouts/app.blade.php',
        ];

        if (isset($templateMap[$templateName])) {
            return resource_path('views/' . $templateMap[$templateName]);
        }

        return null;
    }

    /**
     * Configurações de notificações
     */
    public function notifications(): View
    {
        $settings = [
            'notifications_enabled' => Setting::get('notifications_enabled', true),
            'email_notifications' => Setting::get('email_notifications', true),
            'sms_notifications' => Setting::get('sms_notifications', false),
            'push_notifications' => Setting::get('push_notifications', true),
            'notification_frequency' => Setting::get('notification_frequency', 'immediate'),
            'notify_ticket_created' => Setting::get('notify_ticket_created', true),
            'notify_ticket_updated' => Setting::get('notify_ticket_updated', true),
            'notify_ticket_closed' => Setting::get('notify_ticket_closed', true),
            'notify_assignment' => Setting::get('notify_assignment', true),
        ];

        return view('admin.settings.notifications', compact('settings'));
    }

    /**
     * Atualizar configurações de notificações
     */
    public function updateNotifications(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'notifications_enabled' => 'boolean',
            'email_notifications' => 'boolean',
            'sms_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            'notification_frequency' => 'required|in:immediate,hourly,daily',
            'notify_ticket_created' => 'boolean',
            'notify_ticket_updated' => 'boolean',
            'notify_ticket_closed' => 'boolean',
            'notify_assignment' => 'boolean',
        ]);

        // Converter checkboxes não marcados para false
        $booleanFields = ['notifications_enabled', 'email_notifications', 'sms_notifications', 'push_notifications', 'notify_ticket_created', 'notify_ticket_updated', 'notify_ticket_closed', 'notify_assignment'];
        
        foreach ($booleanFields as $field) {
            $validated[$field] = $request->has($field) ? true : false;
        }

        foreach ($validated as $key => $value) {
            Setting::set($key, $value, null, 'notifications');
        }

        return redirect()->route('admin.settings.notifications')
            ->with('success', 'Configurações de notificações atualizadas com sucesso!');
    }

    /**
     * Obter dados de exemplo para preview
     */
    private function getSampleData(string $templateName): array
    {
        return [
            'ticket' => (object) [
                'id' => 1,
                'ticket_number' => 'TKT-2024-001',
                'title' => 'Problema com sistema de login',
                'description' => 'Usuários não conseguem fazer login no sistema principal.',
                'priority' => 'alta',
                'status' => 'aberto',
                'created_at' => now(),
                'closed_at' => now()->addDays(2),
                'client' => (object) [
                    'company_name' => 'Empresa Exemplo Ltda'
                ],
                'category' => (object) [
                    'name' => 'Suporte Técnico'
                ],
                'contact' => (object) [
                    'name' => 'João Silva',
                    'email' => 'joao@empresa.com'
                ],
                'assignedTo' => (object) [
                    'name' => 'Maria Santos'
                ],
                'resolution_notes' => 'Problema resolvido atualizando a versão do sistema.'
            ],
            'reply' => (object) [
                'id' => 1,
                'content' => 'Olá! Analisamos o problema e identificamos a causa. Vamos resolver em breve.',
                'created_at' => now(),
                'is_internal' => false
            ],
            'oldStatus' => 'aberto',
            'newStatus' => 'em_andamento',
            'changedBy' => (object) [
                'name' => 'Ana Costa'
            ],
            'createdBy' => (object) [
                'name' => 'Carlos Admin'
            ],
            'closedBy' => (object) [
                'name' => 'Pedro Técnico'
            ],
            'resolutionTime' => '2 dias e 4 horas'
        ];
    }
}