@php
Helium::header()->title("Edition de l'admin");
Helium::header()->save("Sauvegarder","editAdminUser");
@endphp

@extends('helium::layouts.master')

@section('content')

<form id="editAdminUser" class="" action="{{ route('admin.admin_user.update',[$admin->id]) }}" method="post">
  {{ csrf_field() }}

  <x-helium-box>
    <x-slot name="header">
      <x-helium-box-header title="Informations" />
    </x-slot>
    @include('helium::admin_user.form')
  </x-helium-box>

</form>

@endsection
