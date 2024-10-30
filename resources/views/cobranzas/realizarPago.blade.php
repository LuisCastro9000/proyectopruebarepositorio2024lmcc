@extends('layouts.app')
@section('title', 'Realizar Pago')
@section('content')
    <div class="container">
        @if (session('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif

    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row mt-4">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            <section class="w-100 px-4 m-auto badge badge-success  rounded pt-3 pb-1 my-4">
                                <span class="fs-30">
                                    {{ $selectCuota->TotalDeuda }} </span>
                                <p class="font-weight-bold card-titulo ">(Deuda Total)</p>
                            </section>

                            {!! Form::open([
                                'url' => '/detalle-pago/realizar-pago',
                                'method' => 'POST',
                                'files' => true,
                                'id' => 'myform',
                            ]) !!}
                            {{ csrf_field() }}
                            <input type="hidden" min="0.0" class="form-control text-black" name="deudaTotal"
                                value="{{ $selectCuota->TotalDeuda }}" readonly>
                            <input type="hidden" name="tipoMoneda" value="{{ $tipoMoneda }}">
                            @if ($subpermisos->contains('IdSubPermisos', 31))
                                <div class="my-4 input-switch">
                                    <input type="checkbox" id="switchRegistrarGasto" name="switchRegistrarGasto"
                                        {{ old('switchRegistrarGasto') ? 'checked' : '' }} data-switch="bool" />
                                    <label for="switchRegistrarGasto" data-on-label="Si" data-off-label="No"></label>
                                    <span class="ml-2">Registrar Pago como Gasto</span>
                                </div>
                            @endif
                            <div class="row my-4">
                                <div class="col-12 col-md-4" id="columnaModoPago">
                                    <div class="form-group">
                                        <label>Modo de Pago</label>
                                        <select class="form-control" id="modoPago" name="modoPago">
                                            <option value="1" {{ old('modoPago') == 1 ? 'selected' : '' }}>Totalidad
                                            </option>
                                            <option value="2" {{ old('modoPago') == 2 ? 'selected' : '' }}>Parcial
                                            </option>
                                        </select>
                                        <input class="form-control text-black" name="idFechaCompra"
                                            value="{{ $selectCuota->IdFechaCompras }}" hidden>
                                        <input class="form-control text-black" name="idCompra"
                                            value="{{ $selectCuota->IdCompras }}" hidden>
                                        <input class="form-control text-black" name="importePagado"
                                            value="{{ $selectCuota->ImportePagado }}" hidden>
                                        <input class="form-control text-black" name="importe"
                                            value="{{ $selectCuota->Importe }}" hidden>
                                    </div>
                                </div>
                                <div class="col-12 col-md-3" id="pagoParcial">
                                    <div class="form-group">
                                        <label class="form-control-label">Monto a Pagar(Parcial)</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0.0" class="form-control py-2"
                                                name="pagoParcial" value="{{ old('pagoParcial') }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4" id="columnaPagoEfectivo">
                                    <div class="form-group">
                                        <label class="form-control-label">Monto Pagado(Efectivo)</label>
                                        <div class="input-group">
                                            <input type="number" step="any" min="0.0" class="form-control py-2"
                                                name="pagoEfectivo" value="{{ old('pagoEfectivo') }}">
                                        </div>
                                        <input class="form-control text-black" name="totalEfectivo"
                                            value="{{ $selectCuota->MontoEfectivo }}" hidden>
                                    </div>
                                </div>
                                <div class="col-12 col-md-4" id="columnaCuentaBancaria">
                                    <div class="form-group">
                                        <label>Cuenta Bancaria</label>
                                        <select class="form-control" id="cuentaBancaria" name="cuentaBancaria">
                                            <option value="0">Seleccione cuenta bancaria</option>
                                            @foreach ($bancos as $banco)
                                                <option value="{{ $banco->IdBanco }}"
                                                    {{ old('cuentaBancaria') == $banco->IdBanco ? 'selected' : '' }}>
                                                    {{ $banco->Banco }} -
                                                    {{ $banco->NumeroCuenta }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <section id="seccionDatosCuenta">
                                <x-fieldset :legend="'Datos cuenta'" :legendClass="'px-2'">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label>FECHA ABONO DEPÓSITO</label>
                                                <div class="input-group">
                                                    <input id="date" type="text" class="form-control datepicker"
                                                        name="fechaPagoCuenta"
                                                        {{ old('cuentaBancaria') == '0' || old('cuentaBancaria') == '' ? 'disabled' : '' }}
                                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                        data-date-end-date="0d" autocomplete="off"
                                                        onkeydown="return false">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Numero Operación</label>
                                                <div class="input-group">
                                                    <input id="nroOperacion" type="text" class="form-control"
                                                        name="nroOperacion" value="{{ old('nroOperacion') }}"
                                                        {{ old('cuentaBancaria') == '0' || old('cuentaBancaria') == '' ? 'disabled' : '' }}>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="form-control-label">Monto (Cuenta Bancaria)</label>
                                                <div class="input-group">
                                                    <input id="pagoCuenta" type="number" step="any"
                                                        class="form-control" name="montoCuenta"
                                                        value="{{ old('montoCuenta') }}"
                                                        {{ old('cuentaBancaria') == '0' || old('cuentaBancaria') == '' ? 'disabled' : '' }}>
                                                </div>
                                                <input class="form-control text-black" name="totalCuenta"
                                                    value="{{ $selectCuota->MontoBanco }}" hidden>
                                            </div>
                                        </div>
                                    </div>
                                </x-fieldset>
                            </section>
                            @if ($subpermisos->contains('IdSubPermisos', 31))
                                <section id="seccionRegistrarGasto">
                                    <x-fieldset :legend="'Datos gasto'" :legendClass="'px-2'">
                                        <div class="row">
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>SELECCIONE TIPO GASTO</label>
                                                    <select class="form-control" id="tipoGasto" name="tipoGasto">
                                                        <option value="0">-</option>
                                                        <option value="1">Fijo</option>
                                                        <option value="2">Variable</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-6">
                                                <div class="form-group">
                                                    <label>SELECCIONE ITEM</label>
                                                    <select id="listaGastos"
                                                        class="m-b-10 form-control select2-hidden-accessible"
                                                        name="idListaGasto" data-placeholder="Seleccionar Opción"
                                                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <label>Observación</label>
                                                    <textarea id="observacion" class="form-control" rows="4" name="observacion">{{ old('observacion') }}</textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </x-fieldset>
                                </section>
                            @endif

                            <div class="col-md-3 form-actions btn-list mt-2">
                                <button id="btnPagar" class="btn btn-primary" type="submit">Pagar</button>
                                <a href="../../{{ $selectCuota->IdCompras }}"><button class="btn btn-default"
                                        type="button">Regresar</button></a>
                            </div>
                            {!! Form::close() !!}
                        </div>
                    </div>
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script>
        $(function() {
            $('#seccionRegistrarGasto').hide();
            $('#seccionDatosCuenta').hide();
            const valueSelectCuentas = $("#cuentaBancaria").val();
            if (valueSelectCuentas != '0') {
                $('#seccionDatosCuenta').show();
            }
            const switchRegistrarGasto = $('#switchRegistrarGasto').prop('checked');
            if (switchRegistrarGasto) {
                $('#seccionRegistrarGasto').show();
            }
            const valueModoPago = $("#modoPago").val();
            if (valueModoPago == '2') {
                $('#columnaModoPago').removeClass('col-md-4').addClass('col-md-3');
                $('#columnaPagoEfectivo').removeClass('col-md-4').addClass('col-md-3');
                $('#columnaCuentaBancaria').removeClass('col-md-4').addClass('col-md-3');
                $('#pagoParcial').show();
            } else {
                $('#pagoParcial').hide();
            }
        })

        $(document).ready(function() {
            $('#switchRegistrarGasto').change(function() {
                if ($(this).is(':checked')) {
                    $('#seccionRegistrarGasto').show();
                } else {
                    $('#seccionRegistrarGasto').hide();
                }
            })

            $("#modoPago").on('change', function() {
                if ($(this).val() == '2') {
                    $('#columnaModoPago').removeClass('col-md-4').addClass('col-md-3');
                    $('#columnaPagoEfectivo').removeClass('col-md-4').addClass('col-md-3');
                    $('#columnaCuentaBancaria').removeClass('col-md-4').addClass('col-md-3');
                } else {
                    $('#columnaModoPago').removeClass('col-md-3').addClass('col-md-4');
                    $('#columnaPagoEfectivo').removeClass('col-md-3').addClass('col-md-4');
                    $('#columnaCuentaBancaria').removeClass('col-md-3').addClass('col-md-4');
                }
            })

            $("#tipoGasto").on('change', function() {
                var tipo = $("#tipoGasto").val();
                $.ajax({
                    type: 'get',
                    url: "{{ route('pagos.obtener-gastos') }}",
                    data: {
                        'tipo': tipo
                    },
                    success: function(data) {
                        $('#listaGastos option').remove();
                        for (var i = 0; i < data.length; i++) {
                            $('#listaGastos').append('<option value="' + data[i][
                                    "IdListaGastos"
                                ] + '">' + data[i]["Descripcion"] +
                                '</option>');
                        }
                    }
                })
            });

        })
    </script>
    <script>
        $(function() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var dateBanco = dd + '/' + mm + '/' + yyyy;
            $("#date").val(dateBanco);
        });
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
    <script>
        $(function() {
            // $('#pagoParcial').hide();
            $("#modoPago").on('change', function() {
                var tipo = $("#modoPago").val();
                if (tipo == "1") {
                    $('#pagoParcial').hide();
                } else {
                    $('#pagoParcial').show();
                }
            });

            $("#cuentaBancaria").on('change', function() {
                var tipoBan = $("#cuentaBancaria").val();
                if (tipoBan == "0") {
                    $('#pagoCuenta').attr("disabled", true);
                    $('#nroOperacion').attr("disabled", true);
                    $('#pagoCuenta').val('0');
                    $('#date').attr("disabled", true);

                    $('#seccionDatosCuenta').hide();
                } else {
                    $('#pagoCuenta').attr("disabled", false);
                    $('#nroOperacion').attr("disabled", false);
                    $('#date').attr("disabled", false);

                    $('#seccionDatosCuenta').show();
                }
            });

            $('#btnPagar').on('click', function() {
                var myForm = $("form#myform");
                if (myForm) {
                    $(this).attr('disabled', true);
                    $(myForm).submit();
                }
            });
        });
    </script>
@stop
