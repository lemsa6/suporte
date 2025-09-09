@props([
    'label' => null,
    'error' => null,
    'help' => null,
    'required' => false,
    'rows' => 4,
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
    
    <textarea 
        {{ $attributes->merge([
            'rows' => $rows,
            'class' => "form-textarea {$sizeClasses} {$errorClasses}"
        ]) }}
    >{{ $slot }}</textarea>
    
    @if($help && !$error)
        <p class="form-help">{{ $help }}</p>
    @endif
    
    @if($error)
        <p class="form-error">{{ $error }}</p>
    @endif
</div>
