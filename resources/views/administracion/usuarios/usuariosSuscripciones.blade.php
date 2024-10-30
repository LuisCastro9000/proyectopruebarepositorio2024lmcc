
        @extends('layouts.app')
        <link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
		@section('title', 'Suscripciones de Usuarios')
		@section('content')	
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Suscripciones</h6>
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
                <div id="modal">

                </div>
            </div>
            
            <div class="container">
                <div class="widget-list">
                    <div class="row">
                        <div class="col-md-12 widget-holder">
                            <div class="widget-bg">
                                <div class="pb-2">
                                        <button class="btn btn-success" onclick="seleccionarSuscripciones()" type="button"><i class="material-icons list-icon">check</i>&nbsp;Guardar Cambios</button>
                                    </div>
                                <div class="widget-body clearfix">
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col"></th>
                                                <th scope="col">Razon Social</th>
                                                <th scope="col">Usuario</th>
                                                <th scope="col">Plan</th>
                                                <th scope="col">Fecha Final Contrato</th>
                                                <th scope="col">Fecha Final CDT</th>
                                                <th scope="col">Monto Pagar</th>
                                                <th scope="col">Días Bloqueo</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @php $k=0; @endphp
												@foreach($usuariosSuscripciones as $usuarioSuscripcion)
                                                    @if($usuarioSuscripcion->Mostrar == 1)
                                                        <tr class="chequear" id="{{$k}}">	
                                                            <td>
                                                                {{$usuarioSuscripcion->IdSuscripcion}}
                                                            </td>
                                                            <td>{{$usuarioSuscripcion->Empresa}}</td>
                                                            <td>{{$usuarioSuscripcion->Nombre}} </td>
                                                            <td>
                                                                @if($usuarioSuscripcion->Plan == 1)
                                                                    Mensual
                                                                @elseif($usuarioSuscripcion->Plan == 2)
                                                                    Semestral
                                                                @else
                                                                    Anual
                                                                @endif
                                                            </td>
                                                            <td>{{$usuarioSuscripcion->FechaFinalContrato}}</td>
                                                            <td>{{$usuarioSuscripcion->FechaFinalCDT}}</td>
                                                            <td>{{$usuarioSuscripcion->MontoPago}}</td>
                                                            <td>{{$usuarioSuscripcion->Bloqueo}}</td>
                                                        </tr>
                                                        @php $k++; @endphp
                                                    @endif
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

            <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
               <div class="modal-dialog">
               {!!Form::open(array('url'=>'/administracion/usuarios-suscripciones/guardar-cambios','method'=>'POST', 'class' => 'form-material', 'id' => 'form-suscripciones'))!!}
                {{csrf_field()}}
                  <div class="modal-content">
                       <div class="modal-header">
                           <label class="fs-14">Desea renovar la suscripcion de estos usuarios?</h6>
                       </div>
                       <!--<div class="modal-body form-material">
                           <div>
                               <label class="fs-12">Se eliminaran <strong id="total" class="fs-14 text-black"></strong> producto(s), de los cuales <strong id="masCero" class="fs-14 text-danger"></strong> aún tienen stock</label>
                           </div>
                       </div>-->
                       <div class="modal-footer">
                           <div class="form-actions btn-list mt-3">
                               <button class="btn btn-info">Aceptar</button>
                               <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                           </div>
                       </div>
                  </div>
                  {!!Form::close()!!}
               </div>
            </div>
            <!-- /.container -->
		@stop			
			
        @section('scripts')	
        
        <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
        <script src="{{asset('assets/js/administracion/productos.js')}}"></script>
        <script>
            function seleccionarSuscripciones() {
                var rows_selected = table.column(0).checkboxes.selected();
                $("#mostrarmodal").modal("show");
            }

            $('#form-suscripciones').on('submit', function(e) {
                var form = this;
                var rows_selected = table.column(0).checkboxes.selected();
                $.each(rows_selected, function(index, rowId) {
                    $(form).append(
                        $('<input>')
                        .attr('type', 'hidden')
                        .attr('name', 'id[]')
                        .val(rowId)
                    );
                });

            });
        </script>
	@stop	

