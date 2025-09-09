@props([
    'title' => '',
    'value' => '',
    'icon' => null,
    'trend' => null, // positive, negative, neutral
    'trendValue' => null,
    'color' => 'primary', // primary, success, warning, danger, info
    'class' => ''
])

@php
$colorClasses = [
    'primary' => 'stat-card-primary',
    'success' => 'stat-card-success',
    'warning' => 'stat-card-warning', 
    'danger' => 'stat-card-danger',
    'info' => 'stat-card-info'
][$color] ?? 'stat-card-primary';

$trendClasses = [
    'positive' => 'stat-trend-positive',
    'negative' => 'stat-trend-negative',
    'neutral' => 'stat-trend-neutral'
][$trend] ?? '';
@endphp

<div class="stat-card {{ $colorClasses }} {{ $class }}">
    <div class="stat-card-content">
        <div class="stat-card-icon">
            @if($slot->isNotEmpty())
                {{ $slot }}
            @else
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
            @endif
        </div>
        
        <div class="stat-card-info">
            <div class="stat-card-value">{{ $value }}</div>
            <div class="stat-card-title">{{ $title }}</div>
            
            @if($trend && $trendValue)
                <div class="stat-card-trend {{ $trendClasses }}">
                    @if($trend === 'positive')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 17l9.2-9.2M17 17V7H7"></path>
                        </svg>
                    @elseif($trend === 'negative')
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 7l-9.2 9.2M7 7v10h10"></path>
                        </svg>
                    @endif
                    <span>{{ $trendValue }}</span>
                </div>
            @endif
        </div>
    </div>
</div>
