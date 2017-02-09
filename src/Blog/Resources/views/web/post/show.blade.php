<?php
SEO::setTitle($post->seo_title);
SEO::setDescription($post->seo_description);
?>

@extends('layouts.master')
@section('content')
<section class="blogPost">
    <div class="pageTitle">Actualités</div>
    <article class="container">
        <img class="blogPost__visual" alt="" src="{{ $post->imageUrl }}" />
            <header class="blogPost__head blogPost__container">
                <h1 class="blogPost__title">{{ $post->title }}</h1>
                <div class="blogPost__date">&#128336; {{ $post->published_at->format('d/m/Y') }}</div>
            </header>
        <div class="blogPost__container blogPost__content">
            {{ $post->content }}
        </div>
    </article>
</section>
@endsection
