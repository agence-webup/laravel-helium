@extends('helium::layouts.master')

@section('content')
<article class="box">
    <header class="box__header">Édition d'un article</header>

    <div class="box__content">
        <form action="{{ route('admin.post.update', ['id' => $post->id]) }}" method="post" enctype="multipart/form-data">
            {{ method_field('PUT') }}
            {{ csrf_field() }}

            @include('helium::post.form')
        </form>
    </div>
</article>
@endsection

@section('js')
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_editor.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_style.min.css') }}" />

<script src="{{ asset('/node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/froala_editor.min.js') }}"></script>
<script type="text/javascript">
$('[name=content]').froalaEditor();
</script>
@endsection
