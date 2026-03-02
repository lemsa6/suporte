@props([])

<tr {{ $attributes->merge(['class' => 'border-b border-padrao']) }}>
    {{ $slot }}
</tr>
