        @extends('layouts.app')
		@section('title', 'Reporte de Resumen Diario - Administrador')
		@section('content')	
            <div class="container">
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }} <br><br>
                        @if(Session::has('arrayBoletasPendientes'))
                            @php $array = Session::get('arrayBoletasPendientes') @endphp
                            <label>Las siguientes documentos fueron actualizados:</label>
                            @foreach($array as $boleta)
                                <p class="text-danger">* {{$boleta->Serie}} - {{$boleta->Numero}}</p>
                            @endforeach
                        @endif
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
                
                <div class="row page-title clearfix">
                    <!-- /.page-title-left -->
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Resumen Diario</h6>
                    </div>
                </div>
                
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
                                <!--<div class="widget-heading clearfix">
                                    <h5>TableSaw</h5>
                                </div>-->
                                <!-- /.widget-heading -->
                                <div class="widget-body clearfix">
                                    <!--<p>Listado de ventas</p>-->
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Fecha Emitida</th>
                                                <th scope="col">Fecha Enviada</th>
                                                <th scope="col">Sucursal</th>
                                                <th scope="col">Tipo Resumen</th>
                                                <th scope="col">Identificador</th>
                                                <th scope="col">Ticket</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Opciones</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($resumenDiario as $resumen)
                                                <tr>
                                                    <td>{{$resumen->FechaEmitida}}</td>
                                                    <td>{{$resumen->FechaEnviada}}</td>
                                                    <td>{{$resumen->Sucursal}}</td>
                                                    <td>@if($resumen->TipoResumen == 1)
                                                        RD Boletas
                                                        @elseif($resumen->TipoResumen == 2)
                                                        RD Nota Crédito
                                                        @else
                                                        RD Bajas
                                                        @endif
                                                    </td>
                                                    <td>{{$resumen->Numero}}</td>
                                                    <td>{{$resumen->Ticket}}</td>
                                                    <td>{{$resumen->Estado}}</td>
                                                    <td class="text-center">
                                                        <a href id="btnCambiar" href="#" data-toggle="modal" data-target=".bs-modal-sm-primary" onclick="cambiarEstado({{$resumen->IdResumenDiario}}, {{$resumen->TipoResumen}});" title="Cambiar Estado"><i class="list-icon material-icons">arrow_forward</i></a>
                                                        <a href="resumen-diario/enviar-ticket-admin/{{$resumen->IdResumenDiario}}/{{$resumen->IdUsuario}}/{{$resumen->IdSucursal}}" id="btnEnviar{{$resumen->IdResumenDiario}}" onclick="enviar({{$resumen->IdResumenDiario}});" title="Enviar Ticket"><i class="list-icon material-icons">send</i></a>
                                                    </td>
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
                    <!-- /.row -->
                </div>

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
                    aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-body">
                                <h6 class="modal-title">Desea Eliminar Usuario?</h6>
                                <input id="idUsuario" hidden />
                            </div>
                            <div class="modal-footer">
                                <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.widget-list -->
                <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-sm">
                        {!!Form::open(array('url'=>'/reportes/facturacion/cambiar-estado-resumen-diario','method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                            <div class="modal-content">
                                <div class="modal-header text-inverse">
                                    <h6 class="modal-title" id="mySmallModalLabel2">Cambiar Estado de Resumen Diario</h6>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <label>Seleccionar Estado:</label>
                                        <div class="form-group form-material">
                                            <select id="idFecha" class="form-control" name="estado">
                                                <option value="1">Aceptado</option>
                                                <option value="2">Pendiente</option>
                                                <option value="3">Rechazo</option>
                                            </select>
                                        </div>
                                        <div class="form-group form-material">
                                            <label>Identificador</label>
                                            <input class="form-control"  name="identificador"/>
                                        </div>
                                        <input hidden id="inpIdResumenDiario" name="idResumendiario"/>
                                        <input hidden id="inpTipoResumen" name="tipoResumen"/>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Aceptar</button>
                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        {!!Form::close()!!}
                        <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                </div>
                
            </div>
		@stop			
			
		@section('scripts')		
    <script>
    
		
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

        function cambiarEstado(idResumenDiario, tipoResumen){
            $('#inpIdResumenDiario').val(idResumenDiario);
            $('#inpTipoResumen').val(tipoResumen);
        }

        function enviar(id){
            window.scrollBy(0, -window.innerHeight);
            $.showLoading({
                name:'circle-fade',
            });
            $('#btnEnviar'+id).attr("disabled", true);
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
	@stop




