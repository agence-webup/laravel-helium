@php
Helium::header()->title('Edition de la redirection');
Helium::header()->save('Sauvegarder', 'editRedirection');
Helium::header()->contextual([
    'Supprimer la redirection' => [
        'data-confirm' => 'Voulez vous vraiment supprimer ?',
        'data-submit' => 'deleteRedirection',
        'dangerous' => true,
    ],
]);
@endphp

@extends('helium::layouts.master')

@section('content')
    <form id="editRedirection" action="{{ helium_route('tools.redirection.update', [$redirection->id]) }}" method="post">
        {{ csrf_field() }}

        <x-helium-box>
            <x-slot name="header">
                <x-helium-box-header title="Informations" />
            </x-slot>
            @include('helium::admin.redirection.form.form')
        </x-helium-box>
    </form>


    <form id="deleteRedirection" action="{{ helium_route('tools.redirection.destroy', $redirection->id) }}" method="post">
        {{ method_field('delete') }}
        {{ csrf_field() }}
    </form>
@endsection

@section('js')
    @include('helium::admin.redirection.form.javascript')
@endsection
