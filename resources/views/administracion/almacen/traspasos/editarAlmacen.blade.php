@extends('layouts.app')
    @section('title', 'Editar Almacén')
    @section('content')	
        
        <!-- Page Title Area -->
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Editar Almacén</h6>
                    </div>
                    <!-- /.page-title-left -->
                </div>
                <!-- /.page-title -->
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
                                <div class="widget-body clearfix">
                                    {!!Form::open(array('url'=>'administracion/almacen/traspasos/'.$almacen->IdAlmacen,'method'=>'PUT','files'=>true, 'class' => 'form-material'))!!}
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Nombre" type="text" name="nombre" value="{{$almacen->Nombre}}">
                                                    <label for="nombre">Nombre</label>
                                                    <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-group">
                                                    <select class="form-control" name="idSucursal">
                                                        @foreach ($sucursales as $sucursal)
                                                            @if($almacen->IdSucursal == $sucursal->IdSucursal)
                                                            <option value="{{$sucursal->IdSucursal}}" selected>{{$sucursal->Nombre}}</option>
                                                            @else
                                                            <option value="{{$sucursal->IdSucursal}}">{{$sucursal->Nombre}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <label for="idSucursal">Sucursal</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Dirección" type="text" name="direccion" value="{{$almacen->Direccion}}">
                                                    <label for="direccion">Dirección</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Teléfono" type="text" name="telefono" value="{{$almacen->Telefono}}" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <label for="telefono">Teléfono</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">Actualizar</button>
                                            <a href="../../traspasos"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
                                        </div>
                                    {!!Form::close()!!}
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



