<div class="container">
  <div class="navigation">
    @foreach ($menu as $menuItem)
    @if($menuItem->isDropdown)
    <span class="{{ current_class($menuItem->currentRoute,"is-active") }}">
      <i data-feather="{{ $menuItem->icon }}"></i> {{ $menuItem->label }}
      <span class="navigation__sub">
        @foreach($menuItem->urls as $submenuLabel => $submenuLink)
        <a href="{{$submenuLink}}">{{$submenuLabel}}</a>
        @endforeach
      </span>
    </span>
    @else
    <a href="{{ $menuItem->url }}" class="{{ current_class($menuItem->currentRoute,"is-active") }}"><i
        data-feather="{{$menuItem->icon}}"></i> {{ $menuItem->label }}</a>
    @endif
    @endforeach
  </div>
</div>
