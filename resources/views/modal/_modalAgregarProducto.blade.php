{{-- Modal Productos --}}
<div class="modal fade bs-modal-lg-productos" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <h6 class="modal-title" id="myLargeModalLabel">Listado de
                    Productos</h6>
                <div class="widget-body clearfix">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <select class="form-control select2-hidden-accessible" id="categoria" name="categoria"
                                data-placeholder="Choose" tabindex="-1" data-toggle="select2" aria-hidden="true">
                                <option value="0">Seleccionar Categoría
                                </option>
                                @foreach ($categorias as $categoria)
                                    <option value="{{ $categoria->IdCategoria }}">
                                        {{ $categoria->Nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group mt-2">
                        <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                        <input type="search" id="inputBuscarProductos" name="textoBuscar"
                            placeholder="Buscar producto..."
                            class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                    </div>
                    <!-- Products List -->
                    <div id="listaProductos" class="ecommerce-products list-unstyled row">
                        @foreach ($productos as $producto)
                            <div class="product col-12 col-md-6">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-12">
                                                <span id="p1-{{ $producto->IdArticulo }}"
                                                    class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                            </div>
                                            <div class="col-12 d-flex">
                                                <span class="fs-22" style="line-height: 2;">S/</span>
                                                <input id="p2-{{ $producto->IdArticulo }}"
                                                    class="form-control product-price fs-16"
                                                    value="{{ $producto->Costo }}" type="number" step="any" />
                                            </div>
                                            <div class="col-12">
                                                <span class="text-muted">{{ $producto->Marca }}</span>
                                            </div>
                                        </div>

                                        <input hidden id="p3-{{ $producto->IdArticulo }}" value="{{ $producto->UM }}" />

                                        <div class="form-group col-12" hidden>
                                            <label class="col-form-label-sm">Cantidad</label>
                                            <input id="p4-{{ $producto->IdArticulo }}" type="number" min="1"
                                                value="1" class="form-control text-center" />
                                        </div>

                                        <div class="form-group col-12">
                                            <div class="row d-flex">
                                                @if ($sucExonerado == 1)
                                                    <div class="col-6">
                                                        <label class="col-form-label-sm">Precio
                                                            Venta</label>
                                                        <input id="p5-{{ $producto->IdArticulo }}"
                                                            value="{{ $producto->Precio }}"
                                                            class="form-control text-success text-center" readonly />
                                                    </div>
                                                    <div class="col-6">
                                                        <label class="col-form-label-sm">Precio
                                                            Costo</label>
                                                        <input value="{{ number_format($producto->Precio / 1.18, 2) }}"
                                                            class="form-control text-danger text-center" readonly />
                                                    </div>
                                                @else
                                                    <div class="col-12">
                                                        <input id="p5-{{ $producto->IdArticulo }}"
                                                            value="{{ $producto->Precio }}"
                                                            class="form-control text-success text-center" readonly />
                                                    </div>
                                                @endif
                                                @if ($producto->Codigo != null)
                                                    <div class="col-12 mt-2">
                                                        <span class="text-success font-weight-bold fs-14">Codigo
                                                            Barra:
                                                            {{ $producto->Codigo }}</span>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>

                                        <input hidden id="p6-{{ $producto->IdArticulo }}"
                                            value="{{ $producto->TipoOperacion }}" />
                                        <input hidden id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                            value="{{ $producto->IdUnidadMedida }}" />

                                    </div>

                                    <div class="card-footer">
                                        <div class="product-info col-12">
                                            <a class="bg-info color-white"
                                                onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                href="javascript:void(0);">
                                                <i class="list-icon material-icons">add</i>Agregar
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <!-- /.card -->
                            </div>
                        @endforeach
                    </div>
                    <!-- Product Navigation -->
                    <div class="col-md-12">
                        <nav aria-label="Page navigation">
                            <ul id="paginasProductos"
                                class="pagination pagination-md d-flex justify-content-center pagProd">
                                @if ($productos->onFirstPage())
                                    <li class="page-item"><a class="page-link disabled" aria-label="Previous"><span
                                                aria-hidden="true"><i
                                                    class="feather feather-chevrons-left"></i></span></a>
                                    </li>
                                    <li class="page-item"><a class="page-link disabled" aria-label="Previous"><span
                                                aria-hidden="true"><i
                                                    class="feather feather-chevron-left"></i></span></a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link" href="productos?page=1"
                                            aria-label="Previous"><span aria-hidden="true"><i
                                                    class="feather feather-chevrons-left"></i></span></a>
                                    </li>
                                    <li class="page-item"><a class="page-link"
                                            href="{{ $productos->previousPageUrl() }}" aria-label="Previous"><span
                                                aria-hidden="true"><i
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
                                @if ($productos->currentPage() >= 3 && $productos->currentPage() <= $productos->lastPage() - 2)
                                    @for ($i = $productos->currentPage() - 2; $i <= $productos->currentPage() + 2; $i++)
                                        @if ($i > 0 && $i <= $productos->lastPage())
                                            @if ($i == $productos->currentPage())
                                                <li class="page-item active">
                                                    <a class="page-link"
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
                                            href="{{ $productos->nextPageUrl() }}" aria-label="Next"><span
                                                aria-hidden="true"><i
                                                    class="feather feather-chevron-right"></i></span></a>
                                    </li>
                                    <li class="page-item"><a class="page-link"
                                            href="productos?page={{ $productos->lastPage() }}"
                                            aria-label="Next"><span aria-hidden="true"><i
                                                    class="feather feather-chevrons-right"></i></span></a>
                                    </li>
                                @else
                                    <li class="page-item"><a class="page-link disabled" aria-label="Next"><span
                                                aria-hidden="true"><i
                                                    class="feather feather-chevron-right"></i></span></a>
                                    </li>
                                    <li class="page-item"><a class="page-link disabled" aria-label="Next"><span
                                                aria-hidden="true"><i
                                                    class="feather feather-chevrons-right"></i></span></a>
                                    </li>
                                @endif
                            </ul>
                        </nav>
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
{{-- Fin --}}
