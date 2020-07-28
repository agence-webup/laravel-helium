<header class="box__header box__header--action">
  <h2>{{$title}}</h2>
  @if(count($actions) > 0)
  <div>
    @include('helium::components.box-header-actions-dropdown',["actions" => $actions])
  </div>
  @endif
</header>
