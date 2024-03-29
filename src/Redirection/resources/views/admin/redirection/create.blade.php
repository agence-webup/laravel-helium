@php
Helium::header()->title("Création d'une redirection");
Helium::header()->save('Sauvegarder', 'createRedirection');
@endphp

@extends('helium::layouts.master')

@section('content')
    <form id="createRedirection" class="" action="{{ helium_route('tools.redirection.store') }}" method="post">
        {{ csrf_field() }}

        <x-helium-box>
            <x-slot name="header">
                <x-helium-box-header title="Informations" />
            </x-slot>
            @include('helium::admin.redirection.form.form')
        </x-helium-box>

    </form>
@endsection

@section('js')
    @include('helium::admin.redirection.form.javascript')
@endsection
