# Sistema de Componentes Blade - Documentação v1.2.6

> **📚 Para documentação completa, consulte o [Compêndio do Sistema](COMPENDIO_SISTEMA_SUPORTE.md)**

## Visão Geral

Este sistema utiliza **Tailwind CSS** como framework CSS principal (sem Bootstrap) e **Componentes Blade** personalizados para criar uma interface consistente e reutilizável.

## 🎨 Framework CSS

### Tailwind CSS
- **Framework:** Tailwind CSS v3+
- **Configuração:** `tailwind.config.js`
- **Arquivo principal:** `resources/css/tailwind.css`
- **Compilação:** Vite (`npm run build`)

### Fonte Principal
- **Fonte:** Lato (local)
- **Arquivo:** `public/fonts/lato/lato.css`
- **Pesos:** 100, 300, 400, 700, 900
- **Aplicação:** Global via `@layer base`

## 🧩 Componentes Disponíveis

### 1. `<x-button>` - Botões
```blade
<x-button variant="primary" size="lg" tag="a" href="/link">
    Texto do Botão
</x-button>
```

**Props:**
- `variant`: `primary`, `secondary`, `outline`, `ghost`, `danger`
- `size`: `sm`, `default`, `lg`
- `tag`: `button`, `a`, `div`
- `loading`: `true/false`
- `disabled`: `true/false`
- `icon`: HTML do ícone
- `iconPosition`: `left`, `right`

### 2. `<x-card>` - Cards/Containers
```blade
<x-card title="Título" subtitle="Subtítulo" variant="elevated">
    Conteúdo do card
</x-card>
```

**Props:**
- `title`: Título do card
- `subtitle`: Subtítulo do card
- `variant`: `default`, `elevated`, `flat`
- `size`: `sm`, `default`, `lg`

### 3. `<x-stat-card>` - Cards de Estatísticas
```blade
<x-stat-card title="Total" :value="100" color="primary">
    <svg>...</svg>
</x-stat-card>
```

**Props:**
- `title`: Título da estatística
- `value`: Valor numérico
- `color`: `primary`, `success`, `warning`, `danger`, `info`
- `trend`: `positive`, `negative`, `neutral`
- `trendValue`: Valor da tendência

### 4. `<x-table>` - Tabelas
```blade
<x-table striped hover bordered>
    <thead>
        <tr>
            <th>Coluna 1</th>
            <th>Coluna 2</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>Dados 1</td>
            <td>Dados 2</td>
        </tr>
    </tbody>
</x-table>
```

**Props:**
- `striped`: `true/false` - Linhas alternadas
- `hover`: `true/false` - Hover nas linhas
- `bordered`: `true/false` - Bordas
- `responsive`: `true/false` - Responsivo

### 5. `<x-input>` - Campos de Entrada
```blade
<x-input 
    label="Nome" 
    type="text" 
    :error="$errors->first('name')" 
    required 
    help="Digite seu nome completo"
/>
```

**Props:**
- `label`: Label do campo
- `type`: Tipo do input (`text`, `email`, `password`, etc.)
- `error`: Mensagem de erro
- `help`: Texto de ajuda
- `required`: `true/false`
- `size`: `sm`, `default`, `lg`

### 6. `<x-select>` - Seletores
```blade
<x-select 
    label="Categoria" 
    :options="['1' => 'Opção 1', '2' => 'Opção 2']"
    :error="$errors->first('category')"
    required
/>
```

**Props:**
- `label`: Label do campo
- `options`: Array de opções
- `error`: Mensagem de erro
- `help`: Texto de ajuda
- `required`: `true/false`
- `size`: `sm`, `default`, `lg`

### 7. `<x-textarea>` - Áreas de Texto
```blade
<x-textarea 
    label="Descrição" 
    :rows="4" 
    :error="$errors->first('description')"
    required
/>
```

**Props:**
- `label`: Label do campo
- `rows`: Número de linhas
- `error`: Mensagem de erro
- `help`: Texto de ajuda
- `required`: `true/false`
- `size`: `sm`, `default`, `lg`

### 8. `<x-alert>` - Alertas/Notificações
```blade
<x-alert type="success" dismissible>
    Operação realizada com sucesso!
</x-alert>
```

**Props:**
- `type`: `success`, `error`, `warning`, `info`
- `dismissible`: `true/false` - Botão de fechar

### 9. `<x-badge>` - Badges/Etiquetas
```blade
<x-badge variant="success" size="lg" pill>
    Ativo
</x-badge>
```

**Props:**
- `variant`: `default`, `primary`, `success`, `warning`, `danger`, `info`
- `size`: `sm`, `default`, `lg`
- `pill`: `true/false` - Formato pill

### 10. `<x-menu-item>` - Itens de Menu
```blade
<x-menu-item 
    href="/dashboard" 
    :active="request()->routeIs('dashboard')"
    icon='<path d="..."></path>'
    badge="5"
>
    Dashboard
</x-menu-item>
```

**Props:**
- `href`: URL do link
- `active`: `true/false` - Estado ativo
- `icon`: HTML do ícone SVG
- `badge`: Texto do badge
- `class`: Classes CSS adicionais

## 🎯 Classes CSS Semânticas

### Títulos
```css
.page-title      /* Título principal da página */
.section-title   /* Título de seção */
.subsection-title /* Título de subseção */
.acorn-title     /* Título estilo Acorn */
.acorn-subtitle  /* Subtítulo estilo Acorn */
```

### Cards
```css
.card            /* Card padrão */
.card-elevated   /* Card com elevação */
.card-flat       /* Card sem sombra */
.stat-card       /* Card de estatística */
```

### Botões
```css
.btn             /* Botão base */
.btn-primary     /* Botão primário */
.btn-secondary   /* Botão secundário */
.btn-outline     /* Botão outline */
.btn-ghost       /* Botão ghost */
.btn-danger      /* Botão de perigo */
```

### Formulários
```css
.form-group      /* Grupo de formulário */
.form-label      /* Label do formulário */
.form-input      /* Input do formulário */
.form-select     /* Select do formulário */
.form-textarea   /* Textarea do formulário */
```

### Alertas
```css
.alert           /* Alerta base */
.alert-success   /* Alerta de sucesso */
.alert-error     /* Alerta de erro */
.alert-warning   /* Alerta de aviso */
.alert-info      /* Alerta de informação */
```

### Tabelas
```css
.table           /* Tabela base */
.table-striped   /* Tabela com linhas alternadas */
.table-hover     /* Tabela com hover */
.table-bordered  /* Tabela com bordas */
```

### Badges
```css
.badge           /* Badge base */
.badge-primary   /* Badge primário */
.badge-success   /* Badge de sucesso */
.badge-warning   /* Badge de aviso */
.badge-danger    /* Badge de perigo */
.badge-info      /* Badge de informação */
```

## 🚀 Como Usar

### 1. Compilação do CSS
```bash
# Desenvolvimento
npm run dev

# Produção
npm run build
```

### 2. Importação nos Layouts
```blade
<!-- No <head> -->
<link rel="stylesheet" href="{{ asset('fonts/lato/lato.css') }}">
@vite(['resources/css/tailwind.css', 'resources/js/app.js'])
```

### 3. Uso dos Componentes
```blade
<!-- Exemplo de página -->
@extends('layouts.app')

@section('content')
    <x-card title="Minha Página">
        <x-button variant="primary" size="lg">
            Ação Principal
        </x-button>
        
        <x-table striped hover>
            <!-- Conteúdo da tabela -->
        </x-table>
    </x-card>
@endsection
```

## 📁 Estrutura de Arquivos

```
resources/
├── views/
│   ├── components/          # Componentes Blade
│   │   ├── alert.blade.php
│   │   ├── badge.blade.php
│   │   ├── button.blade.php
│   │   ├── card.blade.php
│   │   ├── input.blade.php
│   │   ├── menu-item.blade.php
│   │   ├── select.blade.php
│   │   ├── stat-card.blade.php
│   │   ├── table.blade.php
│   │   └── textarea.blade.php
│   └── layouts/
│       └── app.blade.php    # Layout principal
├── css/
│   └── tailwind.css         # CSS principal com componentes
└── js/
    └── app.js              # JavaScript principal

public/
└── fonts/
    └── lato/               # Fontes Lato locais
        ├── lato.css
        └── *.ttf

tailwind.config.js          # Configuração do Tailwind
vite.config.js              # Configuração do Vite
```

## 🔧 Personalização

### Adicionando Novos Componentes
1. Crie o arquivo em `resources/views/components/`
2. Use `@props()` para definir propriedades
3. Adicione classes CSS semânticas em `tailwind.css`
4. Documente o componente

### Modificando Estilos
1. Edite `resources/css/tailwind.css`
2. Use `@apply` para aplicar classes Tailwind
3. Compile com `npm run build`

### Adicionando Novas Variantes
1. Adicione a variante no componente
2. Crie a classe CSS correspondente
3. Atualize a documentação

## 📝 Exemplos Práticos

### Dashboard
```blade
<!-- Métricas -->
<x-stat-card title="Total" :value="100" color="primary">
    <svg>...</svg>
</x-stat-card>

<!-- Tabela -->
<x-table striped hover>
    <thead>
        <tr>
            <th>Nome</th>
            <th>Status</th>
        </tr>
    </thead>
    <tbody>
        <tr>
            <td>João</td>
            <td><x-badge variant="success">Ativo</x-badge></td>
        </tr>
    </tbody>
</x-table>
```

### Formulário
```blade
<x-card title="Novo Usuário">
    <form>
        <x-input label="Nome" :error="$errors->first('name')" required />
        <x-input label="Email" type="email" :error="$errors->first('email')" required />
        <x-select label="Perfil" :options="$profiles" :error="$errors->first('profile')" required />
        <x-textarea label="Observações" :rows="3" />
        
        <div class="flex gap-4">
            <x-button variant="primary" type="submit">Salvar</x-button>
            <x-button variant="outline" type="button">Cancelar</x-button>
        </div>
    </form>
</x-card>
```

### Menu
```blade
<x-menu-item 
    href="/dashboard" 
    :active="request()->routeIs('dashboard')"
    icon='<path d="..."></path>'
>
    Dashboard
</x-menu-item>
```

## 🎨 Cores do Sistema

### Primárias
- `primary-50` a `primary-900`
- Cor principal: `#4d2f6f` (primary-500)

### Neutras
- `gray-50` a `gray-900`
- Base: `#f8fafc` (gray-50)

### Estados
- **Sucesso:** `green-500` (#10b981)
- **Aviso:** `yellow-500` (#f59e0b)
- **Erro:** `red-500` (#ef4444)
- **Info:** `blue-500` (#3b82f6)

## 📱 Responsividade

Todos os componentes são responsivos por padrão:
- **Mobile:** `sm:` (640px+)
- **Tablet:** `md:` (768px+)
- **Desktop:** `lg:` (1024px+)
- **Large:** `xl:` (1280px+)

## ♿ Acessibilidade

- Labels associados aos inputs
- Estados de foco visíveis
- Contraste adequado
- Navegação por teclado
- ARIA labels quando necessário

---

**Última atualização:** {{ date('d/m/Y H:i') }}
**Versão:** 1.0.0
