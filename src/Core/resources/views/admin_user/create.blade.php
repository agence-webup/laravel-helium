@php
Helium::header()->title("Création d'un admin");
Helium::header()->save("Sauvegarder", "createAdminUser");
@endphp

@extends('helium::layouts.master')

@section('content')

<form id="createAdminUser" class="" action="{{ route('admin.admin_user.store') }}" method="post">
  {{ csrf_field() }}

  <x-helium-box>
    <x-slot name="header">
      <x-helium-box-header title="Informations" />
    </x-slot>
    @include('helium::admin_user.form')
  </x-helium-box>

</form>

@endsection