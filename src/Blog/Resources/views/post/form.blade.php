<?php
use Webup\LaravelHelium\Blog\Values\State;

?>

<article class="box">
    <header class="box__header">{{ $title }}</header>

    <div class="box__content">

        {!! Form::create('text', 'title')
        ->label("Titre de l'article")
        ->value($post->title)
        ->attr(['class' => 'f-50'])!!}

        <div class="f-group {{ $errors->has('thumbnail') ? 'f-error' : '' }}">
            <label for="image">Image de prévisualisation</label>

            <div data-js="upload-thumbnail" data-label="Choisir un fichier" data-url="{{ route('admin.post.image', ['id' => $post ? $post->id : null, 'name' => 'thumbnail']).'?_token='.csrf_token() }}" class="iu-container"></div>
            <input type="hidden" name="thumbnail" value="{{ old('thumbnail', $post ? $post->thumbnail : null) }}">

            @if($errors->has('thumbnail'))
            <ul class="f-error-message">
                @foreach ($errors->get('thumbnail') as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            @endif
        </div>

        <div class="f-group {{ $errors->has('image') ? 'f-error' : '' }}">
            <label for="image">Image d'entête</label>

            <div data-js="upload-image" data-label="Choisir un fichier" data-url="{{ route('admin.post.image', ['id' => $post ? $post->id : null, 'name' => 'image']).'?_token='.csrf_token() }}" class="iu-container"></div>
            <input type="hidden" name="image" value="{{ old('image', $post ? $post->image : null) }}">

            @if($errors->has('image'))
            <ul class="f-error-message">
                @foreach ($errors->get('image') as $error)
                <li>{{$error}}</li>
                @endforeach
            </ul>
            @endif
        </div>

        {!! Form::create('textarea', 'content')
        ->label('Contenu')
        ->value($post->content) !!}

        {!! Form::create('radio', 'state')
        ->addRadio(State::DRAFT, 'Enregistrer en tant que brouillon', 'draft')
        ->addRadio(State::SCHEDULED, 'Plannifier la publication', 'scheduled')
        ->addRadio(State::PUBLISHED, 'Mettre en ligne immédiatemment', 'published')
        ->wrapperClass('custom-class')
        ->value($post->state ? $post->state->value() : null) !!}

        {!! Form::create('date', 'published_at')
        ->label('Date de publication')
        ->value($post->published_at) !!}

        <button type="submit" class="btn btn--primary">Enregistrer l'article</button>

    </div>
</article>

<div class="grid-2">
    <article class="box">
        <header class="box__header">Référencement</header>
        <div class="box__content">
            {!! Form::create('text', 'slug')
            ->label('Permalien')
            ->value($post->slug)
            ->attr(['class' => 'f-100'])!!}
            <button type="button" data-js="generate-slug">Générer</button>

            {!! Form::create('text', 'seo_title')
            ->label('Titre SEO')
            ->value($post->seo_title)
            ->attr(['class' => 'f-100']) !!}

            {!! Form::create('textarea', 'seo_description')
            ->label('Description SEO')
            ->value($post->seo_description)
            ->attr(['class' => 'f-100', 'rows' => '3']) !!}
            <style>
                .serp {
                    font-family: arial,sans-serif;
                    border-left: 3px solid #f5f6fa;
                    padding-left: 1rem;
                }
                .serp__title {
                    font-size: 18px;
                    color: #1a0dab;
                }

                .serp__url {
                    color: #006621;
                    font-size: 16px;
                    line-height: 1;
                }

                .serp__description {
                    color: #545454;
                    line-height: 1.4;
                    font-size: small;
                }
            </style>
            <div class="serp">
                <div class="serp__title">Ici le titre de l'article</div>
                <div class="serp__url">http://example.com</div>
                <div class="serp_description">
                    Nullam quis risus eget urna mollis ornare vel eu leo. Morbi leo risus, porta ac consectetur ac, vestibulum at eros.
                </div>
            </div>

        </div>
    </article>

    <article class="box">
        <header class="box__header">Réseaux sociaux</header>
        <div class="box__content">
            ici openGraph et twitter card
        </div>
    </article>
</div>
