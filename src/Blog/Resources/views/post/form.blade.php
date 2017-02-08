<?php
use Webup\LaravelHelium\Blog\Values\State;

?>
<fieldset>
    <legend>Général</legend>

    {!! Form::create('text', 'title')
        ->label('Titre')
        ->value($post->title) !!}

    <div class="f-group {{ $errors->has('image') ? 'f-error' : '' }}">
        <label for="image">Image</label>

        @if ($post->image)
        <div class="thumbnail thumbnail--large">
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
</fieldset>

<fieldset>
    <legend>Référencement naturel (SEO)</legend>

    {!! Form::create('text', 'seo_title')
        ->label('Titre SEO')
        ->value($post->seo_title) !!}

    {!! Form::create('text', 'seo_description')
        ->label('Description SEO')
        ->value($post->seo_description) !!}

    {!! Form::create('text', 'slug')
        ->label('Permalien')
        ->value($post->slug) !!}
</fieldset>

<fieldset>
    <legend>Mise en ligne</legend>

    {!! Form::create('radio', 'state')
        ->addRadio(State::DRAFT, 'Enregistrer en tant que brouillon', 'draft')
        ->addRadio(State::PUBLISHED, 'Mettre en ligne immédiatemment', 'published')
        ->addRadio(State::SCHEDULED, 'Plannifier', 'scheduled')
        ->wrapperClass('custom-class')
        ->value($post->state ? $post->state->value() : null) !!}

    {!! Form::create('date', 'published_at')
        ->label('Date de publication')
        ->value($post->published_at) !!}
</fieldset>

<button type="submit" class="btn btn--primary">Enregistrer</button>
