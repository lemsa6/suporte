@extends('layouts.app')

@section('title', 'Configurações de Email')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Administração</span>
        <h1 class="page-title mt-1">Configurações de Email</h1>
        <p class="text-cinza mt-2">Configure o servidor SMTP e configurações de envio de emails</p>
    </div>
    <x-button variant="outline" tag="a" href="{{ route('admin.settings.index') }}">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar
    </x-button>
</div>
@endsection

@section('content')
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <div class="lg:col-span-2">
        <h2 class="section-title mb-4">Configurações SMTP</h2>
        <x-card>
            <form method="POST" action="{{ route('admin.settings.email.update') }}" class="space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Email de Remetente -->
                    <div>
                        <label for="mail_from_address" class="block text-sm font-medium text-cinza mb-2">Email de Remetente *</label>
                        <input type="email" id="mail_from_address" name="mail_from_address" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_from_address') border-vermelho @enderror"
                            value="{{ old('mail_from_address', $settings['mail_from_address']) }}" required>
                        @error('mail_from_address')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Nome do Remetente -->
                    <div>
                        <label for="mail_from_name" class="block text-sm font-medium text-cinza mb-2">Nome do Remetente *</label>
                        <input type="text" id="mail_from_name" name="mail_from_name" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_from_name') border-vermelho @enderror"
                            value="{{ old('mail_from_name', $settings['mail_from_name']) }}" required>
                        @error('mail_from_name')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Servidor SMTP -->
                    <div>
                        <label for="mail_smtp_host" class="block text-sm font-medium text-cinza mb-2">Servidor SMTP *</label>
                        <input type="text" id="mail_smtp_host" name="mail_smtp_host" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_smtp_host') border-vermelho @enderror"
                            value="{{ old('mail_smtp_host', $settings['mail_smtp_host']) }}" required>
                        @error('mail_smtp_host')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Porta SMTP -->
                    <div>
                        <label for="mail_smtp_port" class="block text-sm font-medium text-cinza mb-2">Porta SMTP *</label>
                        <input type="number" id="mail_smtp_port" name="mail_smtp_port" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_smtp_port') border-vermelho @enderror"
                            value="{{ old('mail_smtp_port', $settings['mail_smtp_port']) }}" required>
                        @error('mail_smtp_port')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Usuário SMTP -->
                    <div>
                        <label for="mail_smtp_username" class="block text-sm font-medium text-cinza mb-2">Usuário SMTP</label>
                        <input type="text" id="mail_smtp_username" name="mail_smtp_username" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_smtp_username') border-vermelho @enderror"
                            value="{{ old('mail_smtp_username', $settings['mail_smtp_username']) }}">
                        @error('mail_smtp_username')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Senha SMTP -->
                    <div>
                        <label for="mail_smtp_password" class="block text-sm font-medium text-cinza mb-2">Senha SMTP</label>
                        <input type="password" id="mail_smtp_password" name="mail_smtp_password" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_smtp_password') border-vermelho @enderror"
                            value="{{ old('mail_smtp_password', $settings['mail_smtp_password']) }}">
                        @error('mail_smtp_password')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Criptografia -->
                    <div>
                        <label for="mail_smtp_encryption" class="block text-sm font-medium text-cinza mb-2">Criptografia</label>
                        <select id="mail_smtp_encryption" name="mail_smtp_encryption" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_smtp_encryption') border-vermelho @enderror">
                            <option value="">Nenhuma</option>
                            <option value="tls" {{ old('mail_smtp_encryption', $settings['mail_smtp_encryption']) == 'tls' ? 'selected' : '' }}>TLS</option>
                            <option value="ssl" {{ old('mail_smtp_encryption', $settings['mail_smtp_encryption']) == 'ssl' ? 'selected' : '' }}>SSL</option>
                        </select>
                        @error('mail_smtp_encryption')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>

                    <!-- Timeout -->
                    <div>
                        <label for="mail_smtp_timeout" class="block text-sm font-medium text-cinza mb-2">Timeout (segundos)</label>
                        <input type="number" id="mail_smtp_timeout" name="mail_smtp_timeout" 
                            class="w-full px-3 py-2 border border-cinza-claro-2 rounded-md focus:outline-none focus:ring-2 focus:ring-roxo focus:border-transparent @error('mail_smtp_timeout') border-vermelho @enderror"
                            value="{{ old('mail_smtp_timeout', $settings['mail_smtp_timeout'] ?? 30) }}" min="1" max="300">
                        @error('mail_smtp_timeout')
                            <div class="text-vermelho text-sm mt-1">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <!-- Botões -->
                <div class="flex justify-end gap-3 pt-6 border-t border-cinza-claro-2">
                    <x-button variant="outline" type="button" onclick="testEmail()">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        Testar Email
                    </x-button>
                    <x-button variant="primary" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        Salvar Configurações
                    </x-button>
                </div>
            </form>
        </x-card>
    </div>

    <!-- Sidebar com informações -->
    <div class="space-y-6">
        <x-card title="Informações">
            <div class="space-y-4">
                <div>
                    <h4 class="font-medium text-cinza mb-2">Configurações SMTP</h4>
                    <p class="text-sm text-cinza-claro">
                        Configure o servidor SMTP para envio de emails do sistema. 
                        As configurações são necessárias para notificações e comunicações.
                    </p>
                </div>
                
                <div>
                    <h4 class="font-medium text-cinza mb-2">Teste de Email</h4>
                    <p class="text-sm text-cinza-claro">
                        Use o botão "Testar Email" para verificar se as configurações 
                        estão funcionando corretamente.
                    </p>
                </div>

                <div>
                    <h4 class="font-medium text-cinza mb-2">Segurança</h4>
                    <p class="text-sm text-cinza-claro">
                        Recomendamos usar TLS ou SSL para criptografar a conexão 
                        com o servidor SMTP.
                    </p>
                </div>
            </div>
        </x-card>

        <x-card title="Status Atual">
            <div class="space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-cinza">Servidor SMTP:</span>
                    <x-badge variant="{{ $settings['mail_smtp_host'] ? 'success' : 'danger' }}">
                        {{ $settings['mail_smtp_host'] ? 'Configurado' : 'Não configurado' }}
                    </x-badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-cinza">Criptografia:</span>
                    <x-badge variant="{{ $settings['mail_smtp_encryption'] ? 'success' : 'warning' }}">
                        {{ $settings['mail_smtp_encryption'] ? strtoupper($settings['mail_smtp_encryption']) : 'Nenhuma' }}
                    </x-badge>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-cinza">Porta:</span>
                    <span class="text-sm text-cinza-claro">{{ $settings['mail_smtp_port'] ?? 'Não definida' }}</span>
                </div>
            </div>
        </x-card>
    </div>
</div>

<script>
function testEmail() {
    // Implementar teste de email
    alert('Funcionalidade de teste de email será implementada em breve.');
}
</script>
@endsection