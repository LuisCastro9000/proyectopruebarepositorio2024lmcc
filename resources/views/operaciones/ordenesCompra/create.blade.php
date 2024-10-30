<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/center-circle.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/js/scriptCompras.js') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Generar Orden-Compra</title>
    <!-- CSS -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css?v' . rand(1, 99)) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/newStyles.css?v' . rand(1, 99)) }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <!-- Head Libs -->
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>
</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        @include('schemas.schemaHeader')
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <aside class="site-sidebar clearfix">
                <div class="container">
                    @include('schemas.schemaSideNav')
                </div>
                <!-- /.container -->
            </aside>
            <!-- /.site-sidebar -->
            <main class="main-wrapper clearfix">
                <!-- Page Title Area -->
                <div class="container">
                    <br />
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Generar Orden de Compra</h6>
                        </div>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="container">
                    <div class="widget-list">
                        <div class="row">
                            <div class="col-md-12 widget-holder">
                                <div class="widget-bg">
                                    <div class="widget-body clearfix">
                                        <!--{!! Form::open([
                                            'url' => '/operaciones/compras',
                                            'method' => 'POST',
                                            'files' => true,
                                            'class' => '',
                                        ]) !!}
                                        {{ csrf_field() }}-->
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    {{-- <label>Proveedor</label> --}}
                                                    <select class="m-b-10 form-control select2-hidden-accessible"
                                                        id="proveedores" name="proveedor" data-placeholder="Choose"
                                                        data-toggle="select2" tabindex="-1" aria-hidden="true">
                                                        <option value="0">Seleccione proveedor</option>
                                                        @foreach ($proveedores as $proveedor)
                                                            <option value="{{ $proveedor->IdProveedor }}">
                                                                {{ $proveedor->Nombre }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Serie</label></div>
                                                        </div>
                                                        <input id="serie" class="form-control disabled-elemento"
                                                            placeholder="Serie" type="text" name="serie"
                                                            value="{{ $serie }}">

                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Número</label></div>
                                                        </div>
                                                        <input id="numero" class="form-control disabled-elemento"
                                                            placeholder="Número" name="numero" type="text"
                                                            onkeypress='return event.charCode >= 48 && event.charCode <= 57'
                                                            value="{{ $numero }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text font-weight-bolder">
                                                                <label>Fecha Emisión</label></label>
                                                            </div>
                                                        </div>
                                                        <input id="fechaEmision" type="text"
                                                            class="form-control datepicker disabled-elemento"
                                                            name="fechaEmitida"
                                                            data-plugin-options='{"autoclose": true, "format": "dd-mm-yyyy"}'
                                                            autocomplete="off" onkeydown="return false"
                                                            data-date-end-date="0d" required
                                                            value="{{ $fecha }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Fecha
                                                                    Recepción</label>
                                                            </div>
                                                        </div>
                                                        <input id="fechaRecepcion" type="text"
                                                            class="form-control datepicker" name="fechaEmitida"
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                            autocomplete="off" onkeydown="return false" required
                                                            value="">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    @if ($subniveles->contains('IdSubNivel', 46))
                                                        <select id="tipoMoneda" class="form-control"
                                                            name="tipoMoneda">
                                                            @foreach ($tipoMonedas as $tipMon)
                                                                @if ($tipMon->IdTipoMoneda < 3)
                                                                    <option value="{{ $tipMon->IdTipoMoneda }}">
                                                                        {{ $tipMon->Nombre }}</option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    @else
                                                        <select id="tipoMoneda" class="form-control" disabled
                                                            name="tipoMoneda">
                                                            @foreach ($tipoMonedas as $tipMon)
                                                                <option value="{{ $tipMon->IdTipoMoneda }}">
                                                                    {{ $tipMon->Nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    @endif
                                                    <label>Moneda</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                @if ($exonerado == 1 && $sucExonerado == 1)
                                                    <select id="tipoCompra" class="form-control" name="tipoCompra">
                                                        <option value="1">Venta Op. Gravada</option>
                                                        <option selected value="2">Venta Op. Exonerada</option>
                                                    </select>
                                                @else
                                                    <input id="tipoCompra" type="text" name="tipoCompra"
                                                        value="1" hidden>
                                                @endif
                                            </div>
                                        </div>

                                        <div class="row mt-4">
                                            <div class="col-md-6">
                                                <div class="dropdown mt-2 mb-2">
                                                    <button id="agregarArticulo" class="btn btn-info ripple"
                                                        type="button" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos"><i
                                                            class="list-icon material-icons">add_circle</i> Agregar
                                                        <span class="caret"></span>
                                                    </button>
                                                    <!-- /.dropdown-menu -->
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <div id="armarArray" hidden>
                                                </div>
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
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
                                                    <textarea id="observacion" class="form-control" rows="4" name="observacion"></textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4 col-8">
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Gravada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opGravada" name="subtotal" type="text"
                                                            readonly>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-5 col-8">
                                                        <label>Op Exonerada:</label>
                                                    </div>
                                                    <div class="col-lg-5 col-8">
                                                        <input id="opExonerado" name="opExonerado" type="text"
                                                            readonly>
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
                                        <br> <br>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="tipoPago" name="tipoPago">
                                                        <option value="1">Contado</option>
                                                        <option value="2">Crédito</option>
                                                    </select>
                                                    <label>Forma de Pago</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="plazoCredito" class="form-group">
                                                    <div class="input-group">
                                                        <input id="_plazoCredito" type="number" step="any"
                                                            value="" class="form-control" name="plazoCredito"
                                                            min="1">
                                                    </div>
                                                    <label class="form-control-label">Dias</label>
                                                </div>
                                            </div>
                                            <input hidden type="text" id="valorCambio" name="valorCambio"
                                                class="form-control" value="0">
                                        </div>

                                        <div
                                            class="form-actions btn-list mt-3 d-flex justify-content-md-end justify-content-center  flex-wrap">
                                            {{-- <button class="btn btn--celeste btnGuardarOrdenCompra" value="Pendiente"
                                                type="submit">
                                                <span id="btnTexto">Generar Orden de Compra</span>
                                                <div id="seccionLoader" class="d-none">
                                                    <span class="d-flex align-items-center">
                                                        <i class='bx bx-loader-alt bx-spin fs-28 mr-2'></i>Guardando
                                                    </span>
                                                </div>
                                            </button> --}}
                                            <button id="btnCompraPendiente"
                                                class="btn btn--celeste btnGuardarOrdenCompra" value="Pendiente"
                                                type="submit">Generar Orden de Compra</button>
                                        </div>
                                        <div class="col-12 text-center text-md-right mt-4">
                                            <p class="fs-15"><span class="font-weight-bold">*Orden de compra
                                                    pendiente de recepción de productos.</span> </p>
                                        </div>
                                        <!--{!! Form::close() !!}-->
                                    </div>

                                    {{-- Modal Productos --}}
                                    @include('modal._modalAgregarProducto')
                                    {{-- Fin --}}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        @include('schemas.schemaFooter')
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.maskedinput/1.4.1/jquery.maskedinput.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets/js/jquery.loading.min.js') }}"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/scriptCompras.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert1.js?v=' . time()) }}"></script>



    <script src="{{ asset('assets/js/operaciones/ordenesCompra/scriptStoreOrdenCompra.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/operaciones/ordenesCompra/scriptCargarProductoModal.js') }}"></script>

    <script>
        // $('#btnGuardarOrdenCompra').click(function () {
        //     var fecha = $('#fechaRecepcion').val();
        //     alert(fecha);
        // })
    </script>

    {{-- <script>
        $(function() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = dd + '-' + mm + '-' + yyyy;
            $("#datepicker").val(today);
        });
    </script> --}}
    <script>
        let tipoPeticion = 'POST';
        let urlOrdenCompra = "{{ route('ordenDeCompra.store') }}";
        let token = "{{ csrf_token() }}";
        var total = 0;
        var subtotal = 0;
        var igvTotal = 0;
        var iden = 1;
        var array = [];
        var arrayIds = [];
        var opExonerado = 0;
        var sucExonerado = 0;
        $(function() {
            $('#plazoCredito').hide();
            $('#interes').hide();
            sucExonerado = <?php echo json_encode($sucExonerado); ?>;


            $("#proveedores").on('change', function() {
                var idProveedor = $("#proveedores").val();
                if (idProveedor != 0) {
                    $('#agregarArticulo').removeAttr('disabled');
                } else {
                    $('#agregarArticulo').attr('disabled', 'disabled');
                }
            });

            $("#tipoPago").on('change', function() {
                var tipo = $("#tipoPago").val();
                if (tipo == "1") {
                    $('#plazoCredito').hide();
                    $('#interes').hide();
                    $('#efectivo').show();
                    $('#cuentaCorriente').show();
                } else {
                    $('#plazoCredito').show();
                    $('#interes').show();
                    $('#efectivo').hide();
                    $('#cuentaCorriente').hide();
                }
            });
        });
    </script>

    {{-- Cargar modal con productos en soles o dólares --}}
    <script>
        $(function() {
            $("#tipoMoneda").on('change', function() {
                var tipoMoneda = $("#tipoMoneda").val();
                $.ajax({
                    type: 'get',
                    // url: 'ajax/productos-por-tipo-moneda',
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
                        // Esta funcion se encuentra en el archivo public/assets/js/operaciones/ordenesCompra
                        limpiarArticulos();
                    }
                });
            });

            $("#tipoCompra").on('change', function() {
                arrayIds.splice(0, arrayIds.length);
                $('#tablaAgregado tr:gt(0)').remove();
                $('#armarArray div').remove();

                opExonerado = parseFloat(0);
                total = parseFloat(0);
                igvTotal = parseFloat(0);
                subtotal = parseFloat(0);
                exonerada = parseFloat(0);

                $('#total').val('');
                $('#subtotal').val('');
                $('#igv').val('');
                $('#opExonerado').val('');

            });

            $("#categoria").on('change', function() {
                var idCategoria = $("#categoria").val();
                var textoBusqueda = $("#inputBuscarProductos").val();
                var tipoMoneda = $("#tipoMoneda").val();
                $.ajax({
                    type: 'get',
                    // url: 'ajax/productos-por-busqueda',
                    url: "{{ route('articulos.buscar-productos-ajax') }}",
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda,
                        'idCategoria': idCategoria
                    },
                    success: function(data) {
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
        });
    </script>
    {{-- Fin --}}
    {{-- Buscar Producto --}}
    <script>
        $("#inputBuscarProductos").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductos").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                var tipoMoneda = $("#tipoMoneda").val();
                var idCategoria = $("#categoria").val();
                $.ajax({
                    type: 'get',
                    // url: 'ajax/productos-por-busqueda',
                    url: "{{ route('articulos.buscar-productos-ajax') }}",
                    data: {
                        'textoBuscar': textoBusqueda,
                        'tipoMoneda': tipoMoneda,
                        'idCategoria': idCategoria
                    },
                    success: function(data) {
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
    </script>
    {{-- Fin --}}
    {{-- Paginacion Producto --}}
    <script>
        $(document).on('click', '.pagProd a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getProductos(page);
        });


        function getProductos(page) {
            var textoBusqueda = $("#inputBuscarProductos").val();
            var tipoMoneda = $("#tipoMoneda").val();
            var idCategoria = $("#categoria").val();
            $.ajax({
                type: 'get',
                // url: 'ajax/productos-por-paginacion?page=' + page,
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
        }
    </script>
    {{-- Fin --}}
    {{-- Table --}}
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tablaProductos').DataTable({
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
    {{-- Fin --}}
    <script>
        $('input[name=serie]').on('keypress', function(event) {
            var regex = new RegExp("^[a-zA-Z0-9]+$");
            var key = String.fromCharCode(event.charCode);
            if (!regex.test(key)) {
                event.preventDefault();
                return false;
            }
        });
    </script>
</body>

</html>
