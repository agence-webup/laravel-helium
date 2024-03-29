<div class="title-wrapper">
    <div class="container">
        <h1 class="title">{{ $title }}</h1>
        <div class="title-nav">
            @if ($saveAction)
                <button class="btn btn--secondary title-nav__item" data-helium-save="{{ $saveAction->formId }}">
                    <i data-feather="upload-cloud"></i>
                    {{ $saveAction->label }}
                </button>
            @endif

            @if ($addAction)
                <a href="{{ $addAction->url }}" class="btn btn--primary title-nav__item" data-helium-add>
                    <i data-feather="plus"></i>
                    {{ $addAction->label }}
                </a>
            @endif

            @if ($customAction)
                <button {!! $customAction->attrs !!}>
                    {!! $customAction->icon ? '<i data-feather="' . $customAction->icon . '"></i>' : null !!}
                    {{ $customAction->label }}
                </button>
            @endif

            @foreach ($customElems as $custom)
                {{-- attrs --}}
                {{-- icon --}}
                {{-- label --}}
                {{-- modifier --}}
                @if ($custom->isLink)
                @else
                @endif
            @endforeach

            @if (count($contextualActions) > 0)
                @include('helium::components.actions-dropdown', ['actions' => $contextualActions])
            @endif
        </div>
    </div>
</div>
