@props([
    'event-type' => 'viewed',
    'text' => null,
    'class' => ''
])

@php
    $colorClass = \App\Helpers\SystemHelper::getAuditEventColorClass($eventType);
    $displayText = $text ?? ucfirst($eventType);
@endphp

<span class="badge bg-{{ $colorClass }} text-cinza {{ $class }}">
    {{ $displayText }}
</span>
