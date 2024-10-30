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
                
                {!!Form::open(array('url'=>'/reportes/facturacion/generar-baja-documentos','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                        <div class="col-md-4 mt-4 order-md-2">
                            <div class="form-group form-material">
                                <label class="form-control-label">Fecha</label>
                                <div class="input-group">
                                    <input id="datepicker" type="text" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' class="form-control datepicker" name="fecha" value="{{$fecha}}">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><i class="list-icon material-icons">date_range</i></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3 mt-4 order-md-3 order-last">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-primary">Buscar</button>
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
                {!!Form::open(array('url'=>'/reportes/facturacion/generar-baja-documentos/enviar-documentos','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="input-group">
                        <input id="datepicker2" hidden type="text" data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' class="form-control datepicker" name="fechaDocumentos" value="{{$fecha}}">
                    </div>
                    <div class="form-actions btn-list mt-1 mb-1">
                        <button type="submit" class="btn btn-primary">Enviar Documentos</button>
                         <a href="resumen-diario"><button type="button" class="btn btn-default">Volver</button></a>
                    </div>
                {!!Form::close()!!}
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
                                                <th scope="col">Cliente</th>
                                                <th scope="col">Tipo Comprob.</th>
                                                <th scope="col">Código</th>
                                                <th scope="col">Motivo</th>
                                                <th scope="col">Estado</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($documentos as $documento)
                                                <tr>
                                                    <td>{{$documento->Fecha}}</td>
                                                    <td>{{$documento->Nombres}}</td>
                                                    <td>{{$documento->Tipo}}</td>
                                                    <td>{{$documento->Serie}}-{{$documento->Numero}}</td>
                                                    <td>{{$documento->Motivo}}</td>
                                                    <td>{{$documento->Estado}}</td>
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
        $(function() {
            var fecha = <?php echo json_encode($fecha);?>;
            if(fecha == null || fecha == ''){
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
	@stop







