@extends('helium::layouts.master')

@section('content')
@include('helium::elements.flash')
<article class="box">
    <header class="box__header">Liste des articles</header>

    <div class="box__actions">
        <a href="{{ route('admin.post.create') }}" class="btn btn--primary">Ajouter un article</a>
    </div>

    <div class="box__content">
        <table data-js="datatable">
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Date de publication</th>
                    <th>État</th>
                    <th class="table-150p txtcenter">Actions</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</article>
@endsection

@section('js')
<link rel="stylesheet" href="{{ asset('/node_modules/dropmic/dist/dropmic.css') }}">

<script src="{{ asset('/node_modules/jquery/dist/jquery.js') }}"></script>
<script src="{{ asset('/node_modules/datatables.net/js/jquery.dataTables.js') }}"></script>
<script src="{{ asset('/node_modules/dropmic/dist/dropmic.js') }}"></script>
<script src="{{ asset('/assets/admin/js/modules/datatable.js') }}"></script>
<script src="{{ asset('/assets/admin/js/modules/confirm-delete.js') }}"></script>
<script type="text/javascript">
$('[data-js=datatable]').DataTable({
    processing: true,
    serverSide: true,
    lengthChange: false, // select nombre item per page
    ajax: '{{ route('admin.post.datatable') }}',
    columns: [
        {data: 'title', name: 'title'},
        {data: 'published_at', name: 'published_at'},
        {data: 'state', name: 'state'},
        {data: 'actions', name: 'actions', orderable: false, searchable: false, className: 'table-actions'},
    ],
    order: [[1, "desc"]],
    rowCallback: function( row, data, index ) {
        new Dropmic(row.querySelector('[data-dropmic]'));
    },
    pageLength: 20,
});
</script>
@endsection
