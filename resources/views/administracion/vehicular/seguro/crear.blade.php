@extends('layouts.app')
		@section('title', 'Crear Seguro Vehicular')
		@section('content')	
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Crear Seguro Vehicular</h6>
                    </div>
                    <!-- /.page-title-left -->
                </div>
                @if($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif 
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
                                        <div class="row form-material">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <select id="tipoDoc" class="form-control" name="tipoDocumento">
                                                    <option value="2" selected >RUC</option>
                                                    </select>
                                                    <label for="tipoDoc">Tipo Documento</label>
                                                </div>
                                            </div>
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <input id="numDoc" class="form-control" placeholder="Número de Documento" type="text" name="numDoc" maxlength="12" value="{{old('numDoc')}}">
                                                    <label for="numDoc">Número de Documento</label>
                                                    <span class="text-danger font-size">{{ $errors->first('numDoc') }}</span>
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <button id="consultar" class="btn btn-primary">Buscar</button>
                                            </div>
                                        </div>
                                    
                                    {!!Form::open(array('url'=>'administracion/vehicular/seguros-vehiculares','method'=>'POST','files'=>true))!!}
                                        {{csrf_field()}}
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label for="razonSocial">Nombre o Razón Social</label>
                                                    <input id="razonSocial" class="form-control" type="text" name="razonSocial" value="{{old('razonSocial')}}">
													<span class="text-danger font-size">{{ $errors->first('razonSocial') }}</span>
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
                                                            <option value="{{$departamento->IdDepartamento}}">{{$departamento->Nombre}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="provincia">Provincia</label>
                                                    <select id="provincia" class="form-control" name="provincia">
                                                       
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="distrito">Distrito</label>
                                                    <select id="distrito" class="form-control" name="distrito">
                                                        
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="direccion">Dirección</label>
                                                    <input id="direccion" class="form-control" type="text" name="direccion" maxlength="100" value="{{old('direccion')}}">
                                                	<span class="text-danger font-size">{{ $errors->first('direccion') }}</span>
												</div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <input id="tipoDocumento" hidden class="form-control" type="text" name="tipoDocumento">
                                            <input id="nroDocumento" hidden class="form-control" type="text" name="nroDocumento">
                                        </div>
                                        
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">Crear</button>
                                            <a href="../seguros-vehiculares"><button class="btn btn-outline-default" type="button">Cancelar</button></a>
                                            <!--<a href="https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias" target="_blank"><button class="btn btn-outline-danger" type="button">Consultar RUC</button></a>-->
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
                $('#consultar').on('click', function(){
                   var tipDoc = $("#tipoDoc option:selected").val();
                   var numdoc = $("#numDoc").val();
                   $.ajax({
                        type:'get',
                        url:'create/consultar-ruc',
                        data:{
                            'idDoc': tipDoc, 
                            'numDoc': numdoc
                        },
                        success: function(data){
                            console.log(data);
                            $('#departamento option[value="0"]').prop('selected', true);
                            $('#distrito option').remove();
                            $('#provincia option').remove();
                            if((data[0]) == 1){
                                if(tipDoc == 1){
                                    //$('#tipoDocumento').val(data[1]);
                                    $('#nroDocumento').val(data[2]);
                                    $('#razonSocial').val(data[3]);
                                    $('#nombreComercial').val("-");
                                    $('#direccion').val("-");
                                    $('#condicion').text("-");
                                    $('#estado').text("-");
                                }
                                if(tipDoc == 2){
                                    //$('#tipoDocumento').val(data[1]);
                                    $('#nroDocumento').val(data[2]);
                                    $('#razonSocial').val(data[3]);
                                    $('#nombreComercial').val(data[4]);
                                    $('#direccion').val(data[5]);
                                    $('#condicion').text(data[9]);
                                    $('#estado').text(data[10]);
                                    if(data[6] != null){
                                        $('#departamento option[value="'+data[6]+'"]').prop('selected', true);

                                        $('#provincia').append('<option value="0">-</option>');
                                        for(var i=0; i<data[7][1].length; i++){
                                            if(data[7][1][i]["IdProvincia"] == data[7][0]){
                                                $('#provincia').append('<option selected value="'+data[7][1][i]["IdProvincia"]+'">'+data[7][1][i]["Nombre"]+'</option>');  
                                            }else{
                                                $('#provincia').append('<option value="'+data[7][1][i]["IdProvincia"]+'">'+data[7][1][i]["Nombre"]+'</option>');  
                                            }   
                                        }

                                        $('#distrito').append('<option value="0">-</option>');
                                        for(var j=0; j<data[8][1].length; j++){
                                            if(data[8][1][j]["IdDistrito"] == data[8][0]){
                                                $('#distrito').append('<option selected value="'+data[8][1][j]["IdDistrito"]+'">'+data[8][1][j]["Nombre"]+'</option>');  
                                            }else{
                                                $('#distrito').append('<option value="'+data[8][1][j]["IdDistrito"]+'">'+data[8][1][j]["Nombre"]+'</option>');  
                                            }   
                                        }
                                    }
                                }
                                if(tipDoc == 3){
                                    alert("El Servicio no funciona para Pasaportes");
                                }
                            }else{
                                $('#nombreComercial').val("");
                                $('#razonSocial').val("");
                                $('#direccion').val("");
                                $('#condicion').text("-");
                                $('#estado').text("-");
                                alert("Número de RUC o DNI no encontrado");
                            }
                                		
                        },
                        error: function (jqXHR, textStatus, errorThrown) {
                            alert("servicio no disponible");
                        }
                        
                        
                    });
                });
        });
        $("#numDoc" ).keyup(function() {
            var numeroDoc = $("#numDoc").val();
            var tipDoc = $("#tipoDoc option:selected").val();
            $("#nroDocumento").val(numeroDoc);
            $("#tipoDocumento").val(tipDoc);
        });
        $("#tipoDoc").on('change', function () {
            var numeroDoc = $("#numDoc").val();
            var tipDoc = $("#tipoDoc option:selected").val();
            $("#nroDocumento").val(numeroDoc);
            $("#tipoDocumento").val(tipDoc);
        });
    </script>
    <script>
        $(function(){
            $("#departamento").on('change', function () {
                var departamento = $("#departamento").val();
                
                $.ajax({
                    type : 'get',
                    url : 'create/consultar-provincias',
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
                    url : 'create/consultar-distritos',
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

