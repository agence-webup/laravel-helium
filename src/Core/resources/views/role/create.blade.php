@php
Helium::header()->title("Création d'un rôle");
Helium::header()->save("Sauvegarder", "createRole");
@endphp

@extends('helium::layouts.master')

@section('content')

<form id="createRole" class="" action="{{ route('admin.role.store') }}" method="post">
  {{ csrf_field() }}

  <x-helium-box>
    <x-slot name="header">
      <x-helium-box-header title="Informations" />
    </x-slot>
    @include('helium::role.form')
  </x-helium-box>

</form>

@endsection

@section('js')
@include('helium::role.javascript')
@endsection