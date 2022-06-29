@php
Helium::header()->title('Liste des admins');
if ($heliumUser->can('admin_users.create')) {
    Helium::header()->add('Ajouter', 'admin_user.create');
}
@endphp

@extends('helium::layouts.master')

@section('content')
    <x-helium-box id="test">
        <x-slot name="header">
        </x-slot>
        <table class="dataTable dataTable--admins stripe hover">
            <thead>
                <tr>
                    <th>Email</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>

    </x-helium-box>
@endsection


@section('js')
    <script type="text/javascript">
        $('.dataTable--admins').DataTable({
            processing: true,
            serverSide: true,
            responsive: true,
            ajax: "{{ helium_route('admin_user.datatable') }}",
            columns: [{
                    data: 'email',
                    name: 'email'
                },
                {
                    data: 'actions',
                    name: 'actions',
                    orderable: false,
                    searchable: false,
                    className: 'table-actions'
                },
            ],
            rowCallback: function(row, data, index) {
                row.dataset.link = "{{ helium_route('admin_user.edit', ['id' => '%id%']) }}".replace("%id%",
                    data.id);
                let dropmicEl = row.querySelector('[data-dropmic]')
                if (dropmicEl) {
                    new Dropmic(dropmicEl);
                }
            },
            drawCallback: function(settings, json) {
                feather.replace()
            },
            initComplete: function() {
                let collumnsDefs = this.fnSettings().aoColumns;
                if (collumnsDefs && collumnsDefs.length > 2) {
                    let newLine = document.createElement('tr')
                    newLine.classList.add('searchLine')
                    $(newLine).appendTo(this.api().table().header())
                    this.api().columns().every(function() {
                        let cell = document.createElement('th')
                        let column = this;
                        let collumnDef = collumnsDefs[column[0]];
                        let headerSearchable = !$(column.header()).hasClass('table-actions')
                        if (headerSearchable) {
                            let input = document.createElement("input");
                            input.placeholder = "Rechercher par " + column.header(column[0]).innerHTML
                                .toLowerCase();
                            input.classList.add("f-size-full");
                            if (column.search()) {
                                input.value = column.search();
                            }
                            var searchField = $(input).on('keyup change clear', function() {
                                column.search($(this).val(), false, true, true).draw();
                            })
                            $(input).appendTo(cell)
                        }
                        $(cell).appendTo(newLine)
                    });
                }
            },
            order: [
                [0, "desc"]
            ],
        });
    </script>
@endsection
