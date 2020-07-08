@foreach ($shortcuts as $shortcutContainer)
@foreach ($shortcutContainer as $shortcut)
<a href="{{ $shortcut->link }}">{{ $shortcut->label }}</a>
@endforeach
@endforeach
