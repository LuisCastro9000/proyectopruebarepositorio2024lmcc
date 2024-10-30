@extends('layouts.app')
@section('title', 'Eliminacion Masiva')
@section('content')
    <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
        rel="stylesheet" />
    <div class="container">
        <section>
            @if (session('status'))
                <div class="alert alert-success mt-4">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('status') }}
                </div>
            @endif
            @if (session('error'))
                <div class="alert alert-danger mt-4">
                    <button type="button" class="close" data-dismiss="alert">&times;</button>
                    {{ session('error') }}
                </div>
            @endif
        </section>
        {!! Form::open([
            'route' => 'serviciosEliminacionMasiva',
            'method' => 'POST',
            'id' => 'formulario',
        ]) !!}
        {{ csrf_field() }}
        <section class="mt-4 d-flex justify-content-between">
            <h6 class="page-title-heading mr-0 mr-r-5">Lista Servicios</h6>
            <button id="btnEliminar" class="btn btn-primary" type="submit"><i
                    class="material-icons list-icon">delete</i>&nbsp;Eliminar</button>
        </section>

        <section class="jumbotron bg-jumbotron--white">
            <table id="table" class="table table-responsive-sm" style="width:100%">
                <thead>
                    <tr class="bg-primary">
                        <th scope="col"></th>
                        <th scope="col">Nombre Producto</th>
                        <th scope="col">Tipo Moneda</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Codigo</th>
                    </tr>
                </thead>
                <tbody>
                    @php $k=0; @endphp
                    @foreach ($servicios as $servicio)
                        <tr class="chequear" id="{{ $k }}">
                            <td>
                                {{ $servicio->IdArticulo }}
                            </td>
                            <td>{{ $servicio->Descripcion }} </td>
                            <td>
                                @if ($servicio->IdTipoMoneda == 1)
                                    Soles
                                @else
                                    Dólares
                                @endif
                            </td>
                            <td>{{ $servicio->Precio }}</td>
                            <td>{{ $servicio->Codigo }}</td>
                        </tr>
                        @php $k++; @endphp
                    @endforeach
                </tbody>
            </table>
        </section>
        <section id="seccionInputId">

        </section>
        {!! Form::close() !!}
    </div>
@stop
@section('scripts')
    <script type="text/javascript"
        src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>

    <script>
        $('#btnEliminar').click((event) => {
            event.preventDefault();
            let checked = table.column(0).checkboxes.selected();
            $.each(checked, function(index, rowId) {
                var datos = rowId.split("-");
                var ids = parseInt(datos[0]);
                $('#seccionInputId').append(`<input type="hidden" name="id[]" value="${ids}">`);
            });
            mensajeDeConfirmacionConSubmit("Estás seguro de eliminar?",
                'Si elimina los Servicios ya no podrá recuperarse', 'Entendido')
        })
    </script>

    <script>
        var table = $("#table").DataTable({
            columnDefs: [{
                targets: 0,
                checkboxes: {
                    selectRow: true,
                },
            }, ],
            select: {
                style: "multi",
            },
            responsive: true,
            order: [
                [0, "desc"]
            ],
            language: {
                processing: "Procesando...",
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                infoFiltered: "",
                infoPostFix: "",
                loadingRecords: "Cargando...",
                zeroRecords: "No se encontraron resultados",
                emptyTable: "Ningún dato disponible en esta tabla",
                paginate: {
                    first: "Primero",
                    previous: "Anterior",
                    next: "Siguiente",
                    last: "Último",
                },
                aria: {
                    sortAscending: ": Activar para ordenar la columna de manera ascendente",
                    sortDescending: ": Activar para ordenar la columna de manera descendente",
                },
            },
        });
    </script>

@stop
