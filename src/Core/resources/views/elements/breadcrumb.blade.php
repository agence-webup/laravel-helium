<div class="navigation-breadcrumb">
  <div class="container">
    <div class="breadcrumb">
      <ol>
        @foreach ($items as $item)
        <li><a href="{{ $item->link }}">{{ $item->label }}</a></li>
        @endforeach
      </ol>
    </div>
  </div>
</div>
