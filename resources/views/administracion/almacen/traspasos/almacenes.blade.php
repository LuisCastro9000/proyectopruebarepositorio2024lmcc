    @extends('layouts.app')
    @section('title', 'Listado de Almacenes')
    @section('content')	
                <!-- Page Title Area -->
                <div class="container">
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Listado de Almacenes</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right m-1">
                            <div class="row mr-b-50 mt-2">
                                <div class="col-12 mr-b-20 d-sm-block d-none">
                                    <a href="../almacen/traspasos/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">add</i> Agregar</button></a>
                                </div>
                                <div class="col-12 mr-b-20 d-sm-none d-block">
                                    <a href="../almacen/traspasos/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">add</i></button></a>
                                </div>
                            </div>
                        </div>
                        <!-- /.page-title-right -->
                    </div>
                    <!-- /.page-title -->
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
                            <div class="col-md-4 col-sm-6 col-xs-8">
                                <a href="../almacen/realizar-traspaso"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">swap_horiz</i> Realizar Traspaso</button></a>
                            </div>
                            <div class="col-md-12 widget-holder">
                                <div class="widget-bg">
                                    <!--<div class="widget-heading clearfix">
                                        <h5>TableSaw</h5>
                                    </div>-->
                                    <!-- /.widget-heading -->
                                    <div class="widget-body clearfix">
                                        <!--<p>Listado de ventas</p>-->
                                        <table id="tabla1" class="table table-responsive-md" style="width:100%">
                                            <thead>
                                                <tr class="bg-primary">
                                                    <th scope="col">Almacén</th>
                                                    <th scope="col">Dirección</th>
                                                    <th scope="col">Teléfono</th>
                                                    <th scope="col">Opciones</th>
                                                </tr>
                                            </thead>
                                                <tbody>
                                                    @foreach($almacenes as $almacen)
                                                    <tr>
                                                        <td scope="row">{{$almacen->Nombre}}</td>
                                                        <td>{{$almacen->Direccion}}</td>
                                                        <td>{{$almacen->Telefono}}</td>
                                                        <td>
                                                            <a href="../almacen/traspasos/{{$almacen->IdAlmacen}}/edit" title="Editar" ><i class="list-icon material-icons">edit</i></a>
                                                            <a href="javascript:void(0);" onclick="modalEliminar({{$almacen->IdAlmacen}})" title="Eliminar" ><i class="list-icon material-icons">delete</i></a>
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
    <script src="{{asset('assets/js/administracion/traspasos.js')}}"></script>
    <script src="{{asset('assets/js/general.js')}}"></script>
    @stop




