@props([
    'href' => '#',
    'active' => false,
    'icon' => null,
    'badge' => null,
    'class' => ''
])

@php
$activeClasses = $active ? 'nav-link-active' : '';
@endphp

<a href="{{ $href }}" class="nav-link {{ $activeClasses }} {{ $class }}">
    @if($icon)
        <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            {!! $icon !!}
        </svg>
    @endif
    
    <span>{{ $slot }}</span>
    
    @if($badge)
        <x-badge variant="primary" size="sm" class="ml-auto">{{ $badge }}</x-badge>
    @endif
</a>
