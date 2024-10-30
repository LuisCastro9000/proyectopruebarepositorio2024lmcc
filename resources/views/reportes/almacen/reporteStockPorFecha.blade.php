@extends('layouts.app')
@section('title', 'Stock Histórico')
@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="container">
        <section class="d-flex justify-content-between align-items-center my-4 flex-wrap">
            <article>
                <h6>Stock Histórico</h6>
            </article>
            <a id="btnExcel" class="p-0" target="_blank" href="#">
                <span class="btn bg-excel ripple">
                    <i class="list-icon material-icons fs-20">explicit</i>XCEL
                </span>
            </a>
        </section>
        <section>
            {!! Form::open([
                'url' => 'reportes/almacen/stock-por-fecha',
                'method' => 'POST',
                'id' => 'formularioConsultar',
            ]) !!}
            {{ csrf_field() }}
            <div class="row">
                <div class="input-group col-12">
                    <input id="datepicker" type="text" class="form-control datepicker" name="fecha" autocomplete="off"
                        onkeydown="return false" placeholder="Ingrese la Fecha" required
                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy", "orientation": "bottom"}'
                        data-date-end-date="0d">
                    <div class="input-group-append">
                        <button id="boton" type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </section>
        <section class="jumbotron bg-jumbotron--white">
            <table id="table" class="table table-responsive-sm" style="width:100%">
                <thead>
                    <tr class="bg-primary">
                        <th scope="col">FechaCreación</th>
                        <th scope="col">Descripción</th>
                        <th scope="col">Precio</th>
                        <th scope="col">Marca</th>
                        <th scope="col">Categoría</th>
                        <th scope="col">CódigoBarra</th>
                        <th scope="col">FechaMovimiento</th>
                        <th scope="col">Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datosStock as $dato)
                        <tr>
                            <td>{{ $dato->FechaCreacionArticulo }}</td>
                            <td>{{ $dato->Descripcion }}</td>
                            <td>{{ $dato->Precio }}</td>
                            <td>{{ $dato->NombreMarca }}</td>
                            <td>{{ $dato->NombreCategoria }}</td>
                            <td>{{ $dato->CodigoBarra }}</td>
                            <td>{{ $dato->FechaMovimiento }}</td>
                            <td><span
                                    class="badge-autoncontrol badge-autoncontrol__success fs-15">{{ $dato->Existencia }}</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script>
        let fechaView = @json($fechaView);
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
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
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });

            $('#datepicker').val(fechaView);


            console.log(@json($fecha));
        });

        $('#btnExcel').click(function(e) {
            e.preventDefault();
            const datos = @json($datosStock);
            const fecha = @json($fecha);
            if (datos.length < 1) {
                swal("Error", 'No hay datos para Exportar', "error")
            } else {
                let url = "{{ route('exportarExcelStockPorFecha', [':fecha']) }}";
                url = url.replace(':fecha', fecha);
                window.open(url, '_blank');
            }
        })
    </script>
@stop
