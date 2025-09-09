@props([
    'id' => 'chart-' . uniqid(),
    'type' => 'line',
    'data' => [],
    'options' => [],
    'width' => null,
    'height' => null
])

<div class="chart-container {{ $width ? 'w-[' . $width . 'px]' : '' }} {{ $height ? 'h-[' . $height . 'px]' : '' }}">
    <canvas id="{{ $id }}"></canvas>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const ctx = document.getElementById('{{ $id }}').getContext('2d');
    
    new Chart(ctx, {
        type: '{{ $type }}',
        data: @json($data),
        options: @json($options)
    });
});
</script>
@endpush
