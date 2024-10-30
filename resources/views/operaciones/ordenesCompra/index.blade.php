@extends('layouts.app')
@section('title', 'Ordenes-Compra')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert/sweetAlert2-11.7.3/sweetalert2.min.css') }}" />
    <div class="container">
        <section class="d-flex justify-content-center justify-content-md-start my-4 d-flex">
            <article>
                <h6>Ordenes de Compra</h6>
            </article>
        </section>
        <section
            class="jumbotron jumbotron-fluid p-4 d-flex justify-content-center flex-wrap align-items-center align-items-md-end justify-content-sm-between flex-column flex-md-row my-4">
            <article class="text-center mb-2 mb-md-0">
                <a href="{!! url('/reportes/compras/ordenes-compra') !!}">
                    <img width="100px" src="{{ asset('/assets/img/reporteOrdenCompra.png') }}"
                        alt="Reporte de Ordenes de compra" style="width: 50px; heigth:50px"><br>
                    <button class="btn bg-green ripple">Ver Reporte</button></a>
            </article>
            <article>
                <a href="{{ route('ordenDeCompra.create') }}"><button class="btn btn--verde ripple">Generar
                        Orden de Compra</button></a>
            </article>
        </section>

        <section class="jumbotron bg-jumbotron--white">
            <table id="tableOrdenesDeCompra" class="table table-responsive-sm" style="width:100%">
                <thead>
                    <tr class="bg-primary">
                        <th scope="col">Fecha Emision</th>
                        <th scope="col">Proveedor</th>
                        <th scope="col">Ruc</th>
                        <th scope="col">Codigo</th>
                        <th scope="col">Tipo de Moneda</th>
                        <th scope="col">Tipo de Pago</th>
                        <th scope="col">Total</th>
                        <th scope="col">Tipo de Compra</th>
                        <th scope="col">Ver Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ordenesCompra as $ordenes)
                        <tr>
                            <td>{{ $ordenes->FechaEmision }}</td>
                            <td>{{ $ordenes->Nombres }}</td>
                            <td>{{ $ordenes->NumeroDocumento }}</td>
                            <td>{{ $ordenes->Serie }}-{{ $ordenes->Numero }}</td>
                            <td>{{ $ordenes->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                            <td>{{ $ordenes->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                            <td>{{ $ordenes->Total }}</td>
                            <td>{{ $ordenes->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                            <td>
                                <a href="{{ route('ordenDeCompra.show', $ordenes->IdOrdenCompra) }}"><i
                                        class="list-icon material-icons">visibility</i></a>
                                <a href="{{ route('ordenDeCompra.edit', $ordenes->IdOrdenCompra) }}" title="Editar"><i
                                        class="list-icon material-icons">edit</i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('assets/sweetAlert/sweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>
    <script>
        $(function() {
            $(document).ready(function() {
                $('#tableOrdenesDeCompra').DataTable({
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

            respuestaInfoMensaje(null,
                'Solo se muestra las Ordenes de Compra que no están asociada a un Comprobante de Pago')
        });
    </script>
@stop
