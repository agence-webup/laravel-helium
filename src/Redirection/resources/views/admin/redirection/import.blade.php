@php
Helium::header()->title("Import de redirections");
Helium::header()->save("Importer","importRedirections");
@endphp


@extends('helium::layouts.master')

@section('content')


<form id="importRedirections" class="" action="{{ route('admin.tools.redirection.postImport') }}" method="post"
  enctype="multipart/form-data">
  {{ csrf_field() }}

  <x-helium-box>
    <p>
      Le fichier doit être un csv avec deux colonnes (sans nom de colonne):
    </p>
    <ul>
      <li>Ancienne url</li>
      <li>Nouvelle url</li>
    </ul>
    <input type="file" name="file" id="">
  </x-helium-box>

</form>
@endsection

@section('js')
@include("helium::admin.redirection.form.javascript")
@endsection
