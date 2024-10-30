
		@extends('layouts.app')
		@section('title', 'Reporte Kardex')
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
				
				@if (count($errors) > 0)
                    <div class="alert alert-danger">
                    	<p>Corrige los siguientes errores:</p>
                        <ul>
                            @foreach ($errors->all() as $message)
                                <li>{{ $message }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
				
				<div class="row page-title clearfix">
                   	<div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Kardex - movimientos</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
						@if($band)
                            <div class="col-12 mr-b-20 d-flex">
                                <a class="p-1" target="_blank" href='{{ url("reportes/almacen/imprimir-kardex/$prod/$fecha/$ini/$fin") }}'>
	            				    <button class="btn btn-block btn-primary ripple">
	            				    	<i class="list-icon material-icons fs-20">print</i>
	            				    </button>
	            				</a>
                                
	            				<a class="p-1" href='{{ url("reportes/almacen/excel-kardex/$prod/$fecha/$ini/$fin") }}' target="_blank">
	            				    <button class="btn btn-block btn-primary ripple">
	            				        <i class="list-icon material-icons fs-20">explicit</i>
	            				    </button>
	            				</a>
								
								<a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-primary" onclick="cargarCorreo()">
								    <button class="btn btn-block btn-primary ripple">
								        <i class="list-icon material-icons fs-20">mail</i>
								    </button>
								</a>
                            </div>
                        @endif	
                        </div>
                    </div>
                </div>

                {!!Form::open(array('url'=>'/reportes/almacen/kardex-antiguo-filtrar','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
				   <div class="row">
						<div class="col-md-3 mt-3">
                            <div class="form-group form-material">
                                <label>Local</label>
								  <select class="form-control" name="local">
									<option value="">Seleccione el Local</option>
									<option value="*{{$sucursal->IdSucursal}}">{{$sucursal->Nombre}}</option>
									@foreach ($almacenes as $almacen)
                                    <option value="{{$almacen->IdAlmacen}}">{{$almacen->Nombre}}</option>
									@endforeach
                                  </select>
                            </div>
                        </div>
						
						<div class="col-md-3 mt-3">
							
							<div class="form-group form-material">
                                <label>Producto</label>
								<!--<select class="form-control" id="clientes" name="cliente">-->
								<select class="m-b-10 form-control select2-hidden-accessible" id="producto" name="producto" data-placeholder="Seleccione el Producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                                    <option value="">Seleccione el Producto</option>
									  @foreach ($productos as $producto)
                                        <option value="{{$producto->CodigoInterno}}">{{$producto->Descripcion}}</option>
                                    @endforeach
                                </select>
                                <small class="text-muted"><strong>Seleccione el Producto</strong></small>
                            </div>

                        </div>
						<div class="col-md-2 mt-3">
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

                    <div class="col-md-1 mt-2 order-md-2 order-last">
                        <div class="form-group">
                            <button style=" margin-top: 8px;" type="submit" class="btn btn-primary">Buscar</button>
                        </div>
                    </div>
					<div class="col-md-3 mt-4 order-md-4">
					</div>
					<div class="col-md-3 mt-4 order-md-4">
					</div>
					
					<div class="col-md-3 mt-4 order-md-4">
                        <div id="Inicio" class="form-group">
                            <label class="form-control-label">Desde</label>
                            <div class="input-group">
                                <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mt-4 order-md-5">
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
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Tipo</th>
                                                <th scope="col">Usuario</th>
                                                <th scope="col">Documento</th>
												<th scope="col">Costo/Precio</th>
												<th scope="col">Entrada</th>
                                                <th scope="col">Salida</th>
                                                <th scope="col">Imp. Entrada</th>
                                                <th scope="col">Imp. Salida</th>
                                            </tr>
                                        </thead>
                                            <tbody>
											   @php $bandExis=0; 
											   		$entrada=0;
													$salida=0;
													$imp_salida=0;
													$imp_entrada=0;@endphp
                                               		@foreach($reporteKardex as $kardex)
														@if($kardex->Tipo == 1)	
                                                        @php $salida=$salida + $kardex->Cantidad;
															$imp_salida=$imp_salida+ $kardex->Costo * $kardex->Cantidad;
															$existencia=$existencia-$kardex->Cantidad;@endphp
														@elseif($kardex->Tipo==2)
														@php $entrada=$entrada + $kardex->Cantidad;
															$imp_entrada=$imp_entrada + $kardex->Costo * $kardex->Cantidad;
															$existencia=$existencia+$kardex->Cantidad;@endphp
														@elseif($kardex->Tipo==3)
                                                        @php $entrada=$entrada + $kardex->Cantidad;
															$existencia=$existencia+$kardex->Cantidad;@endphp
														@elseif($kardex->Tipo==4)
                                                        @php $existencia=$existencia-$kardex->Cantidad;
															$salida=$salida + $kardex->Cantidad;@endphp
														@elseif($kardex->Tipo==5)
                                                        @php $salida=$salida + $kardex->Cantidad;
															$existencia=$existencia-$kardex->Cantidad;@endphp
														@elseif($kardex->Tipo==7 || $kardex->Tipo==6)
                                                        @php $entrada=$entrada + $kardex->Cantidad;
                                                            $existencia=$existencia+$kardex->Cantidad;@endphp
                                                        @elseif($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
                                                        @php $entrada=$entrada + $kardex->Cantidad;
                                                            $existencia=$existencia;@endphp
														@endif
													
                                                        <tr>
                                                            <td>{{$kardex->fecha2}}</td>
                                                            <td>
                                                            @if($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
                                                                    <span>Inicial</span>
                                                            @elseif($kardex->Tipo==1)
                                                                    <span>Venta</span>
                                                            @elseif($kardex->Tipo==2)
                                                                    <span>Compra</span>
                                                            @elseif($kardex->Tipo==3 || $kardex->Tipo==5)
                                                                    <span>Traspaso</span>
                                                            @elseif($kardex->Tipo==4)
                                                                    <span>Baja Producto</span>
                                                            @elseif($kardex->Tipo==7)
                                                                    <span>N. Credito</span>
                                                            @elseif($kardex->Tipo==6)
                                                                    <span>Baja Documento</span>
                                                            @endif

                                                            </td>
                                                            <td>{{$kardex->Nombre}}</td>
                                                            @if($kardex->Tipo==8)
                                                                <td>Inventario Inicial</td>
                                                            @elseif($kardex->Tipo==9)
                                                                <td>Importación Excel</td>
                                                            @elseif($kardex->Tipo==10)
                                                                <td>Sucursal Inicial</td>
                                                            @elseif($kardex->Tipo==11)
                                                                <td>Traspaso Inicial</td>
                                                            @else
                                                                <td>{{$kardex->Serie.'-'.$kardex->Numero}}</td>
                                                            @endif
                                                            <td>{{$kardex->Costo}}</td>
                                                            <td>
                                                            @if($kardex->Tipo==1 || $kardex->Tipo==4 || $kardex->Tipo==5)
                                                                    0
                                                            @elseif($kardex->Tipo==2 || $kardex->Tipo==3 || $kardex->Tipo==6 || $kardex->Tipo==7)
                                                                {{ $kardex->Cantidad }}
                                                            @elseif($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
                                                                {{ $entrada }}
                                                            @endif
                                                            </td>
                                                            <td>
                                                                @if($kardex->Tipo==1)
                                                                    {{ $kardex->Cantidad }}
                                                                @elseif($kardex->Tipo==2)
                                                                    0
                                                                @elseif($kardex->Tipo==3)
                                                                    0
                                                                @elseif($kardex->Tipo==4 || $kardex->Tipo==5)
                                                                    {{ $kardex->Cantidad }}
                                                                @elseif($kardex->Tipo==8 || $kardex->Tipo==9 || $kardex->Tipo==10 || $kardex->Tipo==11)
                                                                    {{$salida}}
                                                                @else
                                                                    0
                                                                @endif
                                                            </td>
                                                            <td>{{ $kardex->Tipo == 2 ? $kardex->Costo * $kardex->Cantidad : '0.00' }}</td>
                                                            <td>{{ $kardex->Tipo == 1 ? $kardex->Total : '0.00'}}</td>
                                                        </tr>
												@endforeach
                                            </tbody>
                                            <tfoot>
                                                <tr>
													 <td colspan="5"><span class="">Totales: </span></td>
													 <td>{{ $entrada }}</td>
													 <td>{{ $salida}}</td>
													 <td>{{$imp_entrada}}</td>
													 <td>{{$imp_salida}}</td>
												</tr> 
                                            </tfoot>
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
                
				<div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-sm">
                        {!!Form::open(array('url'=>"reportes/almacen/correo-kardex/$prod/$fecha/$ini/$fin", 'method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                            <div class="modal-content">
                                <div class="modal-header text-inverse">
                                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                    <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <label>Ingrese correo:</label>
                                        <input id="inpCorreo" class="form-control" name="correo"/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Enviar</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        {!!Form::close()!!}
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>	
            </div>
            <!-- /.container -->
	@stop			
			
	@section('scripts')	
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
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
            $('#Inicio').hide();
            $('#Final').hide();
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



