@props(['status', 'class' => ''])

@php
    $colorClass = \App\Helpers\SystemHelper::getTicketStatusColorClass($status);
    $displayText = \App\Helpers\SystemHelper::getTicketStatusText($status);
@endphp

<span class="badge bg-{{ $colorClass }} text-cinza {{ $class }}">
    {{ $displayText }}
</span>
