@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'variant' => 'default', // default, elevated, flat
    'size' => 'default', // sm, default, lg
    'class' => ''
])


@php
$cardClasses = [
    'default' => 'card',
    'elevated' => 'card-elevated', 
    'flat' => 'card-flat'
][$variant] ?? 'card';

$sizeClasses = [
    'sm' => 'card-sm',
    'default' => 'card-default',
    'lg' => 'card-lg'
][$size] ?? 'card-default';
@endphp

<div class="{{ $cardClasses }} {{ $sizeClasses }} {{ $class }}">
    @if($title || $subtitle)
        <div class="card-header">
            <div class="card-title-section">
                @if($title)
                    <h3 class="card-title">{{ $title }}</h3>
                @endif
                @if($subtitle)
                    <p class="card-subtitle">{{ $subtitle }}</p>
                @endif
            </div>
        </div>
    @endif
    
    <div class="card-body">
        {{ $slot }}
    </div>
</div>
