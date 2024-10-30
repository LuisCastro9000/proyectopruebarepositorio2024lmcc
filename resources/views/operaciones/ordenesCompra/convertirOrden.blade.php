@extends('layouts.app')
@section('title', 'Convertir Orden-Compra')
@section('content')
    <div class="container">
        <br />
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Covertir Orden</h6>
            </div>
        </div>
        @if ($errors->any())
            <div class="alert alert-danger" role="alert">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix form-material">
                            <input hidden id="idOrdenCompra" class="form-control" type="text"
                                value="{{ $idOrdenCompra }}" readonly>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="selectTipoComp" class="form-control" name="tipoComprobante">
                                            @foreach ($tipoComprobante as $tipCom)
                                                <option value="{{ $tipCom->IdTipoComprobante }}">
                                                    {{ $tipCom->Descripcion }}</option>
                                            @endforeach
                                        </select>
                                        <label>Tipo Comprobante</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><label>Serie</label></div>
                                            </div>
                                            <input id="serie" class="form-control" placeholder="Serie" type="text"
                                                name="serie">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text"><label>Número</label></div>
                                            </div>
                                            <input id="numero" class="form-control" placeholder="Número" name="numero"
                                                type="number"
                                                onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group disabled-elemento">
                                        <label>Proveedor</label>
                                        <select disabled class="m-b-10 form-control select2-hidden-accessible"
                                            id="proveedores" name="proveedor" data-placeholder="Choose"
                                            data-toggle="select2" tabindex="-1" aria-hidden="true">
                                            <option value="{{ $datosOrdenCompra->IdProveedor }}">
                                                {{ $datosOrdenCompra->Nombres }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="tipoMoneda" class="form-control disabled-elemento" name="tipoMoneda">
                                            @foreach ($tipoMoneda as $tipMon)
                                                @if ($datosOrdenCompra->IdTipoMoneda == $tipMon->IdTipoMoneda)
                                                    <option value="{{ $datosOrdenCompra->IdTipoMoneda }}" selected>
                                                        {{ $tipMon->Nombre }}</option>
                                                @else
                                                    <option value="{{ $datosOrdenCompra->IdTipoMoneda }}">
                                                        {{ $tipMon->Nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label>Moneda</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <input id="datepicker" type="text" class="form-control datepicker "
                                                name="fechaEmision"
                                                data-plugin-options='{"autoclose": true, "format": "dd-mm-yyyy"}'
                                                autocomplete="off" onkeydown="return false" data-date-end-date="0d"
                                                value="{{ now()->format('d-m-Y') }}" required>
                                            <div class="input-group-append">
                                                <div class="input-group-text"><i
                                                        class="list-icon material-icons">date_range</i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    @if ($datosOrdenCompra->TipoCompra == 1)
                                        <select id="tipoCompra" class="form-control disabled-elemento" name="tipoCompra">
                                            <option selected value="1">Venta Op. Gravada</option>
                                        </select>
                                    @else
                                        <select id="tipoCompra" class="form-control disabled-elemento" name="tipoCompra">
                                            <option selected value="2">Venta Op. Exonerada</option>
                                        </select>
                                    @endif
                                </div>
                            </div>
                            <br>
                            <div class="row mt-4">
                                <div class="col-md-12">
                                    <table id="tablaAgregado" class="table table-responsive-lg" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary-contrast">
                                                <th scope="col" data-tablesaw-priority="persist">Código
                                                </th>
                                                <th scope="col">Descripción</th>
                                                <th scope="col">Und/Medida</th>
                                                <th scope="col">Precio Venta</th>
                                                <th scope="col">Precio Costo</th>
                                                <th scope="col">Cantidad</th>
                                                <th scope="col">Importe</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="row mt-2">
                                <div class="col-lg-8 col-md-12">
                                    <div class="form-group">
                                        <textarea id="observacion" class="form-control pt-4" rows="4" name="observacion">{{ $datosOrdenCompra->Observacion }}</textarea>
                                        <label>Observación</label>
                                    </div>
                                </div>
                                <div class="col-md-4 col-8">
                                    <div class="row">
                                        <div class="col-lg-5 col-8">
                                            <label>Op Gravada:</label>
                                        </div>
                                        <div class="col-lg-5 col-8">
                                            <input id="opGravada" name="subtotal" type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 col-8">
                                            <label>Op Exonerada:</label>
                                        </div>
                                        <div class="col-lg-5 col-8">
                                            <input id="opExonerado" name="opExonerado" type="text" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 col-8">
                                            <label>IGV (18%):</label>
                                        </div>
                                        <div class="col-lg-5 col-8">
                                            <input id="igv" type="text" name="igv" readonly>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-lg-5 col-8">
                                            <label>Total:</label>
                                        </div>
                                        <div class="col-lg-5 col-8">
                                            <input id="total" type="text" name="total" readonly>
                                        </div>
                                    </div>
                                </div>
                            </div><br><br>
                            <div class="row mt-4">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @if ($datosOrdenCompra->IdTipoPago == 1)
                                            <select class="form-control" id="tipoPago" name="tipoPago">
                                                <option selected value="1">Contado</option>
                                            </select>
                                        @else
                                            <select class="form-control" id="tipoPago" name="tipoPago">
                                                <option selected value="2">Crédito</option>
                                            </select>
                                        @endif
                                        <label>Forma de Pago</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div id="plazoCredito" class="form-group">
                                        <label class="form-control-label">Dias</label>
                                        <div class="input-group">
                                            <input id="_plazoCredito" type="number" step="any" class="form-control"
                                                name="plazoCredito" value="{{ $datosOrdenCompra->DiasPlazoCredito }}"
                                                min="1">
                                        </div>
                                    </div>
                                    {{-- Modificado --}}
                                    <div id="interes" hidden class="form-group">
                                        <label class="form-control-label">Interés (%)</label>
                                        <div class="input-group">
                                            <input id="_interes" type="number" step="any" class="form-control"
                                                name="interes" value="0" min="0">
                                        </div>
                                    </div>
                                    {{-- Fin --}}
                                    <div id="efectivo" class="form-group">
                                        <label class="form-control-label">Monto (Efectivo)</label>
                                        <div class="input-group">
                                            <input id="montoEfectivo" type="number" step="any" class="form-control"
                                                name="montoEfec" value="{{ $datosOrdenCompra->Total }}">
                                        </div>
                                    </div>
                                </div>
                                <div id="cuentaCorriente" class="col-md-4">
                                    <div class="form-group">
                                        @if ($datosOrdenCompra->IdTipoMoneda == 1)
                                            <select class="form-control" id="cuentaBancaria" name="cuentaBancaria">
                                                <option value="0">Seleccione cuenta bancaria</option>
                                                @foreach ($cuentasSoles as $banco)
                                                    <option value="{{ $banco->IdBanco }}">
                                                        {{ $banco->Banco }}
                                                        - {{ $banco->NumeroCuenta }}</option>
                                                @endforeach
                                            </select>
                                            <label>Cuenta Bancaria</label>
                                        @else
                                            <select class="form-control" id="cuentaBancaria" name="cuentaBancaria">
                                                <option value="0">Seleccione cuenta bancaria</option>
                                                @foreach ($cuentasDolares as $banco)
                                                    <option value="{{ $banco->IdBanco }}">
                                                        {{ $banco->Banco }}
                                                        - {{ $banco->NumeroCuenta }}</option>
                                                @endforeach
                                            </select>
                                            <label>Cuenta Bancaria</label>
                                        @endif
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Numero Operación</label>
                                        <div class="input-group">
                                            <input id="nroOperacion" type="text" class="form-control"
                                                name="nroOperacion" value="{{ old('nroOperacion') }}" disabled>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label class="form-control-label">Monto (Cuenta Bancaria)</label>
                                        <div class="input-group">
                                            <input id="pagoCuenta" type="number" step="any" class="form-control"
                                                name="montoCuenta" value="0.00" disabled>
                                        </div>
                                    </div>
                                </div>
                                @if ($datosTipoCambio->isNotEmpty())
                                    <input hidden type="text" id="valorCambio" name="valorCambio"
                                        class="form-control" value="{{ $datosTipoCambio[0]->TipoCambioVentas }}">
                                @else
                                    <input hidden type="text" id="valorCambio" name="valorCambio"
                                        class="form-control" value="">
                                @endif
                            </div>
                            <div
                                class="form-actions btn-list mt-3 d-flex justify-content-center justify-content-md-end flex-wrap">
                                {{-- <button id="" class="btn btn--celeste guardarCompra" value="Finalizado"
                                    type="submit">
                                    <span id="btnTexto">Convertir Orden</span>
                                    <div id="seccionLoader" class="d-none">
                                        <span class="d-flex align-items-center">
                                            <i class='bx bx-loader-alt bx-spin fs-28 mr-2'></i>Guardando
                                        </span>
                                    </div>
                                </button> --}}
                                <button id="btnCompraFinalizada" class="btn btn--celeste guardarCompra"
                                    value="Finalizado" data-estado-compra='Registrado' type="submit">Finalizar
                                    Compra</button>
                            </div>
                            <div class="col-12 text-center text-md-right mt-4">
                                <p class="fs-15"><span class="font-weight-bold">*Orden de Compra
                                        Facturada ingresada con su documento fiscal.</span> </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- Modal Mostrar Tipo de Cambio --}}
            <div class="modal fade tipoCambio" id="tipoCambio" tabindex="-1" role="dialog"
                aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 class="modal-title">Configurar Tipo de Cambio</h6>
                        </div>
                        <div class="modal-body p-4">
                            <div class="form-group">
                                <label for="soles">Tipo de Cambio Compras</label>
                                <input id="tipoCambioCompras" class="form-control" name="TipoCambioCompras">
                            </div>
                            <div class="form-group">
                                <label for="soles">Tipo de Cambio Ventas</label>
                                <input id="tipoCambioVentas" class="form-control" name="TipoCambioVentas">
                            </div>
                            <section class="text-center">
                                <span class="text-crimson font-weight-bold">Para Facturar la Orden de
                                    Compra es necesario GUARDAR EL TIPO DE CAMBIO</span>
                            </section>
                        </div>
                        <div class="modal-footer">
                            <button id="btnTipoCambio" onclick="guardaTipoCambio();"
                                class="btn btn-primary btnEliminar">Aceptar</button>
                            <a href="{{ route('ordenDeCompra.show', $idOrdenCompra) }}"><button
                                    class="btn btn-primary ripple">Cancelar</button></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script src="{{ asset('assets/js/scriptCompras.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert1.js?v=' . time()) }}"></script>

    <script src="{{ asset('assets/js/operaciones/ordenesCompra/scriptConveritOrdenCompra.js?v=' . time()) }}"></script>
    <script>
        let urlOrdenCompra = "{{ route('ordenDeCompra.store') }}";
        let token = "{{ csrf_token() }}";
        var total = 0;
        var subtotal = 0;
        var igvTotal = 0;
        var iden = 0;
        var array = [];
        var arrayIds = [];
        var opExonerado = 0;
        var $datosOrdenCompra = @json($datosOrdenCompra);
        $(function() {
            $('#plazoCredito').hide();
            $('#interes').hide();

            if ($datosOrdenCompra.IdTipoPago == 1) {
                $('#plazoCredito').hide();
                $('#interes').hide();
                $('#efectivo').show();
                $('#cuentaCorriente').show();
            } else {
                $('#plazoCredito').show();
                $('#interes').show();
                $('#efectivo').hide();
                $('#cuentaCorriente').hide();
            }

            $("#cuentaBancaria").on('change', function() {
                var tipoBan = $("#cuentaBancaria").val();
                if (tipoBan == "0") {
                    $('#pagoCuenta').attr("disabled", true);
                    $('#nroOperacion').attr("disabled", true);
                    $('#pagoCuenta').val('0');
                } else {
                    $('#pagoCuenta').attr("disabled", false);
                    $('#nroOperacion').attr("disabled", false);
                }
            });

        });

        function PadLeft(value, length) {
            return (value.toString().length < length) ? PadLeft("0" + value, length) :
                value;
        }

        // function agregarProducto(id) {
        //     if (arrayIds.includes(id) == true) {
        //         alert("Producto ya agregado, por favor de modificar la cantidad en vez de agregar más");
        //     } else {
        //         $('#total').val('');
        //         //$('#exonerada').val('');
        //         var descripcion = $('#p1-' + id).text();
        //         var unidadMedida = $('#p3-' + id).val();
        //         var precioVenta = $('#p5-' + id).val();
        //         var cantidad = $('#p4-' + id).val();
        //         var precioCosto = $('#p2-' + id).val();
        //         var tipoCompra = $('#tipoCompra').val();
        //         var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
        //         var total = $('#total').text();
        //         var step;
        //         if (idUnidadMedida == 1) {
        //             step = '';
        //         } else {
        //             step = '0.05';
        //         }

        //         if (tipoCompra == 2) {
        //             precioVenta = parseFloat(precioVenta / 1.18);
        //             precioCosto = parseFloat(precioCosto / 1.18);
        //         }

        //         var importe = parseFloat(parseFloat(precioCosto) * parseInt(cantidad, 10));
        //         var importeFinal = parseFloat(importe);

        //         productoEnTabla(id, descripcion, unidadMedida, precioVenta, precioCosto, cantidad, importeFinal, step);
        //     }
        // }

        // function productoEnTabla(id, descripcion, unidadMedida, precioVenta, precioCosto, cantidad, importeFinal, step) {
        //     $('#tablaAgregado tr:last').after('<tr id="row' + iden + '"><td>PRO-' + id +
        //         '<input id="pro' + iden + '" name="Codigo[]" readonly type="hidden" value="PRO-' + id +
        //         '" style="width:80px">' +
        //         '</td><td id="descrip' + iden + '">' + descripcion +
        //         '</td><td id="um' + iden + '">' + unidadMedida +
        //         '</td><td id="prec' + iden + '">' + redondeo(precioVenta) +
        //         '</td><td>' + redondeo(precioCosto) +
        //         '<input id="cost' + iden + '" name="PrecioCosto[]" onchange="calcular(this, ' + iden +
        //         ');" type="hidden" value="' + redondeo(precioCosto) + '">' +
        //         '</td><td>' + cantidad +
        //         '<input id="cant' + iden + '" name="Cantidad[]" onchange="calcular(this, ' + iden +
        //         ');" type="hidden" step="' + step + '" value="' + cantidad + '">' +
        //         '</td><td>' + redondeo(importeFinal) +
        //         '<input id="imp' + iden + '" name="Importe[]" step="any" readonly type="hidden" value="' + redondeo(
        //             importeFinal) + '" >' +
        //         '</td>' +
        //         '</tr>');
        //     iden++;
        //     var tipoCompra = $('#tipoCompra').val();
        //     if (tipoCompra == 1) {

        //         var igv = parseFloat((18 / 100) + 1);
        //         total += parseFloat(importeFinal);
        //         subtotal = parseFloat(total) / parseFloat(igv);
        //         igvTotal = parseFloat(total) - parseFloat(subtotal);
        //         $('#subtotal').val(redondeo(subtotal));
        //         $('#subtotal').attr('value', redondeo(subtotal));
        //         $('#opExonerado').val(redondeo(opExonerado));
        //         $('#opExonerado').attr('value', redondeo(opExonerado));
        //         $('#igv').val(redondeo(igvTotal));
        //         $('#igv').attr('value', redondeo(igvTotal));
        //         $('#total').val(redondeo(total));
        //         $('#total').attr('value', redondeo(total));
        //     } else {
        //         $('#opExonerado').val('');
        //         total += parseFloat(importeFinal);
        //         opExonerado = parseFloat(total);

        //         $('#subtotal').val(redondeo(subtotal));
        //         $('#opExonerado').val(redondeo(opExonerado));
        //         $('#igv').val(redondeo(igvTotal));
        //         $('#total').val(redondeo(total));
        //     }
        //     arrayIds.push(id);
        // }

        // function calcular(idRow, id) {
        //     var row = idRow.parentNode.parentNode;
        //     //var codigo = row.cells[0].getElementsByTagName('input')[0].value;
        //     //var idPro = codigo.substring(4);
        //     var costo = row.cells[4].getElementsByTagName('input')[0].value;
        //     var cantidad = row.cells[5].getElementsByTagName('input')[0].value;
        //     var impTotal = parseFloat(parseFloat(costo) * parseFloat(cantidad));
        //     var tipoCompra = $('#tipoCompra').val();
        //     $('#imp' + id).val(redondeo(impTotal));

        //     var filas = $("#tablaAgregado").find("tr");
        //     var sumTotalGravada = 0;
        //     var sumTotalExonerada = 0;
        //     var sumTotal = 0;
        //     for (i = 1; i < filas.length; i++) { //Recorre las filas 1 a 1
        //         var celdas = $(filas[i]).find("td"); //devolverá las celdas de una fila
        //         _total = $($(celdas[6]).children("input")[0]).val();

        //         if (tipoCompra == 1) {
        //             sumTotalGravada += parseFloat(_total);
        //         } else {
        //             sumTotalExonerada += parseFloat(_total);
        //         }
        //     }

        //     sumTotal += parseFloat(sumTotalGravada) + parseFloat(sumTotalExonerada);
        //     var igv = parseFloat((18 / 100) + 1);
        //     var _subtotal = parseFloat(sumTotalGravada) / parseFloat(igv);
        //     var _igvTotal = parseFloat(sumTotalGravada) - parseFloat(_subtotal);

        //     total = sumTotal;
        //     subtotal = _subtotal;
        //     opExonerado = sumTotalExonerada;
        //     igvTotal = _igvTotal;

        //     $('#subtotal').val(redondeo(subtotal));
        //     $('#opExonerado').val(redondeo(opExonerado));
        //     $('#igv').val(redondeo(igvTotal));
        //     $('#total').val(redondeo(sumTotal));
        // }

        // function redondeo(num) {

        //     if (num == 0 || num == "0.00") return "0.00";
        //     if (!num || num == 'NaN') return '-';
        //     if (num == 'Infinity') return '&#x221e;';
        //     num = num.toString().replace(/\$|\,/g, '');
        //     if (isNaN(num))
        //         num = "0";
        //     sign = (num == (num = Math.abs(num)));
        //     num = Math.floor(num * 100 + 0.50000000001);
        //     cents = num % 100;
        //     num = Math.floor(num / 100).toString();
        //     if (cents < 10)
        //         cents = "0" + cents;
        //     for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        //         num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
        //     return (((sign) ? '' : '-') + num + '.' + cents);
        // }

        // $('.guardarCompra').click(function() {
        //     $('#seccionLoader').removeClass('d-none');
        //     $('#btnTexto').addClass('d-none');
        //     $('.guardarCompra').attr('disabled', 'disabled');


        //     var estadoOrdenCompra = $(this).val();
        //     var tipoComprobante = $("#selectTipoComp").val();
        //     var proveedor = $("#proveedores").val();
        //     var idOrdenCompra = @json($idOrdenCompra);
        //     var fecha = $("#datepicker").val();
        //     var serie = $("#serie").val();
        //     var numero = $("#numero").val();
        //     var observacion = $("#observacion").val();
        //     var subtotal = $("#subtotal").val();
        //     var opExonerado = $('#opExonerado').val();
        //     var igv = $("#igv").val();
        //     var total = $("#total").val();
        //     var tipoPago = $("#tipoPago").val();
        //     var plazoCredito = $("#_plazoCredito").val();
        //     var montoEfect = $("#montoEfectivo").val();
        //     var montoCuenta = $("#pagoCuenta").val();
        //     var nroOperacion = $("#nroOperacion").val();
        //     var cuentaBancaria = $("#cuentaBancaria").val();
        //     var interes = $("#_interes").val();
        //     var tipoMoneda = $("#tipoMoneda").val();
        //     var tipoCompra = $('#tipoCompra').val();
        //     var valorCambio = $('#valorCambio').val();
        //     var ids = arrayIds;
        //     var codigos = $("input[name='Codigo[]']").map(function() {
        //         return $(this).val();
        //     }).get();
        //     var precios = $("input[name='PrecioCosto[]']").map(function() {
        //         return $(this).val();
        //     }).get();
        //     var cantidades = $("input[name='Cantidad[]']").map(function() {
        //         return $(this).val();
        //     }).get();
        //     var importes = $("input[name='Importe[]']").map(function() {
        //         return $(this).val();
        //     }).get();
        //     $.ajax({
        //         type: 'post',
        //         url: '../../ordenes-compra',
        //         data: {
        //             "_token": "{{ csrf_token() }}",
        //             "estadoOrdenCompra": estadoOrdenCompra,
        //             "idOrdenCompra": idOrdenCompra,
        //             "tipoComprobante": tipoComprobante,
        //             "proveedor": proveedor,
        //             "fechaEmision": fecha,
        //             "serie": serie,
        //             "numero": numero,
        //             "observacion": observacion,
        //             "subtotal": subtotal,
        //             "opExonerado": opExonerado,
        //             "igv": igv,
        //             "total": total,
        //             "tipoPago": tipoPago,
        //             "plazoCredito": plazoCredito,
        //             "Id": ids,
        //             "Codigo": codigos,
        //             "PrecioCosto": precios,
        //             "Cantidad": cantidades,
        //             "Importe": importes,
        //             "MontoEfect": montoEfect,
        //             "MontoCuenta": montoCuenta,
        //             "nroOperacion": nroOperacion,
        //             "CuentaBancaria": cuentaBancaria,
        //             "Interes": interes,
        //             "TipoMoneda": tipoMoneda,
        //             "tipoCompra": tipoCompra,
        //             "valorCambio": valorCambio
        //         },

        //         success: function(data) {
        //             if (data[0] == 'error') {
        //                 respuestaErrorAjax(data[1]);
        //                 $('#seccionLoader').addClass('d-none');
        //                 $('#btnTexto').removeClass('d-none');
        //                 $('.guardarCompra').removeAttr('disabled', 'disabled');
        //             }
        //             if (data[0] == 'cajaCerrada') {
        //                 alert(data[1]);
        //                 window.location =
        //                     '../../../caja/cierre-caja';
        //             }
        //             if (data[0] == 'succes') {
        //                 $('.guardarCompra').animate({
        //                     opacity: 0
        //                 }, 'slow');
        //                 url = '../../compras/comprobante-generado/' + data[2];
        //                 respuestaSuccesAjaxConConfirmacion(data[1], url);
        //             }
        //         }
        //     });
        // })
    </script>

    <script>
        $(function() {
            let itemsOrden = @json($arrayItemsOrdenCompra);
            var step;
            const tipoCompra = @json($tipoCompra);
            for (var i = 0; i < itemsOrden.length; i++) {
                if (itemsOrden[i]["IdUnidadMedida"] == 1) {
                    step = '';
                } else {
                    step = '0.05';
                }
                if (tipoCompra == 2) {
                    precioVenta = parseFloat(itemsOrden[i]["Precio"] / 1.18);
                    precioCosto = parseFloat(itemsOrden[i]["PrecioCosto"] / 1.18);
                } else {
                    precioVenta = itemsOrden[i]["Precio"];
                    precioCosto = itemsOrden[i]["PrecioCosto"];
                }
                productoEnTabla(itemsOrden[i]["IdArticulo"], itemsOrden[i]["Descripcion"], itemsOrden[i][
                        "UniMedida"
                    ],
                    precioVenta, precioCosto, itemsOrden[i]["Cantidad"],
                    itemsOrden[i]["Importe"], step)
            }
        });
    </script>

    <script>
        let datosOrdenCompra = @json($datosOrdenCompra);
        let datosTipoCambio = @json($datosTipoCambio);
        if (datosOrdenCompra.IdTipoMoneda == 2 && datosTipoCambio.length == 0) {
            $(function() {
                $('.tipoCambio').modal({
                    backdrop: 'static',
                    keyboard: false
                }, 'show');
            })
        }
    </script>
    <script>
        function guardaTipoCambio() {
            var tipoCambioCompras = $("#tipoCambioCompras").val();
            var tipoCambioVentas = $("#tipoCambioVentas").val();
            $.ajax({
                type: 'post',
                url: 'guardar-tipo-cambio',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "tipoCambioCompras": tipoCambioCompras,
                    "tipoCambioVentas": tipoCambioVentas
                },
                success: function(data) {
                    if (data[0] == 'success') {
                        $("#valorCambio").val(tipoCambioVentas);
                        $("#tipoCambio").modal("hide");
                    }
                    alert(data[1]);
                }
            });
        }
    </script>
@stop
