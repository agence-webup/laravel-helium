@php
Helium::header()->title("Helium CRUD Generator");
Helium::header()->save("Sauvegarder","createMigration");
@endphp

@extends('helium::layouts.master')

@section('content')

<form id="createMigration" class="" action="{{ route('crud.index') }}" method="post">
  {{ csrf_field() }}
  <x-helium-box>
    <x-slot name="header">
      <x-helium-box-header title="Création de la migration" />
    </x-slot>


    <div class="f-group">
      <div>
        <input class="f-switch" type="checkbox" name="migration" id="migration">
        <label for="migration">Souhaitez vous créer une migration ?</label>
      </div>
    </div>

    <div>
      {!! Form::create('select', 'model')
      ->label('Model à utiliser')
      ->addOptions($models)
      !!}
    </div>

    <div>
      {!! Form::create('text', 'name')
      ->label('Nom de la table')
      !!}
    </div>

  </x-helium-box>
</form>
@endsection
