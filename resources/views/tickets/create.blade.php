@extends('layouts.app')

@section('title', 'Novo Ticket')

@section('header')
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
    <div class="mb-4 sm:mb-0">
        <span class="text-sm text-cinza-claro">Tickets</span>
        <h1 class="page-title mt-1">Novo Ticket</h1>
        <p class="text-cinza mt-2">Crie um novo ticket de suporte</p>
    </div>
    <x-button 
        variant="outline" 
        tag="a"
        href="{{ route('tickets.index') }}"
        data-no-loading
    >
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
        </svg>
        Voltar aos Tickets
    </x-button>
</div>
@endsection

@section('content')
<div class="space-y-6">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <div class="lg:col-span-2">
            <!-- Formulário Principal -->
            <h2 class="section-title mb-4">Informações do Ticket</h2>
            <x-card>
                <form method="POST" action="{{ route('tickets.store') }}" enctype="multipart/form-data" class="space-y-6">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Título -->
                        <div class="md:col-span-2">
                            <x-input 
                                label="Título do Ticket"
                                name="title"
                                value="{{ old('title') }}"
                                placeholder="Descreva brevemente o problema"
                                required
                                error="{{ $errors->first('title') }}"
                            />
                        </div>

                        <!-- Descrição -->
                        <div class="md:col-span-2">
                            <x-textarea 
                                label="Descrição Detalhada"
                                name="description"
                                rows="6"
                                placeholder="Descreva detalhadamente o problema, incluindo passos para reproduzir, se aplicável"
                                required
                                error="{{ $errors->first('description') }}"
                                help="Seja o mais específico possível para ajudar nossa equipe a resolver o problema rapidamente."
                            >
                                {{ old('description') }}
                            </x-textarea>
                        </div>

                        <!-- Categoria -->
                        <div>
                            <x-select 
                                label="Categoria"
                                name="category_id"
                                :options="collect($categories)->mapWithKeys(fn($cat) => [$cat->id => $cat->name])->prepend('Selecione uma categoria', '')"
                                :selected="old('category_id')"
                                required
                                error="{{ $errors->first('category_id') }}"
                            />
                        </div>

                        <!-- Prioridade -->
                        <div>
                            <x-select 
                                label="Prioridade"
                                name="priority"
                                :options="[
                                    '' => 'Selecione a prioridade',
                                    'baixa' => 'Baixa',
                                    'média' => 'Média',
                                    'alta' => 'Alta'
                                ]"
                                :selected="old('priority')"
                                required
                                error="{{ $errors->first('priority') }}"
                            />
                        </div>

                        <!-- Cliente -->
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                        <div>
                            <x-select 
                                label="Cliente"
                                name="client_id"
                                :options="collect($clients)->mapWithKeys(fn($client) => [$client->id => $client->name . ($client->company_name ? ' - ' . $client->company_name : '')])->prepend('Selecione um cliente', '')"
                                :selected="old('client_id')"
                                error="{{ $errors->first('client_id') }}"
                            />
                        </div>
                        @endif

                        <!-- Técnico Responsável -->
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'tecnico')
                        <div>
                            <x-select 
                                label="Técnico Responsável"
                                name="assigned_to"
                                :options="collect($technicians)->mapWithKeys(fn($tech) => [$tech->id => $tech->name])->prepend('Não atribuído', '')"
                                :selected="old('assigned_to')"
                                error="{{ $errors->first('assigned_to') }}"
                            />
                        </div>
                        @endif

                        <!-- Anexos -->
                        <div class="md:col-span-2">
                            <x-input 
                                label="Anexos"
                                name="attachments[]"
                                type="file"
                                multiple
                                accept=".pdf,.doc,.docx,.txt,.jpg,.jpeg,.png,.gif"
                                error="{{ $errors->first('attachments') }}"
                                help="Formatos aceitos: PDF, DOC, DOCX, TXT, JPG, JPEG, PNG, GIF. Máximo 5 arquivos, 10MB cada."
                            />
                        </div>

                        <!-- Tags -->
                        <div class="md:col-span-2">
                            <x-input 
                                label="Tags"
                                name="tags"
                                value="{{ old('tags') }}"
                                placeholder="Digite as tags separadas por vírgula"
                                error="{{ $errors->first('tags') }}"
                                help="Use tags para facilitar a busca e categorização do ticket."
                            />
                        </div>
                    </div>

                    <div class="flex justify-end gap-3">
                        <x-button variant="outline" tag="a" href="{{ route('tickets.index') }}">
                            Cancelar
                        </x-button>
                        <x-button type="submit" variant="primary">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            Criar Ticket
                        </x-button>
                    </div>
                </form>
            </x-card>
        </div>

        <div class="lg:col-span-1">
            <!-- Sidebar com Informações -->
            <div class="space-y-4">
                <!-- Dicas para um Bom Ticket -->
                <x-card>
                    <div class="bg-roxo-det bg-opacity-10 border border-roxo-det rounded-lg p-4 mb-4">
                        <h6 class="font-semibold text-roxo flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Dicas para um Bom Ticket
                        </h6>
                    </div>
                    <ul class="space-y-2">
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Seja específico no título</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Descreva o problema detalhadamente</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Inclua passos para reproduzir</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Anexe screenshots se relevante</span>
                        </li>
                        <li class="flex items-start">
                            <svg class="w-4 h-4 mr-2 mt-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <span class="text-sm">Escolha a categoria correta</span>
                        </li>
                    </ul>
                </x-card>

                <!-- Informações do Sistema -->
                <h3 class="text-lg font-semibold text-cinza mb-4">Informações do Sistema</h3>
                <x-card>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Ticket #</span>
                            <span class="font-medium text-sm">{{ $nextTicketNumber }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Criado por</span>
                            <span class="font-medium text-sm">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Data</span>
                            <span class="font-medium text-sm">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Status</span>
                            <x-badge variant="primary">Aberto</x-badge>
                        </div>
                    </div>
                </x-card>

                <!-- SLA Estimado -->
                <x-card>
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-4">
                        <h6 class="font-semibold text-yellow-800 flex items-center">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            SLA Estimado
                        </h6>
                    </div>
                    <div class="space-y-2">
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Baixa</span>
                            <span class="font-medium text-sm">72h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Média</span>
                            <span class="font-medium text-sm">48h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Alta</span>
                            <span class="font-medium text-sm">24h</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-cinza-claro text-sm">Urgente</span>
                            <span class="font-medium text-sm">4h</span>
                        </div>
                    </div>
                </x-card>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
// Preview de arquivos
document.getElementById('attachments').addEventListener('change', function(e) {
    const files = e.target.files;
    const maxFiles = 5;
    const maxSize = 10 * 1024 * 1024; // 10MB
    
    if (files.length > maxFiles) {
        alert(`Máximo de ${maxFiles} arquivos permitidos.`);
        this.value = '';
        return;
    }
    
    for (let i = 0; i < files.length; i++) {
        if (files[i].size > maxSize) {
            alert(`Arquivo "${files[i].name}" excede o tamanho máximo de 10MB.`);
            this.value = '';
            return;
        }
    }
});

// Validação do formulário
document.querySelector('form').addEventListener('submit', function(e) {
    const title = document.getElementById('title').value.trim();
    const description = document.getElementById('description').value.trim();
    const category = document.getElementById('category_id').value;
    const priority = document.getElementById('priority').value;
    
    if (!title || !description || !category || !priority) {
        e.preventDefault();
        alert('Por favor, preencha todos os campos obrigatórios.');
        return;
    }
    
    if (title.length < 10) {
        e.preventDefault();
        alert('O título deve ter pelo menos 10 caracteres.');
        return;
    }
    
    if (description.length < 20) {
        e.preventDefault();
        alert('A descrição deve ter pelo menos 20 caracteres.');
        return;
    }
});

// Auto-resize da textarea
document.getElementById('description').addEventListener('input', function() {
    this.style.height = 'auto';
    this.style.height = this.scrollHeight + 'px';
});
</script>
@endpush
@endsection
