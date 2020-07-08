@foreach ($actions as $action)
<a href="{{ $action->url }}" class="btn btn--{{$action->style}} {{$action->cssClass}}" @foreach ($action->others as $key
  => $value)
  {{$key}}="{{ $value }}"
  @endforeach
  >{{ $action->label }}</a>
@endforeach
