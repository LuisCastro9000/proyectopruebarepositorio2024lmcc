		@extends('layouts.app')
		@section('title', 'Registro Ventas Electronicas')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Ventas</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <!--<div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                            </div>
                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
                            </div>
                        </div>
                    </div>-->
                    <!-- /.page-title-right -->
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                
                {!!Form::open(array('url'=>'/reportes/registro-ventas-electronicas','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                        <div class="col-md-3 order-md-1">
                            <div class="form-group form-material">
                                <label>Fecha</label>
                                <select id="idFecha" class="form-control" name="fecha">
                                    <option value="0">Todo</option>
                                    <option value="1">Hoy</option>
                                    <option value="2">Ayer</option>
                                    <option value="3">Esta semana</option>
                                    <option value="4">Última semana</option>
                                    <option value="5" selected >Este mes</option>
                                    <option value="6">Último mes</option>
                                    <option value="7">Este año</option>
                                    <option value="8">Último año</option>
                                    <option value="9">Personalizar</option>
                                </select>
                            </div>
                        </div>
						<div class="col-md-3 order-md-2">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                        <div class="col-md-5 order-md-3 mt-3">
                                
                                <a class="m-1 btn btn-primary" href="../reportes/registro-ventas-texto-plano/{{ $defecto }}/{{$ini}}/{{$fin}}">Archivo Plano Sunat</a>
                                <a class="m-1" data-toggle="tooltip" data-placement="top" title="Ver video Instructivo" href="https://www.youtube.com/watch?v=WcPY5cJ2YIs" target="_blank">
                                    <span class="btn btn-autocontrol-naranja ripple">
                                        <i class="list-icon material-icons fs-20 color-icon">videocam</i>
                                    </span>
                                </a>
                                <a class="m-4" href="../reportes/registro-excel/{{$defecto }}/{{$ini}}/{{$fin}}">
                                    <span class="btn bg-excel ripple">
                                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                    </span>
                                </a>
                            
                        </div>
                        
                        <div class="col-md-3 order-md-4">
                            <div id="Inicio" class="form-group">
                                <label class="form-control-label">Desde</label>
                                <div class="input-group">
                                    <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 order-md-5">
                            <div id="Final" class="form-group">
                                <label class="form-control-label">Hasta</label>
                                <div class="input-group">
                                    <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                                </div>
                            </div>
                        </div>
                        @if($datosEmpresa->ArchivoPLE == 1)
                        <div class="col-md-6 order-md-6">
                            <div class="form-group">
                                <a class="m-1" href="../reportes/registro-excel-ple/{{$defecto}}/{{$ini}}/{{$fin}}">
                                    <span class="btn bg-excel ripple">
                                        <i class="list-icon material-icons fs-20">explicit</i>XCEL PLE
                                    </span>
                                </a>
                            </div>
                        </div>
                        @endif
                        <div class="col-md-6 order-md-7">
                            <div class="form-group">
                                <text class="text-danger"><strong>IMPORTANTE: </strong> <br> Antes de realizar tu declaración se recomienda verificar la correcta aceptación, descargando y validando el archivo plano ante Sunat</text>
                            </div>
                        </div>
                    </div>
                {!!Form::close()!!}
                <!-- /.page-title -->
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
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Código</th>
                                                <th scope="col">Tipo Comprob.</th>
                                                <th scope="col">Tipo Pago</th>
                                                <th scope="col">Tipo Operac.</th>
                                                <th scope="col">Gener. Nota</th>
                                                <th scope="col">Importe</th>
                                                <th scope="col">Estado</th>
                                            </tr>
                                        </thead>
                                           
                                        <tbody>
                                                @foreach($facturasVentas as $factura)
                                                <tr>
                                                    <td>{{$factura->FechaCreacion}}</td>
                                                    <td>{{$factura->Nombres}}</td>
                                                    <td>{{$factura->Serie}}-{{$factura->Numero}}</td>
                                                    <td>{{$factura->Descripcion}}</td>
                                                    @if($factura->IdTipoPago == 1)
                                                        <td>Contado</td>
                                                    @else
                                                        <td>Crédito</td>
                                                    @endif
                                                    <td>{{$factura->TipoVenta == 1 ? 'Gravada': 'Exonerada'}}</td>
                                                    <td>{{$factura->TipoNota}}</td>
                                                    <td>{{$factura->Total}}</td>
                                                    <td>{{$factura->Estado}}</td>
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
            
            <div class="modal modal-primary fade bs-modal-sm-anular" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                <div class="modal-dialog modal-sm">
                    {!!Form::open(array('url'=>'/consultas/ventas-boletas-facturas/anulando','method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                <h6 class="modal-title" id="mySmallModalLabel2">Dar baja documento</h6>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <label>Descripción:</label>
                                    <input id="inpDescripcion" class="form-control" name="descripcion"/>
                                    <input id="inpVenta" hidden class="form-control" name="idVenta"/>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Anular</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    {!!Form::close()!!}
                </div>
            </div>
            
            <div class="modal modal-primary fade bs-modal-sm-enviar" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                <div class="modal-dialog modal-md">
                    {!!Form::open(array('url'=>'/consultas/ventas-boletas-facturas/enviando-sunat','method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                <h6 class="modal-title" id="mySmallModalLabel2">Enviar Comprobante</h6>
                            </div>
                            <div class="modal-body">
                                    <label>Desea enviar comprobante electrónico a Sunat ?</label>
                                    <input id="idDocEnvio" hidden class="form-control" name="idDocEnvio"/>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    {!!Form::close()!!}
                </div>
            </div>
	@stop			
			
	@section('scripts')
    <script>
        $(function() {
            $('#Inicio').hide();
            $('#Final').hide();
            var idTipoPago = <?php echo json_encode($IdTipoPago);?>;
            var fecha = <?php echo json_encode($fecha);?>;
            if(fecha == '9'){
                var fechaIni = <?php echo json_encode($fechaInicial);?>;
                var fechaFin = <?php echo json_encode($fechaFinal);?>;
                $('#Inicio').show();
                $('#Final').show();
                $('#datepickerIni').val(fechaIni);
                $('#datepickerFin').val(fechaFin);
            }
            $('#tipoPago option[value='+idTipoPago+']').prop('selected', true);
            $('#idFecha option[value='+fecha+']').prop('selected', true);
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
            for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
                num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
            return (((sign) ? '' : '-') + num + '.' + cents);
        }
        
        function anular(id){
            $('#inpVenta').val(id);
        }
        
        function enviarSunat(id){
            $('#idDocEnvio').val(id);
        }
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
                    "order": [[ 0, "desc" ]],
                    language: {
                        processing:     "Procesando...",
                        search:         "Buscar:",
                        lengthMenu:     "Mostrar _MENU_ registros",
                        info:           "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty:      "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered:   "",
                        infoPostFix:    "",
                        loadingRecords: "Cargando...",
                        zeroRecords:    "No se encontraron resultados",
                        emptyTable:     "Ningún dato disponible en esta tabla",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        aria: {
                            sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });
        
    </script>
    <script>
        $(function() {
            $("#idFecha").on('change', function () {
                var valor = $("#idFecha").val();
                if(valor == "9"){
                    $('#Inicio').show();
                    $('#Final').show();
                }else{
                    $('#Inicio').hide();
                    $('#Final').hide();
                    $('#datepickerIni').val('');
                    $('#datepickerFin').val('');
                }
            });
        });
    </script>
	@stop


