		@extends('layouts.app')
		@section('title', 'Detalles de Pago')
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
                                <div class="widget-header">
                                    <h5>Detalles de Pago</h5>
                                </div>
                                <div class="widget-body clearfix">
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Fecha Pago</th>
                                                <th scope="col">Importe Inicial</th>
                                                <th scope="col">Importe Pendiente</th>
                                                <th scope="col">Monto Pagado</th>
                                                <th scope="col">Modo de Pago</th>
                                                <th scope="col">Monto Efectivo</th>
                                                <th scope="col">Monto Tarjeta</th>
                                                <th scope="col">Cuenta Banc.</th>
                                                <th scope="col">Monto Cuenta Banc.</th>
                                                <th scope="col">Resta Importe</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                               @foreach($pagosDetalles as $pagoDetalle)
                                                <tr>
                                                    <td>{{$pagoDetalle->FechaPago}}</td>
                                                    <td>{{$pagoDetalle->ImporteInicial}}</td>
                                                    <td>{{$pagoDetalle->ImportePendiente}}</td>
                                                    <td>{{$pagoDetalle->MontoPagado}}</td>
                                                    <td>{{$pagoDetalle->ModoPago}}</td>
                                                    <td>{{ $pagoDetalle->Efectivo == null ? '0.00' : $pagoDetalle->Efectivo}}</td>
                                                    <td>{{ $pagoDetalle->Tarjeta == null ? '0.00' : $pagoDetalle->Tarjeta}}</td>
                                                    <td>
                                                        @if($pagoDetalle->CuentaBancaria == '0.00')
                                                            -
                                                        @else
                                                            {{$pagoDetalle->Nombre}}-{{$pagoDetalle->NumeroCuenta}}
                                                        @endif
                                                    </td>
                                                    <td>
                                                        @if($pagoDetalle->CuentaBancaria == null)
                                                            0.00
                                                        @else
                                                            {{$pagoDetalle->CuentaBancaria}}
                                                        @endif
                                                    </td>
                                                    <td>{{$pagoDetalle->RestaImporte}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                    </table>
                                </div>
                                <div class="form-actions btn-list ml-3">
                                    <a href="../../cobranzas"><button class="btn btn-primary" type="button">Volver</button></a>
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
    
	@stop




