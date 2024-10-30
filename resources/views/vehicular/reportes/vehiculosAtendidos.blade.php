@extends('layouts.app')
@section('title', 'Vehiculos Atendidos')
@section('content')
    <style>
        HTML CSS JSResult Skip Results Iframe EDIT ON .loader-section {
            width: 100vw;
            height: 100vh;
            max-width: 100%;
            position: fixed;
            top: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            background-color: #ffffff;
            z-index: 999;
            transition: all 1s 1s ease-out;
            opacity: 1;
        }

        .loaded {
            opacity: 0;
            z-index: -1;
        }

        .loader {
            width: 30px;
            height: 30px;
            border: 5px solid #623ddb;
            border-bottom-color: transparent;
            border-radius: 50%;
            display: inline-block;
            box-sizing: border-box;
            animation: rotation 1s linear infinite;
        }

        @keyframes rotation {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
    <div class="container">
        <section class="d-flex  justify-content-between align-items-center my-4">
            <h6>Vehículos Atendidos</h6>
            <article>
                <a href="https://www.youtube.com/watch?v=sRzdjLL-h7c" target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white">
                        Video Instructivo <i class="list-icon material-icons fs-20 color-icon">videocam</i>
                    </span>
                </a>
                <a class="p-0" target="_blank" href='{{ route('exportarExcel', $mecanico) }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
            </article>
        </section>

        {!! Form::open([
            'url' => 'vehicular/reportes/vehiculos-atendidos',
            'method' => 'POST',
            'id' => 'formularioConsultar',
        ]) !!}
        {{ csrf_field() }}
        <section class="row">
            <article class="col-12">
                {{-- onchange="this.form.submit()" --}}
                <div class="form-group">
                    <label>Mecánico / Operador</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="mecanico" name="mecanico"
                        data-placeholder="Seleccione Mecanico" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Mecanico</option>
                        @if ($mecanico == 'Generico')
                            <option value="Generico" selected>Generico</option>
                        @else
                            <option value="Generico">Generico</option>
                        @endif
                        @foreach ($mecanicos as $listaMecanico)
                            @if ($mecanico == $listaMecanico->IdOperario)
                                <option value="{{ $listaMecanico->IdOperario }}" selected>{{ $listaMecanico->Nombres }}
                                </option>
                            @else
                                <option value="{{ $listaMecanico->IdOperario }}">{{ $listaMecanico->Nombres }}</option>
                            @endif
                        @endforeach
                    </select>
                </div>
            </article>
            <div class="loader-section m-auto mt-4" id="loader">
                <span class="loader"></span>
            </div>


            {{-- <article class="col-12 col-md-4">
                <div class="card bg-celeste">
                    <div class="card-body text-center">
                        <span class="d-block fs-20 font-weight-bolder text-uppercase">Vehículo en Taller</span>
                        <button class="btnConsultar" data-nombre-Estado="En Taller" data-id-estado="2"
                            style="font-size: 75%;border-radius:0.25em 0.4em; font-weight:800; outline:none;border: none; background-color:darkgrey;color:beige;cursor:pointer">Consultar</button>
                    </div>
                </div>
            </article> --}}

            {{-- <article class="col-12 col-md-4">
                <div class="card bg-celeste">
                    <div class="card-body text-center">
                        <span class="d-block fs-20 font-weight-bolder text-uppercase">Vehículo Facturado</span>
                        <button class="btnConsultar" data-nombre-Estado="Facturado" data-id-estado="3"
                            style="font-size: 75%;border-radius:0.25em 0.4em; font-weight:800; outline:none;border: none; background-color:darkgrey;color:beige;cursor:pointer">Consultar</button>
                    </div>
                </div>
            </article> --}}

            {{-- <article class="col-12 col-md-4">
                <div class="card bg-celeste">
                    <div class="card-body text-center">
                        <span class="d-block fs-20 font-weight-bolder text-uppercase">Vehículo Listo Para Entrega</span>
                        <button class="btnConsultar" data-nombre-Estado="X Entregar" data-id-estado="4"
                            style="font-size: 75%;border-radius:0.25em 0.4em; font-weight:800; outline:none;border: none; background-color:darkgrey;color:beige;cursor:pointer">Consultar</button>
                    </div>
                </div>
            </article> --}}
            {!! Form::close() !!}
        </section>
        @if (count($datosVehiculos) >= 1)
            <section class="jumbotron bg-jumbotron--white">
                <article class="col-12 col-md-4 m-auto">
                    <div class="card bg-celeste">
                        <div class="card-body text-center">
                            <span
                                class="fs-26 font-weight-bold">{{ $atencionesGraficoMesActual->TotalAtenciones ?? 0 }}</span>
                            <span class="fs-16 d-block">Vehiculos Atendidos <br>del Mes Actual</span>
                        </div>
                    </div>
                </article>
                <div class="mt-5">
                    <div id="chart">
                    </div>
                </div>
            </section>
            <section class="jumbotron bg-jumbotron--white">
                <table id="table" class="table table-responsive-sm" style="width:100%">
                    <thead>
                        <tr class="bg-primary">
                            <th scope="col">Fecha</th>
                            <th scope="col">Cliente</th>
                            <th scope="col">Placa</th>
                            <th scope="col">RUC/DNI</th>
                            <th scope="col">Mecánico</th>
                            <th scope="col">Tipo Atención</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datosVehiculos as $vehiculo)
                            <tr>
                                <td>{{ $vehiculo->FechaCreacion }}</td>
                                <td>{{ $vehiculo->RazonSocial }}</td>
                                <td>{{ $vehiculo->PlacaVehiculo }}</td>
                                <td>{{ $vehiculo->NumeroDocumento }}</td>
                                <td>
                                    @if ($vehiculo->IdOperario != 0)
                                        {{ $vehiculo->NombreOperario }}
                                    @else
                                        Genérico
                                    @endif
                                </td>
                                <td>{{ $vehiculo->TipoAtencion }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </section>
        @else
            <br><br><br>
            <div class="text-center fs-20 text-danger">No Hay resultados..</div>
        @endif

        {{-- @include('vehicular.reportes.pruebaPaginacion') --}}

    </div>
@stop

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#mecanico').on('change', function() {
                $('#loader').show();
                $('#formularioConsultar').submit();
            });
        });
        // $(document).ready(function() {
        //     $('#formularioConsultar').submit(function(e) {
        //         e.preventDefault();
        //         console.log(e.target);
        //     })
        // })

        document.addEventListener("click", function(event) {
            if (event.target.matches('.btnConsultar')) {
                event.preventDefault();
                const nombreEstado = event.target.dataset.nombreEstado;
                const idEstado = event.target.dataset.idEstado;
                $('#inputIdEstado').val(idEstado);
                $('#inputNombreEstado').val(nombreEstado);
                $('#formularioConsultar').submit();
            }
        })
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

    <script>
        var options = {
            series: [{
                name: 'Cantidad:',
                data: @json($totalAtenciones)
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
                enabled: true
            },
            legend: {
                show: false
            },
            xaxis: {
                categories: @json($mesesAtencionesGrafico),
                labels: {
                    style: {
                        fontSize: '12px'
                    }
                }
            }
        };
        var chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();

        $(function() {
            let idMecanico = @json($mecanico);
            $('#mecanico option[value=' + 54 + ']').attr("selected", true);
            $('#loader').hide();
        })
    </script>

    {{-- <script>
        $('#btnProceso').click(() => {
            alert('estado Proceso')

            $.ajax({
                type: 'GET',
                url: 'vehiculos-atendidos/ajax/vehiculo-por-estado',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'idEstado': 2,
                },
                success: function(data) {
                    console.log(data);
                }

            })
        })
    </script> --}}

    <script>
        // $('.pagination a').click((e) => {
        //     e.preventDefault();
        //     let page = e.target.href.split('page=')[1];
        //     console.log(page);
        //     $.ajax({
        //         type: 'GET',
        //         url: 'vehiculos-atendidos/ajax/vehiculo-por-estado',
        //         // dataType: 'json',
        //         data: {

        //             'page': page,
        //         },
        //         success: function(data) {
        //             // $('.datos').empty();
        //             // data["data"].forEach(element => {
        //             //     $('.datos').append(element.PlacaVehiculo);
        //             //     console.log(element);
        //             // });
        //             // console.log(data);
        //             $('.datos').html(data);
        //         }

        //     })
        // })


        // $(document).on('click', '.pagination a', function(event) {
        //     event.preventDefault();
        //     var page = $(this).attr('href').split('page=')[1];
        //     $.ajax({
        //         type: 'GET',
        //         url: "vehiculos-atendidos/ajax/vehiculo-por-estado?page=" + page,
        //         success: function(data) {
        //             console.log(data);
        //             $('.datos').html(data);
        //         }
        //     });
        // });
    </script>
@stop
