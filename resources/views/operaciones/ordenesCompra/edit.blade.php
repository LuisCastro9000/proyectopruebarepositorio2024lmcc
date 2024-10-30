@extends('layouts.app')
@section('title', 'Editar-Orden-Compra')
@section('content')
    <link rel="stylesheet" href="{{ asset('assets/sweetAlert/sweetAlert2-11.7.3/sweetalert2.min.css') }}" />
    <div class="container">
        <section class="d-flex justify-content-center justify-content-md-start my-4 d-flex">
            <article>
                <h6>Editar Orden de Compra</h6>
            </article>
        </section>
        <section class="jumbotron bg-jumbotron--white p-4 my-4">
            <div class="row">
                <article class="col-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Proveedor</label>
                        <input type="text" class="form-control" value="{{ $ordenCompra->Nombres }}" readonly>
                    </div>
                </article>
                <article class="col-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Serie</label>
                        <input type="text" class="form-control" placeholder="Example input"
                            value="{{ $ordenCompra->Serie }}" readonly>
                    </div>
                </article>
                <article class="col-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Número</label>
                        <input type="text" class="form-control" value="{{ $ordenCompra->Numero }}" readonly>
                    </div>
                </article>
                <article class="col-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Tipo Compra</label>
                        <input type="text" class="form-control"
                            value="{{ $ordenCompra->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}" readonly>
                        <input id="tipoCompra" type="text" class="form-control" value="{{ $ordenCompra->TipoCompra }}"
                            hidden>
                    </div>
                </article>
                <article class="col-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Moneda</label>
                        <input type="text" class="form-control"
                            value="{{ $ordenCompra->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}" readonly>
                        <input id="tipoMoneda" type="text" class="form-control" value="{{ $ordenCompra->IdTipoMoneda }}"
                            hidden>
                    </div>
                </article>
                <article class="col-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Fecha Recepción</label>
                        <input type="text" class="form-control" value="{{ $ordenCompra->FechaRecepcion }}" readonly>
                    </div>
                </article>
            </div>
            <div class="row mt-4">
                <div class="col-md-6">
                    <button id="agregarArticulo" class="btn btn-info ripple" type="button" data-toggle="modal"
                        data-target=".bs-modal-lg-productos"><i class="list-icon material-icons">add_circle</i> Agregar
                        <span class="caret"></span>
                    </button>
                </div>
            </div>
            <div class="row mt-2">
                <div class="col-md-12">
                    <table id="tablaAgregado" class="table table-responsive-lg" style="width:100%">
                        <thead>
                            <tr class="bg-primary-contrast">
                                <th scope="col" data-tablesaw-priority="persist">Código
                                </th>
                                <th scope="col">Descripción</th>
                                <th scope="col">Und/Medida</th>
                                <th scope="col">Precio Venta</th>
                                <th scope="col">Precio Costo</th>
                                <th scope="col">Cantidad</th>
                                <th scope="col">Importe</th>
                                <th scope="col">Acción</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>

            <div class="row mt-2">
                <div class="col-lg-8 col-md-12">
                    <div class="form-group">
                        <textarea id="observacion" class="form-control" rows="4" name="observacion">{{ $ordenCompra->Observacion }}</textarea>
                        <label>Observación</label>
                    </div>
                </div>
                <div class="col-md-4 col-8">
                    <div class="row">
                        <div class="col-lg-5 col-8">
                            <label>Op Gravada:</label>
                        </div>
                        <div class="col-lg-5 col-8">
                            <input id="opGravada" name="subtotal" type="text" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-8">
                            <label>Op Exonerada:</label>
                        </div>
                        <div class="col-lg-5 col-8">
                            <input id="opExonerado" name="opExonerado" type="text" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-8">
                            <label>IGV (18%):</label>
                        </div>
                        <div class="col-lg-5 col-8">
                            <input id="igv" type="text" name="igv" readonly>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-lg-5 col-8">
                            <label>Total:</label>
                        </div>
                        <div class="col-lg-5 col-8">
                            <input id="total" type="text" name="total" readonly>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-3 d-flex justify-content-md-end justify-content-center  flex-wrap">
                <button id="btnCompraPendiente" class="btn btn--celeste btnGuardarOrdenCompra" value="Pendiente"
                    type="submit">Atualizar Orden de Compra</button>

            </div>
        </section>

        {{-- Modales --}}
        @include('modal._modalAgregarProducto')
    @stop

    @section('scripts')
        <script src="{{ asset('assets/js/operaciones/ordenesCompra/scriptStoreOrdenCompra.js?v=' . time()) }}"></script>
        <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
        <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert1.js?v=' . time()) }}"></script>
        <script src="{{ asset('assets/js/operaciones/ordenesCompra/scriptCargarProductoModal.js') }}"></script>
        <script>
            let idRespuesta = ''
            const tipoPeticion = 'PUT';
            const urlOrdenCompra = "{{ route('ordenDeCompra.update', [':id']) }}".replace(':id', @json($idOrdenComrpra));
            const token = "{{ csrf_token() }}";
        </script>
        <script>
            $(function() {
                let items = @json($itemsOrdenCompra);
                console.log(items);
                let tipoOrdenCompra = @json($tipoOrdenCompra);
                let step;
                for (var i = 0; i < items.length; i++) {
                    datosProductos = new Array();
                    // productoEnTabla(id, descripcion, unidadMedida, precioVenta, precioCosto, cantidad, importeFinal, step);
                    if (items[i]["IdUnidadMedida"] == 1) {
                        step = '';
                    } else {
                        step = '0.05';
                    }
                    if (tipoOrdenCompra == 2) {
                        console.log('soy tipo compra 2');
                        precioVenta = parseFloat(items[i]["Precio"] / igv);
                        precioCosto = parseFloat(items[i]["PrecioCosto"] / igv);
                    } else {
                        precioVenta = items[i]["Precio"];
                        precioCosto = items[i]["PrecioCosto"];
                    }
                    productoEnTabla(items[i]["IdArticulo"], items[i]["Descripcion"], items[i]["UniMedida"],
                        precioVenta, precioCosto, items[i]["Cantidad"], items[i]["Importe"], step)
                }
            });
        </script>


        <script>
            let sucExonerado = @json($sucExonerado);
            // BUSCAR PRODUCTO POR TIPO MONEDA
            $(function() {
                var tipoMoneda = $("#tipoMoneda").val();
                $.ajax({
                    type: 'get',
                    // url: '../ajax/productos-por-tipo-moneda',
                    url: "{{ route('articulos.buscar-productos-ajax') }}",
                    data: {
                        'tipoMoneda': tipoMoneda
                    },
                    success: function(data) {
                        $('#listaProductos').empty();
                        $("#inputBuscarProductos").val('');
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        var precioExo = '';
                        cargarProductosEnVista(data, precioExo, moneda);
                    }

                });
            });
            // BUSCAR PRODUCTO POR CATEGORIA
            $("#categoria").on('change', function() {
                var idCategoria = $("#categoria").val();
                var textoBusqueda = $("#inputBuscarProductos").val();
                var tipoMoneda = $("#tipoMoneda").val();
                $.ajax({
                    type: 'get',
                    // url: '../ajax/productos-por-busqueda',
                    url: "{{ route('articulos.buscar-productos-ajax') }}",
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda,
                        'idCategoria': idCategoria
                    },
                    success: function(data) {
                        console.log(data);
                        $('#listaProductos').empty();
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        var precioExo = '';
                        cargarProductosEnVista(data, precioExo, moneda);
                    }
                });

            });

            // BUSCAR PRODUCTOS DESDE EL INPUT
            $("#inputBuscarProductos").keyup(function() {
                var textoBusqueda = $("#inputBuscarProductos").val();
                if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                    var tipoMoneda = $("#tipoMoneda").val();
                    var idCategoria = $("#categoria").val();
                    $.ajax({
                        type: 'get',
                        // url: '../ajax/productos-por-busqueda',
                        url: "{{ route('articulos.buscar-productos-ajax') }}",
                        data: {
                            'textoBuscar': textoBusqueda,
                            'tipoMoneda': tipoMoneda,
                            'idCategoria': idCategoria
                        },
                        success: function(data) {
                            console.log(data);
                            $('#listaProductos').empty();
                            var moneda;
                            if (tipoMoneda == 1) {
                                moneda = 'S/';
                            } else {
                                moneda = '$';
                            }
                            var precioExo = '';
                            cargarProductosEnVista(data, precioExo, moneda);
                        }
                    });
                }
            });

            // BUSCAR PRODUCTO POR PAGINACION
            $(document).on('click', '.pagProd a', function(e) {
                e.preventDefault();
                var page = $(this).attr('href').split('page=')[1];

                var textoBusqueda = $("#inputBuscarProductos").val();
                var tipoMoneda = $("#tipoMoneda").val();
                var idCategoria = $("#categoria").val();
                $.ajax({
                    type: 'get',
                    // url: '../ajax/productos-por-paginacion?page=' + page,
                    url: "{{ route('articulos.paginar-productos-ajax') }}?page=" + page,
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda,
                        'idCategoria': idCategoria
                    },
                    success: function(data) {
                        var inicio = data["to"] - 1;
                        var moneda;
                        if (tipoMoneda == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }
                        var precioExo = '';
                        cargarProductosEnVista(data, precioExo, moneda);
                    }
                });
            });
        </script>
        <script>
            $(function() {
                $(document).ready(function() {
                    $('#tableOrdenesDeCompra').DataTable({
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
