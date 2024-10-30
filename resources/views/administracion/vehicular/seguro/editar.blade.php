
		@extends('layouts.app')
		@section('title', 'Editar Cliente')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Editar Cliente</h6>
                    </div>
                    <!-- /.page-title-left -->
                </div>
                @if(session('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
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
                    <div class="row">
                        <div class="col-md-12 widget-holder">
                            <div class="widget-bg">
                                <div class="widget-body clearfix">
                                    {!!Form::open(array('url'=>'administracion/vehicular/seguros-vehiculares/'.$seguroSelect->IdSeguro,'method'=>'PUT','files'=>true))!!}
                                        {{csrf_field()}}
                                        <div class="row form-material">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" placeholder="Razón Social" type="text" name="razonSocial" value="{{$seguroSelect->Descripcion}}">
                                                    <label for="nombre">Nombre o Razón Social</label>
                                                    <span class="text-danger font-size">{{ $errors->first('razonSocial') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-material">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <select class="form-control" name="tipoDocumento">
                                                        <option selected value="2">RUC</option>
                                                    </select>
                                                    <label for="tipoDoc">Tipo Documento</label>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <input class="form-control" type="text" name="nroDocumento" value="{{$seguroSelect->NumeroDocumento}}" maxlength="12">
                                                    <label for="nroDocumento">Número de Documento</label>
                                                    <span class="text-danger font-size">{{ $errors->first('nroDocumento') }}</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="departamento">Departamento</label>
                                                    <select id="departamento" class="form-control" name="departamento">
                                                        <option value="0">-</option>
                                                        @foreach ($departamentos as $departamento)
                                                            @if($seguroSelect->IdDepartamento == $departamento->IdDepartamento)
                                                                <option selected value="{{$departamento->IdDepartamento}}">{{$departamento->Nombre}}</option>
                                                            @else
                                                                <option value="{{$departamento->IdDepartamento}}">{{$departamento->Nombre}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="provincia">Provincia</label>
                                                    <select id="provincia" class="form-control" name="provincia">
                                                        <option value="0">-</option>
                                                        @foreach ($provincias as $provincia)
                                                            @if($seguroSelect->IdProvincia == $provincia->IdProvincia)
                                                                <option selected value="{{$provincia->IdProvincia}}">{{$provincia->Nombre}}</option>
                                                            @else
                                                                <option value="{{$provincia->IdDepartamento}}">{{$provincia->Nombre}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="distrito">Distrito</label>
                                                    <select id="distrito" class="form-control" name="distrito">
                                                        <option value="0">-</option>
                                                        @foreach ($distritos as $distrito)
                                                            @if($seguroSelect->Ubigeo == $distrito->IdDistrito)
                                                                <option selected value="{{$distrito->IdDistrito}}">{{$distrito->Nombre}}</option>
                                                            @else
                                                                <option value="{{$distrito->IdDistrito}}">{{$distrito->Nombre}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="direccion">Dirección</label>
                                                    <input class="form-control" type="text" name="direccion" maxlength="100" value="{{$seguroSelect->Direccion}}">
                                                </div>
                                            </div>
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">Actualizar</button>
                                            <a href="../../seguros-vehiculares"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
                                            <a href="https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias" target="_blank"><button class="btn btn-outline-danger" type="button">Consultar RUC</button></a>
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
    <script>
		$(function(){
			
            $("#departamento").on('change', function () {
                var departamento = $("#departamento").val();
                $.ajax({
                    type : 'get',
                    url : 'edit/consultar-provincias',
                    data:{'departamento':departamento},
                    success:function(data){
                        if(data.length > 0){
                            $('#distrito option').remove();
                            $('#provincia option').remove();
                            $('#distrito').append('<option value="0">-</option>');
                            $('#provincia').append('<option value="0">-</option>');
                            for(var i=0; i<data.length; i++){
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                                $('#provincia').append('<option value="'+data[i]["IdProvincia"]+'">'+data[i]["Nombre"]+'</option>');     
                            }
                        }else{
                            $('#provincia option').remove();
                            $('#distrito option').remove();
                        }
                    }
                });
            });
            
            $("#provincia").on('change', function () {
                var provincia = $("#provincia").val();
                $.ajax({
                    type : 'get',
                    url : 'edit/consultar-distritos',
                    data:{'provincia':provincia},
                    success:function(data){
                        if(data.length > 0){
                            $('#distrito option').remove();
                            $('#distrito').append('<option value="0">-</option>');
                            for(var i=0; i<data.length; i++){
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                                $('#distrito').append('<option value="'+data[i]["IdDistrito"]+'">'+data[i]["Nombre"]+'</option>');     
                            }
                        }else{
                            $('#distrito option').remove();
                        }
                    }
                });
            });
        });
    </script>
	@stop



