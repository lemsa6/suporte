@props([
    'striped' => false,
    'hover' => true,
    'bordered' => false,
    'responsive' => true,
    'class' => ''
])

@php
$tableClasses = 'table';
$tableClasses .= $striped ? ' table-striped' : '';
$tableClasses .= $hover ? ' table-hover' : '';
$tableClasses .= $bordered ? ' table-bordered' : '';
$tableClasses .= " {$class}";
@endphp

@if($responsive)
    <div class="table-responsive">
@endif
        <table {{ $attributes->merge(['class' => $tableClasses]) }}>
            {{ $slot }}
        </table>
@if($responsive)
    </div>
@endif
