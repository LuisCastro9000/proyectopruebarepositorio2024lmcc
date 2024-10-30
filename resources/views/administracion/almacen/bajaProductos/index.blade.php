@extends('layouts.app')
@section('title', 'Baja-Productos')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert/sweetAlert2-11.7.3/sweetalert2.min.css') }}" />
    <div class="container">
        <section class="d-flex justify-content-center justify-content-md-start my-4 d-flex">
            <article>
                <h6>Bajas De productos</h6>
            </article>
        </section>
        <section
            class="jumbotron jumbotron-fluid p-4 d-flex justify-content-center flex-wrap align-items-center align-items-md-end justify-content-sm-between flex-column flex-md-row my-4">
            <article class="text-center mb-2 mb-md-0">
                <a href="../../reportes/almacen/baja-productos">
                    <img width="100px" src="{{ asset('/assets/img/reporteOrdenCompra.png') }}" alt="Reporte de baja de compra"
                        style="width: 50px; heigth:50px"><br>
                    <button class="btn bg-green ripple">Ver Reporte</button></a>
            </article>
            <article>
                <a href="{{ route('baja-productos.create') }}"><button class="btn btn--verde ripple">Generar Baja de
                        Productos</button></a>
            </article>
        </section>

        <section class="jumbotron bg-jumbotron--white">
            <table id="table" class="table table-responsive-sm" style="width:100%">
                <thead>
                    <tr class="bg-primary text-center">
                        <th scope="col" class="text-left">Fecha Baja</th>
                        <th scope="col">Usuario</th>
                        <th scope="col">Cantidad de Bajas</th>
                        <th scope="col">Motivo</th>
                        <th scope="col">Ver Detalle</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($bajaProductos as $baja)
                        <tr class="text-center">
                            <td class="text-left">{{ $baja->FechaBaja }}</td>
                            <td>{{ $baja->NombreUsuario }}</td>
                            <td>{{ $baja->TotalCantidad }}</td>
                            <td>
                                @if ($baja->IdMotivo == 1)
                                    Consumo Interno
                                @elseif($baja->IdMotivo == 2)
                                    Producto Vencido
                                @elseif($baja->IdMotivo == 3)
                                    Perdida y/o Extravio
                                @else
                                    {{ $baja->DescripcionMotivo }}
                                @endif
                            </td>
                            <td>
                                <form action="{{ route('baja-productos.show', $baja->IdBajaProducto) }}" method="POST">
                                    @method('GET')
                                    <input type="hidden" value="{{ $baja->FechaBaja }}" name="inputFechaBaja">
                                    <button type="submit" class="border-0 bg-transparent cursor-pointer"><i
                                            class="list-icon material-icons text-primary">visibility</i></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
    <script>
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

                Swal.fire({
                    text: 'En la Tabla se mostraran solo las Bajas de Productos de los ULTIMOS TREINTA DÍAS',
                    icon: 'info',
                    confirmButtonText: 'Entendido'
                })
            });
        });
    </script>
@stop
