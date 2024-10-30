@extends('layouts.app')
@section('title', 'Crear Gastos')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">
        <div class="row page-title clearfix mt-4">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Lista de Pdf</h6>
            </div>
            <div class="page-title-rigth">
                <a id='botonEliminarPdf' href="../tareasProgramadas/eliminar-pdf"><button
                        class="btn btn-block btn-primary ripple">Eliminar
                        Pdf</button></a>
            </div>
        </div>


        <table id="table" class="table table-responsive-sm " style="width:100%">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Fecha de Creacion</th>
                    <th scope="col">Usuario</th>
                    <th scope="col">Sucursal</th>
                    <th scope="col">Url</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($listaPdf as $item)
                    <tr>
                        <td scope="row">{{ $item->FechaCreacionPdf }}</td>
                        <td scope="row">{{ $item->IdCreacion }}</td>
                        <td scope="row">{{ $item->IdSucursal }}</td>
                        <td scope="row">{{ $item->UrlPdf }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

    </div>
@stop
@section('scripts')
    <script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>

    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    ordering: false,
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
                        emptyTable: "Ning?n dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "?ltimo"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });

        $('#botonEliminarPdf').click(function(e) {
            $.LoadingOverlay("show", {
                image: '',
                text: 'Espere un momento por favor...',
                imageAnimation: '1.5s fadein',
                textColor: "#f6851a",
                textResizeFactor: '0.3',
                textAutoResize: true
            });
        })
    </script>
@stop
