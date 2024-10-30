@extends('layouts.app')
@section('title', 'Productos Eliminados')
@section('content')
    <div class="container">
        <div class="jumbotron bg-white pt-3 pb-2 border-radius--10 mt-4">
            {!! Form::open(['url' => 'reporte/almacen/productos-eliminados', 'method' => 'post', 'files' => true]) !!}
            {{ csrf_field() }}
            <section class="d-flex justify-content-center justify-content-sm-between align-items-center my-4 flex-wrap">
                <article>
                    <h6>Servicios/Productos Eliminados</h6>
                </article>
                <article>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a class="p-0" target="_blank"
                        href='{{ url("reporte/almacen/descargar-excel/$fecha/$idTipo/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </article>
            </section>
            <div class="row">
                <div class="col-12 col-md-6 mt-4 order-md-0">
                    <div class="form-group">
                        <label>Tipo</label>
                        <select id="idTipo" class="form-control" name="idTipo">
                            <option value="0">Todo</option>
                            <option value="1">Producto</option>
                            <option value="2">Servicio</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-6 mt-4 order-md-0">
                    <x-selectorFiltrosFechas obtenerDatos='false' />
                </div>
            </div>
            <x-inputFechasPersonalizadas mostrarBoton='false' />
            {!! Form::close() !!}
        </div>

        @if (count($productosEliminados) >= 1)
            <div class="row mt-4 justify-content-center">
                <section class="col-12 col-md-4 mb-2 mb-md-0 align-self-center">
                    <div class="card card-bg--celeste">
                        <div class="card-body px-4 pt-2 text-center">
                            @if ($idTipo == 0)
                                <span class="fs-22 font-weight-bold">SERVICIOS/PRODUCTOS ELIMINADOS</span>
                            @endif
                            @if ($idTipo == 1)
                                <span class="fs-22 font-weight-bold">PRODUCTOS ELIMINADOS</span>
                            @endif
                            @if ($idTipo == 2)
                                <span class="fs-22 font-weight-bold">SERVICIOS ELIMINADOS</span>
                            @endif
                            <hr>
                            <span class="font-weight-bold fs-34">{{ $cantProductosEliminados }}</span>
                        </div>
                    </div>
                </section>
                <div class="col-md-12 col-12 mt-5 d-flex justify-content-center">
                    <div id="productosEliminados">
                    </div>
                </div>
                <div class=" col-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha Eliminacion</th>
                                        <th scope="col">Usuario</th>
                                        <th scope="col">Descripcion</th>
                                        <th scope="col">Cantidad</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Moneda</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($productosEliminados as $item)
                                        <tr>
                                            <td>{{ $item->FechaEliminacion }}</td>
                                            <td>{{ $item->Nombre }}</td>
                                            <td>{{ $item->Descripcion }}</td>
                                            <td>{{ $item->Stock }}</td>
                                            <td>{{ $item->Codigo }}</td>
                                            <td>{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <section class="text-md-right text-center">
                            <span class="fs-14 badge badge-warning mr-md-4">Información disponible a partir de ABRIL del
                                2022</span>
                        </section>
                    </div>
                </div>
            </div>
        @else
            <section class="h-100">
                <article class="col-12 text-center font-weight-bold">
                    <span class="fs-28">NO SE ENCONTRARÓN RESULTADOS</span>
                </article>
            </section>
        @endif

    </div>
@endsection
<!-- Estas variables son usadas en el archivo assets/js/utilidades/scriptFechasPersonalizadas.js-->
@section('variablesJs')
    <script>
        const variablesBlade = {
            fecha: @json($fecha),
            fechaInicial: @json($fechaInicial),
            fechaFinal: @json($fechaFinal),
        }
    </script>
@endsection

@section('scripts')
    <script type="text/javascript">
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
        });
    </script>

    <script>
        $(function() {
            const idTipo = @json($idTipo);
            $(`#idTipo option[value = ${idTipo}]`).prop('selected', true);
        });
    </script>
    <script>
        if (@json($idTipo) == 1) {
            var cantEliminadosXProducto = @json($cantEliminadosXProducto);
            var nameProductos = @json($nameProductos);
            let arrayCantidades = cantEliminadosXProducto.map(function(item) {
                return Number((parseFloat(item)).toFixed(2));
            })

            var options = {
                series: arrayCantidades,
                chart: {
                    width: 600,
                    type: 'pie',
                },

                dataLabels: {
                    enabled: false
                },

                labels: nameProductos,
                responsive: [{
                    breakpoint: 400,
                    options: {
                        chart: {
                            width: 300
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }, {
                    breakpoint: 600,
                    options: {
                        chart: {
                            width: 400
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }, {
                    breakpoint: 800,
                    options: {
                        chart: {
                            width: 600
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }, {
                    breakpoint: 1600,
                    options: {
                        chart: {
                            width: 500
                        },
                        legend: {
                            position: 'bottom'
                        }
                    }
                }]
            };

            var chart = new ApexCharts(document.querySelector("#productosEliminados"), options);
            chart.render();
        }
    </script>
@endsection
