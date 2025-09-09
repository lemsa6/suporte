@props([
    'variant' => 'primary', // primary, secondary, outline, ghost, danger
    'size' => 'default', // sm, default, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'loading' => false,
    'disabled' => false,
    'class' => ''
])

@php
$baseClasses = 'btn';
$variantClasses = [
    'primary' => 'btn-primary',
    'secondary' => 'btn-secondary', 
    'outline' => 'btn-outline',
    'ghost' => 'btn-ghost',
    'danger' => 'btn-danger'
][$variant] ?? 'btn-primary';

$sizeClasses = [
    'sm' => 'btn-sm',
    'default' => 'btn-default',
    'lg' => 'btn-lg'
][$size] ?? 'btn-default';

$disabledClasses = $disabled || $loading ? 'btn-disabled' : '';
$loadingClasses = $loading ? 'btn-loading' : '';

$allClasses = "{$baseClasses} {$variantClasses} {$sizeClasses} {$disabledClasses} {$loadingClasses} {$class}";
@endphp

<{{ $attributes->get('tag', 'button') }} 
    {{ $attributes->merge(['class' => $allClasses]) }}
    @if($disabled || $loading) disabled @endif
>
    @if($loading)
        <svg class="btn-spinner" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
    @elseif($icon && $iconPosition === 'left')
        <span class="btn-icon-left">{{ $icon }}</span>
    @endif
    
    <span class="btn-text">{{ $slot }}</span>
    
    @if($icon && $iconPosition === 'right')
        <span class="btn-icon-right">{{ $icon }}</span>
    @endif
</{{ $attributes->get('tag', 'button') }}>
