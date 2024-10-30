
		@extends('layouts.app')
		@section('title', 'Reporte de Resumen Diario')
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
                @php $disabled = ''; @endphp
                @if($horaActual > "23:59:59" && $horaActual < "06:00:00")
                    @php $disabled = 'disabled'; @endphp
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        El envio de Resumenes Diarios solo estará disponible desde las 06:00 AM hasta las 11:59 PM para evitar incidencias con el servicio de Sunat.
                    </div>
                @endif
                
                <div class="row page-title clearfix">
                    <!-- /.page-title-left -->
                    
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Resumen Diario</h6>
                    </div>
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-none d-flex">
                                @if($usuarioSelect->IdOperador == 1)
                                    <a class="m-1" href="ver-resumenes-diario-pendientes"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-24">visibility</i> </button></a>
                                @endif
                                <a class="m-1" href="emitir-resumen-diario"><button class="btn btn-block btn-primary ripple" {{ $disabled }}><i class="list-icon material-icons fs-24">add</i> Generar</button></a>
                            </div>
                            <!--<div class="col-12 mr-b-20  d-block d-flex">
                                @if($usuarioSelect->IdOperador == 1)
                                    <a class="p-1" href="ver-resumenes-diario-pendientes"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-24">visibility</i> </button></a>
                                @endif
                                <a class="p-1" href="emitir-resumen-diario"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-24">add</i></button></a>
                            </div>-->
                        </div>
                    </div>
                    <!-- /.page-title-right -->
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
                                                <th scope="col">Identificador</th>
                                                <th scope="col">Ticket</th>
                                                <th scope="col">Tipo de Resumen</th>
                                                <th scope="col">Tipo Moneda</th>
                                                <th scope="col">Cód Sunat</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Opciones</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($resumenDiario as $resumen)
                                                <tr>
                                                    <td>{{$resumen->FechaEmitida}}</td>
                                                    <td>{{$resumen->FechaEnviada}}</td>
                                                    <td>{{$resumen->Numero}}</td>
                                                    <td>{{$resumen->Ticket}}</td>
                                                    <td>@if($resumen->TipoResumen == 1)
                                                        Boletas
                                                        @elseif($resumen->TipoResumen == 2)
                                                        Nota Crédito
                                                        @else
                                                        Bajas
                                                        @endif
                                                    </td>
                                                    <td>@if($resumen->IdTipoMoneda == 1)
                                                        Soles
                                                        @else
                                                        Dólares
                                                        @endif
                                                    </td>
                                                    <td>{{$resumen->CodResSunat}}</td>
                                                    <td>{{$resumen->Estado}}</td>
                                                    <td class="text-center">
                                                    @if($resumen->Estado == 'Aceptado' || $resumen->Estado == 'Resumen Rechazo' || $resumen->Estado == 'Nota Rechazo' || $resumen->Estado == 'Nota Observada' || $resumen->Estado == 'Baja Rechazo' || $resumen->Estado == 'Resumen Observado')
                                                        <a href="resumen-diario/xml/{{$rucEmpresa}}/{{$resumen->IdResumenDiario}}" title="Descargar XML"><i class="list-icon material-icons">code</i></a>
                                                        <a href="resumen-diario/cdr/{{$rucEmpresa}}/{{$resumen->IdResumenDiario}}" id="enlaceDescarga" title="Descargar CDR"><i class="list-icon material-icons">attach_file</i></a>
                                                    @else
                                                        <a href="resumen-diario/enviar-ticket/{{$resumen->IdResumenDiario}}" id="enlaceTicket" title="Enviar Ticket"><i class="list-icon material-icons">send</i></a>
                                                    @endif
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
                <!-- /.widget-list -->
                
                
            </div>
		@stop			
			
		@section('scripts')		
    <script>
       
        $('#enlaceTicket').click(function(){
			$('#enlaceTicket').attr("disabled", true);
		});
		
		$('#enlaceDescarga').click(function(){
			$('#enlaceDescarga').attr("disabled", true);
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
        });
    </script>
	@stop




