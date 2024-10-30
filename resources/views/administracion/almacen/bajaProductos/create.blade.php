    @extends('layouts.app')
    @section('title', 'Baja de Productos')
    @section('content')
        <!-- Page Title Area -->
        <div class="container">
            <div class="row page-title clearfix mt-3">
                <div class="page-title-left">
                    <h6 class="page-title-heading mr-0 mr-r-5">Generar Baja</h6>
                </div>
                <!-- /.page-title-left -->
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
                            <div class="widget-body clearfix">
                                <!--{!! Form::open([
                                    'url' => '/operaciones/compras',
                                    'method' => 'POST',
                                    'files' => true,
                                    'class' => 'form-material',
                                ]) !!}
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                {{ csrf_field() }}-->
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="selectMotivo" class="form-control" name="motivo">
                                                <option value="1">Consumo Interno</option>
                                                <option value="2">Producto Vencido</option>
                                                <option value="3">Perdida y/o Extravío</option>
                                                <option value="4">Otros</option>
                                            </select>
                                            <label>Seleccionar Motivo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input id="otros" class="form-control" placeholder="Otros" type="text"
                                                name="otros" disabled>
                                            <label for="otros">Otros Motivos</label>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group d-flex flex-row">
                                            <div class="radiobox">
                                                <label>
                                                    <input id="radio1" type="radio" name="radioOption" value="1"
                                                        checked="checked"> <span class="label-text">Soles</span>
                                                </label>
                                                <label>
                                                    <input id="radio2" type="radio" name="radioOption" value="2">
                                                    <span class="label-text">Dólares</span>
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <button id="agregarArticulo" class="btn btn-info ripple" type="button"
                                            data-toggle="modal" data-target=".bs-modal-lg-productos"><i
                                                class="list-icon material-icons">add_circle</i> Agregar <span
                                                class="caret"></span>
                                        </button>
                                    </div>
                                </div>
                                {{-- <div class="row">
                                    <div class="col-md-12 d-flex justify-content-end">
                                        <button id="agregarArticulo" class="btn btn-info ripple" type="button"
                                            data-toggle="modal" data-target=".bs-modal-lg-productos"><i
                                                class="list-icon material-icons">add_circle</i> Agregar <span
                                                class="caret"></span>
                                        </button>
                                    </div>
                                </div> --}}
                                <br>
                                <div class="row mt-4">
                                    <div class="col-md-12">
                                        <div id="armarArray" hidden>
                                        </div>
                                        <table id="tablaAgregado" class="table table-responsive-lg" style="width:100%">
                                            <thead>
                                                <tr class="bg-primary-contrast">
                                                    <th scope="col">Descripción</th>
                                                    <th scope="col">Marca</th>
                                                    <th scope="col">CodigoBarra</th>
                                                    <th scope="col">Und/Medida</th>
                                                    <th scope="col">Tipo Moneda</th>
                                                    <th scope="col">En Stock</th>
                                                    <th scope="col">Cantidad Baja</th>
                                                    <th scope="col">Quitar</th>
                                                </tr>
                                            </thead>
                                            <tbody>

                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                                <div class="form-actions btn-list mt-3 d-flex justify-content-end">
                                    <button id="btnGenerar" class="btn btn-primary" onclick="enviar();">Guardar</button>
                                    <a href="{{ route('baja-productos.index') }}"><button class="btn btn-outline-default"
                                            type="button">Cancelar</button></a>
                                </div>
                                <!--{!! Form::close() !!}-->
                            </div>
                            <div class="modal fade bs-modal-lg-productos" tabindex="-1" role="dialog"
                                aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-body">
                                            <h5 class="modal-title" id="myLargeModalLabel">Listado de Productos</h5>
                                            <div class="clearfix">
                                                <div class="form-group form-material">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <label class="form-control-label fs-14 fw-500">Buscar
                                                                Producto</label>
                                                        </div>
                                                        <div class="col-6">
                                                            <input type="checkbox" id="lector"
                                                                name="activarLector"><span
                                                                class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Lector de
                                                                Códigos</span>
                                                        </div>
                                                    </div>
                                                    <div class="input-group">
                                                        <input type="text" id="inputBuscarProductos"
                                                            name="textoBuscar" class="form-control fs-16 fw-400">
                                                        <input type="text" id="inputBuscarCodigoProductos"
                                                            name="textoBuscarCodigo" autofocus=""
                                                            class="form-control fs-16 fw-400">
                                                    </div>
                                                </div>

                                                <!-- Products List -->
                                                <div id="listaProductos" class="ecommerce-products list-unstyled row">
                                                    @foreach ($productos as $producto)
                                                        <div class="product col-12 col-md-6">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-12" hidden>S/
                                                                        <span id="p2-{{ $producto->IdArticulo }}"
                                                                            class="product-price fs-16">
                                                                            {{ $producto->Precio }}</span>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <span id="p1-{{ $producto->IdArticulo }}"
                                                                            class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                                    </div>
                                                                    <div class="col-12 d-flex justify-content-between">
                                                                        <section>
                                                                            <span id="p9-{{ $producto->IdArticulo }}"
                                                                                class="text-muted">{{ $producto->Marca }}</span>
                                                                            /
                                                                            <span id="marca-{{ $producto->IdArticulo }}"
                                                                                class="text-muted">{{ $producto->Categoria }}</span>
                                                                        </section>
                                                                        <section>
                                                                            <span
                                                                                class="badge-autoncontrol badge-autoncontrol__danger">Stock:
                                                                                {{ $producto->Stock }}</span>
                                                                        </section>
                                                                    </div>
                                                                    <div class="col-12" hidden>
                                                                        <span
                                                                            id="p10-{{ $producto->IdArticulo }}">{{ $producto->Codigo }}</span>
                                                                    </div>
                                                                </div>

                                                                <input hidden id="p3-{{ $producto->IdArticulo }}"
                                                                    value="{{ $producto->UM }}" />
                                                                <input hidden
                                                                    id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                                                    value="{{ $producto->IdUnidadMedida }}" />
                                                                <div class="form-group mt-2" hidden>
                                                                    <label class="col-form-label-sm">Cantidad </label>
                                                                    @if ($producto->Stock < 1)
                                                                        <input id="p4-{{ $producto->IdArticulo }}"
                                                                            type="number" min="0" value="0"
                                                                            class=" text-center" />
                                                                    @else
                                                                        <input id="p4-{{ $producto->IdArticulo }}"
                                                                            type="number" min="1" value="1"
                                                                            max="{{ $producto->Stock }}"
                                                                            class=" text-center" />
                                                                    @endif
                                                                </div>

                                                                <div class="form-group" hidden>
                                                                    <label class="col-form-label-sm">Descuento </label>
                                                                    <input id="p5-{{ $producto->IdArticulo }}"
                                                                        value="0.0" class="text-center" />
                                                                </div>

                                                                <div hidden>
                                                                    <div class="form-group col-12">
                                                                        <label class="col-form-label-sm">Costo</label>
                                                                        <input id="p6-{{ $producto->IdArticulo }}"
                                                                            value="{{ $producto->Costo }}"
                                                                            class="form-control text-center" />
                                                                    </div>
                                                                </div>
                                                                <div hidden>
                                                                    <div class="form-group col-12">
                                                                        <label class="col-form-label-sm">Stock </label>
                                                                        <input id="p7-{{ $producto->IdArticulo }}"
                                                                            value="{{ $producto->Stock }}"
                                                                            class="form-control text-center" />
                                                                    </div>
                                                                </div>
                                                                <div hidden>
                                                                    <div class="form-group col-12">
                                                                        <label class="col-form-label-sm">Tipo Moneda
                                                                        </label>
                                                                        <input id="p8-{{ $producto->IdArticulo }}"
                                                                            value="{{ $producto->IdTipoMoneda }}"
                                                                            class="form-control text-center" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="product-info col-12">
                                                                    @if ($producto->Stock < 1)
                                                                        <a class="bg-info color-white fs-12 disabled"
                                                                            href="javascript:void(0);">
                                                                            Agotado
                                                                        </a>
                                                                    @else
                                                                        <a class="bg-info color-white fs-12"
                                                                            onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                                            href="javascript:void(0);">
                                                                            <i
                                                                                class="list-icon material-icons">add</i>Agregar
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!-- /.ecommerce-products -->
                                                <!-- Product Navigation -->
                                                <div class="col-md-12">
                                                    <nav aria-label="Page navigation">
                                                        <ul id="paginasProductos"
                                                            class="pagination pagination-md d-flex justify-content-center pagProd">
                                                            @if ($productos->onFirstPage())
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                                class="feather feather-chevrons-left"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                                class="feather feather-chevron-left"></i></span></a>
                                                                </li>
                                                            @else
                                                                <li class="page-item"><a class="page-link"
                                                                        href="productos?page=1"
                                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                                class="feather feather-chevrons-left"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link"
                                                                        href="{{ $productos->previousPageUrl() }}"
                                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                                class="feather feather-chevron-left"></i></span></a>
                                                                </li>
                                                            @endif

                                                            @if ($productos->currentPage() < 3)
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i > 0 && $i <= $productos->lastPage())
                                                                        @if ($i == $productos->currentPage())
                                                                            <li class="page-item active"><a
                                                                                    class="page-link"
                                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                                            </li>
                                                                        @else
                                                                            <li class="page-item"><a class="page-link"
                                                                                    href="productos?page={{ $i }}">{{ $i }}</a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                @endfor
                                                            @elseif($productos->lastPage() > 2)
                                                                @if ($productos->currentPage() > $productos->lastPage() - 2)
                                                                    @for ($i = $productos->currentPage() - 4; $i <= $productos->lastPage(); $i++)
                                                                        @if ($i > 0 && $i <= $productos->lastPage())
                                                                            @if ($i == $productos->currentPage())
                                                                                <li class="page-item active"><a
                                                                                        class="page-link"
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
                                                            @if ($productos->currentPage() >= 3 && $productos->currentPage() <= $productos->lastPage() - 2)
                                                                @for ($i = $productos->currentPage() - 2; $i <= $productos->currentPage() + 2; $i++)
                                                                    @if ($i > 0 && $i <= $productos->lastPage())
                                                                        @if ($i == $productos->currentPage())
                                                                            <li class="page-item active"><a
                                                                                    class="page-link"
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
                                                            @if ($productos->hasMorePages())
                                                                <li class="page-item"><a class="page-link"
                                                                        href="{{ $productos->nextPageUrl() }}"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevron-right"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link"
                                                                        href="productos?page={{ $productos->lastPage() }}"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevrons-right"></i></span></a>
                                                                </li>
                                                            @else
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevron-right"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevrons-right"></i></span></a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </nav>
                                                </div>
                                                <!-- /.col-md-12 -->
                                            </div>

                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-success btn-rounded ripple text-left"
                                                data-dismiss="modal">Cerrar</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
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
    @stop
    @section('scripts')
        <script src="{{ asset('assets/js/administracion/bajaProductos.js?v=' . time()) }}"></script>
        <script src="{{ asset('assets/js/general.js') }}"></script>
        <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
        <script>
            function enviar() {
                $('#btnGenerar').attr("disabled", true);
                var idMotivo = $("#selectMotivo").val();
                var otros = $("#otros").val();
                var ids = $("input[name='Id[]']").map(function() {
                    return $(this).val();
                }).get();
                var stocks = $("input[name='Stock[]']").map(function() {
                    return $(this).val();
                }).get();
                var cantidades = $("input[name='Cantidad[]']").map(function() {
                    return $(this).val();
                }).get();
                var codigoBarra = $("td[name='CodigoBarra[]']").map(function() {
                    return $(this).text();
                }).get();
                var descripciones = $('td[name="Descripcion[]"]').map(function() {
                    return $(this).text();
                }).get();
                var nombreMotivo = $("#selectMotivo option:selected").text();
                $.ajax({
                    type: 'post',
                    url: "{{ route('baja-productos.store') }}",
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "idMotivo": idMotivo,
                        "otros": otros,
                        "Id": ids,
                        "Stock": stocks,
                        "Cantidad": cantidades,
                        'Descripcion': descripciones,
                        "CodigoBarra": codigoBarra,
                        "NombreMotivoBaja": nombreMotivo
                    },
                    success: function(data) {
                        if (data.respuesta == 'error') {
                            $('#btnGenerar').attr("disabled", false);
                            alert(data.mensaje);
                            return false;
                        }
                        if (data.respuesta == 'success') {
                            console.log(data.fechaBaja);
                            console.log(data.idDetalleBaja);
                            Swal.fire({
                                text: data.mensaje,
                                icon: 'success',
                                showCancelButton: true,
                                showCancelButton: false,
                                confirmButtonColor: '#3085d6',
                                confirmButtonText: 'Ok'
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    const url = "{{ route('baja-productos.show', [':id']) }}".replace(
                                        ':id', data
                                        .idDetalleBaja);
                                    window.location = url;
                                }
                            })
                        }
                    }
                });
            }
        </script>
    @stop
