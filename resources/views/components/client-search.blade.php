@props([
    'label' => 'Cliente',
    'name' => 'client_id',
    'value' => null,
    'error' => null,
    'required' => false,
    'placeholder' => 'Digite para buscar cliente...',
    'class' => ''
])

@php
$errorClasses = $error ? 'border-red-300 focus:border-red-500 focus:ring-red-500' : 'border-padrao focus:border-roxo focus:ring-roxo';
$uniqueId = 'client_search_' . Str::random(8);
@endphp

<div class="form-group {{ $class }}">
    @if($label)
        <label for="{{ $uniqueId }}" class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <div class="relative">
        <!-- Input de busca -->
        <input 
            type="text" 
            id="{{ $uniqueId }}"
            class="form-input {{ $errorClasses }}"
            placeholder="{{ $placeholder }}"
            autocomplete="off"
            data-client-search
            data-target="{{ $uniqueId }}_hidden"
        />
        
        <!-- Input hidden com o valor real -->
        <input 
            type="hidden" 
            name="{{ $name }}" 
            id="{{ $uniqueId }}_hidden"
            value="{{ $value }}"
        />
        
        <!-- Dropdown de resultados -->
        <div 
            id="{{ $uniqueId }}_dropdown" 
            class="absolute z-50 w-full mt-1 bg-white border border-padrao rounded-md shadow-lg hidden max-h-60 overflow-auto"
        >
            <!-- Resultados serão inseridos aqui via JavaScript -->
        </div>
        
        <!-- Loading indicator -->
        <div id="{{ $uniqueId }}_loading" class="absolute right-3 top-1/2 transform -translate-y-1/2 hidden">
            <svg class="animate-spin h-4 w-4 text-roxo" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        </div>
    </div>
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('{{ $uniqueId }}');
    const hiddenInput = document.getElementById('{{ $uniqueId }}_hidden');
    const dropdown = document.getElementById('{{ $uniqueId }}_dropdown');
    const loading = document.getElementById('{{ $uniqueId }}_loading');
    
    let searchTimeout;
    let selectedIndex = -1;
    
    // Carregar cliente selecionado se houver valor inicial
    @if($value)
        loadInitialClient({{ $value }});
    @endif
    
    // Event listeners
    searchInput.addEventListener('input', handleSearch);
    searchInput.addEventListener('keydown', handleKeydown);
    searchInput.addEventListener('focus', handleFocus);
    
    // Fechar dropdown ao clicar fora
    document.addEventListener('click', function(e) {
        if (!searchInput.contains(e.target) && !dropdown.contains(e.target)) {
            hideDropdown();
        }
    });
    
    function handleSearch(e) {
        const query = e.target.value.trim();
        
        clearTimeout(searchTimeout);
        
        if (query.length < 2) {
            hideDropdown();
            hiddenInput.value = '';
            return;
        }
        
        searchTimeout = setTimeout(() => {
            searchClients(query);
        }, 300);
    }
    
    function handleKeydown(e) {
        const items = dropdown.querySelectorAll('.client-item');
        
        switch(e.key) {
            case 'ArrowDown':
                e.preventDefault();
                selectedIndex = Math.min(selectedIndex + 1, items.length - 1);
                updateSelection(items);
                break;
                
            case 'ArrowUp':
                e.preventDefault();
                selectedIndex = Math.max(selectedIndex - 1, -1);
                updateSelection(items);
                break;
                
            case 'Enter':
                e.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    selectClient(items[selectedIndex]);
                }
                break;
                
            case 'Escape':
                hideDropdown();
                break;
        }
    }
    
    function handleFocus() {
        if (searchInput.value.length >= 2) {
            searchClients(searchInput.value);
        }
    }
    
    function searchClients(query) {
        showLoading();
        
        fetch(`/api/clients?search=${encodeURIComponent(query)}&limit=10`)
            .then(response => response.json())
            .then(clients => {
                hideLoading();
                displayResults(clients);
            })
            .catch(error => {
                hideLoading();
                console.error('Erro ao buscar clientes:', error);
            });
    }
    
    function displayResults(clients) {
        if (clients.length === 0) {
            dropdown.innerHTML = '<div class="p-3 text-sm text-cinza-claro">Nenhum cliente encontrado</div>';
        } else {
            dropdown.innerHTML = clients.map((client, index) => `
                <div class="client-item p-3 hover:bg-creme cursor-pointer border-b border-gray-100 last:border-b-0" 
                     data-id="${client.id}" 
                     data-text="${client.text}">
                    <div class="font-medium text-cinza">${client.text}</div>
                    ${client.cnpj ? `<div class="text-sm text-cinza-claro">CNPJ: ${client.cnpj}</div>` : ''}
                </div>
            `).join('');
            
            // Adicionar event listeners aos itens
            dropdown.querySelectorAll('.client-item').forEach(item => {
                item.addEventListener('click', () => selectClient(item));
            });
        }
        
        selectedIndex = -1;
        showDropdown();
    }
    
    function selectClient(item) {
        const id = item.dataset.id;
        const text = item.dataset.text;
        
        searchInput.value = text;
        hiddenInput.value = id;
        hideDropdown();
    }
    
    function updateSelection(items) {
        items.forEach((item, index) => {
            if (index === selectedIndex) {
                item.classList.add('bg-roxo', 'text-white');
                item.classList.remove('hover:bg-creme');
            } else {
                item.classList.remove('bg-roxo', 'text-white');
                item.classList.add('hover:bg-creme');
            }
        });
    }
    
    function showDropdown() {
        dropdown.classList.remove('hidden');
    }
    
    function hideDropdown() {
        dropdown.classList.add('hidden');
        selectedIndex = -1;
    }
    
    function showLoading() {
        loading.classList.remove('hidden');
    }
    
    function hideLoading() {
        loading.classList.add('hidden');
    }
    
    function loadInitialClient(clientId) {
        fetch(`/api/clients?search=&limit=1000`)
            .then(response => response.json())
            .then(clients => {
                const client = clients.find(c => c.id == clientId);
                if (client) {
                    searchInput.value = client.text;
                }
            })
            .catch(error => {
                console.error('Erro ao carregar cliente inicial:', error);
            });
    }
});
</script>
