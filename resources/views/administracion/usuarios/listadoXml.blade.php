
        @extends('layouts.app')
		@section('title', 'Listado de XML')
		@section('content')	
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de XML pendientes por subir a S3</h6>
                    </div>
                    <!-- /.page-title-left -->
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-warning">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif

                {!!Form::open(array('url'=>'/administracion/usuarios/lista-xml/'.$id,'method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                        <div class="col-md-3 mt-4 order-md-1">
                            <div class="form-group form-material">
                                <label>Sucursal</label>
                                <select id="sucursal" class="form-control" name="sucursal">
                                    @foreach ($sucursales as $sucursal)
                                        @if($_idSucursal == $sucursal->IdSucursal)
                                            <option selected value="{{ $sucursal->IdSucursal}}">{{ $sucursal->Nombre }}</option>
                                        @else
                                            <option value="{{ $sucursal->IdSucursal}}">{{ $sucursal->Nombre }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3 mt-4 order-md-1">
                            <div class="form-group form-material">
                                <label>Tipo Comprobante</label>
                                <select id="tipoComprobante" class="form-control" name="tipoComprobante">
                                    <option value="1">Facturas y Boletas</option>
                                    <option value="2">Nota de Crédito</option>
                                    <option value="3">Guía de Remisión</option>
                                    <option value="4">Resumen Diario</option>
                                    <option value="5">Baja de Documentos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-4 order-md-2">
                            <div class="form-group form-material">
                                <label>Año</label>
                                <select id="anio" class="form-control" name="anio">
                                    <option value="2022">2022</option>
                                    <option value="2021">2021</option>
                                    <option value="2020">2020</option>
                                    <option value="2019">2019</option>
                                    <option value="2018">2018</option>
                                    <option value="1">Todos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-4 order-md-2">
                            <div class="form-group form-material">
                                <label>Mes</label>
                                <select id="mes" class="form-control" name="mes">
                                    <option value="1">Enero</option>
                                    <option value="2">Febrero</option>
                                    <option value="3">Marzo</option>
                                    <option value="4">Abril</option>
                                    <option value="5">Mayo</option>
                                    <option value="6">Junio</option>
                                    <option value="7">Julio</option>
                                    <option value="8">Agosto</option>
                                    <option value="9">Septiembre</option>
                                    <option value="10">Octubre</option>
                                    <option value="11">Noviembre</option>
                                    <option value="12">Diciembre</option>
                                    <option value="0">Todos</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-2 mt-4 order-md-3 order-last">
                            <div class="form-group">
                                <br>
                                <button type="submit" class="btn btn-primary">Buscar</button>
                            </div>
                        </div>
                    </div>
                {!!Form::close()!!}

                {!!Form::open(array('url'=>'/administracion/usuarios/guardar-xml','method'=>'POST','files'=>true))!!}
                    {{csrf_field()}}
                    <div class="row clearfix">
                        <div class="col-md-3 mt-4 order-md-1">
                            <input hidden type="text" name="idUsuario" value="{{$id}}">
                            <input hidden type="text" name="idSucursal" value="{{$_idSucursal}}">
                            <input hidden type="text" name="tipoComp" value="{{$tipoComprobante}}">
                            <input hidden type="text" name="anioDoc" value="{{$anio}}">
                            <input hidden type="text" name="mesDoc" value="{{$mes}}">
                            <div class="form-group form-material">
                                <button class="btn btn-secondary" type="submit">Subir XML y Cdr</button>
                            </div>
                        </div>
                    </div>
                {!!Form::close()!!}
            </div>
            
            <div class="container">
                <div class="widget-list">
                    <div class="row">
                        <div class="col-md-12 widget-holder">
                            <div class="widget-bg">
                                <div class="widget-body clearfix">
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Usuario</th>
                                                <th scope="col">Fecha Creación</th>
                                                <th scope="col">Tipo Comprobante</th>
                                                <th scope="col">Documento</th>
                                                <th scope="col">RutaXML</th>
                                                <th scope="col">RutaCDR</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                            @foreach ($archivosXML as $archivo)
                                                <tr>
                                                    <td>@if(isset($archivo->Usuario))
                                                        {{ $archivo->Usuario }}
                                                        @else
                                                        -
                                                        @endif
                                                        </td>
                                                    <td>{{ $archivo->FechaCreacion }}</td>
                                                    <td>@if($tipoComprobante == 3)
                                                            Guía Remisión
                                                        @elseif($tipoComprobante == 4)
                                                            Resumen Diario
                                                        @elseif($tipoComprobante == 5)
                                                            Baja Documento
                                                        @else
                                                        {{ $archivo->Descripcion }}
                                                        @endif
                                                    </td>
                                                    <td>@if($tipoComprobante == 4)
                                                        {{ $archivo->Numero }}
                                                        @elseif($tipoComprobante == 5)
                                                        {{ $archivo->Identificador }}
                                                        @else
                                                        {{ $archivo->Serie }} - {{$archivo->Numero}}
                                                        @endif
                                                    </td>
                                                    <td>{{ $archivo->RutaXml }}</td>
                                                    <td>{{ $archivo->RutaCdr }}</td>
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
        
        <script src="{{asset('assets/js/administracion/productos.js')}}"></script>
        <script>
            $(function() {
                var anio = <?php echo json_encode($anio); ?>;
                var mes = <?php echo json_encode($mes); ?>;
                var tipoComprobante = <?php echo json_encode($tipoComprobante); ?>;

                $('#tipoComprobante option[value=' + tipoComprobante + ']').prop('selected', true);
                $('#anio option[value=' + anio + ']').prop('selected', true);
                if(anio == 1){
                    $("#mes").prop('disabled', true);
                }else{
                    
                    $('#mes option[value=' + mes + ']').prop('selected', true);
                }

                $("#anio").on('change', function(){
                    var anio = $("#anio").val();
                    if(anio == 1){
                        $("#mes").prop('disabled', true);
                    }else{
                        $("#mes").prop('disabled', false);
                    }
                });
            });
        </script>
	@stop	

