<header class="box__header">
  <h2>{{$title}}</h2>
  <div>
    @include('helium::components.actions-dropdown',["actions" => $actions])
  </div>
</header>
