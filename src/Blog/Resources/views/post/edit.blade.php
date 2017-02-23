@extends('helium::layouts.master')

@section('content')
    <form action="{{ route('admin.post.update', ['id' => $post->id]) }}" method="post" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}

        @include('helium::post.form', ['title' => "Édition d'un article", 'post' => $post])
    </form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_editor.pkgd.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_style.min.css') }}" />

<link rel="stylesheet" href="{{ asset('/node_modules/tingle.js/dist/tingle.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/cropperjs/dist/cropper.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/image-crop-upload/dist/image-uploader.css') }}" />
@endsection


@section('js')
<script src="{{ asset('/node_modules/froala-editor/js/froala_editor.pkgd.min.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/languages/fr.js') }}"></script>

<script src="{{ asset('/node_modules/slug/slug-browser.js') }}"></script>
<script src="{{ asset('/node_modules/tingle.js/dist/tingle.js') }}"></script>
<script src="{{ asset('/node_modules/cropperjs/dist/cropper.min.js') }}"></script>
<script src="{{ asset('/node_modules/image-crop-upload/dist/image-uploader.js') }}"></script>

<script src="{{ asset('/assets/admin/js/modules/service.js') }}"></script>
<script src="{{ asset('/assets/admin/js/vendor/post.js') }}"></script>
<script>
PostPage({
    imageUploadURL: "{{ route('admin.image.store') }}?_token={{ csrf_token() }}"
});
</script>
@endsection
