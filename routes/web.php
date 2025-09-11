<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ClientController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AttachmentController;
use App\Http\Controllers\CompanyUserController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rotas públicas
Route::get('/', function () {
    return redirect()->route('login');
});

// Rotas de autenticação
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    // Rotas de recuperação de senha
    Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'reset'])->name('password.update');
});

// Rotas autenticadas
Route::middleware('auth')->group(function () {
    
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    // Logout
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // Perfil do usuário
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::get('/profile/show', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/activity', [ProfileController::class, 'activity'])->name('profile.activity');
    Route::get('/profile/preferences', [ProfileController::class, 'preferences'])->name('profile.preferences');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::put('/profile/preferences', [ProfileController::class, 'updatePreferences'])->name('profile.preferences.update');
    Route::put('/profile/notifications', [ProfileController::class, 'updateNotifications'])->name('profile.notifications.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Rotas dos Tickets
    Route::middleware(['auth'])->group(function () {
        Route::get('/tickets', [TicketController::class, 'index'])->name('tickets.index');
        Route::get('/tickets/create', [TicketController::class, 'create'])->name('tickets.create');
        Route::post('/tickets', [TicketController::class, 'store'])->name('tickets.store');
        Route::get('/tickets/{ticketNumber}', [TicketController::class, 'show'])->name('tickets.show');
        Route::get('/tickets/{ticketNumber}/edit', [TicketController::class, 'edit'])->name('tickets.edit');
        Route::put('/tickets/{ticketNumber}', [TicketController::class, 'update'])->name('tickets.update');
        Route::delete('/tickets/{ticketNumber}', [TicketController::class, 'destroy'])->name('tickets.destroy');
        Route::post('/tickets/{ticketNumber}/message', [TicketController::class, 'addMessage'])->name('tickets.message');
        
        // Ações em lote
        Route::post('/tickets/bulk-action', [TicketController::class, 'bulkAction'])->name('tickets.bulk-action');
    });
    
    // Rotas de anexos
    Route::get('/attachments/{attachment}/download', [AttachmentController::class, 'download'])->name('attachments.download');
    Route::get('/attachments/{attachment}/preview', [AttachmentController::class, 'preview'])->name('attachments.preview');
    Route::delete('/attachments/{attachment}', [AttachmentController::class, 'destroy'])->name('attachments.destroy');
    
    // Rotas de clientes (apenas Admin e Técnicos)
    Route::middleware('role:admin,tecnico')->group(function () {
        Route::resource('clients', ClientController::class);
        Route::post('/clients/{client}/toggle-status', [ClientController::class, 'toggleStatus'])->name('clients.toggle-status');
        Route::post('/clients/bulk-action', [ClientController::class, 'bulkAction'])->name('clients.bulk-action');
        
        // Rotas para gerenciar contatos dos clientes
        Route::post('/clients/{client}/contacts', [ClientController::class, 'storeContact'])->name('clients.contacts.store');
        Route::get('/clients/{client}/contacts/{contact}/edit', [ClientController::class, 'editContact'])->name('clients.contacts.edit');
        Route::put('/clients/{client}/contacts/{contact}', [ClientController::class, 'updateContact'])->name('clients.contacts.update');
        Route::delete('/clients/{client}/contacts/{contact}', [ClientController::class, 'deleteContact'])->name('clients.contacts.delete');
        
        // API endpoints para clientes
        Route::get('/api/clients', [ClientController::class, 'apiList'])->name('api.clients.list');
        Route::get('/api/clients/search-cnpj', [ClientController::class, 'apiSearchByCnpj'])->name('api.clients.search-cnpj');
        
        // Rotas de categorias (apenas Admin e Técnicos)
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/{category}/toggle-status', [CategoryController::class, 'toggleStatus'])->name('categories.toggle-status');
        
        // API endpoints para categorias
        Route::get('/api/categories', [CategoryController::class, 'apiList'])->name('api.categories.list');
        Route::get('/api/categories/{category}/stats', [CategoryController::class, 'apiStats'])->name('api.categories.stats');
        
        // Rotas de relatórios (apenas Admin e Técnicos)
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/tickets', [ReportController::class, 'tickets'])->name('reports.tickets');
        Route::get('/reports/clients', [ReportController::class, 'clients'])->name('reports.clients');
        Route::get('/api/reports/chart-data', [ReportController::class, 'chartData'])->name('api.reports.chart-data');
    });

    // Rotas para gestores gerenciarem usuários da empresa
    Route::middleware('role:cliente_gestor')->group(function () {
        Route::resource('company.users', CompanyUserController::class);
        Route::post('/company/users/{companyUser}/toggle-status', [CompanyUserController::class, 'toggleStatus'])->name('company.users.toggle-status');
    });

// Rota para buscar contatos de um cliente (usada no formulário de criação de ticket) - Acesso para todos autenticados
Route::get('/clients/{client}/contacts', [ClientController::class, 'getContacts'])->name('clients.contacts.index')->middleware('auth');

// Rota de teste para verificar se está funcionando
Route::get('/test-contacts/{client}', function($client) {
    $clientModel = \App\Models\Client::find($client);
    if (!$clientModel) {
        return response()->json(['error' => 'Cliente não encontrado'], 404);
    }
    
    $contacts = $clientModel->contacts()
        ->select('id', 'name', 'email', 'phone', 'position', 'department', 'is_primary')
        ->orderBy('is_primary', 'desc')
        ->orderBy('name')
        ->get();
    
    return response()->json($contacts);
})->name('test.contacts');
    
    // Rotas de configurações (apenas Admin)
    Route::middleware('role:admin')->group(function () {
        // Configurações principais
        Route::get('/admin/settings', [App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('admin.settings.index');
        Route::get('/admin/settings/system', [App\Http\Controllers\Admin\SettingsController::class, 'system'])->name('admin.settings.system');
        Route::put('/admin/settings/system', [App\Http\Controllers\Admin\SettingsController::class, 'updateSystem'])->name('admin.settings.system.update');
        Route::get('/admin/settings/email', [App\Http\Controllers\Admin\SettingsController::class, 'email'])->name('admin.settings.email');
        Route::put('/admin/settings/email', [App\Http\Controllers\Admin\SettingsController::class, 'updateEmail'])->name('admin.settings.email.update');
        Route::get('/admin/settings/templates', [App\Http\Controllers\Admin\SettingsController::class, 'templates'])->name('admin.settings.templates');
        Route::put('/admin/settings/templates', [App\Http\Controllers\Admin\SettingsController::class, 'updateTemplate'])->name('admin.settings.templates.update');
        Route::post('/admin/settings/templates/preview', [App\Http\Controllers\Admin\SettingsController::class, 'previewTemplate'])->name('admin.settings.templates.preview');
        Route::post('/admin/settings/templates/test', [App\Http\Controllers\Admin\SettingsController::class, 'testEmail'])->name('admin.settings.templates.test');
        Route::get('/admin/settings/notifications', [App\Http\Controllers\Admin\SettingsController::class, 'notifications'])->name('admin.settings.notifications');
        Route::put('/admin/settings/notifications', [App\Http\Controllers\Admin\SettingsController::class, 'updateNotifications'])->name('admin.settings.notifications.update');
        
        // Rotas legadas (compatibilidade)
        Route::get('/settings', function () {
            return redirect()->route('admin.settings.index');
        })->name('settings.index');
        
        Route::get('/settings/users', [UserController::class, 'index'])->name('settings.users');
        
        Route::get('/settings/system', function () {
            return redirect()->route('admin.settings.system');
        })->name('settings.system');
        
        // Gerenciamento de usuários
        Route::resource('users', UserController::class);
        Route::post('/users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Sistema de Auditoria
        Route::prefix('admin/audit')->name('admin.audit.')->group(function () {
            Route::get('/', [App\Http\Controllers\AuditController::class, 'index'])->name('index');
            Route::get('/statistics', [App\Http\Controllers\AuditController::class, 'statistics'])->name('statistics');
            Route::get('/export', [App\Http\Controllers\AuditController::class, 'export'])->name('export');
            Route::get('/ticket/{ticketNumber}', [App\Http\Controllers\AuditController::class, 'ticketLogs'])->name('ticket');
            Route::get('/user/{user}', [App\Http\Controllers\AuditController::class, 'userLogs'])->name('user');
            Route::get('/{auditLog}', [App\Http\Controllers\AuditController::class, 'show'])->name('show');
            
            // APIs
            Route::get('/api/logs', [App\Http\Controllers\AuditController::class, 'apiLogs'])->name('api.logs');
            Route::get('/api/statistics', [App\Http\Controllers\AuditController::class, 'apiStatistics'])->name('api.statistics');
        });
    });
});

// Rota de fallback
Route::fallback(function () {
    return redirect()->route('dashboard');
});
