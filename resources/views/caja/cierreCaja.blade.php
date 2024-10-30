@extends('layouts.app')
@section('title', 'Cierre Caja')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Resumen Caja</h6>
            </div>
            <div class="page-title-right">
                <div class="row mr-b-50 mt-2">
                    <div class="col-12 mr-b-20 d-flex">
                        @if ($estado == 'D')
                            <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-print"><button
                                    class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-20">print</i></button></a>
                            <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-primary"
                                onclick="cargarCorreo()"><button class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-20">mail</i></button></a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
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
        <!-- /.page-title -->
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row justify-content-center">
                <div class="widget-bg">
                    <div class="widget-heading clearfix">
                        <div class="col-12 mr-b-20">
                            <div class="col-12">
                                <a id="abrirCaja" href="javascript:void(0);" data-toggle="modal"
                                    data-target=".bs-modal-caja"><button class="btn btn-block btn-success ripple">Abrir
                                        Caja</button></a>
                                <a id="cerrarCaja" href="javascript:void(0);"><button
                                        class="btn btn-block btn-danger ripple" onclick="cerrarCaja()">Cerrar
                                        Caja</button></a>
                            </div>
                            <div class="text-center mt-4">
                                <span id="cajaCerrada" class="badge bg-danger color-white fs-20 p-2">Caja Cerrada</span>
                                <span id="cajaAbierta" class="badge bg-success color-white fs-20 p-2">Caja Abierta</span>
                            </div>
                            <div class="text-center mt-1">
                                @if ($estado == 'E')
                                    <p><span class="font-weight-bold">Última apertura:</span> {{ $ultimoSesion }}</p>
                                @else
                                    <p><span class="font-weight-bold">Último cierre: </span>{{ $ultimoSesion }}</p>
                                @endif
                            </div>
                            <br>
                            <table id="tablaDetalles" width="100%">
                                <thead>
                                    <tr>
                                        <th class="font-weight-bold fs-14" align="start">DETALLE</th>
                                        <th class="font-weight-bold fs-14" align="end">SOLES (S/)</th>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <th class="font-weight-bold fs-14" align="end">DÓLARES ($)</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr style="border-top: 3px solid #2e6da4;">
                                        <td class="font-weight-bold pt-3">Efectivo Inicial</td>
                                        <td id="efectivoIniSoles" class="pt-3">
                                            {{ number_format($inicialSoles, 2, '.', ',') }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td id="efectivoIniDolares" class="pt-3">
                                                {{ number_format($inicialDolares, 2, '.', ',') }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="font-weight-bold pt-3">Contado</td>
                                        <td class="pt-3">{{ $totalVentasContadoSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $totalVentasContadoDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="pt-3">&ensp;&ensp;Efectivo</td>
                                        <td class="pt-3">{{ $ventasContadoEfectivoSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $ventasContadoEfectivoDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #CCC">
                                        <td class="pt-3">&ensp;&ensp;Tarjeta</td>
                                        <td class="pt-3">{{ $ventasContadoTarjetaSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $ventasContadoTarjetaDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #CCC">
                                        <td class="pt-3">&ensp;&ensp;Cuenta Bancaria</td>
                                        <td class="pt-3">{{ $ventasContadoCuentaBancariaSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $ventasContadoCuentaBancariaDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="font-weight-bold pt-3">Cobranzas</td>
                                        <td class="pt-3">{{ $totalCobranzasSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $totalCobranzasDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="pt-3">&ensp;&ensp;Efectivo</td>
                                        <td class="pt-3">{{ $cobranzasEfectivoSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $cobranzasEfectivoDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #CCC">
                                        <td class="pt-3">&ensp;&ensp;Tarjeta</td>
                                        <td class="pt-3">{{ $cobranzasTarjetaSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $cobranzasTarjetaDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #CCC">
                                        <td class="pt-3">&ensp;&ensp;Cuenta Bancaria</td>
                                        <td class="pt-3">{{ $cobranzasCuentaBancariaSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $cobranzasCuentaBancariaDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="font-weight-bold pt-3">Amortizaciones</td>
                                        <td class="pt-3">{{ number_format($totalAmortizacionSoles, 2, '.', ',') }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ number_format($totalAmortizacionDolares, 2, '.', ',') }}
                                            </td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="pt-3">&ensp;&ensp;Efectivo</td>
                                        <td class="pt-3">{{ number_format($amortizacionEfectivoSoles, 2, '.', ',') }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ number_format($amortizacionEfectivoDolares, 2, '.', ',') }}
                                            </td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #CCC">
                                        <td class="pt-3">&ensp;&ensp;Tarjeta</td>
                                        <td class="pt-3">{{ number_format($amortizacionTarjetaSoles, 2, '.', ',') }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ number_format($amortizacionTarjetaDolares, 2, '.', ',') }}
                                            </td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #CCC">
                                        <td class="pt-3">&ensp;&ensp;Cuenta Bancaria</td>
                                        <td class="pt-3">{{ number_format($amortizacionCuentaBancariaSoles, 2, '.', ',') }}
                                        </td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">
                                                {{ number_format($amortizacionCuentaBancariaDolares, 2, '.', ',') }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="font-weight-bold pt-3">Ingresos</td>
                                        <td class="pt-3">{{ $montoIngresosSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $montoIngresosDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 1px solid #63a3e2">
                                        <td class="font-weight-bold pt-3">Egresos</td>
                                        <td class="pt-3">{{ $montoEgresosSoles }}</td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="pt-3">{{ $montoEgresosDolares }}</td>
                                        @endif
                                    </tr>
                                    <tr style="border-top: 3px solid #2e6da4">
                                        <td class="font-weight-bold pt-3">CAJA TOTAL EFECTIVO</td>
                                        <td class="font-weight-bold pt-3" id="cajatotalSoles"></td>
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <td class="font-weight-bold pt-3" id="cajatotalDolares"></td>
                                        @endif
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- /.widget-heading -->

                    <!-- /.widget-body -->
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
        <div class="modal modal-primary fade bs-modal-caja" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
            <div class="modal-dialog modal-sm">
                <div class="modal-content form-material">
                    <div class="modal-header text-inverse">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                        <h6 class="modal-title" id="mySmallModalLabel2">Efectivo Inicial</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <label>Ingrese monto Soles:</label>
                            <input id="inicialSoles" type="number" step="any" class="form-control"
                                name="inicialSoles" value="0" />
                        </div>
                        @if ($subniveles->contains('IdSubNivel', 46))
                            <div class="container">
                                <label>Ingrese monto Dólares:</label>
                                <input id="inicialDolares" type="number" step="any" class="form-control"
                                    name="inicialDolares" value="0" />
                            </div>
                        @else
                            <input hidden id="inicialDolares" type="number" step="any" class="form-control"
                                name="inicialDolares" value="0" />
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" onclick="abrirCaja();" class="btn btn-primary"
                            data-dismiss="modal">Aceptar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>


        <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="text-danger">ATENCIÓN</h6>
                    </div>
                    <div class="modal-body form-material">
                        <div>
                            <label class="fs-14 negrita">No olvides de enviar tus documentos electrónicos a Sunat</label>
                        </div>
                        <div>
                            <label class="fs-12 negrita"><span id="totalPendientes"
                                    class="fs-12 text-danger"></span></label>
                        </div>
                        <div>
                            <label class="fs-12 negrita"><span id="totalResumen"
                                    class="fs-12 text-danger"></span></label>
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

        <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
            <div class="modal-dialog modal-sm">
                {!! Form::open([
                    'url' => 'caja/cierre-caja/enviar-correo/' . $idCaja,
                    'method' => 'POST',
                    'files' => true,
                    'class' => 'form-material',
                ]) !!}
                <div class="modal-content">
                    <div class="modal-header text-inverse">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                        <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <label>Ingrese correo:</label>
                            <input id="inpCorreo" class="form-control" name="correo" />
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Enviar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        <div class="modal modal-primary fade bs-modal-sm-print" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
            <div class="modal-dialog modal-sm">
                {!! Form::open([
                    'url' => 'caja/cierre-caja/imprimir',
                    'method' => 'POST',
                    'files' => true,
                    'class' => 'form-material',
                    'target' => '_blank',
                ]) !!}
                <div class="modal-content">
                    <div class="modal-header text-inverse">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                        <h6 class="modal-title" id="mySmallModalLabel2">Imprimir comprobante</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <label>Seleccionar tipo de impresión:</label>
                            <select id="selectImpre" class="form-control" name="selectImpre">
                                <option value="1">A4</option>
                                <option value="2">Ticket</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Imprimir</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
    </div>
    <!-- /.container -->
@stop

@section('scripts')

    <script>
        $(function() {
            var cajatotalsoles = <?php echo json_encode($cajaTotalSoles); ?>;
            var cajatotaldolares = <?php echo json_encode($cajaTotalDolares); ?>;
            var estado = <?php echo json_encode($estado); ?>;
            /*var inicialSoles = <?php echo json_encode($inicialSoles); ?>;
            var inicialDolares = <?php echo json_encode($inicialDolares); ?>;
            $('#efectivoIniSoles').text(inicialSoles);
            $('#efectivoIniDolares').text(inicialDolares);*/
            if (estado == 'E') {
                $('#abrirCaja').hide();
                $('#cerrarCaja').show();
                $('#cajaCerrada').hide();
                $('#cajaAbierta').show();
            } else {
                $('#abrirCaja').show();
                $('#cerrarCaja').hide();
                $('#cajaCerrada').show();
                $('#cajaAbierta').hide();
            }
            $("#cajatotalSoles").text(redondeo(cajatotalsoles));
            $("#cajatotalDolares").text(redondeo(cajatotaldolares));
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

        function abrirCaja() {
            $.ajax({
                type: 'post',
                url: 'abrir-caja',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "inicialSoles": $('#inicialSoles').val(),
                    "inicialDolares": $('#inicialDolares').val()
                },
                success: function(data) {
                    alert(data);
                    /*$('#abrirCaja').hide();
                    $('#cerrarCaja').show();
                    $('#cajaCerrada').hide();
                    $('#cajaAbierta').show();*/
                    window.location = 'cierre-caja';
                    //alert(data);
                    /*if(data[0]["IdCliente"] == '' || data[0]["IdCliente"] == null){
                        $('#mensaje').empty();
                        $('#mensaje').append('<div class="alert alert-danger">'+
                                        '<button type="button" class="close" data-dismiss="alert">&times;</button>'+data+
                                    '</div>');
                    }else{
                        alert('Se creo cliente correctamente');
                        window.location='../../operaciones/ventas/realizar-venta';
                    }*/
                }
            });
        }

        function cerrarCaja() {
            $.confirm({
                title: '',
                content: 'Desea cerrar caja?',
                buttons: {
                    confirmar: function() {
                        $.ajax({
                            type: 'post',
                            url: 'cerrar-caja',
                            data: {
                                "_token": "{{ csrf_token() }}",
                            },
                            success: function(data) {
                                alert(data);
                                /*$('#abrirCaja').show();
                                $('#cerrarCaja').hide();
                                $('#cajaCerrada').show();
                                $('#cajaAbierta').hide();*/
                                window.location = 'cierre-caja';
                                /*if(data[0]["IdCliente"] == '' || data[0]["IdCliente"] == null){
                                    $('#mensaje').empty();
                                    $('#mensaje').append('<div class="alert alert-danger">'+
                                                    '<button type="button" class="close" data-dismiss="alert">&times;</button>'+data+
                                                '</div>');
                                }else{
                                    alert('Se creo cliente correctamente');
                                    window.location='../../operaciones/ventas/realizar-venta';
                                }*/
                            }
                        });
                    },
                    cancelar: function() {

                    },
                }
            });

        }
    </script>
    <script>
        $(function() {
            var compPendientes = <?php echo json_encode(count($comprobantesPendientes)); ?>;
            var resumenPendientes = <?php echo json_encode(count($resumenPendientes)); ?>;
            if (compPendientes > 0 || resumenPendientes > 0) {
                if (compPendientes > 0) {
                    $("#totalPendientes").text("- Hay Facturas y/o Notas Electrónicas por enviar")
                }
                if (resumenPendientes > 0) {
                    $("#totalResumen").text("- Hay Boletas y/o Notas Electrónicas por enviar")
                }
                $("#mostrarmodal").modal("show");
            }
        });
    </script>
@stop
