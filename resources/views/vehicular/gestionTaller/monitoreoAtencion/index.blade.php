@extends('layouts.app')
@section('title', 'Monitoreo-Atencion-Vehícular')
@section('content')
    <div class="container">
        <section class="d-flex justify-content-between align-items-center my-4">
            <article>
                <h6>Monitoreo de Atención Vehicular</h6>
            </article>
        </section>
        <section>
            <ul class="nav nav-pills mb-3 justify-content-center" id="pills-tab" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab"
                        aria-controls="pills-home" aria-selected="true">Estados de Atencion Vehícular</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab"
                        aria-controls="pills-profile" aria-selected="false">Atenciones con Tiempos de Retraso</a>
                </li>
                <li class="nav-item">
                    <a class="m-4" href="https://www.youtube.com/watch?v=9CHV5Uj4Y7g" target="_blank">
                        <span class="btn btn-autocontrol-naranja ripple text-white">
                            Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                        </span>
                    </a>
                </li>
            </ul>
            <div class="tab-content" id="pills-tabContent">
                <section class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                    {{-- {!! Form::open([
                    'route' => 'monitoreo-atencion',
                    'method' => 'GET',
                    'id' => 'formularioConsultar',
                    ]) !!}
                    {{ csrf_field() }} --}}
                    <article class="row d-flex align-items-end">

                        <div class="col-md-10 pt-2 pt-md-0">
                            <x-selectorFiltrosFechas :metodoObtenerDatos="'ajax'"></x-selectorFiltrosFechas>
                        </div>
                        <div class="col-12 col-md-2 pt-md-0 form-group">
                            <a id="enlaceDescargarExcel" target="_blank"
                                href="{{ route('monitoreo-atencion.exportar-excel', ['opcion' => 'excelEstadosAtenciones', 'fechaIni' => $fechaI, 'fechaFin' => $fechaF]) }}">
                                <span class="btn bg-excel ripple">
                                    <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                </span>
                            </a>
                        </div>
                    </article>
                    <x-inputFechasPersonalizadas></x-inputFechasPersonalizadas>
                    {{-- {!! Form::close() !!} --}}
                    <article id="contenedorEstados">
                        @include('vehicular.gestionTaller.monitoreoAtencion._tablaEstados')
                    </article>
                </section>
                <section class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                    @include('vehicular.gestionTaller.monitoreoAtencion._tablaRetrasos')
                </section>
            </div>
        </section>
    </div>
@stop

<!-- Etas variables son usadas en el archivo assets/js/utilidades/utilidades.js-->
@section('variablesJs')
    <script>
        const variablesBlade = {
            fecha: @json($fecha),
            fechaInicial: @json($fechaInicial),
            fechaFinal: @json($fechaFinal),
            ejecutarPeticionAjax: () => {
                obtenerDatosAjax();
            }
        }
    </script>
@endsection

@section('scripts')
    <script>
        const obtenerDatosAjax = () => {
            utilidades.showLoadingOverlay();
            let nuevaRuta =
                "{{ route('monitoreo-atencion.exportar-excel', ['opcion' => 'excelEstadosAtenciones', ':fechaIni', ':fechaFin']) }}";

            $.ajax({
                type: 'GET',
                url: "{{ route('monitoreo-atencion.obtener-atenciones') }}",
                data: {
                    'fecha': $('#idFecha').val(),
                    'fechaIni': $('#datepickerIni').val(),
                    'fechaFin': $('#datepickerFin').val(),
                },
                success: function(data) {
                    $('#contenedorEstados').html(data.vista);
                    utilidades.hideLoadingOverlay();
                    mostrarGrafico(data.datosGrafico);
                    inicializarTabla($('#table'));
                    nuevaRuta = nuevaRuta.replace(':fechaIni', data.fechaInicio);
                    nuevaRuta = nuevaRuta.replace(':fechaFin', data.fechaFinal);
                    $('#enlaceDescargarExcel').attr('href', nuevaRuta);
                }
            });
        }
    </script>

    <script>
        const datosGrafico = @json($atencionesRetrasadasGrafico);
        var options = {
            series: [{
                name: 'Retraso',
                data: datosGrafico.TiemposRetraso
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    borderRadius: 5,
                    columnWidth: '50%',
                }
            },
            dataLabels: {
                enabled: false
            },
            stroke: {
                width: 2
            },

            grid: {
                row: {
                    colors: ['#fff', '#f2f2f2']
                }
            },
            xaxis: {
                labels: {
                    rotate: -45
                },
                categories: datosGrafico.Placas,
            },
        };

        var chart = new ApexCharts(document.querySelector("#graficoAtencionesConRetraso"), options);
        chart.render();
    </script>

    <script type="text/javascript">
        $(function() {
            mostrarGrafico(@json($datosGrafico));
            inicializarTabla();

        });

        // == FUNCIONES ==
        const mostrarGrafico = (datos) => {
            var options = {
                series: [{
                    data: datos.map(function(dato) {
                        return {
                            x: dato.estado,
                            y: dato.cantidad,
                        };
                    })
                }],
                legend: {
                    show: false
                },
                chart: {
                    height: 350,
                    type: 'treemap'
                },
                title: {
                    text: 'Cantidad de vehículos',
                    align: 'center'
                },
                dataLabels: {
                    style: {
                        fontSize: '16px',
                        colors: ['#000000'] // Color del texto en negro
                    },
                    formatter: function(text, op) {
                        return [text, op.value]
                    },
                },
                colors: datos.map(function(dato) {
                    return dato.color;
                }),
                plotOptions: {
                    treemap: {
                        enableShades: false,
                        distributed: true,
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector("#graficoEstadosAtenciones"), options);
            chart.render();
        }

        const inicializarTabla = (tabla = $('.table')) => {
            tabla.DataTable({
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
        }
    </script>
@stop
