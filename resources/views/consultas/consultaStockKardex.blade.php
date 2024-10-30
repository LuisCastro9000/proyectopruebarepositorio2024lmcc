@extends('layouts.app')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css" rel="stylesheet" />
		@section('title', 'Consulta Articulos - Stock')
		@section('content')		
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Verificar Artículos y Kardex</h6>
                    </div>
                    <!-- /.page-title-left -->
                    <!--<div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                            </div>
                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
                            </div>
                        </div>
                    </div>-->
                    <!-- /.page-title-right -->
                </div>
                @if (session('error'))
                    <div class="alert alert-danger">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('error') }}
                    </div>
                @endif
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
                    <div class="widget-bg">
                        <div class="p-2 pull-left">
                            <strong style="color:orange">Se necesita emparejar: {{count($arrayDifieren)}} productos</strong>
                        </div>
                        <div class="p-2 pull-right">
                            @if(count($arrayDifieren) > 0)
                            <button class="btn btn-primary" data-toggle="modal" data-target=".bs-modal-lg" onclick="modalEmparejar()" type="button">Emparejar Cantidad</button>
                            @else
                            <button class="btn btn-primary disabled">Emparejar Cantidad</button>
                            @endif
                            
                        </div>
                        <div class="widget-body clearfix">
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col"></th>
                                        <th scope="col">ID Articulo</th>
                                        <th scope="col">Descripción</th>
                                        <th scope="col">Tabla Articulo</th>
                                        <th scope="col">Total Kardex</th>
                                        <th scope="col">Emparejar</th>
                                    </tr>
                                </thead>
                                    <tbody>
                                        @php $k=0; @endphp
                                        @foreach($arrayValores as $datos)
                                        <tr class="chequear" id="{{$k}}">
                                            <td>
                                                {{$datos[0]}}
                                            </td>
                                            <td scope="row">{{$datos[0]}}</td>
                                            <td>{{$datos[1]}}</td>
                                            <td>{{number_format($datos[2],2)}}</td>
                                            <td>{{number_format($datos[3],2)}}</td>
                                            <td>{{$datos[4]}}</td>
                                        </tr>
                                        @php $k++; @endphp
                                        @endforeach
                                    </tbody>
                            </table>
                        </div>
                        <!-- /.widget-body -->
                    </div>
                       
                    <!-- /.row -->
                </div>
                <!-- /.widget-list -->
                
                    <div class="modal fade bs-modal-lg" id="modal-emparejar" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                        <div class="modal-dialog">
                        {!!Form::open(array('url'=>'/consultas/articulos-kardex/emparejar-stock-kardex','method'=>'POST', 'class' => 'form-material', 'id' => 'form-emparejar'))!!}
                            {{csrf_field()}}
                            <div class="modal-content">
                                <div class="modal-header">
                                    <label class="fs-14">Desea emparejar los siguientes productos al total de Kardex?</h6>
                                </div>
                                <div class="modal-body form-material">
                                    <div>
                                        <label class="fs-12">Se emparejaran un total de <strong id="total" class="fs-14 text-black"></strong> producto(s).</label>
                                    </div>
                                </div>
                                <input type="hidden" name="idUser" value="{{$idUser}}"/>
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
            </div>
            <!-- /.container -->
		@stop			
			
	@section('scripts')	
    <script type="text/javascript" src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script>
        var table;
        $(function() {
            
            $(document).ready(function () {
                table = $('#table').DataTable({
                    columnDefs: [
                        {
                            targets: 0,
                            checkboxes: {
                                selectRow: true,
                            },
                        },
                    ],
                    select: {
                        style: "multi",
                    },
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

        function modalEmparejar() {
            var rows_selected = table.column(0).checkboxes.selected();
            console.log(rows_selected);
            $("#modal-emparejar").modal("show");
            var total = rows_selected.length;
            $("#total").text(total);
            /*var masCero = 0;
            var menosCero = 0;
            $.each(rows_selected, function (index, rowId) {
                var datos = rowId.split("-");
                var stock = parseInt(datos[1]);
                if (stock > 0) {
                    masCero = masCero + 1;
                } else {
                    menosCero = menosCero + 1;
                }
            });

            $("#masCero").text(masCero);*/
        }

        $("#form-emparejar").on("submit", function (e) {
            var form = this;
            var rows_selected = table.column(0).checkboxes.selected();
            $.each(rows_selected, function (index, rowId) {
                //var datos = rowId.split("-");
                var idArticulo = parseInt(rowId);
                //var ids = parseInt(datos[0]);
                $(form).append($("<input>").attr("type", "hidden").attr("name", "idArticulo[]").val(idArticulo));
            });
        });
        
    </script>
	@stop




