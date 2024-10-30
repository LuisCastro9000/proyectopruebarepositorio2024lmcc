@extends('layouts.app')
@section('title', 'Emisión de Resumen Diario')
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
        {!! Form::open(['url' => '/reportes/facturacion/emitir-resumen-diario', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row clearfix">
            <!--<div class="col-md-4 mt-4 order-md-1">
                                                            <div class="form-group form-material">
                                                                <label>Tipo Pago</label>
                                                                <select id="estado" class="form-control" name="estado">
                                                                    <option value="1">Todos</option>
                                                                    <option value="2">Pendientes</option>
                                                                    <option value="3">Aceptados</option>
                                                                </select>
                                                            </div>
                                                        </div>-->
            <div class="col-md-4 mt-4 order-md-2">
                <div class="form-group form-material">
                    <label class="form-control-label">Fecha</label>
                    <div class="input-group">
                        <input id="datepicker" type="text"
                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                            class="form-control datepicker" name="fecha" value="{{ $fecha }}">
                        <div class="input-group-append">
                            <div class="input-group-text"><i class="list-icon material-icons">date_range</i></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 mt-4 order-md-3 order-last">
                <div class="form-group">
                    <br>
                    <button type="submit" class="btn btn-primary buscarBoletasPendientes">Buscar</button>
                </div>
            </div>
        </div>
        {!! Form::close() !!}
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="form-actions btn-list mt-1 mb-1">
            <a href="resumen-diario"><button type="button" class="btn btn-default">Volver</button></a>
        </div>
        @if (count($resumenDiario) > 0)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                No se puede generar Resumen Diario en esta fecha, ya se encuentra uno Pendiente en cola.
            </div>
        @endif

        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <!--<div class="widget-heading clearfix">
                                                                    <h5>TableSaw</h5>
                                                                </div>-->
                        <!-- /.widget-heading -->
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Tipo Documento</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (count($boletasSoles) >= 1)
                                        <tr>
                                            <td>{{ $fecha }}</td>
                                            <td>Boleta</td>
                                            <td>Soles</td>
                                            <td>
                                                @if (count($resumenDiario) > 0)
                                                    Ver Resumen de Boletas
                                                @else
                                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg"
                                                        onclick="mostrarDocumentos(1,1)" class="mostrarTipoDoc">Ver Resumen
                                                        de Boletas</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if (count($boletasDolares) >= 1)
                                        <tr>
                                            <td>{{ $fecha }}</td>
                                            <td>Boleta</td>
                                            <td>Dólares</td>
                                            <td>
                                                @if (count($resumenDiario) > 0)
                                                    Ver Resumen de Boletas
                                                @else
                                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg"
                                                        onclick="mostrarDocumentos(1,2)" class="mostrarTipoDoc">Ver Resumen
                                                        de Boletas</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if (count($notasSoles) >= 1)
                                        <tr>
                                            <td>{{ $fecha }}</td>
                                            <td>Nota de Boletas</td>
                                            <td>Soles</td>
                                            <td>
                                                @if (count($resumenDiario) > 0)
                                                    Ver Resumen de Nc y N
                                                @else
                                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg"
                                                        class="mostrarTipoDoc" onclick="mostrarDocumentos(2,1)">Ver Resumen
                                                        de Notas de Crédito</a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endif
                                    @if (count($notasDolares) >= 1)
                                        <tr>
                                            <td>{{ $fecha }}</td>
                                            <td>Nota de Boletas</td>
                                            <td>Dólares</td>
                                            <td>
                                                @if (count($resumenDiario) > 0)
                                                    Ver Resumen de Nc y N
                                                @else
                                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg"
                                                        class="mostrarTipoDoc" onclick="mostrarDocumentos(2,2)">Ver Resumen
                                                        de Notas de Crédito</a>
                                                @endif
                                            </td>

                                        </tr>
                                    @endif
                                    @if (count($bajasSoles) >= 1)
                                        <tr>
                                            <td>{{ $fecha }}</td>
                                            <td>Baja de Boletas</td>
                                            <td>Soles</td>
                                            <td>
                                                @if (count($resumenDiario) > 0)
                                                    Ver Resumen de Baja
                                                @else
                                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg"
                                                        class="mostrarTipoDoc" onclick="mostrarDocumentos(3,1)">Ver Resumen
                                                        de Baja</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                    @if (count($bajasDolares) >= 1)
                                        <tr>
                                            <td>{{ $fecha }}</td>
                                            <td>Baja de Boletas</td>
                                            <td>Dólares</td>
                                            <td>
                                                @if (count($resumenDiario) > 0)
                                                    Ver Resumen de Baja
                                                @else
                                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg"
                                                        class="mostrarTipoDoc" onclick="mostrarDocumentos(3,2)">Ver Resumen
                                                        de Baja</a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->

        <div class="modal fade bs-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel"
            aria-hidden="true" style="display: none">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h5 class="modal-title" id="myLargeModalLabel">Documentos A enviar</h5>
                    </div>
                    <div class="modal-body">
                        <div class="widget-body clearfix" id="tablaAgregado">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-lg" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Tipo Documento</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        {!! Form::open([
                            'url' => '/reportes/facturacion/emitir-resumen-diario/enviar-documentos',
                            'method' => 'POST',
                            'files' => true,
                            'id' => 'myform',
                        ]) !!}
                        {{ csrf_field() }}
                        <input type="hidden" name="tipo" id="tipo" />
                        <input type="hidden" name="newFecha" id="newFecha" />
                        <input type="hidden" name="tipoMoneda" id="tipoMoneda" />
                        <button id="btnEnvio" type="submit"
                            class="btn btn-info btn-rounded ripple text-left">Enviar</button>
                        {!! Form::close() !!}
                        <button type="button" class="btn btn-danger btn-rounded ripple text-left"
                            data-dismiss="modal">Cerrar</button>
                    </div>
                </div>
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
            var fecha = <?php echo json_encode($fecha); ?>;
            if (fecha == null || fecha == '') {
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
                var today = dd + '/' + mm + '/' + yyyy;
                $("#datepicker").val(today);
                $("#datepicker2").val(today);
            }
        });

        function redondeo(num) {
            /*var flotante = parseFloat(numero);
            var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
            return resultado;*/

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

    <script>
        function mostrarDocumentos(idTipoDoc, idTipoMoneda) {
            //idTipoDoc=$(this).attr("id");
            fecha = $('#datepicker').val();
            $('#tipo').val(idTipoDoc);
            $('#newFecha').val(fecha);
            $('#tipoMoneda').val(idTipoMoneda);
            $.ajax({
                type: 'get',
                url: 'mostrar-documentos',
                data: {
                    'tipoDocumento': idTipoDoc,
                    'fecha': fecha,
                    'idTipoMoneda': idTipoMoneda
                },
                beforeSend: function() {
                    $("#tablaAgregado").html("Procesando, espere por favor...");
                },
                success: function(data) {
                    var cadena = '';
                    var inicio = `<table class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">Fecha</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Tipo Documento</th>
                                        <th scope="col">Código</th>
                                        <th scope="col">Numero</th>
                                        <th scope="col">Total</th>
                                        <th scope="col">Estado</th>
                                    </tr>
                                </thead>
                               	<tbody>`;
                    var body = '';
                    $.each(data.datos, function(i, object) {
                        var total;
                        if (idTipoDoc == 1) {
                            total = parseFloat(object.Total) + parseFloat(object.Amortizacion);
                        } else {
                            total = parseFloat(object.Total);
                        }

                        body = '<tr><td>' + object.FechaCreacion + '</td><td>' + object.Nombres +
                            '</td><td>' + object.Comprobante + '</td><td>' + object.Serie +
                            '</td><td>' + object.Numero + '</td><td>' + total + '</td><td>' + object
                            .Estado + '</td></tr>';

                        cadena += body;
                        body = '';
                    });

                    $("#tablaAgregado").html(inicio + '' + cadena + '</tbody></table>');
                }
            });
        }

        /*$('.mostrarTipoDoc').click(function(){
        		idTipoDoc=$(this).attr("id");
        		fecha=$('#datepicker').val();
        		$('#tipo').val(idTipoDoc);
        		$('#newFecha').val(fecha);		
        		
        		$.ajax({
                    type : 'get',
                    url : 'mostrar-documentos',
                    data:{'tipoDocumento':idTipoDoc, 'fecha':fecha},
        			beforeSend: function () {
                        $("#tablaAgregado").html("Procesando, espere por favor...");
                    },
                    success:function(data){
        				var cadena='';
        				var inicio=`<table class="table table-responsive-sm" style="width:100%">
        <thead>
            <tr class="bg-primary">
                <th scope="col">Fecha</th>
                <th scope="col">Cliente</th>
                <th scope="col">Tipo Documento</th>
                <th scope="col">Código</th>
                <th scope="col">Numero</th>
                <th scope="col">Total</th>
                <th scope="col">Estado</th>
            </tr>
        </thead>
       	<tbody>`;
        					var body ='';
        					$.each(data.datos, function(i, object) {
                                var total = parseFloat(object.Total) + parseFloat(object.Amortizacion);
                            	body='<tr><td>'+object.FechaCreacion+'</td><td>'+object.Nombres+'</td><td>'+object.Comprobante+'</td><td>'+object.Serie+'</td><td>'+object.Numero+'</td><td>'+total+'</td><td>'+object.Estado+'</td></tr>';

        						cadena+=body;
        						body='';
        					}); 
        				
        				$("#tablaAgregado").html(inicio+''+cadena+'</tbody></table>');		
                    }
                }); 
        		
        	})*/

        $('#btnEnvio').on('click', function() {
            var myForm = $("form#myform");
            if (myForm) {
                $(this).attr('disabled', true);
                $(myForm).submit();
            }
        });

        /*$('#btnEnviar').click(function(){
            $('#btnEnviar').attr("disabled", true);
        });*/
    </script>

@stop
