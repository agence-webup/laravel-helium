Bonjour, {{ Auth::guard("admin")->user()->email }} !
<a href="#" data-submit="logout-form">
  <i data-feather="log-out"></i>
</a>
