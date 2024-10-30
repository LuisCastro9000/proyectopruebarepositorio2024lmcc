    @extends('layouts.app')
    @section('title', 'Crear Almacén')
    @section('content')	
            <!-- Page Title Area -->
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Crear Almacén</h6>
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
                                    {!!Form::open(array('url'=>'/administracion/almacen/traspasos','method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Nombre" type="text" name="nombre">
                                                    <label for="nombre">Nombre</label>
                                                    <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                 <div class="form-group">
                                                    <select class="form-control" name="idSucursal">
                                                        @foreach ($sucursales as $sucursal)
                                                            <option value="{{$sucursal->IdSucursal}}">{{$sucursal->Nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label for="idSucursal">Sucursal</label>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Dirección" type="text" name="direccion">
                                                    <label for="direccion">Dirección</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Teléfono" type="text" name="telefono" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');">
                                                    <label for="telefono">Teléfono</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">Crear</button>
                                            <a href="../traspasos"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
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


