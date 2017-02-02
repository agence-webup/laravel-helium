@extends('helium::layouts.master')

@section('content')
<article class="box">
    <header class="box__header">Ajouter un article</header>

    <div class="box__content">
        <form action="{{ route('admin.post.store') }}" method="post">
            {{ csrf_field() }}

            @include('helium::post.form')
        </form>
    </div>
</article>
@endsection
