@extends('layouts.app')
		@section('title', 'Consulta Articulos - Stock')
		@section('content')		
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Verificar Artículos y Stock</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <!--<div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                            </div>
                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
                            </div>
                        </div>
                    </div>-->
                    <!-- /.page-title-right -->
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                <!-- /.page-title -->
            </div>
            <!-- /.container -->
            <!-- =================================== -->
            <!-- Different data widgets ============ -->
            <!-- =================================== -->
            <div class="container">
                <div class="widget-list">
                    <div class="widget-bg">
                        <div class="p-2 pull-left"> 
                            <strong style="color:red">Se encontraron: {{count($arrayError)}} inconsistencias</strong>
                        </div>
                        <div class="p-2 pull-left">
                            <strong style="color:orange">Se necesita emparejar: {{count($arrayDifieren)}} productos</strong>
                        </div>
                        
                        <div class="p-2 pull-right">
                        {!! Form::open(['url' => '/consultas/articulos-stock/'.$idUser, 'method' => 'POST', 'files' => true]) !!}
                                {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3 col-12">
                                    <label for="sucursal">Sucursal</label>
                                    <div class="form-group">
                                        <select class="form-control" name="sucursal">
                                            <option value="0">Seleccione Sucursal
                                                    </option>
                                            @foreach ($sucursales as $_sucursal)
                                                @if($_sucursal->IdSucursal == $sucursal)
                                                    <option selected value="{{ $_sucursal->IdSucursal }}">{{ $_sucursal->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $_sucursal->IdSucursal }}">{{ $_sucursal->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        
                                    </div>
                                </div>
                                <div class="col-md-2 col-12">
                                    <div class="form-group">
                                        <br>
                                        <button type="submit" class="btn btn-primary">Buscar</button>
                                    </div>
                                </div>
                            </div>
                            {!! Form::close() !!} 
                            @if(count($arrayValores))
                                @if(count($arrayError) > 0)
                                {!! Form::open(['url' => '/consultas/completar-tabla', 'method' => 'POST', 'files' => true]) !!}
                                {{ csrf_field() }}
                                <!--<a class="btn btn-primary" href="../completar-tabla/{{$idUser}}/{{$sucursal}}">Completar Tabla</a>
                                <a class="btn btn-primary disabled" href="">Emparejar Cantidad</a>-->
                                <input type="text" name="idUsuario" value="{{$idUser}}" hidden>
                                <input type="text" name="idSucursal" value="{{$sucursal}}" hidden>
                                <button type="submit" class="btn btn-primary">Completar Cantidad</button>
                                {!! Form::close() !!}
                                @endif
                                <br>
                                @if(count($arrayDifieren) > 0)
                                {!! Form::open(['url' => '/consultas/emparejar-cantidad', 'method' => 'POST', 'files' => true]) !!}
                                {{ csrf_field() }}
                                <!--<a class="btn btn-primary disabled" href="">Completar Tabla</a>
                                <a class="btn btn-primary" href="../emparejar-cantidad/{{$idUser}}/{{$sucursal}}">Emparejar Cantidad</a>-->
                                <input type="text" name="idUsuario" value="{{$idUser}}" hidden>
                                <input type="text" name="idSucursal" value="{{$sucursal}}" hidden>
                                <button type="submit" class="btn btn-primary">Emparejar Tabla</button>
                                {!! Form::close() !!}
                                @endif
                            @endif
                            
                        </div>
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col">ID Articulo</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Cant. T Articulo</th>
                                        <th scope="col">Cant. T Stock</th>
                                        <th scope="col">Se encuentra en T Stock</th>
                                        <th scope="col">Emparejar</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @foreach($arrayValores as $datos)
                                        <tr>
                                            <td scope="row">{{$datos[0]}}</td>
                                            <td>{{$datos[1]}}</td>
                                            <td>{{$datos[2]}}</td>
                                            <td>{{$datos[3]}}</td>
                                            <td>{{$datos[4]}}</td>
                                            <td>{{$datos[5]}}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                            </table>
                        </div>
                        <!-- /.widget-body -->
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




