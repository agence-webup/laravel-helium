@extends('helium::layouts.master')

@section('content')
@include('helium::elements.flash')
<div class="menu">
    @foreach($locales as $locale)
    <a href="#" @if($page->locale == $locale->code)class="is-active"@endif>{{ $locale->name }}</a>
    @endforeach
</div>

<article class="box">
    <header class="box__header">Modifier une page</header>

    <form action="{{ route('admin.page.update', ['page' => $page->id]) }}" method="post">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        <div class="box__content">
            {!! Form::create('text', 'title')
            ->label("Titre")
            ->value($page->title) !!}

            {!! Form::create('textarea', 'content')
            ->label('Contenu')
            ->value($page->content) !!}
        </div>

        <div class="box__footer">
            {!! Form::create('checkbox', 'published')
                ->label('Mettre en ligne')
                ->value($page->published) !!}

            <button type="submit" class="btn btn--primary">Enregistrer</button>
        </div>
    </form>
</article>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/vendor/redactor/redactor.css') }}" />
@endsection

@section('js')
<script src="{{ asset('/node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('/vendor/redactor/redactor.js') }}"></script>
<script src="{{ asset('/vendor/redactor/fr.js') }}"></script>

<script type="text/javascript">
    $('[name=content]').redactor({
        lang: 'fr',
        buttons: ['format', 'bold', 'italic', 'lists', 'image', 'file', 'link'],
        formatting: ['p', 'blockquote', 'h2', 'h3', 'h4', 'h5'],
        // imageUpload: options.mediaUploadUrl,
        // fileUpload: options.mediaUploadUrl
    });
</script>
@endsection
