@extends('layouts.app')
@section('title', 'Servicios')
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
    </style>
    <div class="container">
        <div class="row mt-3">
            <div class="col">
                <div
                    class="d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-sm-row">
                    <section>
                        <h6 class="page-title-heading mr-0 mr-r-5">Listado de Servicios</h6>
                    </section>
                    <section class="d-flex align-items-center flex-wrap flexGap--5 justify-content-center">
                        <a href="#" class="btnEliminarConClaveSupervisor d-md-block d-none btn btn-primary"
                            data-descripcion-Evento="eliminacionMasiva">
                            <i class="list-icon material-icons fs-20">delete</i> Eliminación Masiva
                        </a>

                        <a href="#" class="btnEliminarConClaveSupervisor d-md-none d-block btn btn-primary"
                            data-descripcion-Evento="eliminacionMasiva">
                            <i class="list-icon material-icons fs-20">delete</i>
                        </a>

                        <div class=" d-md-block d-none">
                            <a href="../almacen/servicios/create"><button class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-20">add</i> Agregar</button></a>
                        </div>
                        {{-- Nuevo boton --}}
                        <div class="d-md-block d-none">
                            <a href="#" data-toggle="modal" data-target="#modalImportarExcelServicios"><button
                                    class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-20">vertical_align_top</i>
                                    Importar</button></a>
                        </div>
                        <div class=" d-md-none d-block">
                            <a href="#" data-toggle="modal" data-target="#modalImportarExcelServicios"><button
                                    class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-20">vertical_align_top</i></button></a>
                        </div>
                        {{-- fin --}}
                        <div class="d-md-block d-none">
                            <a target="_blank" href="exportar-excel-servicios">
                                <span class="btn bg-excel ripple">
                                    <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                </span>
                            </a>
                        </div>
                        <div class="d-md-none d-block">
                            <a target="_blank" href="exportar-excel-servicios">
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
                        <div class="d-md-none d-block">
                            <a href="../almacen/servicios/create"><button class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-20">add</i></button></a>
                        </div>
                        <div class=" d-md-none d-block ">
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
        @if (session('status'))
            <div class="alert alert-success mt-2">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger mt-2">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        @if (Session::has('arrayServicios'))
            @php $array = Session::get('arrayServicios') @endphp
            <label>Estos servicios ya se encuentran registrados</label>
            @foreach ($array as $datos)
                <p class="text-danger">- {{ $datos }}</p>
            @endforeach
        @endif

        {{-- Nuevo codigo --}}
        @if (session('errorServiciosDuplicados'))
            <div class="alert alert-success mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('errorServiciosDuplicados') }}
                <br><br>
                @php $array = Session::get('arrayServiciosNoGuardados') @endphp
                @if (count($array) >= 1)
                    @foreach ($array as $datos)
                        <span class="text-danger">- {{ $datos }}</span><br>
                    @endforeach
                @endif
            </div>
        @endif

        @if (session('errorServiciosCostoMayor'))
            <div class="alert alert-success mt-3">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('errorServiciosCostoMayor') }}
                <br><br>
                @php $array = Session::get('arrayServiciosConMayorCosto') @endphp
                @if (count($array) >= 1)
                    @foreach ($array as $item)
                        <span class="text-danger">- {{ $item }}</span><br>
                    @endforeach
                @endif
            </div>
        @endif
        {{-- Fin --}}
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
                            <!--<div class="form-material"> -->
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
                                <input type="search" id="inputBuscar" name="textoBuscar" placeholder="Buscar servicio..."
                                    class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3"
                                    value="{{ $textoBuscar }}">
                            </div>
                            <!--</div>-->
                            <!-- Products List -->
                            <div id="listaServicios" class="ecommerce-products list-unstyled row">
                                @foreach ($servicios as $servicio)
                                    <div class="product col-12 col-sm-6 col-md-4 col-lg-3">
                                        <div class="card">
                                            <div class="card-header">
                                                <a href="javascript:void(0);">
                                                    @if (str_contains($servicio->Imagen, config('variablesGlobales.urlDominioAmazonS3')) ||
                                                            str_contains($servicio->Imagen, config('variablesGlobales.urlImagenNotFound')))
                                                        <img src="{{ $servicio->Imagen }}" alt="">
                                                    @elseif (str_contains($servicio->Imagen, 'https://easyfactperu2021.s3.us-west-2.amazonaws.com'))
                                                        <img src="{{ config('variablesGlobales.urlImagenNotFound') }}"
                                                            alt="">
                                                    @else
                                                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $servicio->Imagen }}"
                                                            alt="">
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <h5 class="product-title fs-18">{{ $servicio->Descripcion }}</h5>
                                                    </div>
                                                    <div class="col-6 text-center">
                                                        <span class="product-price fs-18">S/
                                                            {{ $servicio->Precio }}</span>
                                                        <span class="badge badge-warning">Con
                                                            IGV</span>
                                                    </div>
                                                    <div class="col-6 text-center">
                                                        <span class="product-price fs-18 text-danger">S/
                                                            {{ number_format($servicio->Precio / 1.18, 2) }}</span>
                                                        <span class="badge badge-warning">Sin
                                                            IGV</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- /.card-body -->
                                            <div class="card-footer">
                                                <div class="product-info"><a
                                                        href="servicios/{{ $servicio->IdArticulo }}/edit"><i
                                                            class="list-icon material-icons">edit</i>Editar</a>
                                                </div>
                                                <div class="product-info"><a class="btnEliminarConClaveSupervisor"
                                                        data-id-Articulo-Eliminar="{{ $servicio->IdArticulo }}"
                                                        href="javascript:void(0);"><i
                                                            class="list-icon material-icons">remove_circle</i>Eliminar</a>
                                                </div>
                                            </div>
                                            <!-- /.card-footer -->
                                        </div>
                                        <!-- /.card -->
                                    </div>
                                @endforeach
                            </div>
                            <!-- /.ecommerce-products -->
                            <!-- Product Navigation -->
                            <div class="col-md-12">
                                @php $pagination_range = 1; @endphp
                                <nav aria-label="Page navigation">
                                    <ul id="paginas"
                                        class="pagination pagination-md d-flex justify-content-center pagServ">
                                        @if ($servicios->onFirstPage())
                                            <li class="page-item"><a class="page-link disabled"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevrons-left"></i></span></a></li>
                                            <li class="page-item"><a class="page-link disabled"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevron-left"></i></span></a></li>
                                        @else
                                            <li class="page-item"><a class="page-link" href="servicios?page=1"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevrons-left"></i></span></a></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $servicios->previousPageUrl() }}"
                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                            class="feather feather-chevron-left"></i></span></a></li>
                                        @endif
                                        @if ($servicios->currentPage() < 3)
                                            @for ($i = 1; $i <= 5; $i++)
                                                @if ($i > 0 && $i <= $servicios->lastPage())
                                                    @if ($i == $servicios->currentPage())
                                                        <li class="page-item active"><a class="page-link"
                                                                href="javascript:void(0);">{{ $i }}</a></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link"
                                                                href="servicios?page={{ $i }}">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endfor
                                        @elseif($servicios->lastPage() > 2)
                                            @if ($servicios->currentPage() > $servicios->lastPage() - 2)
                                                @for ($i = $servicios->currentPage() - 4; $i <= $servicios->lastPage(); $i++)
                                                    @if ($i > 0 && $i <= $servicios->lastPage())
                                                        @if ($i == $servicios->currentPage())
                                                            <li class="page-item active"><a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item"><a class="page-link"
                                                                    href="servicios?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endif
                                        @endif
                                        @if ($servicios->currentPage() >= 3 && $servicios->currentPage() <= $servicios->lastPage() - 2)
                                            @for ($i = $servicios->currentPage() - 2; $i <= $servicios->currentPage() + 2; $i++)
                                                @if ($i > 0 && $i <= $servicios->lastPage())
                                                    @if ($i == $servicios->currentPage())
                                                        <li class="page-item active"><a class="page-link"
                                                                href="javascript:void(0);">{{ $i }}</a></li>
                                                    @else
                                                        <li class="page-item"><a class="page-link"
                                                                href="servicios?page={{ $i }}">{{ $i }}</a>
                                                        </li>
                                                    @endif
                                                @endif
                                            @endfor
                                        @endif
                                        @if ($servicios->hasMorePages())
                                            <li class="page-item"><a class="page-link"
                                                    href="{{ $servicios->nextPageUrl() }}" aria-label="Next"><span
                                                        aria-hidden="true"><i
                                                            class="feather feather-chevron-right"></i></span></a></li>
                                            <li class="page-item"><a class="page-link"
                                                    href="servicios?page={{ $servicios->lastPage() }}"
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

        {{-- Modal validar supervisor --}}
        @include('modal._modalValidaSupervisor')
        {{-- Fin --}}

        {{-- Modal Importar excel --}}
        <div class="modal" id="modalImportarExcelServicios" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-body">
                        <section class="text-center">
                            <label class="fs-18">Importar excel Servicios</label>
                            <hr>
                        </section>
                        <section class="mt-2 mb-4">
                            <button type="button" class="btn btn-success d-block w-100"
                                onclick="descargarFormatoExcel();">Descargar Formato Excel</button>
                        </section>
                        {!! Form::open([
                            'url' => 'administracion/almacen/productos/importar-excel-servicios',
                            'method' => 'POST',
                            'files' => true,
                        ]) !!}
                        <section class="formImport" id="formImport">
                            <span><i class='bx bxs-cloud-upload fs-60 color-celeste'></i></span>
                            <span id="subTitleFile" class="text-center">Click Aquí <br> Seleccionar Archivo</span>
                            <input type="file" class="input-file" name="datosExcelServicios" id="input-file"
                                accept=".xlsx, .xls" hidden>
                            <span id="nameFile"></span>
                        </section>
                        <section class="text-center mt-4">
                            <b>TAMAÑO DE ARCHIVO : 150 KB (1500 SERVICIOS APROX.)</b>
                        </section>
                        <section class="progress d-none" id="progress">
                            <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0"
                                aria-valuemax="100" style="width: 0%">
                                <span id="porcentajeBarra"></span>
                            </div>
                        </section>
                        <div class="modal-footer mt-4">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                            <button type="submit" class="btn btn-primary" id="btnImportar">Importar</button>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </div>
        {{-- Fin --}}
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('assets/js/administracion/servicios.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
    <script>
        let urlDominioAmazonS3 = @json(config('variablesGlobales.urlDominioAmazonS3'));
        let UrlDominioAmazonS3Antiguo = 'https://easyfactperu2021.s3.us-west-2.amazonaws.com/';
        let UrlImagenNotFound = @json(config('variablesGlobales.urlImagenNotFound'));

        // RUTAS PARA LA BUSQUEDA Y PAGINACION DE LOS ARTICULOS
        let rutaBuscarServicios = "{{ route('articulos.buscar-servicios-ajax') }}";
        let rutaPaginarServicios = "{{ route('articulos.paginar-servicios-ajax') }}";
    </script>
    <script>
        const isValidacionClaveSupervisorSuccess = () => {
            ocultarLoader('#btnValidarClave');
            if (descripcionEvento == 'eliminacionMasiva') {
                location.href = "{{ route('vistaServiciosEliminacionMasiva') }}";
            } else {
                $("#modalValidarClaveSupervisor").modal('hide');
                modalEliminar(idArticuloEliminar);
                $('#password').val("");
            }
        }
    </script>

    <script>
        const form = document.querySelector('#formImport');
        const inputFile = form.querySelector('.input-file');
        form.addEventListener('click', () => {
            inputFile.click();
        })

        inputFile.onchange = ({
            target
        }) => {
            if (target.files[0].size <= 153600) {
                $('#nameFile').text(target.files[0].name);
                $('#subTitleFile').text('');
                $('#subTitleFile').removeClass('text-crimson');
            } else {
                $('#subTitleFile').text('');
                $('#nameFile').text('');
                $('#subTitleFile').addClass('text-crimson');
                $('#subTitleFile').text('El Archivo es muy Grande');
            }
        }
    </script>

    <script type="text/javascript">
        $("#btnImportar").click(function() {
            if ($("#input-file").val() != "") {
                const progressBar = document.getElementById('progress');
                const porcentajeBarra = document.getElementById('porcentajeBarra');
                progressBar.classList.remove("d-none")
                var formData = new FormData();
                formData.append("file", $("#input-file")[0].files[0]);
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
                    }
                });
            }
        });
    </script>
@stop
