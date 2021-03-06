<?php
SEO::setTitle('Actualités');
SEO::setDescription(null);

SEO::metatags()
    ->setPrev($posts->previousPageUrl())
    ->setNext($posts->nextPageUrl());
?>

@extends('layouts.master')
@section('content')
<div class="container">
    <section class="blog">
        <h1 class="pageTitle">Actualités</h1>
        <div class="blog-grid-index">
            @foreach($posts as $post)
                <article class="blog-post">
                    <img class="blog-post__visual" alt="" src="{{ $post->thumbnailUrl }}" />
                    <h2 class="blog-post__title">{{ $post->title }}</h2>
                    <div class="blog-post__content">
                        {{ str_limit(strip_tags($post->content), 200) }}
                    </div>
                    <a class="btn btn--secondary blog-post__btn" href="{{route('blog.show', ['slug' => $post->slug])}}">Lire la suite</a>
                </article>
            @endforeach
        </div>
        <div class="txtcenter mt3">
            {{ $posts->links() }}
        </div>
    </section>
</div>
@endsection
