
		@extends('layouts.app')
		@section('title', 'Detalle Cobranza')
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
                                <div class="ecommerce-invoice">
                                    <div class="d-sm-flex">
                                        <div class="col-lg-8 col-sm-6">
                                            <h6>Cliente: {{$ventaSelect->Nombres}}</h6>
                                            <strong>{{$ventaSelect->TipoDoc}}:</strong> {{$ventaSelect->NumeroDocumento}}
                                            <br><strong>DIRECCIÓN:</strong> {{$ventaSelect->DirCliente}}
                                            <br><strong>CIUDAD:</strong> {{$ventaSelect->Ciudad}}
                                            <br><strong>TELÉFONO:</strong> {{$ventaSelect->TelfCliente}}
                                        </div>
                                        <div class="col-lg-4 col-sm-6 text-right d-none d-sm-block">
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; border: 1px solid red; padding:1px"><strong class="text-danger fs-18">Deuda Inicial: </strong>{{$ventaSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->ImporteTotal}}</h6>
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; border: 1px solid red; padding:1px"><strong class="text-danger fs-18">Deuda Actual: </strong>{{$ventaSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->TotalDeuda}}</h6>
                                            <strong>Plazos de Pago:</strong> {{$ventaSelect->PlazoCredito}} días
                                        </div>
                                        <div class="col-sm-6 d-block d-sm-none">
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><strong class="text-danger fs-18">Deuda Inicial: </strong>{{$ventaSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->ImporteTotal}}</h6>
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><strong class="text-danger fs-18">Deuda Actual: </strong>{{$ventaSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->TotalDeuda}}</h6>
                                            <strong>Plazos de Pago:</strong> {{$ventaSelect->PlazoCredito}} días
                                        </div>
                                    </div>
                                </div>
                                <div class="widget-body clearfix">
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Fecha Emis.</th>
                                                <th scope="col">Fecha Venc.</th>
                                                <th scope="col">Últ. Fecha Pagada</th>
                                                <th scope="col">Importe</th>
                                                <th scope="col">Importe Pagado</th>
                                                <th scope="col">Días Atras.</th>
                                                <th scope="col">Estado</th>
                                                <th scope="col">Cobrar</th>
                                                <th scope="col">Detalles</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                               @foreach($detalleCobranzas as $detalleCobranza)
                                                <tr>
                                                    <td>{{$detalleCobranza->FechaInicio}}</td>
                                                    <td>{{$detalleCobranza->FechaUltimo}}</td>
                                                    <td>{{$detalleCobranza->FechaPago}}</td>
                                                    <td class="text-right">{{$detalleCobranza->Importe}}</td>
                                                    <td class="text-right">{{$detalleCobranza->ImportePagado}}</td>
                                                    <td class="text-center">{{$detalleCobranza->DiasPasados}}</td>
                                                    <td>{{$detalleCobranza->NombreEstado}}</td>
                                                    @if($detalleCobranza->Estado == 2)
                                                        <td class="text-center"><i class="list-icon material-icons">payment</i></td>
                                                    @else
                                                        <td class="text-center"><a href="realizar-cobro/{{$detalleCobranza->IdFechaPago}}/{{$detalleCobranza->IdTipoMoneda}}"><i class="list-icon material-icons">payment</i></a></td>
                                                    @endif
                                                    <td class="text-center"><a href="pagos-detalles/{{$detalleCobranza->IdFechaPago}}"><i class="list-icon material-icons">visibility</i></a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>
                                </div>
                                <div class="form-actions btn-list ml-3">
                                    <a href="../cobranzas"><button class="btn btn-primary" type="button">Volver</button></a>
                                </div>
                            </div>
                            <!-- /.widget-bg -->
                        </div>
                        <!-- /.widget-holder -->
                    </div>
                    <!-- /.row -->
                </div>
                <!-- /.widget-list -->
                
                
            </div>
            <!-- /.container -->
		@stop			
			
	@section('scripts')	
    <script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
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




