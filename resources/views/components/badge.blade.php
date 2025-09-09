@props([
    'variant' => 'default', // default, primary, success, warning, danger, info
    'size' => 'default', // sm, default, lg
    'pill' => false,
    'class' => ''
])

@php
$variantClasses = [
    'default' => 'badge-default',
    'primary' => 'badge-primary',
    'success' => 'badge-success',
    'warning' => 'badge-warning',
    'danger' => 'badge-danger',
    'info' => 'badge-info'
][$variant] ?? 'badge-default';

$sizeClasses = [
    'sm' => 'badge-sm',
    'default' => 'badge-default',
    'lg' => 'badge-lg'
][$size] ?? 'badge-default';

$pillClasses = $pill ? 'badge-pill' : '';
@endphp

<span class="badge {{ $variantClasses }} {{ $sizeClasses }} {{ $pillClasses }} {{ $class }}">
    {{ $slot }}
</span>
