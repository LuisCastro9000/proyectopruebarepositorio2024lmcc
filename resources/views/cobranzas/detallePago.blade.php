
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
                                            <h6>Proveedor: {{$compraSelect->Nombres}}</h6>
                                            <strong>{{$compraSelect->TipoDoc}}:</strong> {{$compraSelect->NumeroDocumento}}
                                            <br><strong>DIRECCIÓN:</strong> {{$compraSelect->DirProveedor}}
                                            <br><strong>CIUDAD:</strong> {{$compraSelect->Ciudad}}
                                            <br><strong>TELÉFONO:</strong> {{$compraSelect->TelfProveedor}}
                                        </div>
                                        <div class="col-lg-4 col-sm-6 text-right d-none d-sm-block">
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; border: 1px solid red; padding:1px"><strong class="text-danger fs-18">Deuda Inicial: </strong>{{ $compraSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->ImporteTotal}}</h6>
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif; border: 1px solid red; padding:1px"><strong class="text-danger fs-18">Deuda Actual: </strong>{{ $compraSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->TotalDeuda}}</h6>
                                            <strong>Plazos de Pago:</strong> {{$compraSelect->PlazoCredito}} días
                                            <br><strong>Tipo Moneda:</strong> {{ $compraSelect->IdTipoMoneda == 1 ? 'Soles' : 'Dólares'}}
                                        </div>
                                        <div class="col-sm-6 d-block d-sm-none">
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><strong class="text-danger fs-18">Deuda Inicial: </strong>{{ $compraSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->ImporteTotal}}</h6>
                                            <h6 style="font-family:'Franklin Gothic Medium', 'Arial Narrow', Arial, sans-serif;"><strong class="text-danger fs-18">Deuda Actual: </strong>{{ $compraSelect->IdTipoMoneda == 1 ? 'S/' : '$'}} {{$deudasTotales->TotalDeuda}}</h6>
                                            <strong>Plazos de Pago:</strong> {{$compraSelect->PlazoCredito}} días
                                            <br><strong>Tipo Moneda:</strong> {{ $compraSelect->IdTipoMoneda == 1 ? 'Soles' : 'Dólares'}}
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
                                                <th scope="col">Pagar</th>
                                                <th scope="col">Detalles</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                               @foreach($detallePagos as $detallePago)
                                                <tr>
                                                    <td>{{$detallePago->FechaInicio}}</td>
                                                    <td>{{$detallePago->FechaUltimo}}</td>
                                                    <td>{{$detallePago->FechaPago}}</td>
                                                    <td class="text-right">{{$detallePago->Importe}}</td>
                                                    <td class="text-right">{{$detallePago->ImportePagado}}</td>
                                                    <td class="text-center">{{$detallePago->DiasPasados}}</td>
                                                    @if($detallePago->Estado == 2)
                                                        <td class="text-center"><i class="list-icon material-icons">payment</i></td>
                                                    @else
                                                        <td class="text-center"><a href="realizar-pago/{{$detallePago->IdFechaCompras}}/{{$compraSelect->IdTipoMoneda}}"><i class="list-icon material-icons">payment</i></a></td>
                                                    @endif
                                                    <td class="text-center"><a href="pagos-proveedores-detalles/{{$detallePago->IdFechaCompras}}"><i class="list-icon material-icons">visibility</i></a></td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>
                                </div>
                                <div class="form-actions btn-list ml-3">
                                    <a href="../pagos"><button class="btn btn-primary" type="button">Volver</button></a>
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




