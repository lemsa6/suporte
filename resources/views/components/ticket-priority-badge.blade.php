@props(['priority', 'class' => ''])

@php
    $colorClass = \App\Helpers\SystemHelper::getTicketPriorityColorClass($priority);
    $displayText = \App\Helpers\SystemHelper::getTicketPriorityText($priority);
@endphp

<span class="badge bg-{{ $colorClass }} text-cinza {{ $class }}">
    {{ $displayText }}
</span>
