@extends('helium::layouts.master')

@section('content')
    @include('helium::elements.flash')
    <article class="box">
        <header class="box__header">Liste des pages</header>

        <div class="box__actions">
            <a href="{{ helium_route('page.create') }}" class="btn btn--primary">Ajouter une page</a>
        </div>

        <div class="box__content">
            <table data-js="datatable">
                <thead>
                    <tr>
                        <th>Titre</th>
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
    <script src="{{ asset('/assets/admin/js/modules/confirm-delete.js') }}"></script>
    <script type="text/javascript">
        $('[data-js=datatable]').DataTable({
            processing: true,
            serverSide: true,
            lengthChange: false, // select nombre item per page
            ajax: "{{ helium_route('page.datatable') }}",
            columns: [{
                    data: 'title',
                    name: 'title'
                },
                {
                    data: 'published',
                    name: 'published'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'table-actions'
                },
            ],
            order: [
                [1, "desc"]
            ],
            rowCallback: function(row, data, index) {
                new Dropmic(row.querySelector('[data-dropmic]'));
            },
            pageLength: 20,
        });
    </script>
@endsection
