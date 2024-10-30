@extends('layouts.app')
@section('title', 'Notificar Mantenimiento')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="container m-x-auto pl-3">
        <div class="row mt-4">
            <div class="col">
                <section
                    class="d-flex justify-content-center flex-wrap align-items-center justify-content-md-between flex-column flex-sm-row">
                    <article>
                        <h6 class="page-title-heading mr-0 mr-r-5">Vehículos para Notificar Mantenimiento</h6>
                    </article>
                    <article>
                        <a class="p-0" target="_blank"
                            href='{{ route('notificar-mantenimiento.excel', [$fecha, $ini, $fin]) }}'>
                            <span class="btn bg-excel ripple">
                                <i class="list-icon material-icons fs-20">explicit</i>XCEL
                            </span>
                        </a>
                        <a class="mr-md-4" href="https://www.youtube.com/watch?v=Lr-xcZW_X40" target="_blank">
                            <span class="btn btn-autocontrol-naranja ripple text-white">
                                Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                            </span>
                        </a>
                    </article>
                </section>
            </div>
        </div>
        <br>
        <div class="row justify-content-center mt-4">
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #28A745;">
                    <strong class="fs-14 text-white">Km Inicial</strong>
                    <br>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #148CBA;">
                    <strong class="fs-14 text-white">Km Intermedio</strong>
                    <br>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #FFC107;">
                    <strong class="fs-14 text-white">Km Aproximado</strong>
                    <br>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #ff0000;">
                    <strong class="fs-14 text-white">Km Alcanzado</strong>
                    <br>
                </div>
            </div>
        </div>
        <br>
        <section id="consultar">
            {!! Form::open([
                'route' => 'notificar-mantenimiento.consultar',
                'method' => 'POTS',
                'id' => 'formularioConsultar',
            ]) !!}
            {{ csrf_field() }}
            @method('GET')
            <section class="row">
                <article class="col-md-12">
                    <x-selectorFiltrosFechas></x-selectorFiltrosFechas>
                </article>
                {{-- <article class="col-md-4 mb-1 mb-lg-4 order-0">
                    <div id="Inicio" class="form-group text-center">
                        <label class="form-control-label">Desde</label>
                        <div class="input-group">
                            <input id="datepickerIni" type="text" class="form-control datepicker py-2" name="fechaIni"
                                autocomplete="off" onkeydown="return false"
                                data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' data-date-end-date="0d">
                        </div>
                    </div>
                </article>
                <article class="col-md-4 mb-1 mb-lg-4 order-1">
                    <div id="Final" class="form-group text-center">
                        <label class="form-control-label">Hasta</label>
                        <div class="input-group">
                            <input id="datepickerFin" type="text" class="form-control datepicker py-2" name="fechaFin"
                                autocomplete="off" onkeydown="return false"
                                data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' data-date-end-date="0d">
                        </div>
                    </div>
                </article> --}}
            </section>
            <x-inputFechasPersonalizadas></x-inputFechasPersonalizadas>
            {!! Form::close() !!}
        </section>
        <div class="row mt-4">
            <div class=" col-12 widget-holder">
                <div class="widget-bg">
                    <div class="widget-body clearfix">
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary text-center">
                                    <th scope="col">Placa</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Próxima Fecha de Atención</th>
                                    <th scope="col">próximo Mantenimiento</th>
                                    <th scope="col">Días Restantes</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listaSalidaVehicular as $vehiculo)
                                    <tr>
                                        <td>{{ $vehiculo->PlacaVehiculo }}</td>
                                        <td class="text-center">{{ $vehiculo->Nombre }}</td>
                                        <td class="text-center">{{ $vehiculo->ProximaFecha }}</td>
                                        <td class="text-center">{{ $vehiculo->ProximoMantenimiento }}</td>
                                        <td class="text-center">{{ $vehiculo->DiasRestantes }} Días</td>
                                        <td class="text-center"><span class="badge text-light"
                                                style="background-color: {{ $vehiculo->ColorEstado }}">{{ $vehiculo->Estado }}</span>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

<!-- Etas variables son usadas en el archivo assets/js/utilidades/utilidades.js-->
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
                        [4, "asc"]
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

            // Fecha
            // let fecha = @json($fecha);
            // if (fecha == 9) {
            //     $('#Inicio').show();
            //     $('#Final').show();
            //     var fechaIni = @json($fechaInicial);
            //     var fechaFin = @json($fechaFinal);
            //     $('#datepickerIni').val(fechaIni);
            //     $('#datepickerFin').val(fechaFin);
            // } else {
            //     $('#Inicio').hide();
            //     $('#Final').hide();
            //     $('#datepickerIni').val('');
            //     $('#datepickerFin').val('');
            // }
            // $('#idFecha option[value=' + fecha + ']').prop('selected', true);
        });

        // $("#idFecha").on('change', function() {
        //     var valor = $("#idFecha").val();
        //     if (valor == "9") {
        //         $('#Inicio').show();
        //         $('#Final').show();
        //     } else {
        //         $('#Inicio').hide();
        //         $('#Final').hide();
        //         $('#datepickerIni').val('');
        //         $('#datepickerFin').val('');
        //     }
        // });
        // $(document).on('click', function(e) {
        //     if (e.target.matches('.btnLoader') || e.target.matches('.btnLoader *')) {
        //         showButtonLoader();
        //         $('form').submit();
        //     }
        // });
    </script>
@stop
