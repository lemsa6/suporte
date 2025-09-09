@props([
    'color' => '#6B7280',
    'size' => 'w-4 h-4',
    'class' => ''
])

<div class="{{ $size }} rounded-full {{ $class }}" style="background-color: {{ $color }};"></div>
