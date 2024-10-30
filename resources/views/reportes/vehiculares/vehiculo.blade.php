
		@extends('layouts.app')
		@section('title', 'Reporte Vehicular - Placas')
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
                {!!Form::open(array('url'=>'/reportes/vehiculares/vehiculo','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                        <div class="col-md-5 mt-4 order-md-2">
                            <div class="form-group form-material">
                                <label>Fecha</label>
                                <select id="idFecha" class="form-control" name="fecha">
                                    <option value="1">Hoy</option>
                        	    <option value="2">Ayer</option>
                        	    <option value="3">Semana Actual</option>
                        	    <option value="4">Semana Anterior</option>
                        	    <option value="5">Mes Actual</option>
                        	    <option value="6">Mes Anterior</option>
                        	    <option value="7">A�o Actual</option>
                        	    <option value="8">A�o Anterior</option>
                        	    <option value="9">Personalizar</option>                                
				</select>
                            </div>
                        </div>
                        <div class="col-md-5 mt-4 order-md-3 order-last">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn bg-excel">Buscar</button>
								<a class="p-5" target="_blank" href='{{ url("reportes/vehiculares/excel-vehiculo/$fecha/$ini/$fin") }}'>
		                           		<span class="btn btn-primary ripple">
		                           		    <i class="list-icon material-icons fs-20">explicit</i>xcel
		                           		</span>
		                           	</a>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 order-md-4">
                            <div id="Inicio" class="form-group">
                                <label class="form-control-label">Desde</label>
                                <div class="input-group">
                                    <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
					autocomplete="off" onkeydown="return false" data-date-end-date="0d">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4 mt-4 order-md-5">
                            <div id="Final" class="form-group">
                                <label class="form-control-label">Hasta</label>
                                <div class="input-group">
                                    <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
					autocomplete="off" onkeydown="return false" data-date-end-date="0d">
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
                                                <th scope="col">Fecha/Hora Ingreso</th>
                                        <th scope="col">Cliente</th>
                                        <th scope="col">Celular</th>
                                        <th scope="col">Placa</th>
                                        <th scope="col">Marca</th>
                                        <th scope="col">Modelo</th>
					<th scope="col">A�o</th>
                                        <th scope="col">Color</th>
                                        <th scope="col">Venc. SOAT</th>
                                        <th scope="col">Venc. Rev. T�cnica</th>
                                        @if ($modulosSelect->contains('IdModulo', 6))
                                            <th scope="col">Certif. Anual</th>
                                            <th scope="col">Prueba Quinquenal</th>
                                        @endif
                                        <th scope="col">Motor</th>
                                        <th scope="col">Chasis / VIN</th>
                                        <th scope="col">Numero Flota</th>                                            
						</tr>
                                        </thead>
                                            <tbody>
                                            @foreach($reporteVehiculos as $reporteVehicular)
                                                <tr>
                                                    <td>{{ $reporteVehicular->FechaIngreso }}</td>
                                            <td>{{ $reporteVehicular->Nombre }}</td>
                                            <td>{{ $reporteVehicular->Telefono }}</td>
                                            <td>{{ $reporteVehicular->PlacaVehiculo }}</td>
                                            <td>{{ $reporteVehicular->NombreMarca }}</td>
                                            <td>{{ $reporteVehicular->NombreModelo }}</td>
                                            <td>{{ $reporteVehicular->Anio }}</td>
                                            <td>{{ $reporteVehicular->Color }}</td>
                                            <td>{{ $reporteVehicular->FechaSoat }}</td>
                                            <td>{{ $reporteVehicular->FechaRevTecnica }}</td>
                                            @if ($modulosSelect->contains('IdModulo', 6))
                                                <td>{{ $reporteVehicular->CertificacionAnual }}</td>
                                                <td>{{ $reporteVehicular->PruebaQuinquenal }}</td>
                                            @endif
                                            <td>{{ $reporteVehicular->Motor }}</td>
                                            <td>{{ $reporteVehicular->ChasisVehiculo }}</td>
                                            <td>{{ $reporteVehicular->NumeroFlota }}</td>                                                
						</tr>
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
                </div>
            </div>

            <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
               <div class="modal-dialog">
                  <div class="modal-content">
                       <div class="modal-header">
                           <h6 class="text-success">Reporte Vehicular - Placas</h6>
                       </div>
                       <div class="modal-body form-material">
                           <div>
                               <label class="fs-14 negrita">Reporte del Mes</label>
							   <p class="fs-15negrita">Se mostraran solo las atenciones vehiculares de este mes.......  Si desea ver atenciones vehiculares anteriores utilize los filtros</p>
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


	<script src="https://cdnjs.cloudflare.com/ajax/libs/countup.js/1.9.2/countUp.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-sparklines/2.1.2/jquery.sparkline.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/jquery.vmap.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jqvmap/1.5.1/maps/jquery.vmap.usa.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/jquery.magnific-popup.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-circle-progress/1.2.2/circle-progress.min.js"></script>

    <script>
        $(function() {
            var bandModal=<?php echo json_encode($tipo); ?>;
			
            if(bandModal=='')
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