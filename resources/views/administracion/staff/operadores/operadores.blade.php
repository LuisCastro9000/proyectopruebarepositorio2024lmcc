
		@extends('layouts.app')
		@section('title', 'Operadores')
		@section('content')	
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Operadores</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                <a href="../staff/operadores/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">person_add</i> Crear</button></a>
                            </div>
                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="../staff/operadores/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">person_add</i></button></a>
                            </div>
                        </div>
                    </div>
                    <!-- /.page-title-right -->
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
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
                                <!--<div class="widget-heading clearfix">
                                    <h5>TableSaw</h5>
                                </div>-->
                                <!-- /.widget-heading -->
                                <div class="widget-body clearfix">
                                    <!--<p>Listado de ventas</p>-->
                                    <table id="table" class="table table-responsive-sm" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th scope="col">Operador</th>
                                                <th scope="col">Descripción</th>
                                                <th scope="col">Opciones</th>
                                            </tr>
                                        </thead>
                                            <tbody>
                                                @foreach($operadores as $operador)
                                                <tr>
                                                    <td>{{$operador->Rol}}</td>
                                                    <td>{{$operador->Descripcion}}</td>
                                                    <td class="text-center">
                                                        <a href="operadores/{{$operador->IdOperador}}/edit"><button class="btn btn-primary"><i class="list-icon material-icons">edit</i></button></a>
                                                        <a href="javascript:void(0);"><button class="btn btn-primary" data-toggle="modal" data-target="#exampleModal" onclick="modalEliminar({{$operador->IdOperador}})"><i class="list-icon material-icons">clear</i></button></a>
                                                    </td>
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
                  <div class="modal-content">
                    <!--<div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    </div>-->
                    <div class="modal-body">
                      <h6 class="modal-title">Desea Eliminar Operador?</h6>
                      <input id="idOperador" hidden/>
                    </div>
                    <div class="modal-footer">
                        <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                    </div>
                  </div>
                </div>
            </div>
            <!-- /.container -->
		@stop			
			
		@section('scripts')	
    <script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
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
        function modalEliminar(id){
            $("#idOperador").val(id);
        }
        $(function() {
            $("#exampleModal button.btnEliminar").on("click", function(e) {
                var id = $("#idOperador").val();
                window.location='operadores/'+id+'/delete';
            });
        });
    </script>
	@stop



