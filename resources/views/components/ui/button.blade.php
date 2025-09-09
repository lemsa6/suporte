@props(['variant' => 'primary', 'size' => null])
@php
  $classes = 'btn btn-' . $variant;
  if ($size) $classes .= ' btn-' . $size;
@endphp
<a {{ $attributes->merge(['class' => $classes]) }}>
  {{ $slot }}
</a>


