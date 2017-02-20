@extends('helium::layouts.master')

@section('content')
@include('helium::elements.flash')

<article class="box">
    <header class="box__header">Mentions légales</header>
    <div class="box__content">
        <form action="{{ route('admin.legals.update') }}" method="post">
            {{ csrf_field() }}

            {!! Form::create('textarea', 'legals')
                ->value($settings->get('legals'))
                ->attr(['class' => 'f-100']) !!}

            <button type="submit" class="btn btn--primary">Enregistrer</button>
        </form>
    </div>
</article>
@endsection

@section('css')
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_editor.pkgd.min.css') }}" />
<link rel="stylesheet" href="{{ asset('/node_modules/froala-editor/css/froala_style.min.css') }}" />
@endsection

@section('js')
<script src="{{ asset('/node_modules/froala-editor/js/froala_editor.pkgd.min.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/languages/fr.js') }}"></script>
<script>

  $('[name=legals]').froalaEditor({
      language: 'fr',
      toolbarButtons: ['paragraphFormat', 'bold', 'italic', 'underline', 'formatUL', 'align', '|', 'insertLink', 'insertTable', 'insertImage' ,'|', 'outdent', 'indent', 'insertTable', '|', 'fullscreen', 'undo', 'redo', 'clearFormatting'],
      height: 300,
      heightMax: 500
  });
</script>
@endsection
