<header class="title-wrapper">
  <h1 class="title">{{ $title }}</h1>
  <div>
    @if($saveAction)
    <button class="btn btn--primary" data-helium-save="{{ $saveAction->formId }}">{{ $saveAction->label }}</button>
    @endif

    @if($addAction)
    <a href="{{ $addAction->url }}" class="btn btn--primary" data-helium-add>{{ $addAction->label }}</a>
    @endif

    @include('helium::components.actions-dropdown',["actions" => $contextualActions])
  </div>
</header>
