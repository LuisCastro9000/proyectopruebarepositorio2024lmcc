		@extends('layouts.app')
		@section('title', 'Reporte de Baja de Documentos')
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
                
                <div class="row page-title clearfix">
                    <!-- /.page-title-left -->
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Documentos</h6>
                    </div>
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-none d-flex">
                                @if($usuarioSelect->IdOperador == 1)
                                <a class="m-1" href="ver-baja-documentos-pendientes"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-24">visibility</i> </button></a>
                                @endif
                                <!--<a class="m-1" href="generar-baja-documentos"><button class="btn btn-block btn-primary ripple btn-generar"><i class="list-icon material-icons fs-24">add</i> Generar</button></a>-->
                            </div>
                        </div>
                    </div>
                    <!-- /.page-title-right -->
                </div>
                <!--{!!Form::open(array('url'=>'/reportes/facturacion/baja-documentos/enviar','method'=>'POST','files'=>true))!!}
                        {{csrf_field()}}
                    <div class="row justify-content-between">
                        <div class="col-md-4 col-sm-6 mt-2 order-sm-0 order-1">
                            <div class="form-group form-material">
                                <label class="form-control-label">Seleccionar fecha a enviar</label>
                                <div class="input-group">
                                    <input id="datepicker" type="text" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' class="form-control datepicker" name="fecha">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="list-icon material-icons">date_range</i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2 col-sm-4 mt-2 order-sm-1 order-0">
                            <div class="form-group">
                                <br>
                                <a href="#" data-toggle="modal" data-target=".bs-modal-lg-documentos"><button type="button" class="btn btn-primary">Ver CE Pendientes</button></a>
                            </div>
                        </div>
                    </div>
                    <div>
                        <div class="input-group">
                            <input id="datepicker2" hidden type="text" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' class="form-control datepicker" name="fechaEnvio">
                        </div>
                        <div class="form-actions btn-list mt-1 mb-1">
                            <button type="submit" class="btn btn-primary" type="button">Enviar a Sunat</button>
                        </div>
                    </div>
                {!!Form::close()!!}-->
            </div>
            <!-- /.container -->
            <!-- =================================== -->
            <!-- Different data widgets ============ -->
            <!-- =================================== -->
            <div class="container">
                <div class="col-md-4 mt-4">
                   
                        
                    
                </div>
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
                                    <table id="tableBase" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <!--<th scope="col">Código Hash</th>-->
                                                <th scope="col">Fecha Emitida</th>
                                                <th scope="col">Fecha Enviada</th>
                                                <th scope="col">Documento Baja</th>
                                                <th scope="col">Identificador</th>
                                                <th scope="col">Moneda</th>
                                                <th scope="col">Monto</th>
                                                <th scope="col">Cód. Error</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Opciones</th>
                                                <th scope="col">Nº Ticket</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($bajaDocumentos as $baja)
                                                <tr>
                                                    <!--<td>{{$baja->Hash}}</td>-->
                                                    <td>{{$baja->FechaEmitida}}</td>
                                                    <td>{{$baja->FechaEnviada}}</td>
                                                    <td>@if($baja->TipoDocumento != null)
                                                            {{$baja->Documento->Serie}} - {{$baja->Documento->Numero}}
                                                        @endif
                                                    </td>
                                                    <td>{{$baja->Identificador}}</td>
                                                    <td>
                                                        @if($baja->TipoDocumento != null)
                                                            @if($baja->Documento->IdTipoMoneda == 1)
                                                                Soles
                                                            @else
                                                                Dólares
                                                            @endif
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($baja->TipoDocumento != null)    
                                                        {{$baja->Documento->Total}}
                                                        @endif
                                                    </td>
                                                    <td>{{$baja->CodigoDoc}}</td>
                                                    <td>{{$baja->Estado}}</td>
                                                    <td class="text-center">
                                                        <a href="baja-documentos/xml/{{$rucEmpresa}}/{{$baja->IdBajaDoc}}" title="Descargar XML"><i class="list-icon material-icons">code</i></a>
                                                    @if($baja->Estado == 'Aceptado' || $baja->Estado == 'Baja Rechazo' || $baja->Estado == 'Baja Aceptado')
                                                        <a href="baja-documentos/cdr/{{$rucEmpresa}}/{{$baja->IdBajaDoc}}" id="enlaceDescarga" title="Descargar CDR"><i class="list-icon material-icons">attach_file</i></a>
                                                    @else
                                                        <a href="baja-documentos/enviar-ticket/{{$baja->IdBajaDoc}}/{{$baja->TipoDocumento}}" id="btnEnviar{{$baja->IdBajaDoc}}" onclick="enviar({{$baja->IdBajaDoc}});" title="Enviar Documentos"><i class="list-icon material-icons btn-enviar-bajas">send</i></a>
                                                    @endif
                                                    </td>
                                                    <td>{{$baja->Ticket}}</td>
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
                

                <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                       <div class="modal-content">
                            <div class="modal-header">
                                <h6 class="text-danger">ATENCIÓN</h6>
                            </div>
                            <div class="modal-body form-material">
                                <div>
                                    <label class="fs-14 negrita">Hay Documentos de Bajas Pendientes por enviar, No podrá generar más documentos mientras hayan documentos Pendientes en Lista</label>
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
                
                
            </div>
		@stop			
			
		@section('scripts')	
    <script>
        var bajaDocPend = <?php echo json_encode(count($bajaDocumentoPendientes));?>;
        if(bajaDocPend > 0){
            $('.btn-generar').attr("disabled", true);
            $("#mostrarmodal").modal("show");
        }

		/*$('.btn-enviar').click(function(){
			$('.btn-enviar').attr("disabled", true);
		});*/
		
		$('#enlaceDescarga').click(function(){
			$('#enlaceDescarga').attr("disabled", true);
		});
	
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
                var today = dd + '/' + mm + '/' + yyyy;
                $("#datepicker").val(today);
                $("#datepicker2").val(today);
            
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
            
            $(document).ready(function () {
                $('#tableBase').DataTable({
                    responsive: true,
                    "order": [[ 1, "desc" ]],
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






