@extends('layouts.app')
@section('title', 'Editar Grupo')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <style>
        .pd-1 {
            padding-left: 10px !important;
        }
    </style>
    <div class="container">
        <div class="row mt-4">
            <div class="col">
                <div class="card">
                    <div class="card-body">
                        <section class="d-flex justify-content-between align-items-center">
                            <h6>Editar Paquete</h6>
                            <article>
                                <a href=" {{ url('/administracion/almacen/grupos') }}">
                                    <button type="button" class="btn btn-secondary btn-sm"><i
                                            class='bx bx-share fs-20 icono-vehicular mr-1'></i> Volver</button>
                                </a>
                            </article>
                        </section>
                        <hr><br>
                        <section class="mb-4 d-flex align-items-center flex-wrap">
                            <article class="col-md-5">
                                <div class="form-group">
                                    <label for="nombre" class="font-weight-bold pd-1">Nombre de Paquete:</label>
                                    <input class="form-control" id="nombre" type="text" name="nombre"
                                        autocomplete="off" value="{{ $nombreGrupo }}">
                                    <input hidden id="idGrupo" class="form-control" type="text"
                                        value="{{ $idGrupo }}" readonly>
                                    <input hidden id="idMoneda" class="form-control" type="text"
                                        value="{{ $tipoMoneda }}" readonly>
                                </div>
                            </article>
                            <article class="col-md-7 d-flex justify-content-center justify-content-md-end">
                                @if ($tipoMoneda == 1)
                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos-soles"
                                        id="agregarArticuloSoles"><button class="btn btn-info"><i
                                                class="list-icon material-icons">add_circle</i>Agregar Items al Paquete en
                                            Soles
                                            <span class="caret"></span></button></a>
                                @else
                                    <a href="#" data-toggle="modal" data-target=".bs-modal-lg-productos-dolares"
                                        id="agregarArticuloDolares"><button class="btn btn-info"><i
                                                class="list-icon material-icons">add_circle</i>Agregar Items al Paquete en
                                            Dolares<span class="caret"></span></button></a>
                                @endif
                            </article>
                        </section>
                        </section>
                        <section>
                            <div id="armarArray" hidden="">
                            </div>
                            <table id="tablaAgregado" class="table table-responsive-lg" style="width:100%">
                                <thead>
                                    <tr class="bg-primary-contrast">
                                        <th scope="col" data-tablesaw-priority="persist" class="text-center">Código</th>
                                        <th scope="col" class="text-center">Descripción</th>
                                        <th scope="col" class="text-center">Marca</th>
                                        <th scope="col" class="text-center">Categoria</th>
                                        <th scope="col" class="text-center">Precio</th>
                                        <th scope="col" class="text-center">Stock</th>
                                        <th scope="col" class="text-center">CodigoBarra</th>
                                        <th scope="col" class="text-center">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="body">
                                </tbody>
                            </table>
                        </section>
                        <section class="form-actions btn-list mt-3">
                            <button id="btnGenerar" class="btn btn-primary" type="button" onclick="enviar();">Actualizar
                                Paquete</button>
                        </section>
                    </div>
                </div>
            </div>
        </div>
        {{-- Modal --}}
        <div class="row">
            <div class="modal fade bs-modal-lg-productos-soles" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true" style="display: none">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="tabs tabs-bordered">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a class="nav-link active" href="#tab-productos-soles"
                                            data-toggle="tab" aria-controls="tab-productos-soles">Productos</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#tab-servicios-soles" data-toggle="tab"
                                            aria-controls="tab-servicios-soles">Servicios</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-productos-soles">
                                        <div class="clearfix">
                                            <div class="form-group form-material">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="form-control-label fs-14 fw-500">Buscar
                                                            Producto</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-control select2-hidden-accessible"
                                                            id="categoriaSoles" name="categoriaSoles"
                                                            data-placeholder="Choose" tabindex="-1"
                                                            data-toggle="select2" aria-hidden="true">
                                                            <option value="0">Seleccionar Categoría</option>
                                                            @foreach ($categorias as $categoria)
                                                                <option value="{{ $categoria->IdCategoria }}">
                                                                    {{ $categoria->Nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <i
                                                    class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                <input type="search" id="inputBuscarProductosSoles" name="textoBuscar"
                                                    placeholder="Buscar producto..."
                                                    class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                            </div>

                                            <!-- Products List -->
                                            <div id="listaProductosSoles" class="ecommerce-products list-unstyled row">
                                                @foreach ($productos as $producto)
                                                    <div class="product col-12 col-md-6 idem-{{ $producto->IdArticulo }}">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">S/
                                                                    <span id="p2-{{ $producto->IdArticulo }}"
                                                                        class="product-price fs-16">
                                                                        {{ $producto->Precio }}</span>
                                                                    <input type="hidden" name="Precio"
                                                                        value=" {{ $producto->Precio }}"
                                                                        id="productoPrecio">
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="p1-{{ $producto->IdArticulo }}"
                                                                        class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                                    <input type="hidden" name="Descripcion"
                                                                        value=" {{ $producto->Descripcion }}"
                                                                        id="productoDescrpcion">
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="codBarra-{{ $producto->IdArticulo }}"
                                                                        class="text-success fs-12">{{ $producto->Codigo }}</span>
                                                                    <input type="hidden" name="codigo"
                                                                        value=" {{ $producto->IdArticulo }}"
                                                                        id="productoId">
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="marca-{{ $producto->IdArticulo }}"
                                                                        class="text-muted">{{ $producto->Marca }}</span>/
                                                                    <span class="text-muted"
                                                                        id="categoria-{{ $producto->IdArticulo }}">{{ $producto->Categoria }}</span>/
                                                                    <span class="text-danger fs-13">Stock :
                                                                        {{ $producto->Stock }} </span>
                                                                </div>
                                                            </div>

                                                            <input hidden id="p3-{{ $producto->IdArticulo }}"
                                                                value="{{ $producto->UM }}" />
                                                            <!-- esto puse 1 -->
                                                            <input hidden id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                                                value="{{ $producto->IdUnidadMedida }}" />
                                                            <!-- esto puse 1 -->


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
                                                                <input id="p5-{{ $producto->IdArticulo }}" value="0.0"
                                                                    class="text-center" />
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
                                                                <input id="p8-{{ $producto->IdArticulo }}"
                                                                    value="{{ $producto->IdTipoMoneda }}"
                                                                    class="form-control text-center" />
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="product-info col-12">
                                                                @if ($producto->Stock < 1)
                                                                    <a class="bg-info color-white fs-12"
                                                                        onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                                        href="javascript:void(0);">
                                                                        <i class="list-icon material-icons">add</i>Agregar
                                                                        (Agotado)
                                                                    </a>
                                                                @else
                                                                    <a class="bg-info color-white fs-12"
                                                                        onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                                        href="javascript:void(0);">
                                                                        <i class="list-icon material-icons">add</i>Agregar
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
                                                    <ul id="paginasProductosSoles"
                                                        class="pagination pagination-md d-flex justify-content-center pagProdSoles">
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
                                                                    href="productos?page=1" aria-label="Previous"><span
                                                                        aria-hidden="true"><i
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
                                    <div class="tab-pane" id="tab-servicios-soles">
                                        <div class="clearfix">
                                            <div class="form-group form-material">
                                                <label class="form-control-label fs-14 fw-400">Buscar Servicio</label>
                                                <div class="input-group">
                                                    <input type="text" id="inputBuscarServiciosSoles"
                                                        name="textoBuscar" class="form-control fs-16 fw-400">
                                                </div>
                                            </div>
                                            <!-- Products List -->
                                            <div id="listaServiciosSoles" class="ecommerce-products list-unstyled row">
                                                @foreach ($servicios as $servicio)
                                                    <div class="product col-12 col-md-6">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">S/
                                                                    <span id="s2-{{ $servicio->IdArticulo }}"
                                                                        class="product-price fs-16">{{ $servicio->Precio }}</span>
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="s1-{{ $servicio->IdArticulo }}"
                                                                        class="product-title font-weight-bold fs-16">{{ $servicio->Descripcion }}</span>
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="codBarraSer-{{ $servicio->IdArticulo }}"
                                                                        class="text-success fs-12">{{ $servicio->Codigo }}</span>
                                                                </div>
                                                            </div>
                                                            <input hidden id="s6-{{ $servicio->IdArticulo }}"
                                                                value="{{ $servicio->UM }}" />
                                                            <div class="form-group mt-2" hidden>
                                                                <label class="col-form-label-sm">Cantidad </label>
                                                                <input id="s5-{{ $servicio->IdArticulo }}" type="number"
                                                                    min="1" value="1" class="text-center" />
                                                            </div>
                                                            <div class="form-group" hidden>
                                                                <label class="col-form-label-sm">Descuento </label>
                                                                <input id="s3-{{ $servicio->IdArticulo }}" type="text"
                                                                    value="0.0" class="text-center" />
                                                            </div>
                                                            <div hidden>
                                                                <div class="form-group col-12">
                                                                    <label class="col-form-label-sm">Costo</label>
                                                                    <input id="s4-{{ $servicio->IdArticulo }}"
                                                                        value="{{ $servicio->Costo }}"
                                                                        class=" text-center" />
                                                                </div>
                                                            </div>
                                                            <div hidden>
                                                                <input id="s7-{{ $servicio->IdArticulo }}"
                                                                    value="{{ $servicio->IdTipoMoneda }}"
                                                                    class="form-control text-center" />
                                                            </div>
                                                        </div>

                                                        <div class="card-footer">
                                                            <div class="product-info col-12">
                                                                <a class="bg-info color-white fs-12"
                                                                    onclick="agregarServicio({{ $servicio->IdArticulo }})"
                                                                    href="javascript:void(0);">
                                                                    <i class="list-icon material-icons">add</i>Agregar
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-footer -->

                                                        <!-- /.card -->
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- /.ecommerce-products -->
                                            <!-- Product Navigation -->
                                            <div class="col-md-12">
                                                <nav aria-label="Page navigation">
                                                    <ul id="paginasServiciosSoles"
                                                        class="pagination pagination-md d-flex justify-content-center pagServSoles">
                                                        @if ($servicios->onFirstPage())
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
                                                                    href="servicios?page=1" aria-label="Previous"><span
                                                                        aria-hidden="true"><i
                                                                            class="feather feather-chevrons-left"></i></span></a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $servicios->previousPageUrl() }}"
                                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                                            class="feather feather-chevron-left"></i></span></a>
                                                            </li>
                                                        @endif
                                                        @if ($servicios->currentPage() < 3)
                                                            @for ($i = 1; $i <= 5; $i++)
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
                                                        @elseif($servicios->lastPage() > 2)
                                                            @if ($servicios->currentPage() > $servicios->lastPage() - 2)
                                                                @for ($i = $servicios->currentPage() - 4; $i <= $servicios->lastPage(); $i++)
                                                                    @if ($i > 0 && $i <= $servicios->lastPage())
                                                                        @if ($i == $servicios->currentPage())
                                                                            <li class="page-item active"><a
                                                                                    class="page-link"
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
                                                        @if ($servicios->hasMorePages())
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $servicios->nextPageUrl() }}"
                                                                    aria-label="Next"><span aria-hidden="true"><i
                                                                            class="feather feather-chevron-right"></i></span></a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="servicios?page={{ $servicios->lastPage() }}"
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
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-rounded ripple text-left"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal fade bs-modal-lg-productos-dolares" role="dialog" aria-labelledby="myLargeModalLabel"
                aria-hidden="true" style="display: none">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-body">
                            <div class="tabs tabs-bordered">
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a class="nav-link active" href="#tab-productos-dolares"
                                            data-toggle="tab" aria-controls="tab-productos-dolares">Productos</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#tab-servicios-dolares"
                                            data-toggle="tab" aria-controls="tab-servicios-dolares">Servicios</a>
                                    </li>
                                </ul>
                                <div class="tab-content">
                                    <div class="tab-pane active" id="tab-productos-dolares">
                                        <!--<h5 class="modal-title" id="myLargeModalLabel">Listado de Productos</h5>-->
                                        <div class="clearfix">
                                            <div class="form-group form-material">
                                                <div class="row">
                                                    <div class="col-6">
                                                        <label class="form-control-label fs-14 fw-500">Buscar
                                                            Producto</label>
                                                    </div>
                                                    <div class="col-6">
                                                        <select class="form-control select2-hidden-accessible"
                                                            id="categoriaDolares" name="categoriaDolares"
                                                            data-placeholder="Choose" tabindex="-1"
                                                            data-toggle="select2" aria-hidden="true">
                                                            <option value="0">Seleccionar Categoría</option>
                                                            @foreach ($categorias as $categoria)
                                                                <option value="{{ $categoria->IdCategoria }}">
                                                                    {{ $categoria->Nombre }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="form-group">
                                                <i
                                                    class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                <input type="search" id="inputBuscarProductosDolares" name="textoBuscar"
                                                    placeholder="Buscar producto..."
                                                    class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                            </div>

                                            <!-- Products List -->
                                            <div id="listaProductosDolares" class="ecommerce-products list-unstyled row">
                                                @foreach ($productosDolares as $_producto)
                                                    <div class="product col-12 col-md-6 idem-{{ $producto->IdArticulo }}">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">$
                                                                    <span id="p2-{{ $_producto->IdArticulo }}"
                                                                        class="product-price fs-16">
                                                                        {{ $_producto->Precio }}</span>
                                                                    <input type="hidden" name="Precio"
                                                                        value=" {{ $_producto->Precio }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="p1-{{ $_producto->IdArticulo }}"
                                                                        class="product-title font-weight-bold fs-16">{{ $_producto->Descripcion }}</span>
                                                                    <input type="hidden" name="Descripcion"
                                                                        value=" {{ $_producto->Descripcion }}">
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="codBarra-{{ $_producto->IdArticulo }}"
                                                                        class="text-success fs-12">{{ $_producto->Codigo }}</span>
                                                                </div>
                                                                {{-- <div class="col-12">
                                                                    <span class="text-muted">{{ $_producto->Marca }}
                                                                        / {{ $_producto->Categoria }} / </span> <span
                                                                        class="text-danger fs-13">Stock :
                                                                        {{ $_producto->Stock }} </span>
                                                                </div> --}}
                                                                <div class="col-12">
                                                                    <span id="marca-{{ $_producto->IdArticulo }}"
                                                                        class="text-muted">{{ $_producto->Marca }}</span>/
                                                                    <span class="text-muted"
                                                                        id="categoria-{{ $_producto->IdArticulo }}">{{ $_producto->Categoria }}</span>/
                                                                    <span class="text-danger fs-13">Stock :
                                                                        {{ $_producto->Stock }} </span>
                                                                </div>
                                                            </div>

                                                            <input hidden id="p3-{{ $_producto->IdArticulo }}"
                                                                value="{{ $_producto->UM }}" />
                                                            <!-- esto puse 1 -->
                                                            <input hidden id="IdUnidadMedida-{{ $_producto->IdArticulo }}"
                                                                value="{{ $_producto->IdUnidadMedida }}" />
                                                            <!-- esto puse 1 -->


                                                            <div class="form-group mt-2" hidden>
                                                                <label class="col-form-label-sm">Cantidad </label>
                                                                @if ($_producto->Stock < 1)
                                                                    <input id="p4-{{ $_producto->IdArticulo }}"
                                                                        type="number" min="0" value="0"
                                                                        class=" text-center" />
                                                                @else
                                                                    <input id="p4-{{ $_producto->IdArticulo }}"
                                                                        type="number" min="1" value="1"
                                                                        max="{{ $_producto->Stock }}"
                                                                        class=" text-center" />
                                                                @endif
                                                            </div>

                                                            <div class="form-group" hidden>
                                                                <label class="col-form-label-sm">Descuento </label>
                                                                <input id="p5-{{ $_producto->IdArticulo }}"
                                                                    value="0.0" class="text-center" />
                                                            </div>

                                                            <div hidden>
                                                                <div class="form-group col-12">
                                                                    <label class="col-form-label-sm">Costo</label>
                                                                    <input id="p6-{{ $_producto->IdArticulo }}"
                                                                        value="{{ $_producto->Costo }}"
                                                                        class="form-control text-center" />
                                                                </div>
                                                            </div>
                                                            <div hidden>
                                                                <div class="form-group col-12">
                                                                    <label class="col-form-label-sm">Stock </label>
                                                                    <input id="p7-{{ $_producto->IdArticulo }}"
                                                                        value="{{ $_producto->Stock }}"
                                                                        class="form-control text-center" />
                                                                </div>
                                                            </div>
                                                            <div hidden>
                                                                <input id="p8-{{ $_producto->IdArticulo }}"
                                                                    value="{{ $_producto->IdTipoMoneda }}"
                                                                    class="form-control text-center" />
                                                            </div>
                                                        </div>
                                                        <div class="card-footer">
                                                            <div class="product-info col-12">
                                                                @if ($_producto->Stock < 1)
                                                                    <a class="bg-info color-white fs-12"
                                                                        onclick="agregarProducto({{ $_producto->IdArticulo }})"
                                                                        href="javascript:void(0);">
                                                                        <i class="list-icon material-icons">add</i>Agregar
                                                                        (Agotado)
                                                                    </a>
                                                                @else
                                                                    <a class="bg-info color-white fs-12"
                                                                        onclick="agregarProducto({{ $_producto->IdArticulo }})"
                                                                        href="javascript:void(0);">
                                                                        <i class="list-icon material-icons">add</i>Agregar
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
                                                    <ul id="paginasProductosDolares"
                                                        class="pagination pagination-md d-flex justify-content-center pagProdDolares">
                                                        @if ($productosDolares->onFirstPage())
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
                                                                    href="productos?page=1" aria-label="Previous"><span
                                                                        aria-hidden="true"><i
                                                                            class="feather feather-chevrons-left"></i></span></a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $productosDolares->previousPageUrl() }}"
                                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                                            class="feather feather-chevron-left"></i></span></a>
                                                            </li>
                                                        @endif

                                                        @if ($productosDolares->currentPage() < 3)
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                                    @if ($i == $productosDolares->currentPage())
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
                                                        @elseif($productosDolares->lastPage() > 2)
                                                            @if ($productosDolares->currentPage() > $productosDolares->lastPage() - 2)
                                                                @for ($i = $productosDolares->currentPage() - 4; $i <= $productosDolares->lastPage(); $i++)
                                                                    @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                                        @if ($i == $productosDolares->currentPage())
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
                                                        @if ($productosDolares->currentPage() >= 3 &&
                                                            $productosDolares->currentPage() <= $productosDolares->lastPage() - 2)
                                                            @for ($i = $productosDolares->currentPage() - 2; $i <= $productosDolares->currentPage() + 2; $i++)
                                                                @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                                    @if ($i == $productosDolares->currentPage())
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
                                                        @if ($productosDolares->hasMorePages())
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $productosDolares->nextPageUrl() }}"
                                                                    aria-label="Next"><span aria-hidden="true"><i
                                                                            class="feather feather-chevron-right"></i></span></a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="productos?page={{ $productosDolares->lastPage() }}"
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
                                    <div class="tab-pane" id="tab-servicios-dolares">
                                        <div class="clearfix">
                                            <!--<div class="form-group form-material">
                                                                                                                                    <label class="form-control-label fs-14 fw-400">Buscar Servicio</label>
                                                                                                                                    <div class="input-group">
                                                                                                                                        <input type="text" id="inputBuscarServiciosDolares" name="textoBuscar" class="form-control fs-16 fw-400">
                                                                                                                                    </div>
                                                                                                                                </div>-->
                                            <div class="form-group">
                                                <i
                                                    class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                <input type="search" id="inputBuscarServiciosDolares" name="textoBuscar"
                                                    placeholder="Buscar servicio..."
                                                    class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                            </div>
                                            <!-- Products List -->
                                            <div id="listaServiciosDolares" class="ecommerce-products list-unstyled row">
                                                @foreach ($serviciosDolares as $_servicio)
                                                    <div class="product col-12 col-md-6">
                                                        <div class="card-body">
                                                            <div class="row">
                                                                <div class="col-12">$
                                                                    <span id="s2-{{ $_servicio->IdArticulo }}"
                                                                        class="product-price fs-16">{{ $_servicio->Precio }}</span>
                                                                </div>
                                                                <div class="col-12">
                                                                    <span id="s1-{{ $_servicio->IdArticulo }}"
                                                                        class="product-title font-weight-bold fs-16">{{ $_servicio->Descripcion }}</span>
                                                                </div>
                                                                <div class="col-12">
                                                                    <span
                                                                        class="text-success fs-12">{{ $_servicio->Codigo }}</span>
                                                                </div>
                                                            </div>
                                                            <input hidden id="s6-{{ $_servicio->IdArticulo }}"
                                                                value="{{ $_servicio->UM }}" />
                                                            <div class="form-group mt-2" hidden>
                                                                <label class="col-form-label-sm">Cantidad </label>
                                                                <input id="s5-{{ $_servicio->IdArticulo }}"
                                                                    type="number" min="1" value="1"
                                                                    class="text-center" />
                                                            </div>
                                                            <div class="form-group" hidden>
                                                                <label class="col-form-label-sm">Descuento </label>
                                                                <input id="s3-{{ $_servicio->IdArticulo }}"
                                                                    type="text" value="0.0" class="text-center" />
                                                            </div>
                                                            <div hidden>
                                                                <div class="form-group col-12">
                                                                    <label class="col-form-label-sm">Costo</label>
                                                                    <input id="s4-{{ $_servicio->IdArticulo }}"
                                                                        value="{{ $_servicio->Costo }}"
                                                                        class=" text-center" />
                                                                </div>
                                                            </div>
                                                            <div hidden>
                                                                <input id="s7-{{ $_servicio->IdArticulo }}"
                                                                    value="{{ $_servicio->IdTipoMoneda }}"
                                                                    class="form-control text-center" />
                                                            </div>
                                                        </div>

                                                        <div class="card-footer">
                                                            <div class="product-info col-12">
                                                                <a class="bg-info color-white fs-12"
                                                                    onclick="agregarServicio({{ $_servicio->IdArticulo }})"
                                                                    href="javascript:void(0);">
                                                                    <i class="list-icon material-icons">add</i>Agregar
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <!-- /.card-footer -->

                                                        <!-- /.card -->
                                                    </div>
                                                @endforeach
                                            </div>
                                            <!-- /.ecommerce-products -->
                                            <!-- Product Navigation -->
                                            <div class="col-md-12">
                                                <nav aria-label="Page navigation">
                                                    <ul id="paginasServiciosDolares"
                                                        class="pagination pagination-md d-flex justify-content-center pagServDolares">
                                                        @if ($serviciosDolares->onFirstPage())
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
                                                                    href="servicios?page=1" aria-label="Previous"><span
                                                                        aria-hidden="true"><i
                                                                            class="feather feather-chevrons-left"></i></span></a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $serviciosDolares->previousPageUrl() }}"
                                                                    aria-label="Previous"><span aria-hidden="true"><i
                                                                            class="feather feather-chevron-left"></i></span></a>
                                                            </li>
                                                        @endif
                                                        @if ($serviciosDolares->currentPage() < 3)
                                                            @for ($i = 1; $i <= 5; $i++)
                                                                @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                                    @if ($i == $serviciosDolares->currentPage())
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
                                                        @elseif($serviciosDolares->lastPage() > 2)
                                                            @if ($serviciosDolares->currentPage() > $serviciosDolares->lastPage() - 2)
                                                                @for ($i = $serviciosDolares->currentPage() - 4; $i <= $serviciosDolares->lastPage(); $i++)
                                                                    @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                                        @if ($i == $serviciosDolares->currentPage())
                                                                            <li class="page-item active"><a
                                                                                    class="page-link"
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
                                                        @if ($serviciosDolares->currentPage() >= 3 &&
                                                            $serviciosDolares->currentPage() <= $serviciosDolares->lastPage() - 2)
                                                            @for ($i = $serviciosDolares->currentPage() - 2; $i <= $serviciosDolares->currentPage() + 2; $i++)
                                                                @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                                    @if ($i == $serviciosDolares->currentPage())
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
                                                        @if ($serviciosDolares->hasMorePages())
                                                            <li class="page-item"><a class="page-link"
                                                                    href="{{ $serviciosDolares->nextPageUrl() }}"
                                                                    aria-label="Next"><span aria-hidden="true"><i
                                                                            class="feather feather-chevron-right"></i></span></a>
                                                            </li>
                                                            <li class="page-item"><a class="page-link"
                                                                    href="servicios?page={{ $servicios->lastPage() }}"
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
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-success btn-rounded ripple text-left"
                                data-dismiss="modal">Cerrar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{-- Fin --}}
    </div>
@stop
@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>

    {{-- Tabla responsiva --}}
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tablaAgregado').DataTable({
                    responsive: true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false
                });
            });
        });
    </script>
    {{-- Fin --}}


    {{-- Paginacion --}}
    <script>
        $("#categoriaSoles").on('change', function() {
            var textoBusqueda = $("#inputBuscarProductosSoles").val();
            var idCategoria = $("#categoriaSoles").val();
            ajaxBuscarInput(textoBusqueda, idCategoria, 1);
        });

        $("#categoriaDolares").on('change', function() {
            var textoBusqueda = $("#inputBuscarProductosDolares").val();
            var idCategoria = $("#categoriaDolares").val();
            ajaxBuscarInput(textoBusqueda, idCategoria, 2);
        });

        $("#inputBuscarProductosSoles").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductosSoles").val();
            if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
                var idCategoria = $("#categoriaSoles").val();
                ajaxBuscarInput(textoBusqueda, idCategoria, 1);
            }
        });

        $("#inputBuscarProductosDolares").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductosDolares").val();
            if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
                var idCategoria = $("#categoriaDolares").val();

                ajaxBuscarInput(textoBusqueda, idCategoria, 2);
            }
        });


        $("#inputBuscarServiciosSoles").keyup(function() {
            var textoBusqueda = $("#inputBuscarServiciosSoles").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                ajaxBuscarServiciosInput(textoBusqueda, 1);

            }
        });

        $("#inputBuscarServiciosDolares").keyup(function() {
            var textoBusqueda = $("#inputBuscarServiciosDolares").val();
            if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
                ajaxBuscarServiciosInput(textoBusqueda, 2);
            }
        });

        function ajaxBuscarInput(textoBusqueda, idCategoria, tipoMoneda) {
            $.ajax({
                type: 'get',
                url: '../buscar-productos',
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda,
                    'idCategoria': idCategoria
                },
                success: function(data) {
                    console.log(data);
                    if (tipoMoneda == 1) {
                        listadoProductos = '#listaProductosSoles';
                        paginasProductos = '#paginasProductosSoles';
                    } else {
                        listadoProductos = '#listaProductosDolares';
                        paginasProductos = '#paginasProductosDolares';
                    }

                    $('' + listadoProductos + '').empty();

                    var moneda;
                    var stock = '';
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    for (var i = 0; i < data["data"].length; i++) {
                        var codigo = '';
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        if (data["data"][i]["Stock"] < 1) {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar (Agotado)</a>' +
                                '</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }
                        if (data["data"][i]["Stock"] < 1) {
                            canti = '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="0" value="0" class=" text-center" />';
                        } else {
                            canti = '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] +
                                '" class=" text-center" />';
                        }
                        $('' + listadoProductos + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12">' + moneda +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted">' + data["data"][i]["Marca"] + '/' + data["data"][i][
                                "Categoria"
                            ] + '/' + '</span>' + '<span class="text-danger fs-13">Stock :' + data["data"][
                                i
                            ]["Stock"] + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["UM"] + '"/>' +
                            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            canti +
                            '</div>' +
                            '<div class="form-group" hidden>' +
                            '<label class="col-form-label-sm">Descuento </label>' +
                            '<input id="p5-' + data["data"][i]["IdArticulo"] +
                            '" value="0.0" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Costo"] + '" class="form-control text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Stock </label>' +
                            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Stock"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="p8-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('' + paginasProductos + '').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        primero =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    }


                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }


                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (data[
                                "current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' + data[
                                "last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('' + paginasProductos + '').append(concatenacion);
                }

            });
        }

        function ajaxBuscarServiciosInput(textoBusqueda, tipoMoneda) {
            $.ajax({
                type: 'get',
                url: '../buscar-servicios',
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    if (tipoMoneda == 1) {
                        listadoServicios = '#listaServiciosSoles';
                        paginasServicios = '#paginasServiciosSoles';
                    } else {
                        listadoServicios = '#listaServiciosDolares';
                        paginasServicios = '#paginasServiciosDolares';
                    }
                    $('' + listadoServicios + '').empty();
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    for (var i = 0; i < data["data"].length; i++) {
                        var codigo = '';
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        $('' + listadoServicios + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12">' + moneda +
                            '<span id="s2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="s1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            '<input id="s5-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" class="text-center" />' +
                            '</div>' +
                            '<div class="form-group" hidden>' +
                            '<label class="col-form-label-sm">Descuento </label>' +
                            '<input id="s3-' + data["data"][i]["IdArticulo"] +
                            '" value="0.0" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="s7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' +
                            '<a class="bg-info color-white fs-12" onclick="agregarServicio(' + data["data"][
                                i
                            ]["IdArticulo"] + ')" href="javascript:void(0);">' +
                            '<i class="list-icon material-icons">add</i>Agregar' +
                            '</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('' + paginasServicios + '').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a>';
                    }

                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }

                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="' + data[
                                "next_page_url"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="' + data["last_page_url"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('' + paginasServicios + '').append(concatenacion);
                }

            });
        }
    </script>
    <script>
        $(document).on('click', '.pagProdSoles a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarProductosSoles').val();
            var idCategoria = $("#categoriaSoles").val();
            getProductos(page, textoBusqueda, idCategoria, 1);
            //location.hash = page;
            //alert(page);
        });

        $(document).on('click', '.pagProdDolares a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarProductosDolares').val();
            var idCategoria = $("#categoriaDolares").val();
            getProductos(page, textoBusqueda, idCategoria, 2);
        });

        function getProductos(page, textoBusqueda, idCategoria, tipoMoneda) {
            //var textoBusqueda = $('#inputBuscarProductos').val();
            //var tipoMoneda = $("#tipoMoneda").val();
            var stock = '';
            //var idCategoria = $("#categoria").val();

            /////////////////////////////////////aqui agregue
            //var select = '';
            //var u = "u";
            //var t = "t";
            //var m = "m";
            //////////////////////////////////////////
            $.ajax({
                type: 'get',
                url: '../productos?page=' + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda,
                    'idCategoria': idCategoria
                },
                success: function(data) {
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                    } else {
                        moneda = '$';
                    }
                    if (tipoMoneda == 1) {
                        listadoProductos = '#listaProductosSoles';
                        paginasProductos = '#paginasProductosSoles';
                    } else {
                        listadoProductos = '#listaProductosDolares';
                        paginasProductos = '#paginasProductosDolares';
                    }
                    $('' + listadoProductos + '').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        var codigo = '';
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        if (data["data"][i]["Stock"] < 1) {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar (Agotado)</a>' +
                                '</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }

                        if (data["data"][i]["Stock"] < 1) {
                            canti = '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="0" value="0" class=" text-center" />';
                        } else {
                            canti = '<input id="p4-' + data["data"][i]["IdArticulo"] +
                                '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] +
                                '" class=" text-center" />';
                        }

                        $('' + listadoProductos + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12">' + moneda +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted">' + data["data"][i]["Marca"] + '/' + data["data"][i][
                                "Categoria"
                            ] + '/' + '</span>' + '<span class="text-danger fs-13">Stock :' + data["data"][
                                i
                            ]["Stock"] + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["UM"] + '"/>' +
                            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            canti +
                            '</div>' +
                            '<div class="form-group" hidden>' +
                            '<label class="col-form-label-sm">Descuento </label>' +
                            '<input id="p5-' + data["data"][i]["IdArticulo"] +
                            '" value="0.0" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Costo"] + '" class="form-control text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Stock</label>' +
                            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Stock"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="p8-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('' + paginasProductos + '').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        primero =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    }


                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }


                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (data[
                                "current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' + data[
                                "last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('' + paginasProductos + '').append(concatenacion);
                }
            });
        }

        $(document).on('click', '.pagServSoles a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarServiciosSoles').val();
            getServicios(page, textoBusqueda, 1);
        });

        $(document).on('click', '.pagServDolares a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            var textoBusqueda = $('#inputBuscarServiciosDolares').val();
            getServicios(page, textoBusqueda, 2);
        });

        function getServicios(page, textoBusqueda, tipoMoneda) {
            $.ajax({
                type: 'get',
                // url : 'servicios?page=' + page,
                url: '../servicios?page=' + page,
                data: {
                    'textoBuscar': textoBusqueda,
                    'tipoMoneda': tipoMoneda
                },
                success: function(data) {
                    var moneda;
                    if (tipoMoneda == 1) {
                        moneda = 'S/';
                        listadoServicios = '#listaServiciosSoles';
                        paginasServicios = '#paginasServiciosSoles';
                    } else {
                        moneda = '$';
                        listadoServicios = '#listaServiciosDolares';
                        paginasServicios = '#paginasServiciosDolares';
                    }
                    $('' + listadoServicios + '').empty();
                    for (var i = 0; i < data["data"].length; i++) {
                        var codigo = '';
                        if (data["data"][i]["Codigo"] != null) {
                            codigo = data["data"][i]["Codigo"];
                        }
                        $('' + listadoServicios + '').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-12">' + moneda +
                            '<span id="s2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="s1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + codigo + '</span>' +
                            '</div>' +
                            '</div>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            '<input id="s5-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" class="text-center" />' +
                            '</div>' +
                            '<div class="form-group" hidden>' +
                            '<label class="col-form-label-sm">Descuento </label>' +
                            '<input id="s3-' + data["data"][i]["IdArticulo"] +
                            '" value="0.0" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<input id="s7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' +
                            '<a class="bg-info color-white fs-12" onclick="agregarServicio(' + data["data"][
                                i
                            ]["IdArticulo"] + ')" href="javascript:void(0);">' +
                            '<i class="list-icon material-icons">add</i>Agregar' +
                            '</a>' +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }

                    $('' + paginasServicios + '').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a>';
                    }

                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="servicios?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }

                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="servicios?page=' + (data[
                                "current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="servicios?page=' + data[
                                "last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('' + paginasServicios + '').append(concatenacion);
                }
            });
        }
    </script>
    {{-- Fin --}}

    {{-- Agregar Productos --}}
    <script>
        var iden = 1;
        var arrayIds = [];

        function agregarProducto(id) {
            var descripcion = $('#p1-' + id).text();
            var precio = $('#p2-' + id).text();
            var stock = $('#p7-' + id).val();
            var marca = $('#marca-' + id).text();
            var categoria = $('#categoria-' + id).text();
            var codigoBarra = $('#codBarra-' + id).text();

            if (arrayIds.includes(id) == true) {
                swal("Items ya Agregado en el Grupo");
                return 0;
            }
            productoEnTabla(id, descripcion, marca, categoria, precio, stock, codigoBarra);
            return 0;
        }

        function productoEnTabla(id, descripcion, marca, categoria, precio, stock, codigoBarra) {

            var fila = '<tr class="text-center" id="row' + iden + '"><td id="pro' + iden + '">' + 'PRO-' + id +
                '<input type="hidden" name="Codigo[]"  value="PRO-' + id + ' "</td>' +
                '<td id="descrip' + iden + '">' + descripcion +
                '<input type="hidden" id="Descripcion" name="Descripcion" value="hello" </td>' +
                '<td>' + marca + '</td>' +
                '<td>' + categoria + '</td>' +
                '<td id="precio' + iden + '">' + precio + '</td>' +
                '<td>' + stock + '</td>' +
                '<td>' + codigoBarra + '</td>' +
                '<td ><button id="btn' + iden + '" onclick="eliminarFila(' + iden + ',' + id +
                ')" class="btn btn-primary"><i class="list-icon material-icons fs-16">clear</i></button>' +
                '</td>' +
                '</tr>';
            $('#tablaAgregado').append(fila);
            iden++;
            arrayIds.push(parseInt(id));
        }


        function agregarServicio(id) {
            if (arrayIds.includes(id) == true) {
                swal("Items ya Agregado en el Grupo");
            } else {
                var descripcion = $('#s1-' + id).text();
                var precio = $('#s2-' + id).text();
                var marca = "-";
                var categoria = "-";
                var stock = "-";
                var codigoBarra = $('#codBarraSer-' + id).text();
                servicioEnTabla(id, descripcion, marca, categoria, precio, stock, codigoBarra);

            }
        }

        function servicioEnTabla(id, descripcion, marca, categoria, precio, stock, codigoBarra) {
            $('#tablaAgregado tr:last').after('<tr class="text-center"  id="row' + iden + '"><td  id="pro' + iden + '">' +
                'SER-' + id +
                '<input type="hidden" name="Codigo[]"  value="SER-' + id + ' "' +
                '</td><td id="descrip' + iden + '">' + descripcion +
                '<input type="hidden" id="Descripcion" name="Descripcion" value="' + descripcion + '" </td> ' +
                '<td>' + marca + '</td>' +
                '<td>' + categoria + '</td>' +
                '<td>' + precio + '</td>' +
                '<td>' + stock + '</td>' +
                '<td>' + codigoBarra + '</td>' +
                '<td><button id="btn' + iden + '" onclick="eliminarFila(' + iden + ',' + id +
                ')" class="btn btn-primary"><i class="list-icon material-icons fs-16">clear</i></button>' +
                '</td>' +
                '</tr>');
            iden++;
            arrayIds.push(id);
        }

        function eliminarFila(id, i) {
            swal({
                    title: "Estás seguro?",
                    text: "Una vez eliminado, no podrá recuperar este Items!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        swal("Items Eliminado!", {
                            icon: "success",
                        });
                        $('#row' + id).remove();
                        $('#' + id).remove();
                    }
                });
            var index = arrayIds.indexOf(i);
            if (index > -1) {
                arrayIds.splice(index, 1);
            }
        }

        function enviar() {

            swal({
                    title: "Desea Continuar?",
                    text: "Una vez actualizado el grupo, no podrá recuperar el grupo anterior!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willActualizar) => {
                    if (willActualizar) {
                        let tipoMoneda = $('#idMoneda').val();
                        var nombreGrupo = $('#nombre').val();
                        var idGrupo = $("#idGrupo").val();
                        var codigos = $("input[name='Codigo[]']").map(function() {
                            return $(this).val();
                        }).get();
                        var ids = arrayIds;
                        $.ajax({
                            type: 'post',
                            url: '../actualizar-grupo',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "nombreGrupo": nombreGrupo,
                                "tipoMoneda": tipoMoneda,
                                "Codigo": codigos,
                                "idGrupo": idGrupo,
                                "Id": ids
                            },
                            // success: function(data) {
                            //     if (data[0] == "alert1") {
                            //         alert("El nombre del grupo no puede estar vacio");
                            //     }
                            //     swal({
                            //             title: "Se actualizo Correctamente!",
                            //             icon: "success",
                            //             button: "Entendido",
                            //         })
                            //         .then((Entendido) => {
                            //             if (Entendido) {
                            //                 window.location = '../../grupos/detalle-grupo/' + data[2];
                            //             }
                            //         });
                            // }

                            success: function(data) {

                                if (data[0] == 'alert1') {
                                    // alert(data[1]);
                                    swal("Nombre de grupo Vacio", "Por favor ingrese el nombre");
                                    // mensajeError('Ingrese el nombre de grupo');
                                } else {
                                    if (data[0] == 'alert2') {
                                        // alert(data[1]);
                                        swal("Falta Ingresar items al Grupo",
                                            "Por favor ingrese los Items");
                                        // mensajeError('El Grupo esta vacio, Ingrese Items');
                                    } else {
                                        if (data[0] == 'alert3') {
                                            // alert(data[1]);
                                            swal("El grupo ya existe", "Por favor agregue otro nombre");
                                            // mensajeError('El nombre de Grupo, Ingresado Ya existe');
                                        } else {
                                            swal({
                                                    title: "El Grupo Creo Correctamente!",
                                                    icon: "success",
                                                    button: "Entendido",
                                                })
                                                .then((Entendido) => {
                                                    if (Entendido) {
                                                        window.location =
                                                            '../../grupos/detalle-grupo/' + data[2];
                                                    }
                                                });
                                        }
                                    }
                                }
                            }
                        })
                    }
                })
        }
    </script>
    {{-- Fin --}}

    {{-- Cargar Items en tabla --}}
    <script>
        $(function() {
            var itemsGrupo = <?php echo json_encode($itemsGrupo); ?>;
            for (var i = 0; i < itemsGrupo.length; i++) {
                datosProductos = new Array();
                if (parseInt(itemsGrupo[i]["idTipoArticulo"]) == 1) {
                    productoEnTabla(itemsGrupo[i]["IdArticulo"], itemsGrupo[i]["Descripcion"], itemsGrupo[i][
                            "nombreMarca"
                        ], itemsGrupo[i]["nombreCategoria"], itemsGrupo[i]["Precio"], itemsGrupo[i]["Stock"],
                        itemsGrupo[i]["codigoBarra"])
                } else {
                    servicioEnTabla(itemsGrupo[i]["IdArticulo"], itemsGrupo[i]["Descripcion"], itemsGrupo[i][
                            "nombreMarca"
                        ], itemsGrupo[i]["nombreCategoria"], itemsGrupo[i]["Precio"], itemsGrupo[i]["Stock"],
                        itemsGrupo[i]["codigoBarra"])
                }
            }
        });
    </script>



@stop
