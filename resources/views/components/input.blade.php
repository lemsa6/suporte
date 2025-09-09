@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'type' => 'text',
    'size' => 'default', // sm, default, lg
    'class' => ''
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
    
    <input 
        {{ $attributes->merge([
            'type' => $type,
            'class' => "form-input {$sizeClasses} {$errorClasses}"
        ]) }}
    />
    
    @if($help && !$error)
        <p class="form-help">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
