@extends('layouts.app')
@section('title', 'Panel-Facturación')
@section('content')
    <style>
        .btn-administracion {
            background-color: mediumseagreen;
            color: #ffff;
            cursor: pointer;
        }

        .btn-facturacion {
            background-color: #0095E8;
            color: #ffff;
            cursor: pointer;
        }

        .btn-vehicular {
            background-color: #f8973d;
            color: #ffff;
        }

        .btn-vehicular:hover {
            background-color: #F98518;
        }
    </style>
    <div class="container">
        <section
            class="jumbotron jumbotron-fluid p-4 d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-md-row my-4 border rounded">
            <h6 class="font-weight-bold text-center">Área Facturación</h6>
            <article>
                <div class="icon d-flex  justify-content-center flex-wrap">
                    <a href=" {{ url('panel') }}" class="mr-1">
                        <button type="button" class="btn btn-secondary btn-sm"><i
                                class='bx bx-share fs-20 icono-vehicular'></i> Ir Panel
                            Principal</button>
                    </a>
                    @if ($permisosBotones->contains('Nombre', 'Area Administracion'))
                        <a href=" {{ url('area-administrativa') }}">
                            <button type="button" class="btn btn-administracion btn-sm"><i
                                    class='bx bxs-user-detail fs-20  mr-1'></i> Area Administrativa</button>
                        </a>
                    @endif
                    @if ($permisosBotones->contains('Nombre', 'Area Vehicular'))
                        <a href=" {{ url('area-vehicular') }}">
                            <button type="button" class="btn btn-vehicular btn-sm ml-2"><i
                                    class='bx bxs-car-mechanic fs-20 mr-1'></i> Area Vehicular</button>
                        </a>
                    @endif
                </div>
            </article>
        </section>

        <section class="row my-4">
            <div class="col-12">
                <label>Selecione Tipo Documento</label>
                <select id="selectorDocumento" class="custom-select" name="anio">
                    <option value="2">Factura</option>
                    <option value="1">Resumenes de boletas, bajas y NC</option>
                    <option value="3">Guía Remisión</option>
                    <option value="4">Boleta Pendientes</option>
                </select>
            </div>
        </section>

        <section class="mt-4 jumbotron bg-jumbotron--white">
            <article class="d-flex justify-content-center justify-content-md-between flex-wrap">
                <h6 class="text-muted text-left">Documentos </h6>
                <ul class="nav nav-pills mb-3 lista" id="pills-tab" role="tablist">
                    @foreach ($meses as $mes)
                        @if ($mes->NombreMes == $nombreMesActual)
                            <li class="nav-item">
                                <a class="nav-link active btnMes" id="tab-{{ $mes->NombreMes }}" data-toggle="pill"
                                    href="#btn-{{ $mes->NombreMes }}" role="tab"
                                    aria-controls="btn-{{ $mes->NombreMes }}" aria-selected="true"
                                    data-fechainicial="{{ $mes->FechaInicial }}"
                                    data-fechafinal="{{ $mes->FechaFinal }}">{{ $mes->NombreMes }}</a>
                            @else
                            <li class="nav-item">
                                <a class="nav-link btnMes" id="tab-{{ $mes->NombreMes }}" data-toggle="pill"
                                    href="#btn-{{ $mes->NombreMes }}" role="tab"
                                    aria-controls="btn-{{ $mes->NombreMes }}" aria-selected="false"
                                    data-fechainicial="{{ $mes->FechaInicial }}"
                                    data-fechafinal="{{ $mes->FechaFinal }}">{{ $mes->NombreMes }}</a>
                            </li>
                        @endif
                    @endforeach
                </ul>
            </article>
            <hr>
            <div class="tab-content mt-4" id="pills-tabContent">
                @include('areas.areaFacturacion._tablaRespuestaAjax')
            </div>
        </section>


    </div>
@stop

@section('scripts')

    <script>
        $('#selectorDocumento').change(() => {
            let fechaInicial = '';
            let fechaFinal = '';
            $('.lista li a').each(function() {
                var a = $(this);
                console.log($(this));
                if (a.hasClass('active')) {
                    fechaInicial = a.attr("data-fechainicial");
                    fechaFinal = a.attr("data-fechaFinal");
                }
            });
            const idDocumento = $('#selectorDocumento').val();
            ejecutarAjax(idDocumento, fechaInicial, fechaFinal);
        })


        $(document).ready(function() {
            $(".btnMes").click(function(e) {
                const fechaInicial = $(this).data('fechainicial');
                const fechaFinal = $(this).data('fechafinal');
                const idDocumento = $('#selectorDocumento').val();
                ejecutarAjax(idDocumento, fechaInicial, fechaFinal);
            });
        });

        const ejecutarAjax = ((idDocumento, fechaInicial, fechaFinal) => {
            $.showLoading({
                name: 'circle-fade',
            });
            $.ajax({
                url: "{{ route('getDatosDocumentosFacturacion') }}",
                method: 'GET',
                data: {
                    idDocumento: idDocumento,
                    fechaInicial: fechaInicial,
                    fechaFinal: fechaFinal
                },
                success: function(data) {
                    $('.datosTabla').html(data);
                    $.hideLoading();
                    cargarDataTable();
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        })

        const cargarDataTable = () => {
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
        }
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                cargarDataTable();
            });
        });
    </script>
@stop
