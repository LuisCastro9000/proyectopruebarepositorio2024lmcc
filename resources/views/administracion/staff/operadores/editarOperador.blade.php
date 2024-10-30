
		@extends('layouts.app')
		@section('title', 'Editar Operador')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Editar Operador</h6>
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
                                    {!!Form::open(array('url'=>'administracion/staff/operadores/'.$operador->IdOperador,'method'=>'PUT','files'=>true, 'class' => 'form-material'))!!}
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="operador" value="{{$operador->Rol}}">
                                                    <label for="operador">Operador</label>
                                                    <span class="text-danger font-size">{{ $errors->first('operador') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="descripcion" value="{{$operador->Descripcion}}">
                                                    <label for="descripcion">Descripción</label>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">Actualizar</button>
                                            <a href="../../operadores"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
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



