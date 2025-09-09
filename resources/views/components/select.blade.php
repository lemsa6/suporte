@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'size' => 'default', // sm, default, lg
    'class' => '',
    'options' => []
])

@php
$sizeClasses = [
    'sm' => 'input-sm',
    'default' => 'input-default',
    'lg' => 'input-lg'
][$size] ?? 'input-default';

$errorClasses = $error ? 'input-error' : '';
@endphp

<div class="form-group {{ $class }}">
    @if($label)
        <label class="form-label">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif
    
    <select 
        {{ $attributes->merge([
            'class' => "form-select {$sizeClasses} {$errorClasses}"
        ]) }}
    >
        @if(!$required)
            <option value="">Selecione uma opção</option>
        @endif
        
        @foreach($options as $value => $label)
            <option value="{{ $value }}">{{ $label }}</option>
        @endforeach
        
        {{ $slot }}
    </select>
    
    @if($help && !$error)
        <p class="form-help">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
