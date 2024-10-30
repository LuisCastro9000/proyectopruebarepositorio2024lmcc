@extends('layouts.app')
@section('title', 'Servicios Eliminados')
@section('content')
    <style>
        .loader {
            /* width: 30px;
                                        height: 30px;
                                        border-radius: 50%;
                                        border: 4px solid #dadada;
                                        border-left-color: #2b2b2b;
                                        animation: loader 0.8s ease infinite; */

            border: 5px solid #623ddb;
            border-bottom-color: transparent;
            border-radius: 50%;
            box-sizing: border-box;
            display: inline-block;
            height: 30px;
            width: 30px;
            animation: loader 1s ease infinite;
        }

        @keyframes loader {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="container">

        <div class="jumbotron bg-white pt-3 pb-2 border-radius--10 mt-4">
            {!! Form::open(['url' => 'reporte/almacen/servicios-eliminados', 'method' => 'post', 'files' => true]) !!}
            {{ csrf_field() }}
            <section class="d-flex justify-content-center justify-content-sm-between align-items-center my-4 flex-wrap">
                <article>
                    <h6>Servicios Eliminados</h6>
                </article>

                <section>
                    <button type="submit" class="btn btn-primary">Buscar</button>
                    <a class="p-0" target="_blank"
                        href='{{ url("reporte/almacen/servicios-eliminados/descargar-excel/$fecha/$ini/$fin") }}'>
                        <span class="btn bg-excel ripple">
                            <i class="list-icon material-icons fs-20">explicit</i>XCEL
                        </span>
                    </a>
                </section>
            </section>
            <div class="row clearfix justify-content-center">
                <div class="col-12 mt-4">
                    <div class="form-group">
                        <label>Fecha de Eliminación</label>
                        <select id="idFecha" class="form-control" name="fecha">
                            <option value="1">Hoy</option>
                            <option value="2">Ayer</option>
                            <option value="3">Semana Actual</option>
                            <option value="4">Semana Anterior</option>
                            <option value="5">Mes Actual</option>
                            <option value="6">Mes Anterior</option>
                            <option value="7">Año Actual</option>
                            <option value="8">Año Anterior</option>
                            <option value="9">Personalizar</option>
                        </select>
                    </div>
                </div>
                <div id="Inicio" class="col-12 col-md-6 mt-4">
                    <div class="form-group">
                        <label class="form-control-label">Desde</label>
                        <div class="input-group">
                            <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                                data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                                onkeydown="return false" data-date-end-date="0d">
                        </div>
                    </div>
                </div>
                <div id="Final" class="col-12 col-md-6 mt-4">
                    <div class="form-group">
                        <label class="form-control-label">Hasta</label>
                        <div class="input-group">
                            <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                                data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' autocomplete="off"
                                onkeydown="return false" data-date-end-date="0d">
                        </div>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>


        @if (count($productosEliminados) >= 1)
            <div class="row mt-4 justify-content-center">
                <section class="col-12 col-md-4 mb-2 mb-md-0 align-self-center">
                    <div class="card card-bg--celeste">
                        <div class="card-body px-4 pt-2 text-center">
                            <span class="fs-22 font-weight-bold">SERVICIOS ELIMINADOS</span>
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
@section('scripts')
    {{-- <script>
        $('#idFecha').change(() => {
            $('.loader').show();
            $('form').submit();
        })

        $(() => {
            $('.loader').hide();
        })
    </script> --}}

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
            $('#Inicio').hide();
            $('#Final').hide();
            var fecha = @json($fecha);
            if (fecha == '9') {
                var fechaIni = @json($fechaInicial);
                var fechaFin = @json($fechaFinal);
                $('#Inicio').show();
                $('#Final').show();
                $('#datepickerIni').val(fechaIni);
                $('#datepickerFin').val(fechaFin);
            }
            $('#idFecha option[value=' + fecha + ']').prop('selected', true);
        });
    </script>

    <script>
        $(function() {
            $("#idFecha").on('change', function() {
                var valor = $("#idFecha").val();
                if (valor == "9") {
                    $('#Inicio').show();
                    $('#Final').show();
                } else {
                    $('#Inicio').hide();
                    $('#Final').hide();
                    $('#datepickerIni').val('');
                    $('#datepickerFin').val('');
                }
            });
        });
    </script>

    <script>
        var cantEliminadosXProducto = @json($cantEliminadosXProducto);
        var nameProductos = @json($nameProductos);
        var arrayNumeros = [];
        for (let index = 0; index < cantEliminadosXProducto.length; index++) {
            let d = Number(cantEliminadosXProducto[index])
            arrayNumeros.push(d);
        };

        var options = {
            series: arrayNumeros,
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
    </script>
@endsection
