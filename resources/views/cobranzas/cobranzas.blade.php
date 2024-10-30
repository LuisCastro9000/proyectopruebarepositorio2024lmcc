
		@extends('layouts.app')
		@section('title', 'Cobranzas')
		@section('content')		
            <div class="container">
                <table width="100%" cellspacing="5" style="border-collapse: inherit">
                    <tr style="background-color: #ccf2ff;">
                        <td colspan="2">
                            <div class="p-2">
                                <select id="tipoMoneda" name="tipoMoneda">
                                    <option value="1">Soles</option>
                                    <option value="2">Dólares</option>
                                </select>
                                <!--<button id="btnCambiarMoneda" onclick="cambiarTipo();" class="btn btn-default fs-11" type="button">Ver</button>-->
                            </div>
                            <div class="p-3 text-center">
                                <label class="text-black">Total ventas por cobrar:</label>
                                <br>
                                <strong id="ventasCobrarConvertido" class="fs-34 text-black">S/ {{$ventasCobrarConvertido}}</strong>
                            </div>
                        </td>
                    </tr>
                    <tr>
                        <td style="background-color: #ebebe0;">
                            <div class="row align-items-center p-3 text-center">
                                <div class="col-md-6">
                                    <strong class="text-primary">No Vencido:</strong>
                                    <br>
                                    <text id="noVencidoConvertido" class="fs-16 text-primary">S/ {{$noVencidoConvertido}}</text>
                                </div>
                                <div class="col-md-6">
                                    <strong id="porcentajeNoVencido" class="fs-28 text-primary">{{$porcentajeNoVencido}} %</strong>
                                </div>
                            </div>
                        </td>
                        <td style="background-color: #ebebe0;">
                            <div class="row align-items-center p-3 text-center">
                                <div class="col-md-6">
                                    <strong class="text-danger">Vencido:</strong>
                                    <br>
                                    <text id="vencidoConvertido" class="fs-16 text-danger">S/ {{$vencidoConvertido}}</text>
                                </div>
                                <div class="col-md-6">
                                    <strong id="porcentajeVencido" class="fs-28 text-danger">{{$porcentajeVencido}} %</strong>
                                </div>
                            </div>
                        </td>
                    </tr>
                </table>
            </div>
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
                {!!Form::open(array('url'=>'/cobranzas','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                        <div class="col-md-4 mt-4 order-md-0">
                            <div class="form-group form-material">
                                <label>Cliente</label>
                                <input id="list" type="text" list="contenido" name="cliente" class="form-control AvenirMedium lista" style="font-size:14px;" value="{{$inputcliente}}"/>
                                <datalist id="contenido">
                                    @foreach($clientes as $cliente)
                                        <option value="{{$cliente->Nombre}}"></option>
                                    @endforeach
                                </datalist>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 order-md-2">
                            <div class="form-group form-material">
                                <label>Fecha</label>
                                <select id="idFecha" class="form-control" name="fecha">
                                    <option value="0">Todo</option>
                                    <option value="1">Hoy</option>
                                    <option value="2">Ayer</option>
                                    <option value="3">Esta semana</option>
                                    <option value="4">Última semana</option>
                                    <option value="5">Este mes</option>
                                    <option value="6">Último mes</option>
                                    <option value="7">Este año</option>
                                    <option value="8">Último año</option>
                                    <option value="9">Personalizar</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mt-4 order-md-3 order-last">
                            <div class="form-group container ">
                                <br>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 order-md-4">
                            <div id="Inicio" class="form-group">
                                <label class="form-control-label">Desde</label>
                                <div class="input-group">
                                    <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 order-md-5">
                            <div id="Final" class="form-group">
                                <label class="form-control-label">Hasta</label>
                                <div class="input-group">
                                    <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                                </div>
                            </div>
                        </div>
                    </div>
                {!!Form::close()!!}
            </div>
            <!-- /.container -->
            <!-- =================================== -->
            <!-- Different data widgets ============ -->
            <!-- =================================== -->
            <div class="container">
                <div class="widget-list">
                    <div class="row">
                        <div class="col-md-12 widget-holder">
                            <div class="widget-bg">
                                <!-- /.widget-heading -->
                                <div class="widget-body clearfix">
                                    <!--<p>Listado de ventas</p>-->
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Fecha Emitida</th>
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Documento</th>
                                                <th scope="col">Tipo Moneda</th>
                                                <th scope="col">Importe Total</th>
                                                <th scope="col">Tiempo Pago (Días)</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Opciones</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                               @php $bandPago=0; @endphp
											   @foreach($cobranzas as $cobranza)
                                                <tr>
                                                    <td>{{$cobranza->FechaCreacion}}</td>
                                                    <td>{{$cobranza->Cliente}}</td>
                                                    <td>{{$cobranza->Serie.'-'.$cobranza->Numero}}</td>
                                                    <td>{{$cobranza->IdTipoMoneda == 1 ? 'Soles' : 'Dólares'}}</td>
                                                    <td class="text-right">{{$cobranza->Total}}</td>
                                                    <td class="text-center">{{$cobranza->Dias}}</td>
                                                    <td>
														@if($cobranza->Nota == 1)
															{{'N. Credito'}}
															@php $bandPago=1; @endphp
														@elseif(!is_null($cobranza->MotivoAnulacion))
															{{'Doc. de Baja'}}
															@php $bandPago=1; @endphp
														@else  
                                                            {{ $cobranza->TipoEstado == 1 ? 'Pendiente' : 'Cancelado'}}
														@endif
													</td>
                                                    <td class="text-center">
														@if($bandPago==1)
														<span><i class="list-icon material-icons">check</i></span>
														@else
															<a href="detalle-cobranza/{{$cobranza->IdVentas}}"><i class="list-icon material-icons">visibility</i></a>
														@endif
                                                    </td>
                                                </tr>
												@php $bandPago=0; @endphp
                                                @endforeach
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
                
                
            </div>

            <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                       <div class="modal-header">
                           <h6 class="text-success">Reportes de Cobranzas</h6>
                       </div>
                       <div class="modal-body form-material">
                           <div>
                               <label class="fs-14 negrita">Reporte de los 3 últimos meses</label>
							   <p class="fs-15negrita">Se mostraran los reportes de últimos 3 meses incluyendo este.......  Si desea ver las cobranzas anteriores utilize los filtros</p>
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
            <!-- /.container -->
		@stop			
			
	@section('scripts')		
    <script>
        $(function() {
            var idTipoPago=<?php echo json_encode($IdTipoPago); ?>;
			
            if(idTipoPago=='')
            {
                $("#mostrarmodal").modal("show");
            }

            $('#Inicio').hide();
            $('#Final').hide();
            var fecha = <?php echo json_encode($fecha);?>;
            if(fecha == '9'){
                var fechaIni = <?php echo json_encode($fechaInicial);?>;
                var fechaFin = <?php echo json_encode($fechaFinal);?>;
                $('#Inicio').show();
                $('#Final').show();
                $('#datepickerIni').val(fechaIni);
                $('#datepickerFin').val(fechaFin);
            }
            $('#idFecha option[value='+fecha+']').prop('selected', true);
        });
       
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

        /*function cambiarTipo(){
            var idTipoMoneda = $('#tipoMoneda').val();
            $.ajax({
                type : 'get',
                url : 'cobranzas/cobranzas-moneda',
                data:{'idTipoMoneda':idTipoMoneda},
                success:function(data){
                    if(data.length > 0){
                        if(idTipoMoneda == 1){
                            $('#ventasCobrarConvertido').text('S/ '+data[0]);
                            $('#noVencidoConvertido').text('S/ '+data[1]);
                            $('#vencidoConvertido').text('S/ '+data[2]);
                        }else{
                            $('#ventasCobrarConvertido').text('$ '+data[0]);
                            $('#noVencidoConvertido').text('$ '+data[1]);
                            $('#vencidoConvertido').text('$ '+data[2]);
                        }
                        $('#porcentajeNoVencido').text(data[3]+' %');
                        $('#porcentajeVencido').text(data[4]+' %');
                    }
                }
            }); 
        }*/

        $("#tipoMoneda").on('change', function() {
            var idTipoMoneda = $('#tipoMoneda').val();
            $.ajax({
                type : 'get',
                url : 'cobranzas/cobranzas-moneda',
                data:{'idTipoMoneda':idTipoMoneda},
                success:function(data){
                    if(data.length > 0){
                        if(idTipoMoneda == 1){
                            $('#ventasCobrarConvertido').text('S/ '+data[0]);
                            $('#noVencidoConvertido').text('S/ '+data[1]);
                            $('#vencidoConvertido').text('S/ '+data[2]);
                        }else{
                            $('#ventasCobrarConvertido').text('$ '+data[0]);
                            $('#noVencidoConvertido').text('$ '+data[1]);
                            $('#vencidoConvertido').text('$ '+data[2]);
                        }
                        $('#porcentajeNoVencido').text(data[3]+' %');
                        $('#porcentajeVencido').text(data[4]+' %');
                    }
                }
            }); 
        });
    </script>
	@stop


