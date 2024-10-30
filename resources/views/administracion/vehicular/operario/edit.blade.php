
		@extends('layouts.app')
		@section('title', 'Editar Operario')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Editar Operario</h6>
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
                                    {!!Form::open(array('url'=>'administracion/vehicular/operario/'.$operario->IdOperario,'method'=>'PUT', 'class' => 'form-material'))!!}
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <label for="nombres">Nombres y Apellidos</label>
                                                <input class="form-control" id="nombres" placeholder="Nombre y Apellido" type="text" name="nombres" value="{{$operario->Nombres}}">
                                                <span class="text-danger font-size">{{ $errors->first('nombres') }}</span>
                                            </div>
                                            <div class="col-md-6">
                                                <label for="rol">Seleccionar Rol</label>
                                                <select class="form-control" name="rol">
                                                    <option value="1">Ninguno</option>
                                                    @foreach ($roles as $rol)
                                                        @if($rol->IdRolOperario > 1)
                                                            @if($rol->IdRolOperario == $operario->IdRolOperario)
                                                                <option selected value="{{$rol->IdRolOperario}}">{{$rol->Descripcion}}</option>
                                                            @else
                                                                <option value="{{$rol->IdRolOperario}}">{{$rol->Descripcion}}</option>
                                                            @endif
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-info" type="submit">actualizar</button>
                                            <a href="../../operario"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
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
	@stop

