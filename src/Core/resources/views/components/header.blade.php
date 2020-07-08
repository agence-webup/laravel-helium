<header class="title-wrapper">
  <h1 class="title">Dashboard</h1>
  <div>
    @if($saveAction)
    <a href="{{ $saveAction->url }}" class="btn btn--primary" data-helium-save>{{ $saveAction->label }}</a>
    @endif

    @if($addAction)
    <a href="{{ $addAction->url }}" class="btn btn--primary" data-helium-add>{{ $addAction->label }}</a>
    @endif

    @include('helium::components.actions-dropdown',["actions" => $contextualActions])
  </div>
</header>
