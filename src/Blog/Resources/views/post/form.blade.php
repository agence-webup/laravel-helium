<?php
use Webup\LaravelHelium\Blog\Values\State;

?>

<article class="box">
    <header class="box__header">Ajouter un article</header>

    <div class="box__content">

        {!! Form::create('text', 'title')
        ->label("Titre de l'article")
        ->value($post->title)
        ->attr(['class' => 'f-50'])!!}

        <div class="f-group {{ $errors->has('image') ? 'f-error' : '' }}">
            <label for="image">Image de prévisualisation</label>

            @if ($post->image)
            <div class="thumbnail thumbnail--small">
                <img src="{{ $post->imageUrl /* 300x300 */}}" alt="">
            </div>
            @endif

            <div>
                <input type="file" id="image" name="image">
            </div>

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
