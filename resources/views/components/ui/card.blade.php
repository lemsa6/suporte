<div {{ $attributes->merge(['class' => 'card shadow-sm']) }}>
  @if(isset($title) || isset($header))
    <div class="card-header flex justify-between items-center">
      <h5 class="mb-0">{{ $title ?? '' }}</h5>
      {{ $header ?? '' }}
    </div>
  @endif
  <div class="card-body">
    {{ $slot }}
  </div>
</div>


