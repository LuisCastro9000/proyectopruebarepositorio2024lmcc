@extends('layouts.app')
@section('title', 'Tipo Atenciones')
@section('content')
    <div class="container">
        <section
            class="d-flex justify-content-center justify-content-sm-between align-items-center my-4 flex-column flex-sm-row">
            <article>
                <h6>Tipo Atenciones</h6>
            </article>
            <a class="p-0" target="_blank" href='{{ route('exportarReporteTipoAtencion', [$fecha, $ini, $fin]) }}'>
                <span class="btn bg-excel ripple">
                    <i class="list-icon material-icons fs-20">explicit</i>XCEL
                </span>
            </a>
        </section>
        <section>
            {!! Form::open([
                'url' => 'vehicular/reportes/tipo-atencion',
                'method' => 'POST',
                'id' => 'formularioTipoAtencion',
            ]) !!}
            {{ csrf_field() }}
            <article class="row  clearfix">
                <div class="col-12 col-md-5">
                    <div class="form-group">
                        <label>Tipo Atención</label>
                        <select class="m-b-10 form-control select2-hidden-accessible" id="idTipoAtencion"
                            name="idTipoAtencion" data-toggle="select2" tabindex="-1" aria-hidden="true">
                            <option value="0">Todo</option>
                            @foreach ($listaAtenciones as $atencion)
                                @if ($atencion->IdTipoAtencion == $idTipoAtencion)
                                    <option value="{{ $atencion->IdTipoAtencion }}" selected>{{ $atencion->Descripcion }}
                                    </option>
                                @else
                                    <option value="{{ $atencion->IdTipoAtencion }}">{{ $atencion->Descripcion }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <x-selectorFiltrosFechas :obtenerDatos="'false'" :tipoRangoFechas="'anual'"></x-selectorFiltrosFechas>
                </div>
                <div class="col-12 col-md-2 d-flex justify-content-center justify-content-md-end">
                    <div class="form-group mt-2">
                        <br>
                        <button type="submit" class="btn btn-primary">Buscar</button>
                    </div>
                </div>
            </article>
            <x-inputFechasPersonalizadas :mostrarBoton="'false'" :tipoRangoFechas="'anual'"></x-inputFechasPersonalizadas>
            {!! Form::close() !!}
        </section>


        @if (count($atenciones) < 1)
            <div class="fs-20 text-danger d-flex align-items-center justify-content-center" style="height: 200px">No Hay
                resultados..
            </div>
        @else
            <section class="mt-5">
                <div class="row">
                    <article class="col-12 col-md-3">
                        <div class="card bg-celeste">
                            <div class="card-body text-center">
                                <span class="d-block fs-22 font-weight-bolder text-uppercase">Abierto</span>
                                <span class="d-block font-weight-bold fs-30">{{ $datos[1] ?? '0' }}</span>
                            </div>
                        </div>
                    </article>
                    <article class="col-12 col-md-3">
                        <div class="card bg-celeste">
                            <div class="card-body text-center">
                                <span class="d-block fs-22 font-weight-bolder text-uppercase">Proceso</span>
                                <span class="d-block font-weight-bold fs-30">{{ $datos[2] ?? '0' }}</span>
                            </div>
                        </div>
                    </article>
                    <article class="col-12 col-md-3">
                        <div class="card bg-celeste">
                            <div class="card-body text-center">
                                <span class="d-block fs-22 font-weight-bolder text-uppercase">Finalizado</span>
                                <span class="d-block font-weight-bold fs-30">{{ $datos[3] ?? '0' }}</span>
                            </div>
                        </div>
                    </article>
                    <article class="col-12 col-md-3">
                        <div class="card bg-celeste">
                            <div class="card-body text-center">
                                <span class="d-block fs-22 font-weight-bolder text-uppercase">Cerrado</span>
                                <span class="d-block font-weight-bold fs-30">{{ $datos[4] ?? '0' }}</span>
                            </div>
                        </div>
                    </article>
                </div>
            </section>

            <section class="jumbotron bg-jumbotron--white">
                <div id="grafico" class="mt-5">

                </div>
                <table id="table" class="table table-responsive-sm" style="width:100%">
                    <thead>
                        <tr class="bg-primary">
                            <th scope="col">Fecha</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Placa</th>
                            <th scope="col">Tipo Atención</th>
                            <th scope="col">Mecánico</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($atenciones as $atencion)
                            <tr>
                                <td>{{ $atencion->FechaCreacion }}</td>
                                <td>{{ $atencion->RazonSocial }}</td>
                                <td>{{ $atencion->PlacaVehiculo }}</td>
                                <td>{{ $atencion->Descripcion }}</td>
                                <td>{{ $atencion->NombreOperario }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @endif
    </div>
@stop

<!-- Estas variables son usadas en el archivo assets/js/utilidades/scriptFechasPersonalizadas.js-->
@section('variablesJs')
    <script>
        const variablesBlade = {
            fecha: @json($fecha),
            fechaInicial: @json($fechaIni),
            fechaFinal: @json($fechaFin),
        }
    </script>
@endsection

@section('scripts')
    <script>
        const atencion = @json($idTipoAtencion);
        $(() => {
            $(`#idTipoAtencion option[value= ${atencion}]`).prop('selected', true);

            if (variablesBlade.fecha == 1 || variablesBlade.fecha == 2 || variablesBlade.fecha == 3 ||
                variablesBlade.fecha == 4 || variablesBlade.fecha == 9 || variablesBlade.fecha == 11) {
                $('#grafico').hide();
            }
        })
    </script>

    <script>
        var options = {
            series: [{
                name: 'Cantidad:',
                data: @json($cantidadesAtenciones)
            }],
            chart: {
                height: 350,
                type: 'bar',
            },
            plotOptions: {
                bar: {
                    columnWidth: '45%',
                    distributed: true,
                }
            },
            dataLabels: {
                enabled: false
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: @json($nombresAtenciones),
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#grafico"), options);
        chart.render();
    </script>



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
            });
        });
    </script>

@stop
