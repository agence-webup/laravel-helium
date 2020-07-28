<div class="dropmic ActionsBtnContainer" data-dropmic="title-nav-dropdown" data-dropmic-direction="bottom-left"
  role="navigation">
  <button class="box__headerActionsBtn" title="Actions" aria-label="Actions" data-dropmic-btn>
    Actions
  </button>
  <div class="dropmic-menu dropmic-menu--noradius" aria-hidden="true">
    <ul class="dropmic-menu__list" role="menu">
      @foreach ($actions as $action)
      <li class="dropmic-menu__listItem" role="menuitem">
        <button href="{{ $action->url }}"
          class="dropmic-menu__listContent dropmic-menu__listContent--{{$action->style}} {{$action->cssClass}}"
          @foreach($action->others as $key => $value)
          {{$key}}="{{ $value }}"
          @endforeach
          >{{ $action->label }}</button>
      </li>
      @endforeach
    </ul>
  </div>
</div>
