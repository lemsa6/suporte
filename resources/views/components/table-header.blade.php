@props([])

<th {{ $attributes->merge(['class' => 'px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-cinza-claro bg-creme']) }}>
    {{ $slot }}
</th>
