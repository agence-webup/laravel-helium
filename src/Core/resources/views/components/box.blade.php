<article class="box {{ $class }}">
    @if (isset($header))
        {{ $header }}
    @endif

    <div class="box__content @if (!$padding) box__content--noPadding @endif">
        {{ $slot }}
    </div>

    @if (isset($footer))
        <footer class="box__footer txtcenter">{{ $footer }}</footer>
    @endif
</article>
