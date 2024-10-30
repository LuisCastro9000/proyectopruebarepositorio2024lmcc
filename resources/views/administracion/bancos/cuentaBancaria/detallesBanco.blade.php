@extends('layouts.app')
@section('title', 'Bancos')
@section('content')
    <style>
        .bg-verde {
            background: #2AB994;
            color: #FFF;
        }
    </style>
    <div class="container">
        {{-- <div class="row page-title clearfix my-3">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Movimientos de Cuenta</h6>
            </div>
            <div class="page-title-rigth">
                <a href="../../bancos/cuentas-bancarias" class="d-block ">
                    <button class="btn btn-secondary btn-sm">Transferencia</button>
                    <a href="../../bancos/cuentas-bancarias" class="d-block ">
                        <button class="btn btn-secondary btn-sm"><i class='bx bx-share fs-20 mr-1'></i>Volver</button>
            </div>
        </div> --}}
        <section class="row my-3">
            <div class="col-12 d-flex justify-content-between">
                <div>
                    <h6 class="page-title-heading mr-0 mr-r-5">Movimientos de Cuenta</h6>
                </div>
                <div>
                    <a href="../../bancos/cuentas-bancarias">
                        <button class="btn btn-secondary btn-sm"><i class='bx bx-share fs-20 mr-1'></i>Volver</button></a>
                </div>
            </div>
        </section>
        @if (session('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif
        <!-- /.page-title -->
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="row">
            <div class="col-md-4 mr-b-20">
                <a href="javascript:void(0);"><button class="btn btn-block btn-success ripple" data-toggle="modal"
                        data-target="#exampleModal" onclick="modal(1)"><i class="list-icon material-icons fs-26">add</i>
                        Ingreso</button></a>
            </div>
            <div class="col-md-4 mr-b-20">
                <a href="javascript:void(0);"><button class="btn btn-block btn-secondary ripple" data-toggle="modal"
                        data-target="#exampleModal" onclick="modal(2)"><i class="list-icon material-icons fs-26">add</i>
                        Salida</button></a>
            </div>
            <div class="col-md-4 mr-b-20">
                <button class="btn btn-block btn-warning d-flex align-self-center" data-toggle="modal"
                    data-target="#modalTransferencia">
                    <i class='bx bx-transfer-alt fs-26 mr-1'></i> Transferencia entre cuentas
                </button>
            </div>
        </div>
        <br>
        <div class="jumbotron fluid bg-white pt-3 pb-2">
            <div class="row pb-2">
                <div class="col-12 col-md-2 text-center text-md-left mb-3 mb-md-0">
                    <a href="{!! url('/reportes/financieros/bancos') !!}">
                        <img width="100px" src="{{ asset('/assets/img/imagenReporteBanco.jpg') }}" alt=""><br>
                        <button class="btn  bg-verde ripple">Ver
                            Reporte</button></a>
                </div>
                <div class="col-12 col-md-10">
                    {!! Form::open([
                        'route' => ['cuentas-bancarias.show', $id],
                        'method' => 'POST',
                        'id' => 'formulario',
                    ]) !!}
                    @csrf
                    @method('GET')
                    <div class="row">
                        <div class="col-12">
                            <label>Seleccionar Fecha</label>
                            <select id="idFecha" class="form-control selectFecha" name="fecha">
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
                    <section id="seccionInputCalendario" class="row mt-4">
                        <article class="col-6 col-md-5">
                            <div id="Inicio" class="text-center">
                                <label class="form-control-label">Desde</label>
                                <div class="input-group">
                                    <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                                        autocomplete="off" onkeydown="return false"
                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                        data-date-end-date="0d">
                                </div>
                            </div>
                        </article>
                        <article class="col-6 col-md-5">
                            <div id="Final" class="text-center">
                                <label class="form-control-label">Hasta</label>
                                <div class="input-group">
                                    <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                                        autocomplete="off" onkeydown="return false"
                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                        data-date-end-date="0d">
                                </div>
                            </div>
                        </article>
                        <article class="col-12 col-md-2 mt-2 mt-md-0 align-self-end">
                            <button type="submit" id="botonConsultar" class="btn btn-primary">Consultar</button>
                        </article>
                    </section>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>
        <!-- Tabla de datos-->
        <div class="row">
            <div class="col-md-12 widget-holder">
                <div class="widget-bg">
                    <div class="widget-body clearfix">
                        <section id="seccionTabla">
                            @include('administracion.bancos.cuentaBancaria.tablaDetalleBanco')
                        </section>
                    </div>
                </div>
            </div>
        </div>
        <!-- Fin -->
        <!-- /.widget-list -->
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            {!! Form::open([
                'route' => 'cuentas-bancarias.store-ingreso-salida',
                'method' => 'POST',
                'files' => true,
                'id' => 'myform',
            ]) !!}
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel"></h5>
                </div>
                <div class="modal-body p-4">
                    <div class="row">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="montoActual">Monto Actual</label>
                                <input class="form-control" id="montoActual" name="montoActual" readonly required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="numeroOperacion">Número de Operación</label>
                                <input class="form-control" id="numeroOperacion" name="numeroOperacion" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="detalle">Detalle</label>
                                <input class="form-control" id="detalle" name="detalle" required />
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <label for="monto">Monto</label>
                                <input class="form-control" id="monto" name="monto" type="number" min="0.01"
                                    step="any" required />
                            </div>
                        </div>

                        <div class="col-12">
                            <div id="Inicio" class="form-group">
                                <div class=" my-1">
                                    <div class="custom-control custom-checkbox ">
                                        <input type="checkbox" class="custom-control-input" id="checkActivarFecha">
                                        <label class="custom-control-label" for="checkActivarFecha" id="movimientoBanco">
                                        </label>
                                    </div>
                                </div>
                                <div class="input-group">
                                    <input id="date" type="text" class="form-control datepicker "
                                        name="fechaAnterior" disabled='true'
                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                        autocomplete="off" onkeydown="return false" data-date-end-date="0d">
                                </div>
                            </div>
                        </div>
                        <div class="col-12 mt-2">
                            <section id="mensajeFecha"></section>
                            <div class="custom-control custom-checkbox" id="contenedorCheckGasto">
                                <input type="checkbox" class="custom-control-input" id="checkActivarRegistroGastos"
                                    name="checkGasto">
                                <label class="custom-control-label" for="checkActivarRegistroGastos"
                                    id="movimientoBanco">Registrar simultáneamente esta salida como un gasto
                                </label>
                            </div>
                        </div>
                    </div>
                    {{-- Nuevo Codigo --}}
                    <section id="contenedorGastos" class="row d-none mt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="sucursal">Seleccionar Tipo de Gasto</label>
                                <select id="tipoGasto" class="form-control" name="tipoGasto">
                                    <option value="0">-</option>
                                    <option value="1">Fijo</option>
                                    <option value="2">Variable</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="form-group">
                                <small class="text-muted"><strong>SELECCIONE ITEM</strong></small>
                                <select id="listaGastos" class="m-b-10 form-control select2-hidden-accessible"
                                    name="listaGastos" data-placeholder="Seleccionar Opción" data-toggle="select2"
                                    tabindex="-1" aria-hidden="true">
                                </select>
                                <span class="text-danger font-size">{{ $errors->first('listaGastos') }}</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea id="observacion" class="form-control" rows="4" name="observacion"></textarea>
                                <span class="text-danger font-size">{{ $errors->first('observacion') }}</span>
                            </div>
                        </div>
                    </section>
                    {{-- Fin --}}
                    <input class="form-control" id="tipoMovimiento" name="tipoMovimiento" hidden />
                    <input class="form-control" id="idBanco" name="idBanco" hidden />
                </div>
                <div class="modal-footer">
                    <button id="btnRegistrar" type="submit" class="btn btn-primary">Aceptar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="text-success">Reportes de Movimientos de Cuentas Bancarias</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-14 negrita">Reporte del Mes</label>
                        <p class="fs-15 negrita">Se mostraran solo los movimientos de este mes....... Si desea
                            ver otros
                            movimientos utilize los filtros</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions btn-list mt-3">
                        <button class="btn btn-info" type="button" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalTransferencia" tabindex="-1" role="dialog" aria-labelledby="exampleModal3Label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formRegitrarTransferencia">
                    <div class="modal-header d-flex justify-content-center">
                        <h6 class="modal-title">Realizar Transferencia</h6>
                    </div>
                    <div class="modal-body">
                        <div class="m-0 badge badge-warning w-100">
                            <strong id="textoMontoActual" class="fs-18 d-block pb-1">{{ $cuentaCorriente->MontoActual }}
                            </strong>
                            <span>Saldo Actual</span>
                        </div>
                        <br><br>
                        <input type="hidden" id="inputNumeroCuentaOrigen" name="inputNumeroCuentaOrigen"
                            value="{{ $cuentaCorriente->NumeroCuenta }}">
                        <select id="selectBancoDestino" class="form-control" name="selectBancoDestino">
                            <option value="0">Seleccionar número de Cuenta</option>
                            @foreach ($allCuentasCorrientes as $cuenta)
                                <option value="{{ $cuenta->IdBanco }}" data-numero-cuenta="{{ $cuenta->NumeroCuenta }}">
                                    {{ $cuenta->Banco . ' - ' . $cuenta->NumeroCuenta }}
                                </option>
                            @endforeach
                        </select>
                        <br>
                        <input id="inputMontoTranferencia" class="form-control text-center input-disabled" type="number"
                            name="inputMontoTranferencia" placeholder="Ingrese el Monto" disabled>
                        <input type="hidden" id="inputBancoOrigen" name="inputBancoOrigen"
                            value="{{ $cuentaCorriente->IdBanco }}">
                        <br>
                        <input id="inputNumeroOperacion" class="form-control text-center input-disabled" type="text"
                            name="inputNumeroOperacion" placeholder="Ingrese Numero de operación" disabled>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Transferir</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalEditarRegistro" tabindex="-1" role="dialog" aria-labelledby="exampleModal3Label"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form id="formRegitrarTransferencia">
                    <div class="modal-header d-flex justify-content-center">
                        <h6 id="tituloEditarRegistro" class="modal-title"></h6>
                    </div>
                    <div class="modal-body">
                        <section class="text-center badge-success">
                            <strong id="subTituloEditarRegistro"></strong>
                            <span id="valorEditarRegistro" class="fs-30 font-weight-bold d-block">
                            </span>
                        </section>
                        <br>
                        <div class="form-group text-center">
                            <input type="number" class="form-control text-center" id="nuevoMontoEditarRegistro"
                                name="nuevoMonto">
                            <label for="nuevoMontoEditarRegistro" id="labelEditarRegistro"></label>
                            <input type="text" class="form-control text-center" id="detalleEditarRegistro"
                                name="detalle">
                            <label for="detalleEditarRegistro">Editar Detalle</label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary" id="btnActualizarRegistro">Actualizar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>


    {{-- Modal validar clave supervisor --}}
    <div class="modal fade" id="modalValidar" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenteredLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <form id="formValidarClaveSupervisor" action="{{ route('validarClaveSupervisor') }}">
                        <div class="form-group text-center mt-3">
                            <input id="id" class="d-none" type="text" value="">
                            <label for="formGroupExampleInput">Ingrese la clave Supervisor para comprobar Permiso</label>
                            <input id="password" type="text" class="form-control text-center" placeholder="********"
                                style="-webkit-text-security: disc;" autocomplete="off">
                            <span class=" d-block text-danger font-weight-bold py-2" id="textoMensaje"></span>

                            <x-buttonLoader id='btnValidar'>
                                @slot('textoBoton', 'Comprobar Permiso')
                                @slot('textoLoader', 'Validando')
                            </x-buttonLoader>
                            <hr>
                            <div class="text-center mt-2">
                                @if (Session::get('Cliente') == 1)
                                    <a href="{{ url('cambiar-contrasena') }}">¿Has olvidado la contraseña?</a>
                                @else
                                    <span class="text-danger">Si no recuerda la clave, inicie Sesión como Administrador y
                                        <br>
                                        <b>proceda a Actualizar</b></span>
                                @endif
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin --}}

@stop

@section('scripts')
    <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>

    <script>
        // esta variable sera actulizada, al obtener nuevos datos con los filtros de fechas
        let detallesBanco = @json($detallesCuentaCorriente);
        let registroEncontrado = '';

        $(document).on('click', (event) => {
            const btnEditar = $(event.target).closest('.btnEditarConClaveSupervisor');
            if (btnEditar.length) {
                $('#password').val('');
                $('#nuevoMontoEditarRegistro').val('');
                const idRegistro = btnEditar.data('idRegistro');
                registroEncontrado = Object.values(detallesBanco).find(registro => registro.IdBancoDetalles ===
                    idRegistro);
                $('#tituloEditarRegistro').text(`Editar ${registroEncontrado.TipoMovimiento}`);
                $('#modalValidar').modal('show');
                $('#detalleEditarRegistro').val(registroEncontrado.Detalle);
                if (registroEncontrado.TipoMovimiento === 'Registro Ingreso') {
                    $('#valorEditarRegistro').text(registroEncontrado.Entrada);
                    $('#subTituloEditarRegistro').text('Ingreso Actual');
                    $('#labelEditarRegistro').text('Nuevo Ingreso');
                }
                if (registroEncontrado.TipoMovimiento === 'Registro Salida') {
                    $('#valorEditarRegistro').text(registroEncontrado.Salida);
                    $('#subTituloEditarRegistro').text('Salida Actual');
                    $('#labelEditarRegistro').text('Nueva Salida');
                }
            }
        });

        $('#btnActualizarRegistro').on('click', (event) => {
            event.preventDefault();
            $('#modalEditarRegistro').modal('hide');
            const montoActual = $('#valorEditarRegistro').text();
            const montoNuevo = $('#nuevoMontoEditarRegistro').val();
            if (montoNuevo === '') {
                return mensajeValidacion("Monto Vacio?", "Se olvido de ingresar el monto");
            }
            if (parseFloat(montoActual) === parseFloat(montoNuevo)) {
                return mensajeValidacion("Monto Igual?", "El nuevo monto debe ser diferente al actual");
            }
            if (parseFloat(montoActual) > parseFloat(montoNuevo)) {
                montoParaActualizar = parseFloat(montoActual) - parseFloat(montoNuevo);
            }
            if (parseFloat(montoActual) < parseFloat(montoNuevo)) {
                montoParaActualizar = parseFloat(montoNuevo) - parseFloat(montoActual);
            }
            utilidades.showLoadingOverlay('Actualizando Registro ....');
            $.ajax({
                type: "POST",
                url: "{{ route('cuentas-bancarias.update-ingreso-salida') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    idRegistro: registroEncontrado.IdBancoDetalles,
                    tipoMovimiento: registroEncontrado.TipoMovimiento,
                    montoParaActualizar: montoParaActualizar,
                    montoActual: montoActual,
                    montoNuevo: montoNuevo,
                    fechaPago: registroEncontrado.FechaPago,
                    idBanco: registroEncontrado.IdBanco,
                    detalle: $('#detalleEditarRegistro').val() == '' ? registroEncontrado.Detalle : $(
                        '#detalleEditarRegistro').val(),
                    fecha: $('#idFecha').val(),
                    fechaIni: $('#datepickerIni').val(),
                    fechaFin: $('#datepickerFin').val()
                },
                success: function(data) {
                    utilidades.hideLoadingOverlay();
                    if (data.respuesta === 'error') {
                        swal({
                            title: "Saldo Insuficiente",
                            text: data.mensaje,
                            icon: "error",
                            button: "Entendido!",
                        });
                    } else {
                        detallesBanco = data.detallesCuentaCorriente;
                        $('#seccionTabla').html(data.renderView);
                        inicializarTabla($('#table'));
                    }
                }
            })
        })

        $('#btnValidar').on('click', (event) => {
            event.preventDefault();
            utilidades.validarClaveSupervisor(function(response) {
                $('#modalEditarRegistro').modal('show');
            });
        })

        function mensajeValidacion(titulo, texto) {
            swal({
                    title: titulo,
                    text: texto,
                    icon: "error",
                    button: {
                        text: "Entendido",
                    },
                    dangerMode: true,
                })
                .then((willDelete) => {
                    $('#modalEditarRegistro').modal('show');
                });
        }
    </script>

    <script>
        let urlConsultarBanco = '';
        $(document).ready(function() {
            urlConsultarBanco = $('#formulario').attr('action');
            inicializarTabla($('#table'));
            var bandModal = @json($idTipo);
            var fecha = @json($fecha);
            var fechaIni = @json($fechaInicial);
            var fechaFin = @json($fechaFinal);

            if (bandModal == '') {
                $("#mostrarmodal").modal("show");
            }
            inicializarElementosFechas(fecha);
        })

        $('#selectBancoDestino').change(function() {
            const valueFecha = $(this).val();
            if (valueFecha == 0) {
                $('.input-disabled').attr('disabled', true);
                $('.input-disabled').val('');

            } else {
                $('.input-disabled').removeAttr('disabled', false);
            }
        })
        $('#formRegitrarTransferencia').submit(function(e) {
            e.preventDefault();
            showLoadingOverlay('Realizando la transferencia. <br> Espere un momento por favor ...')
            $.ajax({
                type: "POST",
                url: "{{ route('cuentas-bancarias.store-transferencia') }}",
                data: {
                    "_token": "{{ csrf_token() }}",
                    'numeroCuentaDestino': $('#selectBancoDestino option:selected').data('numeroCuenta'),
                    'inputNumeroCuentaOrigen': $('#inputNumeroCuentaOrigen').val(),
                    'inputNumeroOperacion': $('#inputNumeroOperacion').val(),
                    'inputMontoTranferencia': $('#inputMontoTranferencia').val(),
                    'selectBancoDestino': $('#selectBancoDestino').val(),
                    'inputBancoOrigen': $('#inputBancoOrigen').val()
                },
                success: function(data) {
                    if (data.respuesta === 'success') {
                        $('#inputMontoTranferencia').val('');
                        $('#inputNumeroOperacion').val('');
                        $('#selectBancoDestino').prop('selectedIndex', 0);
                        $('#modalTransferencia').modal('hide');
                        $('#textoMontoActual').empty().text(data.montoActual);
                        swal("Registro Exitoso!", data.mensaje, "success");
                    }
                    if (data.respuesta === 'error') {
                        $('#modalTransferencia').modal('hide');
                        swal("Registro Fallido!", data.mensaje, "error").then((value) => {
                            $('#modalTransferencia').modal('show');
                        });
                    }
                    hideLoadingOverlay();
                }
            })
        })

        $('#botonConsultar').click(function(e) {
            e.preventDefault();
            obtenerDatosAjax();
        })

        $('.selectFecha').change(function() {
            const valueFecha = $(this).val();
            if (valueFecha == 9) {
                showSeccionCalendario();
            } else {
                hideSeccionCalendario();
                obtenerDatosAjax();
            }
        })

        const obtenerDatosAjax = () => {
            showLoadingOverlay();
            $.ajax({
                type: 'GET',
                url: urlConsultarBanco,
                data: {
                    'fecha': $('#idFecha').val(),
                    'fechaIni': $('#datepickerIni').val(),
                    'fechaFin': $('#datepickerFin').val(),
                },
                success: function(data) {
                    detallesBanco = data.detallesCuentaCorriente;
                    $('#seccionTabla').html(data.renderView);
                    hideLoadingOverlay();
                    inicializarTabla($('#table'));
                }
            });
        }

        const inicializarTabla = (tabla) => {
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
    <script>
        document.addEventListener("keypress", function(event) {
            if (event.target.type === "number") {
                isNumeroEnteroOdecimal(event);
            }
        });
    </script>
    <script>
        $(function() {
            $('#btnRegistrar').on('click', function() {
                var myForm = $("form#myform");
                if (myForm) {
                    $(this).attr('disabled', true);
                    $(myForm).submit();
                }
            });

        });
    </script>
    <script>
        $(function() {
            var date = new Date();
            date.setDate(date.getDate());
            $('.inputFecha').datepicker({
                endDate: date,
                changeMonth: false,
                format: "dd/mm/yyyy",
                autoclose: true
            });
        });
    </script>

    <script>
        $('#detalle').keyup(function() {
            const detalle = $(this).val();
            $('#observacion').val(detalle);
        });
        // Nuevo codigo Mostrar Seccion Gastos
        $("#checkActivarFecha").click(function() {
            $('#date').removeAttr('disabled');
            if ($('#checkActivarFecha').is(':checked') != true) {
                $("#date").val("");
                $('#date').attr('disabled', "true");
                $('#textoMensaje').remove();
            }
        });

        $("#checkActivarRegistroGastos").click(function() {
            if ($('#checkActivarRegistroGastos').is(':checked')) {
                $('#contenedorGastos').removeClass('d-none');
                $('#checkActivarRegistroGastos').attr("value", "1");
            } else {
                $('#checkActivarRegistroGastos').attr("value", "0");
                $('#contenedorGastos').addClass('d-none')
                $('#textoMensaje').remove()
            }
        });

        $("#tipoGasto").on('change', function() {
            var tipo = $("#tipoGasto").val();
            $.ajax({
                type: 'get',
                url: "{{ route('cuentas-bancarias.obtener-gastos') }}",
                data: {
                    'tipo': tipo
                },
                success: function(data) {
                    console.log('info');
                    console.log(data);
                    $('#listaGastos option').remove();
                    for (var i = 0; i < data.length; i++) {
                        $('#listaGastos').append('<option value="' + data[i][
                                "IdListaGastos"
                            ] + '">' + data[i]["Descripcion"] +
                            '</option>');
                    }
                }
            });
        });
        // Fin
    </script>

    <script>
        function modal(id) {
            if (id == 1) {
                $("#exampleModalLabel").text("Registrar Ingreso");
                $("#movimientoBanco").text("Cambiar Fecha de Ingreso");
                $('#checkActivarRegistroGastos').prop("checked", false);
                $('#checkActivarFecha').prop("checked", false);
                $('#contenedorCheckGasto').addClass("d-none");
                $('#contenedorGastos').addClass('d-none')
                $('#textoMensaje').remove()
            } else {
                $("#exampleModalLabel").text("Registrar Salida");
                $("#movimientoBanco").text("Cambiar Fecha de Salida");
                $('#contenedorCheckGasto').removeClass("d-none");
            }

            var cuentaCorriente = <?php echo json_encode($cuentaCorriente); ?>;
            $("#montoActual").val(cuentaCorriente["MontoActual"]);
            $("#tipoMovimiento").val(id);
            $("#idBanco").val(cuentaCorriente["IdBanco"]);
        }
        // $(function() {
        //     $("#aceptar").on("click", function(e) {
        //         $.ajax({
        //             type: 'post',
        //             url: 'registrar-ingreso-salida',
        //             data: {
        //                 "_token": "{{ csrf_token() }}",
        //                 "tipoMovimiento": $("#tipoMovimiento").val(),
        //                 "idBanco": $("#idBanco").val(),
        //                 "numeroOperacion": $("#numeroOperacion").val(),
        //                 "detalle": $("#detalle").val(),
        //                 "montoActual": $("#montoActual").val(),
        //                 "monto": $("#monto").val()
        //             },
        //             success: function(data) {
        //                 if (data[0] == 'success') {
        //                     alert(data[1]);
        //                     window.location = '../detalles-banco/' + $("#idBanco").val();
        //                 } else {
        //                     alert(data[1]);
        //                 }
        //             }
        //         });
        //     });
        // });
    </script>
@stop
