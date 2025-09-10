@props(['category', 'class' => ''])

@if($category)
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $class }}" style="background-color: {{ $category->color }}20; color: {{ $category->color }};">
        {{ $category->name }}
    </span>
@else
    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-cinza-claro-2 text-cinza {{ $class }}">
        N/A
    </span>
@endif
