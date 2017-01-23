@extends('helium::layouts.master')

@section('content')
@include('helium::elements.flash')

<article class="box">
    <header class="box__header">Paramètres</header>
    <div class="box__content">
        <form action="{{ route('admin.setting.update') }}" method="post">
            {{ csrf_field() }}

            {!! Form::create('text', 'contact_email')
                ->label('Adresse e-mail de contact')
                ->value($settings->get('contact_email')) !!}

            <button type="submit" class="btn btn--primary">Enregistrer</button>
        </form>
    </div>
</article>
@endsection
