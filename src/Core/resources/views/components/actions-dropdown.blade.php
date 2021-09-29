<div class="dropmic title-nav__item" data-dropmic="title-nav-dropdown" data-dropmic-direction="bottom-left"
  role="navigation">
  <button class="btn btn--secondary btn--bullet" title="Menu" aria-label="Menu" data-dropmic-btn>
    <i data-feather="more-horizontal"></i>
  </button>
  <div class="dropmic-menu" aria-hidden="true">
    <ul class="dropmic-menu__list" role="menu">
      @foreach ($actions as $action)
      <li class="dropmic-menu__listItem" role="menuitem">
        <a href="{{ $action->url }}"
          class="dropmic-menu__listContent dropmic-menu__listContent--{{$action->style}} {{$action->cssClass}}"
          @foreach($action->others as $key => $value)
          {{$key}}="{{ $value }}"
          @endforeach
          >{{ $action->label }}</a>
      </li>
      @endforeach



    </ul>
  </div>
</div>
