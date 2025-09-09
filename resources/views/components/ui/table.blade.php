<div class="table-responsive">
  <table {{ $attributes->merge(['class' => 'table table-hover align-middle']) }}>
    @if(isset($head))
      <thead>
        {{ $head }}
      </thead>
    @endif
    <tbody>
      {{ $slot }}
    </tbody>
  </table>
</div>


