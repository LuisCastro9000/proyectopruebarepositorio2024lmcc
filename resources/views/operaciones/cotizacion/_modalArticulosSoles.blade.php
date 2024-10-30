<div class="modal fade bs-modal-lg-productos-soles" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="tabs tabs-bordered">
                    <ul class="nav nav-tabs">
                        <li class="nav-item"><a class="nav-link active" href="#tab-productos-soles" data-toggle="tab"
                                aria-controls="tab-productos-soles">Productos</a>
                        </li>
                        <li class="nav-item"><a class="nav-link" href="#tab-servicios-soles" data-toggle="tab"
                                aria-controls="tab-servicios-soles">Servicios</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-productos-soles">
                            <!--<h5 class="modal-title" id="myLargeModalLabel">Listado de Productos</h5>-->
                            <div class="clearfix">
                                <div class="form-group form-material">
                                    <div class="row">
                                        <div class="col-6">
                                            <label class="form-control-label fs-14 fw-500">Buscar
                                                Producto</label>
                                        </div>
                                        <div class="col-6">
                                            <select class="form-control select2-hidden-accessible" id="categoriaSoles"
                                                name="categoriaSoles" data-placeholder="Choose" tabindex="-1"
                                                data-toggle="select2" aria-hidden="true">
                                                <option value="0">Seleccionar
                                                    Categoría</option>
                                                @foreach ($categorias as $categoria)
                                                    <option value="{{ $categoria->IdCategoria }}">
                                                        {{ $categoria->Nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                    <input type="search" id="inputBuscarProductosSoles" name="textoBuscar"
                                        placeholder="Buscar producto..."
                                        class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                </div>

                                <!-- Products List -->
                                <div id="listaProductosSoles" class="ecommerce-products list-unstyled row">
                                    @foreach ($productos as $producto)
                                        <div class="product col-12 col-md-6">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">S/
                                                        <span id="p2-{{ $producto->IdArticulo }}"
                                                            class="product-price fs-16">
                                                            {{ $producto->Precio }}</span>
                                                    </div>
                                                    @if ($sucExonerado == 1)
                                                        <div class="col-6">S/
                                                            <span
                                                                class="text-danger product-price fs-16">{{ number_format($producto->Precio / 1.18, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="col-12">
                                                        <span id="p1-{{ $producto->IdArticulo }}"
                                                            class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-success fs-12">{{ $producto->Codigo }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span class="text-muted">{{ $producto->Marca }}
                                                            /
                                                            {{ $producto->Categoria }}
                                                            / </span> <span class="text-danger fs-13">Stock
                                                            :
                                                            {{ $producto->Stock }}
                                                        </span>
                                                    </div>
                                                </div>

                                                <input hidden id="p3-{{ $producto->IdArticulo }}"
                                                    value="{{ $producto->UM }}" />
                                                <!-- esto puse 1 -->
                                                <input hidden id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                                    value="{{ $producto->IdUnidadMedida }}" />
                                                <!-- esto puse 1 -->


                                                <div class="form-group mt-2" hidden>
                                                    <label class="col-form-label-sm">Cantidad
                                                    </label>
                                                    @if ($producto->Stock < 1)
                                                        <input id="p4-{{ $producto->IdArticulo }}" type="number"
                                                            min="0" value="0" class=" text-center" />
                                                    @else
                                                        <input id="p4-{{ $producto->IdArticulo }}" type="number"
                                                            min="1" value="1" max="{{ $producto->Stock }}"
                                                            class=" text-center" />
                                                    @endif
                                                </div>

                                                <div class="form-group" hidden>
                                                    <label class="col-form-label-sm">Descuento
                                                    </label>
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
                                                        <label class="col-form-label-sm">Stock
                                                        </label>
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
                                                        <button type="button" data-id="{{ $producto->IdArticulo }}"
                                                            {{ $deshabilidato }}
                                                            id="botonAgregarArticuloPRO-{{ $producto->IdArticulo }}"
                                                            class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarProducto">
                                                            <span id="iconoAgregarPRO-{{ $producto->IdArticulo }}">
                                                                <i class="list-icon material-icons">add</i>Agregar
                                                                Producto
                                                                (Agotado)
                                                            </span>
                                                            <span id="iconoCandadoPRO-{{ $producto->IdArticulo }}"
                                                                class=" d-none"><i
                                                                    class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>
                                                    @else
                                                        <button type="button" data-id="{{ $producto->IdArticulo }}"
                                                            id="botonAgregarArticuloPRO-{{ $producto->IdArticulo }}"
                                                            class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarProducto"><span
                                                                id="iconoAgregarPRO-{{ $producto->IdArticulo }}">
                                                                <i class="list-icon material-icons">add</i>Agregar
                                                                Producto</span>
                                                            <span id="iconoCandadoPRO-{{ $producto->IdArticulo }}"
                                                                class=" d-none"><i
                                                                    class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>
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
                                                <li class="page-item"><a class="page-link" href="productos?page=1"
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
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
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
                                                                <li class="page-item active">
                                                                    <a class="page-link"
                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                </li>
                                                            @else
                                                                <li class="page-item">
                                                                    <a class="page-link"
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
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
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
                                    <label class="form-control-label fs-14 fw-400">Buscar
                                        Servicio</label>
                                </div>
                                <div class="form-group">
                                    <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                    <input type="search" id="inputBuscarServiciosSoles" name="textoBuscar"
                                        placeholder="Buscar servicio..."
                                        class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                </div>
                                <!-- Products List -->
                                <div id="listaServiciosSoles" class="ecommerce-products list-unstyled row">
                                    @foreach ($servicios as $servicio)
                                        <div class="product col-12 col-md-6">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-6">S/
                                                        <span id="s2-{{ $servicio->IdArticulo }}"
                                                            class="product-price fs-16">{{ $servicio->Precio }}</span>
                                                    </div>
                                                    @if ($sucExonerado == 1)
                                                        <div class="col-6">S/
                                                            <span
                                                                class="text-danger product-price fs-16">{{ number_format($servicio->Precio / 1.18, 2) }}</span>
                                                        </div>
                                                    @endif
                                                    <div class="col-12">
                                                        <span id="s1-{{ $servicio->IdArticulo }}"
                                                            class="product-title font-weight-bold fs-16">{{ $servicio->Descripcion }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <span
                                                            class="text-success fs-12">{{ $servicio->Codigo }}</span>
                                                    </div>
                                                </div>
                                                <input hidden id="s6-{{ $servicio->IdArticulo }}"
                                                    value="{{ $servicio->UM }}" />
                                                <div class="form-group mt-2" hidden>
                                                    <label class="col-form-label-sm">Cantidad
                                                    </label>
                                                    <input id="s5-{{ $servicio->IdArticulo }}" type="number"
                                                        min="1" value="1" class="text-center" />
                                                </div>
                                                <div class="form-group" hidden>
                                                    <label class="col-form-label-sm">Descuento
                                                    </label>
                                                    <input id="s3-{{ $servicio->IdArticulo }}" type="text"
                                                        value="0.0" class="text-center" />
                                                </div>
                                                <div hidden>
                                                    <div class="form-group col-12">
                                                        <label class="col-form-label-sm">Costo</label>
                                                        <input id="s4-{{ $servicio->IdArticulo }}"
                                                            value="{{ $servicio->Costo }}" class=" text-center" />
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
                                                    {{-- <a class="bg-info color-white fs-12"
                                                                                            onclick="agregarServicioTabla({{ $servicio->IdArticulo }})"
                                                                                            href="javascript:void(0);">
                                                                                            <i
                                                                                                class="list-icon material-icons">add</i>Agregarrrr
                                                                                        </a> --}}
                                                    <button type="button"
                                                        id="botonAgregarArticuloSER-{{ $servicio->IdArticulo }}"
                                                        data-id="{{ $servicio->IdArticulo }}"
                                                        class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarServicio"><span
                                                            id="iconoAgregarSER-{{ $servicio->IdArticulo }}">
                                                            <i class="list-icon material-icons">add</i>Agregar
                                                            Servicio</span>
                                                        <span id="iconoCandadoSER-{{ $servicio->IdArticulo }}"
                                                            class="d-none"><i
                                                                class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>
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
                                                <li class="page-item"><a class="page-link" href="servicios?page=1"
                                                        aria-label="Previous"><span aria-hidden="true"><i
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
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
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
                                                                <li class="page-item active">
                                                                    <a class="page-link"
                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                </li>
                                                            @else
                                                                <li class="page-item">
                                                                    <a class="page-link"
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
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
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
