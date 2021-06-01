@php
Helium::header()->title("Edition d'un rôle");
Helium::header()->save("Sauvegarder","editRole");
@endphp

@extends('helium::layouts.master')

@section('content')

<form id="editRole" class="" action="{{ route('admin.role.update',[$role->id]) }}" method="post">
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