@extends('layouts.app')
@section('title', 'Inventarios de Vehículos')
@section('content')
    <div class="container">
        <div class="row d-flex justify-content-center justify-content-md-between my-4 flex-wrap align-items-center">
            <section>
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Inventario Vehicular</h6>
            </section>
            <section>
                <a class="p-0" target="_blank" href='{{ url("vehicular/exportar-excel/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-26">explicit</i>XCEL
                    </span>
                </a>
                <a href="check-in/create"><button class="btn btn-primary ripple"><i
                            class="list-icon material-icons fs-26">add</i> Crear Inventario</button></a>
            </section>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
            </div>
        @endif
        <br>
        <section class="row justify-content-md-center mb-4">
            <article id="card-bajas" class="col-12 col-lg-4 cursor-pointer">
                <div class="card text-center p-4" style="background-color: #FF3333;color: #ffffff">
                    <span class="card_datos fs-22">{{ $cantInvertariosBaja }}</span>
                    <span class="fs-14 m-auto">Cant. Inventarios Bajas</span>
                </div>
            </article>
            <article id="card-aceptadas" class="col-12 col-lg-4 cursor-pointer">
                <div class="card text-center p-4" style="background-color: #06D6A0;color: #ffffff">
                    <span class="card_datos fs-22">{{ $cantInvertariosAceptado }}</span>
                    <span class="fs-14 m-auto">Cant. Inventarios Aceptados</span>
                </div>
            </article>
        </section>
        <form id="formInventario" action="../vehicular/check-in-filtrar" method="POST">
            @csrf
            <div class="row clearfix">
                <div class="col-md-12 mt-4">
                    <x-selectorFiltrosFechas :tipoRangoFechas="'anual'" />
                </div>
            </div>
            <x-inputFechasPersonalizadas :tipoRangoFechas="'anual'" />
        </form>
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <!--<div class="col-md-12 widget-holder">-->
                <div class="widget-bg">
                    <div class="widget-body clearfix">
                        <!--<p>Listado de ventas</p>-->
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary-dark text-white">
                                    <th scope="col">Fecha Emitida</th>
                                    <th scope="col">Asesor Comercial</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Placa</th>
                                    <th scope="col">TipoVehículo</th>
                                    <th scope="col">RUC</th>
                                    <th scope="col">Código</th>
                                    <th scope="col">Estado</th>
                                    <th scope="col">Opciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($inventarios as $inventario)
                                    <tr>
                                        <td>{{ $inventario->FechaEmision }}</td>
                                        <td>{{ $inventario->Nombre }}</td>
                                        <td>{{ $inventario->RazonSocial }}</td>
                                        <td>{{ $inventario->Placa }}</td>
                                        <td class="text-center">{{ $inventario->TipoVehiculo == 1 ? 'Vehículo' : 'Moto' }}
                                        </td>
                                        <td>{{ $inventario->NumeroDocumento }}</td>
                                        <td>{{ $inventario->Serie }} - {{ $inventario->Correlativo }}</td>
                                        <td class="text-center">{!! $inventario->Estado == 'Baja'
                                            ? '<span class="badge badge-danger">Baja</span>'
                                            : '<span class="badge badge-success">Aceptado</span>' !!}</td>
                                        <td>
                                            <a href="../vehicular/CheckIn/documento-generado/{{ $inventario->IdCheckIn }}"><i
                                                    class="list-icon material-icons fs-20">visibility</i></a>
                                            @if ($inventario->Estado != 'Baja')
                                                <a href="javascript:void(0);" title="Editar"
                                                    class=" btnEditarConClaveSupervisor"
                                                    data-id="{{ $inventario->IdCheckIn }}"
                                                    data-ruta-editar="editar-inventario/{{ $inventario->IdCheckIn }}"><i
                                                        class="list-icon material-icons fs-20">edit</i></a>
                                                <form id="formEliminar-{{ $inventario->IdCheckIn }}"
                                                    action="{{ route('checkList.destroy', $inventario->IdCheckIn) }}"
                                                    class="d-inline" method="POST">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" data-id="{{ $inventario->IdCheckIn }}"
                                                        title="Dar Baja"
                                                        class="border-0 bg-transparent p-0 cursor-pointer btn-eliminar"><i
                                                            class="list-icon material-icons text-danger fs-20">do_not_disturb</i></button>
                                                </form>
                                            @endif
                                            <a href="javascript:void(0);" class="btnWhatsApp"
                                                data-celular="{{ $inventario->Telefono }}"
                                                data-routename="{{ route('generarPdfInventario', [$inventario->IdCheckIn, 'whatsapp']) }}"><img
                                                    class="logo-expand" alt="" width="20"
                                                    src="{{ asset('assets/img/whatsapp.png') }}"></a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.widget-body -->
                </div>
                <!-- /.widget-bg -->
                <!--</div>-->
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->

    </div>
    <!-- /.container -->

    {{-- Modal comprobar Permiso --}}
    @include('modal._modalValidaSupervisor')
    {{-- Fin --}}
    {{-- Modal enviar pdf x Whatsapp --}}
    @include('modal._modalEnviarWhatsAppFromIndex')
    {{-- Fin --}}
@stop

<!-- Estas variables son usadas en el archivo assets/js/utilidades/utilidades.js-->
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
    <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script> --}}

    <script>
        $(document).ready(function() {
            $(document).on('click', function(event) {
                const botonObjetivo = $(event.target).closest('.btn-eliminar');
                if (botonObjetivo.length > 0) {
                    const id = botonObjetivo.data('id');
                    event.preventDefault();
                    swal({
                            title: "Confirmación de Eliminación",
                            text: "¿Estás seguro de que deseas dar de baja este inventario? ",
                            icon: "warning",
                            dangerMode: true,
                        })
                        .then((success) => {
                            if (success) {
                                utilidades.showLoadingOverlay();
                                $(`#formEliminar-${id}`).submit();
                            }
                        });
                }
            })

            $('#card-bajas').click(function() {
                $('#table').DataTable().search('Baja').draw();
            })
            $('#card-aceptadas').click(function() {
                $('#table').DataTable().search('Aceptado').draw();
            })

        })
    </script>
    <script>
        $(function() {
            var bandModal = <?php echo json_encode($modal); ?>;

            if (bandModal == 0) {
                $("#mostrarmodal").modal("show");
            }
        });

        function redondeo(num) {
            if (!num || num == 'NaN') return '-';
            if (num == 'Infinity') return '&#x221e;';
            num = num.toString().replace(/\$|\,/g, '');
            if (isNaN(num))
                num = "0";
            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num * 100 + 0.50000000001);
            cents = num % 100;
            num = Math.floor(num / 100).toString();
            if (cents < 10)
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
            return (((sign) ? '' : '-') + num + '.' + cents);
        }
    </script>
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
@stop
