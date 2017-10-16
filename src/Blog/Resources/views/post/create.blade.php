@extends('helium::layouts.master')

@section('content')
<form action="{{ route('admin.post.store') }}" method="post" enctype="multipart/form-data">
    {{ csrf_field() }}

    @include('helium::post.form', ['title' => 'Ajouter un article', 'post' => $post])
</form>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/vendor/redactor/redactor.css') }}" />

<link rel="stylesheet" href="{{ asset('/node_modules/tingle.js/dist/tingle.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/cropperjs/dist/cropper.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/image-crop-upload/dist/image-uploader.css') }}" />
@endsection

@section('js')
<script src="{{ asset('/vendor/redactor/redactor.js') }}"></script>
<script src="{{ asset('/vendor/redactor/fr.js') }}"></script>

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
