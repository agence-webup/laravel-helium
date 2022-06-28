@php
Helium::header()->title("Helium CRUD Generator");
Helium::header()->save("Sauvegarder","createMigration");
@endphp

@extends('helium::layouts.master')

@section('content')
<div id="app">
  {{-- <form id="createMigration" class="" action="{{ route('crud.index') }}" method="post"> --}}
  {{ csrf_field() }}

  <x-helium-box>
    <x-slot name="header">
      <x-helium-box-header title="Sélection du model" />
    </x-slot>
    <div>
      {!! Form::create('select', 'model')
      ->label('Model à utiliser')
      ->addOptions($models)
      !!}
      <a href="{{ route("crud.migration.index") }}">Créer un model</a>
    </div>
  </x-helium-box>

  {{-- </form> --}}
</div>
@endsection
