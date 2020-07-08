<!doctype html>
<html lang="fr">

<head>
  <meta charset="UTF-8">
  <title>{{ config("helium.title") }}</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="{{ asset('/node_modules/helium-admin/dist/css/helium-vendors.css') }}" media="all">
  <link rel="stylesheet" href="{{ asset('/node_modules/helium-admin/dist/css/helium-base.css') }}" media="all">
  <script src="https://unpkg.com/feather-icons"></script>
  @yield('css')
</head>

<body>
  <header class="header">
    <div class="container">
      <div class="header__logo">
        {{ config("helium.main_title") }}
      </div>
      <div class="header__infos">
        @include('helium::elements.keyboard-shorcuts')
        @include('helium::elements.shorcuts')
        @include('helium::elements.user-infos')
      </div>

    </div>
  </header>
  @include('helium::elements.navigation')

  @include('helium::elements.notif')

  <div class="container">
    <main class="content">
      {!! Helium::header()->generate() !!}

      @yield('content')
    </main>
  </div>


  <form id="logout-form" action="{{ route('admin.logout') }}" method="post" style="display: none;">
    {{ csrf_field() }}
  </form>

  <script src="{{ asset('/node_modules/helium-admin/dist/js/helium-vendors.js') }}"></script>
  <script src="{{ asset('/node_modules/helium-admin/dist/js/helium-base.js') }}"></script>
  <script>
    feather.replace()
  </script>
  <script>
    $('tbody').on('click', '[data-confirm]', function (event) {
            if(event.target.dataset.confirm && !confirm(event.target.dataset.confirm)){
                event.preventDefault();
                event.stopPropagation()
            }
        });
  </script>
  @yield('js')
</body>

</html>
