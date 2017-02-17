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
<script src="{{ asset('/node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/froala_editor.pkgd.min.js') }}"></script>
<script src="{{ asset('/node_modules/froala-editor/js/languages/fr.js') }}"></script>
<script>

  $('[name=legals]').froalaEditor({
      language: 'fr',
      pluginsEnabled: [
         'align',
         'charCounter',
         //'codeBeautifier',
         'codeView',
         'colors',
         //'draggable',
         //'emoticons',
         'entities',
         //'file',
         //'fontFamily',
         'fontSize',
         'fullscreen',
         //'image',
         //'imageManager',
         //'inlineStyle',
         'lineBreaker',
         'link',
         'lists',
         'paragraphFormat',
         //'paragraphStyle',
         //'quickInsert',
         'quote',
         //'save',
         'table',
         //'url',
         //'video',
     ]
  });
</script>
@endsection
