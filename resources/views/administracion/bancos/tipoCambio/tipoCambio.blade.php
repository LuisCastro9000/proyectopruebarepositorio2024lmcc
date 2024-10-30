@extends('layouts.app')
		@section('title', 'Tipo de Cambio')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Tipo de Cambio</h6>
                    </div>
                </div>
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
                            <div class="widget-bg form-material">   
                                <!-- /.widget-heading -->
                                <div class="widget-body clearfix">
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Fecha</th>
                                                <th scope="col">Tipo de Cambio Compras</th>
                                                <th scope="col">Tipo de Cambio Ventas</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($tiposCambios as $tipoCambio)
                                                    <tr>
                                                        <td>{{$tipoCambio->FechaCreacion}}</td>
                                                        <td>{{$tipoCambio->TipoCambioCompras}}</td>
                                                        <td>{{$tipoCambio->TipoCambioVentas}}</td>
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

            <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                <div class="modal-dialog" role="document">
                    {!!Form::open(array('url'=>'/administracion/bancos/tipo-cambio','method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                    {{csrf_field()}}
                    <div class="modal-content">
                        <div class="modal-header">
                         <h6 class="modal-title">Configurar Tipo de Cambio</h6>
                        </div>
                        <div class="modal-body p-4">
                            <fieldset class="fieldset fieldset--bordeCeleste">
                            <legend class="legend legend--colorNegro">Tipo Cambio:</legend>
                                <div class="form-group">
                                    <label for="soles">TC Compras</label>
                                    <input id="tipoCambioCompras" class="form-control" type="number"
                                        name="TipoCambioCompras">
                                </div>
                                <div class="form-group">
                                    <label for="soles">TC Ventas</label>
                                    <input id="tipoCambioVentas" class="form-control" type="number"
                                        name="TipoCambioVentas">
                                </div>
                            </fieldset>
                            <fieldset class="fieldset fieldset--bordeCeleste">
                            <legend class="legend legend--colorNegro">Tipo de Cambio Sunat:</legend>
                                <div class="form-group">
                                    <label for="soles"><span>TCS Compras</span></label>
                                    <input id="tipoCambioComprasSunat" class="form-control" type="number"
                                        name="TipoCambioComprasSunat">
                                </div>
                                <div class="form-group">
                                    <label for="soles"><span>TCS ventas</span></label>
                                    <input id="tipoCambioVentasSunat" class="form-control" type="number"
                                        name="TipoCambioVentasSunat">
                                </div>
                            </fieldset>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary btnEliminar">Aceptar</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        </div>
                    </div>
                    {!!Form::close()!!}
                </div>
            </div>
            
            
		@stop			
			
		@section('scripts')		
    <script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
                    "order": [[ 0, "desc" ]],
                    language: {
                        processing:     "Procesando...",
                        search:         "Buscar:",
                        lengthMenu:     "Mostrar _MENU_ registros",
                        info:           "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty:      "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered:   "",
                        infoPostFix:    "",
                        loadingRecords: "Cargando...",
                        zeroRecords:    "No se encontraron resultados",
                        emptyTable:     "Ningún dato disponible en esta tabla",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        aria: {
                            sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
            
        });
        
    </script>
    <script>
        $(function() {
            var tiposCambios = <?php echo json_encode(count($validarTipoCambio));?>;
            if(tiposCambios == 0){
                $("#exampleModal").modal("show");
            }
        });
       
    </script>
	@stop