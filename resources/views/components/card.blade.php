<div class="card shadow mb-4">
  <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
    <h6 class="m-0 font-weight-bold text-primary">{{ $header ?? '' }}</h6>
    {{$headerButton ?? ''}}
  </div>
  <div class="card-body">
      {{ $body }}
  </div>
  <div class="card-footer">
      {{$footer ?? ''}}
  </div>
</div>
