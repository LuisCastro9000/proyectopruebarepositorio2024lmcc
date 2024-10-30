@extends('layouts.app')
@section('title', 'Productos')
@section('content')
    <style>
        /* Safari Chrome */
        progress::-webkit-progress-bar {
            background-color: #0D6EFD
        }

        progress::-webkit-progress-value {
            background: #0D6EFD
        }

        /* firefox */
        progress::-moz-progress-bar {
            background: #0D6EFD
        }

        .progress {
            height: 20px;
            border-radius: 10px;
        }

        .progress-bar {
            font-size: 13px;
        }

        .p-tb {
            text-align: center;
            padding: 7px 0 7px 0;
        }

        #password {
            -webkit-text-security: disc;
        }

        #contrasena {
            -webkit-text-security: disc;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('pace-1.2.4/themes/purple/pace-theme-loading-bar.css') }}">
    <div class="container">
        {{-- Nuevos botones Responsivo --}}
        <div class="row mt-4">
            <div class="col">
                <div
                    class="d-flex justify-content-center flex-wrap align-items-center justify-content-lg-between flex-column flex-sm-row">
                    <section>
                        <div class="mb-3 mb-sm-0">
                            <h6 class="page-title-heading mr-0 mr-r-5">Listado de Productos</h6>
                        </div>
                    </section>
                    <section class="d-flex align-items-center flex-wrap justify-content-center">

                        <a href="#" class="btnEliminarConClaveSupervisor d-md-block d-none btn btn-primary"
                            data-descripcion-Evento="eliminacionMasiva">
                            <i class="list-icon material-icons fs-20">delete</i> Eliminación Masiva
                        </a>

                        <a href="#" class="btnEliminarConClaveSupervisor d-md-none d-block btn btn-primary"
                            data-descripcion-Evento="eliminacionMasiva">
                            <i class="list-icon material-icons fs-20">delete</i>
                        </a>

                        @if ($principal == 1)
                            <div class="d-md-block d-none mx-2">
                                <a href="../almacen/productos/create"><button class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">add</i> Agregar</button></a>
                            </div>
                            <div class="d-md-none d-block mx-2">
                                <a href="../almacen/productos/create"><button class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">add</i></button></a>
                            </div>

                            <div class="d-md-block d-none">
                                <a href="#" data-toggle="modal" data-target=".bs-modal-sm-importar"><button
                                        class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">vertical_align_top</i>
                                        Importar</button></a>
                            </div>
                            <div class=" d-md-none d-block">
                                <a href="#" data-toggle="modal" data-target=".bs-modal-sm-importar"><button
                                        class="btn btn-block btn-primary ripple"><i
                                            class="list-icon material-icons fs-20">vertical_align_top</i></button></a>
                            </div>
                            <!-- /.page-title-right -->
                        @endif
                        <div class="d-md-block d-none mx-2">
                            <a target="_blank" href="exportar-excel-productos">
                                <span class="btn bg-excel ripple">
                                    <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                </span>
                            </a>
                        </div>
                        <div class="d-md-none d-block mx-2">
                            <a target="_blank" href="exportar-excel-productos">
                                <span class="btn bg-excel ripple">
                                    <i class="list-icon material-icons fs-20">explicit</i>
                                </span>
                            </a>
                        </div>
                        <div class="d-md-block d-none">
                            <a class="" href="https://youtu.be/xukm1opnX2o" target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    Video Instructivo <i class="list-icon material-icons fs-20 color-icon">videocam</i>
                                </span>
                            </a>
                        </div>
                        <div class=" d-md-none d-block">
                            <a class="" href="https://youtu.be/xukm1opnX2o" target="_blank">
                                <span class="btn btn-autocontrol-naranja ripple text-white">
                                    <i class="list-icon material-icons fs-20 color-icon">videocam</i>
                                </span>
                            </a>
                        </div>
                    </section>
                </div>
            </div>
        </div>
        {{-- Fin --}}

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
        @if ($errors->first('excel') != null)
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ $errors->first('excel') }}
            </div>
        @endif
        @if (Session::has('arrayProductos'))
            @php $array = Session::get('arrayProductos') @endphp
            <label>Estos productos ya se encuentran registrados</label>
            @foreach ($array as $datos)
                <p class="text-danger">- {{ $datos }}</p>
            @endforeach
        @endif



        @if (session('errorProductosDuplicados'))
            <div class="alert alert-success mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('errorProductosDuplicados') }}
                <br><br>
                @php $array = Session::get('arrayProductosNoGuardados') @endphp
                @if (count($array) >= 1)
                    @foreach ($array as $datos)
                        <span class="text-danger">- {{ $datos }}</span><br>
                    @endforeach
                @endif
            </div>
        @endif

        @if (session('errorProductosCostoMayor'))
            <div class="alert alert-success mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('errorProductosCostoMayor') }}
                <br><br>
                @php $array = Session::get('arrayProductoConMayorCosto') @endphp
                @if (count($array) >= 1)
                    @foreach ($array as $item)
                        <span class="text-danger">- {{ $item }}</span><br>
                    @endforeach
                @endif
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
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg-transparent">
                        <div class="widget-body clearfix">
                            <!--<form accept-charset="utf-8" method="POST">-->
                            <div class="form-group">
                                <div class="radiobox">
                                    <label>
                                        <input id="radio1" type="radio" name="radioOption" value="1"
                                            checked="checked">
                                        <span class="label-text p-4">Soles</span>
                                    </label>
                                    @if ($subniveles->contains('IdSubNivel', 46))
                                        <label>
                                            <input id="radio2" type="radio" name="radioOption" value="2"> <span
                                                class="label-text p-4">Dólares</span>
                                        </label>
                                    @endif
                                </div>
                            </div>
                            <div class="form-group">
                                <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                <input autocomplete="nope" type="search" id="inputBuscar" name="texto"
                                    placeholder="Buscar producto..."
                                    class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3"
                                    value="{{ $texto }}">
                            </div>
                            <!--</form>-->

                            <!-- Products List -->
                            <div id="listaProductos" class="ecommerce-products list-unstyled row">
                                @foreach ($articulos as $articulo)
                                    <div class="product col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <a href="javascript:void(0);">
                                                    @if (str_contains($articulo->Imagen, config('variablesGlobales.urlDominioAmazonS3')) ||
                                                            str_contains($articulo->Imagen, config('variablesGlobales.urlImagenNotFound')))
                                                        <img src="{{ $articulo->Imagen }}" alt="">
                                                    @elseif (str_contains($articulo->Imagen, 'https://easyfactperu2021.s3.us-west-2.amazonaws.com'))
                                                        <img src="{{ config('variablesGlobales.urlImagenNotFound') }}"
                                                            alt="">
                                                    @else
                                                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $articulo->Imagen }}"
                                                            alt="">
                                                    @endif
                                                </a>
                                            </div>
                                            <!-- /.card-header -->
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="product-title fs-18">{{ $articulo->Descripcion }}</h5>
                                                    </div>
                                                    <div class="col-6 text-center">
                                                        <span class="product-price fs-18">
                                                            @if ($articulo->IdTipoMoneda == 1)
                                                                S/
                                                            @else
                                                                $
                                                            @endif {{ $articulo->Precio }}
                                                        </span>
                                                        <span class="badge badge-warning">Con IGV</span>
                                                    </div>
                                                    <div class="col-6 text-center">
                                                        <span class="product-price text-danger fs-18">
                                                            @if ($articulo->IdTipoMoneda == 1)
                                                                S/
                                                            @else
                                                                $
                                                            @endif
                                                            {{ number_format($articulo->Precio / 1.18, 2) }}
                                                        </span>
                                                        <span class="badge badge-warning">Sin IGV</span>
                                                    </div>
                                                </div>
                                                <!-- /.d-flex --> <span class="text-muted">{{ $articulo->Marca }}</span>
                                            </div>
                                            <div class="card-footer">
                                                <div class="product-info">
                                                    <a href="productos/{{ $articulo->IdArticulo }}/edit">
                                                        <i class="list-icon material-icons">edit</i>Editar
                                                    </a>
                                                </div>
                                                <div class="product-info">
                                                    <a class="btnEliminarConClaveSupervisor"
                                                        data-id-Articulo-Eliminar="{{ $articulo->IdArticulo }}"
                                                        href="javascript:void(0);">
                                                        <i class="list-icon material-icons">remove_circle</i>Eliminar
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                @endforeach
                            </div>
                            <div class="col-md-12">
                                <nav aria-label="Page navigation">
                                    <ul id="paginas"
                                        class="pagination pagination-md d-flex justify-content-center pagProd">
                                        @if ($articulos->onFirstPage())
                                            <li class="page-item"><a class="page-link disabled"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevrons-left"></i></span></a></li>
                                            <li class="page-item"><a class="page-link disabled"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevron-left"></i></span></a></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="productos?page=1"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevrons-left"></i></span></a></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $articulos->previousPageUrl() }}"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevron-left"></i></span></a></li>
                                        @endif
                                        @if ($articulos->currentPage() < 3)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i > 0 && $i <= $articulos->lastPage())
                                                    @if ($i == $articulos->currentPage())
                                                        <li class="page-item active"><a class="page-link"
                                                                href="javascript:void(0);">{{ $i }}</a></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link"
                                                                href="productos?page={{ $i }}">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endfor
                                        @elseif($articulos->lastPage() > 2)
                                            @if ($articulos->currentPage() > $articulos->lastPage() - 2)
                                                @for ($i = $articulos->currentPage() - 4; $i <= $articulos->lastPage(); $i++)
                                                    @if ($i > 0 && $i <= $articulos->lastPage())
                                                        @if ($i == $articulos->currentPage())
                                                            <li class="page-item active"><a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item"><a class="page-link"
                                                                    href="productos?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endif
                                        @endif
                                        @if ($articulos->currentPage() >= 3 && $articulos->currentPage() <= $articulos->lastPage() - 2)
                                            @for ($i = $articulos->currentPage() - 2; $i <= $articulos->currentPage() + 2; $i++)
                                                @if ($i > 0 && $i <= $articulos->lastPage())
                                                    @if ($i == $articulos->currentPage())
                                                        <li class="page-item active"><a class="page-link"
                                                                href="javascript:void(0);">{{ $i }}</a></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link"
                                                                href="productos?page={{ $i }}">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endfor
                                        @endif
                                        @if ($articulos->hasMorePages())
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $articulos->nextPageUrl() }}" aria-label="Next"><span
                                                        aria-hidden="true"><i
                                                            class="feather feather-chevron-right"></i></span></a></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="productos?page={{ $articulos->lastPage() }}"
                                                    aria-label="Next"><span aria-hidden="true"><i
                                                            class="feather feather-chevrons-right"></i></span></a></li>
                                        @else
                                            <li class="page-item"><a class="page-link disabled" aria-label="Next"><span
                                                        aria-hidden="true"><i
                                                            class="feather feather-chevron-right"></i></span></a></li>
                                            <li class="page-item"><a class="page-link disabled" aria-label="Next"><span
                                                        aria-hidden="true"><i
                                                            class="feather feather-chevrons-right"></i></span></a></li>
                                        @endif
                                    </ul>
                                </nav>
                            </div>
                            <!-- /.col-md-12 -->
                        </div>
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <div class="modal modal-primary fade bs-modal-sm-importar" tabindex="-1" role="dialog"
            aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
            <div class="modal-dialog modal-md">
                {!! Form::open([
                    'url' => 'administracion/almacen/productos/importar',
                    'method' => 'POST',
                    'files' => true,
                    'class' => 'form-material',
                    'id' => 'myform',
                ]) !!}
                <div class="modal-content">
                    <div class="modal-header text-inverse">
                        <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                        <h6 class="modal-title" id="mySmallModalLabel2">Seleccionar Excel</h6>
                    </div>
                    <div class="modal-body">
                        <div class="container">
                            <div class="row">
                                <div class="col-sm-6">
                                    <div class="form-group">
                                        <button class="btn btn-info" onclick="descargarFormato();" type="button"><i
                                                class="list-icon material-icons fs-22">file_download</i>Descargar
                                            Formato</button>
                                    </div>
                                </div>
                                <div class="col-sm-6 text-left">
                                    <!--<label class="text-danger">Formato NUEVO</label>-->
                                </div>
                                <div class="col-sm-6">
                                    <div class="fileUpload btnCambiarFotoPerfil">
                                        <i class="list-icon material-icons">description</i><span class="hide-menu">Subir
                                            Excel (.xlsx, .xls)</span>
                                        <input id="file" class="upload btn" type="file" name="excel"
                                            accept=".xlsx, .xls" />
                                    </div>
                                    <div class="mt-2">
                                        <label id="nombreArchivo"></label>
                                    </div>
                                </div>
                                <div class="col-sm-6">
                                    <label class="text-danger">tamaño de archivo : 250 Kb (1000 productos
                                        aprox.)</label>
                                </div>
                                <div class="col-sm-12">
                                    <label id="alerta" class="text-success"></label>
                                </div>
                            </div>
                            <div class="progress d-none" id="progress">
                                <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                    aria-valuemax="100" style="width: 0%">
                                    <span id="porcentajeBarra"></span>
                                </div>
                            </div>
                            <div class="p-1">
                                <label>MUY IMPORTANTE</label>
                                <p>Este Módulo de importación se debe utilizar solo por única vez para subir su inventario
                                    inicial(productos nuevos), Si se utilizara más de una vez, podría generar
                                    inconsistencias en dupilicidad con productos ya creados.
                                    Si desea aumentar el stock de sus productos ya creados, debe dirigirse al módulo
                                    "Operaciones - Compras".
                                </p>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button id="btnEnvio" type="submit" class="btn btn-primary">Guardar</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
                {!! Form::close() !!}
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>

        @include('modal._modalValidaSupervisor')
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
    <script>
        let urlDominioAmazonS3 = @json(config('variablesGlobales.urlDominioAmazonS3'));
        let UrlDominioAmazonS3Antiguo = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/';
        let UrlImagenNotFound = @json(config('variablesGlobales.urlImagenNotFound'));

        // RUTAS PARA LA BUSQUEDA Y PAGINACION DE LOS ARTICULOS
        let rutaBuscarProductos = "{{ route('articulos.buscar-productos-ajax') }}";
        let rutaPaginarProductos = "{{ route('articulos.paginar-productos-ajax') }}";
    </script>
    <script type="text/javascript">
        $("#btnEnvio").click(function() {
            const progressBar = document.getElementById('progress');
            const porcentajeBarra = document.getElementById('porcentajeBarra');
            progressBar.classList.remove("d-none")
            var formData = new FormData();
            formData.append("file", $("#file")[0].files[0]);
            $.ajax({
                url: "/upload",
                type: "post",
                data: formData,
                contentType: false, // Debe ser falso para agregar automáticamente el tipo de contenido correcto
                processData: false,
                xhr: function() {
                    myXhr = $.ajaxSettings.xhr();
                    if (myXhr.upload) { // Comprueba si el atributo de carga existe
                        // La función de devolución de llamada vinculada al evento de progreso
                        myXhr.upload.addEventListener('progress', function(e) {
                            var curr = e.loaded;
                            var total = e.total;
                            process = Math.round((curr / total) * 100);
                            $(".progress-bar").css("width", process + "%");
                            $("#porcentajeBarra").text(`${process}%`);

                        }, false);
                    }
                    return myXhr;
                },
                success: function(data) {
                    console.log(data);
                }
            });
        });
    </script>
    <script src="{{ asset('assets/js/administracion/productos.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/general.js') }}"></script>
    {{-- Permiso para eliminacion --}}
    <script>
        const isValidacionClaveSupervisorSuccess = () => {
            ocultarLoader('#btnValidarClave');
            if (descripcionEvento == 'eliminacionMasiva') {
                location.href = "../almacen/eliminacion-personalizada";
            } else {
                $("#modalValidarClaveSupervisor").modal('hide');
                modalEliminar(idArticuloEliminar);
                $('#password').val("");
            }
        };
    </script>
@stop
