
		@extends('layouts.app')
		@section('title', 'Reporte Stock Critico de Reposición')
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
                {!!Form::open(array('url'=>'/reportes/almacen/stock-critico','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                    
                    <!--<div class="col-md-4 mt-4 order-md-2">
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
                    </div>-->
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
                                                <th scope="col">Descripción</th>
                                                <th scope="col">UM</th>
                                                <th scope="col">Stock</th>
                                                <th scope="col">Costo</th>
                                                <th scope="col">Precio</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                               @foreach($reporteStock as $stock)
                                                <tr>
                                                    <td>{{$stock->Descripcion}}</td>
                                                    <td>{{$stock->Nombre}}</td>
                                                    <td>{{$stock->Stock}}</td>
                                                    <td>{{$stock->Costo}}</td>
                                                    <td>{{$stock->Precio}}</td>
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



