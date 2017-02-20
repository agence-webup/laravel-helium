<?php
SEO::setTitle('Mentions Légales');
SEO::setDescription('Mentions Légales');
?>
@extends('layouts.master')

@section('content')
<h1 class="pageTitle">Mentions légales</h1>
<section class="container textPage">
    {!! $legals !!}
</section>
@endsection
