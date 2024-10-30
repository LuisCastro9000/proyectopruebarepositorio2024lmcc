
		@extends('layouts.app')
		@section('title', 'Editar Marca')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Editar Marca</h6>
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
                                    {!!Form::open(array('url'=>'administracion/almacen/marcas/'.$marca->IdMarca,'method'=>'PUT','files'=>true, 'class' => 'form-material'))!!}
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" id="nombre" placeholder="Nombre" type="text" name="nombre" value="{{$marca->Nombre}}">
                                                    <label for="nombre">Nombre</label>
                                                    <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                                </div>
                                            </div>
                                            
                                            <!--<div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" id="nombre" placeholder="Descripción" type="text" name="descripcion" value="{{$marca->Descripcion}}">
                                                    <label for="descripcion">Descripcion</label>
                                                </div>
                                            </div>-->
                                        </div>
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="fileUpload btnCambiarFotoPerfil">
                                                        <i class="list-icon material-icons">image</i><span class="hide-menu">Cargar Imagen</span>
                                                        <input id="archivo" class="upload btn" type="file" name="imagen" accept=".png, .jpg, .jpeg, .gif"/>
                                                    </div>
                                                    <span class="text-danger fs-12">Peso máx. 300 Kb</span>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <img id="imgPrevia" src="" alt="Vista de Imagen" width="100%"/>
                                                </div>
                                                <span class="text-danger font-size">{{ $errors->first('imagen') }}</span>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">actualizar</button>
                                            <a href="../../marcas"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
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
			
	@section('scripts')		
    <script src="{{asset('assets/js/administracion/marcas.js')}}"></script>
	@stop

