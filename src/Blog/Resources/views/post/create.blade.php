@extends('helium::layouts.master')

@section('content')

        <form action="{{ route('admin.post.store') }}" method="post">
            {{ csrf_field() }}

            @include('helium::post.form')
        </form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_editor.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_style.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/plugins/image.min.css') }}" />
@endsection

@section('js')
<script src="{{ asset('/node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/froala_editor.min.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/languages/fr.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/plugins/image.min.js') }}"></script>

<script src="{{ asset('/assets/admin/js/vendor/post.js') }}"></script>
@endsection
