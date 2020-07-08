@php
Helium::header()->title("Liste des redirections");
Helium::header()->add("Ajouter une redirection","admin.tools.redirection.create");
Helium::header()->contextual([
"Supprimer toutes les redirections" => [
"data-confirm" => "Voulez vous vraiment tout supprimer ?",
"data-submit" => "deleteRedirections",
"dangerous" => true
],
"Importer un csv" => [
"route" => "admin.tools.redirection.import",
]]
);
@endphp

@extends('helium::layouts.master')

@section('content')

<x-helium-box>
  <table class="dataTable stripe hover">
    <thead>
      <tr>
        <th>Url à rediriger</th>
        <th>Nouvelle url</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
    </tbody>
  </table>
</x-helium-box>

<form id="deleteRedirections" action="{{ route('admin.tools.redirection.destroyAll') }}" method="post">
  {{ method_field('delete') }}
  {{ csrf_field() }}
</form>

@endsection

@section('js')
<script type="text/javascript">
  $('.dataTable').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: "{{ route('admin.tools.redirection.datatable') }}",
        columns: [
            {data: 'from', name : 'from'},
            {data: 'to', name : 'to'},
            {data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'table-actions'},
        ],
        rowCallback: function( row, data, index ) {
            row.dataset.link = "{{ route("admin.tools.redirection.edit", ["id" => "%id%"]) }}".replace("%id%",data.id);
            let dropmicEl = row.querySelector('[data-dropmic]')
            if(dropmicEl){
                new Dropmic(dropmicEl);
            }
        },
        drawCallback: function(settings, json) {
            feather.replace()
        },
        order: [[0, "desc"]],
    });
</script>
@endsection
