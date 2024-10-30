@extends('layouts.app')
@section('title', 'Productos')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Listado de Productos</h6>
            </div>
            <!-- /.page-title-left -->
            <div class="page-title-right m-1">
                <div class="row mr-b-50 mt-2">
                    <div class="col-12 mr-b-20 d-sm-block d-none">
                        <a href='{!! url('/administracion/almacen/producto-sucursal') !!}'><button class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-28">add</i>Ver Mi Stock</button></a>
                    </div>
                    <div class="col-12 mr-b-20 d-sm-none d-block">
                        <a href="../almacen/productos/create"><button class="btn btn-block btn-primary ripple"><i
                                    class="list-icon material-icons fs-28">add</i></button></a>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.page-title -->
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
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <!--<div class="col-md-12 widget-holder">-->
                <div class="widget-bg">

                    {{-- {!! Form::open([
                        'url' => '/administracion/almacen/productos_sucursal',
                        'method' => 'POST',
                        'class' => 'form-material',
                    ]) !!}
                    {{ csrf_field() }} --}}
                    <div class="widget-body clearfix">
                        <button id="btnGuardarArticulo" class="btn btn-success" type="submit"><i
                                class="material-icons list-icon">check</i>&nbsp;&nbsp;Guardar Articulos</button>
                        <!--<p>Listado de ventas</p>-->
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary">
                                    <th scope="col"></th>
                                    <th scope="col">Nombre Producto</th>
                                    <th scope="col">Tipo Moneda</th>
                                    <th scope="col">Costo</th>
                                    <th scope="col">Precio</th>
                                    <th scope="col">Stock</th>
                                    <th scope="col">Codigo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $k=0; @endphp
                                @foreach ($articulos as $articulo)
                                    <tr class="chequear" id="{{ $k }}">
                                        <td>
                                            @if ($articulo->Estado == 1)
                                                <i class="material-icons">done</i>
                                            @else
                                                <input id="check-{{ $articulo->IdArticulo }}" type="checkbox"
                                                    onclick="comprobarChekeo({{ $articulo->IdArticulo }})" />
                                            @endif
                                            <input type="text" value="{{ $articulo->CodigoInterno }}"
                                                id="ci{{ $articulo->IdArticulo }}" hidden />
                                        </td>
                                        <td>{{ $articulo->Descripcion }} </td>
                                        <td>
                                            @if ($articulo->IdTipoMoneda == 1)
                                                Soles
                                            @else
                                                Dólares
                                            @endif
                                        </td>
                                        <td><input style="width: 120px" type="number" id="c{{ $articulo->IdArticulo }}"
                                                name="costo[]" disabled value="{{ $articulo->Costo }}" min="0"
                                                step="0.01"
                                                onchange="actualizarDatosInput({{ $articulo->IdArticulo }})" /></td>
                                        <td><input style="width: 120px" type="number" id="p{{ $articulo->IdArticulo }}"
                                                name="precio[]" disabled value="{{ $articulo->Precio }}" min="0"
                                                step="0.01"
                                                onchange="actualizarDatosInput({{ $articulo->IdArticulo }})" /></td>
                                        @if ($articulo->Estado == 1)
                                            <td><input style="width: 80px" type="number" id="s{{ $articulo->IdArticulo }}"
                                                    name="stock[]" disabled value="{{ $articulo->Stock }}" min="0"
                                                    onchange="actualizarDatosInput({{ $articulo->IdArticulo }})" /></td>
                                        @else
                                            <td><input style="width: 80px" type="number" id="s{{ $articulo->IdArticulo }}"
                                                    name="stock[]" disabled value="0" min="0"
                                                    onchange="actualizarDatosInput({{ $articulo->IdArticulo }})" /></td>
                                        @endif
                                        <td>{{ $articulo->Codigo }}</td>
                                    </tr>
                                    @php $k++; @endphp
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- {!! Form::close() !!} --}}
                </div>
                <!-- /.widget-bg -->
                <!--</div>-->
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    {{-- Nueva funcion --}}
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var articulos = [];

        function comprobarChekeo(id) {
            if ($('#check-' + id).is(':checked')) {
                $("#c" + id).prop('disabled', false);
                $("#p" + id).prop('disabled', false);
                $("#s" + id).prop('disabled', false);
                capturarDatosArticulo(id);
            } else {
                $("#c" + id).prop('disabled', true);
                $("#p" + id).prop('disabled', true);
                $("#s" + id).prop('disabled', true);
                $('#s' + idArticulo).val('');
                eliminarArticulo(id);
            }
        }

        function actualizarDatosInput(idArticulo) {
            const stockActualizado = $('#s' + idArticulo).val();
            if (stockActualizado < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stock Negativo',
                    text: 'El stock no debe ser menor que cero',
                    confirmButtonText: 'Entendido',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#s' + idArticulo).val('');
                    }
                })
            }
            indice = articulos.findIndex((item => item.idArticulo === idArticulo));
            if (indice > -1) {
                articulos[indice].stock = $('#s' + idArticulo).val();
                articulos[indice].costo = $('#c' + idArticulo).val();
                articulos[indice].precio = $('#p' + idArticulo).val();
            }

        }

        function capturarDatosArticulo(idArticulo) {
            articulos.push({
                idArticulo: idArticulo,
                costo: $('#c' + idArticulo).val(),
                precio: $('#p' + idArticulo).val(),
                stock: $('#s' + idArticulo).val(),
                codigoInterno: $('#ci' + idArticulo).val(),
            });
        }

        function eliminarArticulo(id) {
            articulos = articulos.filter((item) => item.idArticulo != id);
        }


        $('#btnGuardarArticulo').click(function() {
            if (articulos.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error..',
                    text: 'No ha seleccionado ningún Artículo!',
                })
            } else {
                Swal.fire({
                    text: 'Desea crear los siguientes artículos?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Aceptar',
                    cancelButtonText: 'Cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            url: 'productos_sucursal',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'articulos': articulos
                            },
                            success: function(data) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Registro Exitoso!',
                                    text: 'Los datos enviados han sido Almacenados',
                                    allowOutsideClick: false,
                                    allowEscapeKey: false,
                                    allowEnterKey: false,
                                    stopkeydownPropagation: true
                                }).then((result) => {
                                    if (result.isConfirmed) {
                                        window.location =
                                            'productos';
                                    }
                                })

                            }
                        });
                    }
                })
            }
        })
    </script>
    {{-- Fin --}}

    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
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
@stop
