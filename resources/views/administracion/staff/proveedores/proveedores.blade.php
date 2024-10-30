@extends('layouts.app')
@section('title', 'Proveedores')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Proveedores</h6>
            </div>
            <!-- /.page-title-left -->
            <div class="page-title-right">
                <div class="row mr-b-50 mt-2">
                    <div class="col-6 mr-b-20 d-sm-block d-none pt-2">
                        <a href="../staff/proveedores/create"><button class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-26">person_add</i> Crear</button></a>
                    </div>
                    <div class="col-6 mr-b-20 d-sm-block d-none">
                        <a target="_blank" href="excel-proveedores">
                            <span class="btn bg-excel ripple">
                                <i class="list-icon material-icons fs-20">explicit</i>XCEL
                            </span>
                        </a>
                    </div>
                    <div class="col-6 mr-b-20 d-sm-none d-block pt-2">
                        <a href="../staff/proveedores/create"><button class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-26">person_add</i></button></a>
                    </div>
                    <div class="col-6 mr-b-20 d-sm-none d-block">
                        <a target="_blank" href="excel-proveedores">
                            <span class="btn bg-excel ripple">
                                <i class="list-icon material-icons fs-20">explicit</i>XCEL
                            </span>
                        </a>
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
                                        <th scope="col">R. Social</th>
                                        <th scope="col">Documento</th>
                                        {{-- <th scope="col">Ruc</th> --}}
                                        <th scope="col">Banco</th>
                                        <th scope="col">Número Cuenta</th>
                                        <th scope="col">Teléfono</th>
                                        <th scope="col">Email</th>
                                        <th scope="col">Contacto</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($proveedores as $proveedor)
                                        <tr>
                                            <td>{{ $proveedor->RazonSocial }}</td>
                                            <td>{{ $proveedor->Descripcion }}: {{ $proveedor->NumeroDocumento }}</td>
                                            <td>{{ $proveedor->NombreBanco }}</td>
                                            <td>{{ $proveedor->CuentaCorriente }}</td>
                                            <td>{{ $proveedor->Telefono }}</td>
                                            <td>{{ $proveedor->Email }}</td>
                                            <td>{{ $proveedor->PersonaContacto }}</td>
                                            <td class="text-center">
                                                <a href="proveedores/{{ $proveedor->IdProveedor }}/edit"><button
                                                        class="btn btn-primary px-2 py-1"><i
                                                            class="list-icon material-icons">edit</i></button></a>
                                                <a href="javascript:void(0);"><button class="btn btn-primary px-2 py-1"
                                                        data-toggle="modal" data-target="#exampleModal"
                                                        onclick="modalEliminar({{ $proveedor->IdProveedor }})"><i
                                                            class="list-icon material-icons">clear</i></button></a>
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
    <!-- /.container -->

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <!--<div class="modal-header">
                                                                          <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                                        </div>-->
                <div class="modal-body">
                    <h6 class="modal-title">Desea Eliminar Proveedor?</h6>
                    <input id="idProveedor" hidden />
                </div>
                <div class="modal-footer">
                    <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });

        });
    </script>
    <script>
        function modalEliminar(id) {
            $("#idProveedor").val(id);
        }
        $(function() {
            $("#exampleModal button.btnEliminar").on("click", function(e) {
                var id = $("#idProveedor").val();
                window.location = 'proveedores/' + id + '/delete';
            });
        });
    </script>
@stop
