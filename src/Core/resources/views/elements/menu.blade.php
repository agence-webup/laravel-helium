@php
use Webup\LaravelHelium\Core\Helpers\HeliumHelper;
@endphp

<div class="container">
    <div class="navigation">
        @foreach ($menu as $menuItem)
            @if ($menuItem->isDropdown)
                <span class="{{ HeliumHelper::current_class($menuItem->currentRoute) }}">
                    <i data-feather="{{ $menuItem->icon }}"></i> {{ $menuItem->label }}
                    <span class="navigation__sub">
                        @foreach ($menuItem->urls as $submenuLabel => $submenu)
                            <a href="{{ $submenu->url }}">{{ $submenuLabel }}</a>
                        @endforeach
                    </span>
                    @if ($menuItem->displayChevron)
                        <i data-feather="chevron-down"></i>
                    @endif
                </span>
            @else
                <a href="{{ $menuItem->url }}" class="{{ HeliumHelper::current_class($menuItem->currentRoute) }}">
                    <i data-feather="{{ $menuItem->icon }}"></i> {{ $menuItem->label }}
                </a>
            @endif
        @endforeach
    </div>
</div>
