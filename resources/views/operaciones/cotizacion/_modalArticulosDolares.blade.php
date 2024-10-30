 <div class="modal fade bs-modal-lg-productos-dolares" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
     style="display: none">
     <div class="modal-dialog modal-lg">
         <div class="modal-content">
             <div class="modal-body">
                 <div class="tabs tabs-bordered">
                     <ul class="nav nav-tabs">
                         <li class="nav-item"><a class="nav-link active" href="#tab-productos-dolares" data-toggle="tab"
                                 aria-controls="tab-productos-dolares">Productos</a>
                         </li>
                         <li class="nav-item"><a class="nav-link" href="#tab-servicios-dolares" data-toggle="tab"
                                 aria-controls="tab-servicios-dolares">Servicios</a>
                         </li>
                     </ul>
                     <div class="tab-content">
                         <div class="tab-pane active" id="tab-productos-dolares">
                             <div class="clearfix">
                                 <div class="form-group form-material">
                                     <div class="row">
                                         <div class="col-6">
                                             <label class="form-control-label fs-14 fw-500">Buscar
                                                 Producto</label>
                                         </div>
                                         <div class="col-6">
                                             <select class="form-control select2-hidden-accessible"
                                                 id="categoriaDolares" name="categoriaDolares" data-placeholder="Choose"
                                                 tabindex="-1" data-toggle="select2" aria-hidden="true">
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
                                     <input type="search" id="inputBuscarProductosDolares" name="textoBuscar"
                                         placeholder="Buscar producto..."
                                         class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                 </div>

                                 <!-- Products List -->
                                 <div id="listaProductosDolares" class="ecommerce-products list-unstyled row">
                                     @foreach ($productosDolares as $_producto)
                                         <div class="product col-12 col-md-6">
                                             <div class="card-body">
                                                 <div class="row">
                                                     <div class="col-6">$
                                                         <span id="p2-{{ $_producto->IdArticulo }}"
                                                             class="product-price fs-16">
                                                             {{ $_producto->Precio }}</span>
                                                     </div>
                                                     @if ($sucExonerado == 1)
                                                         <div class="col-6">$
                                                             <span
                                                                 class="text-danger product-price fs-16">{{ number_format($_producto->Precio / 1.18, 2) }}</span>
                                                         </div>
                                                     @endif
                                                     <div class="col-12">
                                                         <span id="p1-{{ $_producto->IdArticulo }}"
                                                             class="product-title font-weight-bold fs-16">{{ $_producto->Descripcion }}</span>
                                                     </div>
                                                     <div class="col-12">
                                                         <span
                                                             class="text-success fs-12">{{ $_producto->Codigo }}</span>
                                                     </div>
                                                     <div class="col-12">
                                                         <span class="text-muted">{{ $_producto->Marca }}
                                                             /
                                                             {{ $_producto->Categoria }}
                                                             / </span> <span class="text-danger fs-13">Stock
                                                             :
                                                             {{ $_producto->Stock }}
                                                         </span>
                                                     </div>


                                                 </div>

                                                 <input hidden id="p3-{{ $_producto->IdArticulo }}"
                                                     value="{{ $_producto->UM }}" />
                                                 <!-- esto puse 1 -->
                                                 <input hidden id="IdUnidadMedida-{{ $_producto->IdArticulo }}"
                                                     value="{{ $_producto->IdUnidadMedida }}" />
                                                 <!-- esto puse 1 -->


                                                 <div class="form-group mt-2" hidden>
                                                     <label class="col-form-label-sm">Cantidad
                                                     </label>
                                                     @if ($_producto->Stock < 1)
                                                         <input id="p4-{{ $_producto->IdArticulo }}" type="number"
                                                             min="0" value="0" class=" text-center" />
                                                     @else
                                                         <input id="p4-{{ $_producto->IdArticulo }}" type="number"
                                                             min="1" value="1"
                                                             max="{{ $_producto->Stock }}" class=" text-center" />
                                                     @endif
                                                 </div>

                                                 <div class="form-group" hidden>
                                                     <label class="col-form-label-sm">Descuento
                                                     </label>
                                                     <input id="p5-{{ $_producto->IdArticulo }}" value="0.0"
                                                         class="text-center" />
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
                                                         <label class="col-form-label-sm">Stock
                                                         </label>
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
                                                         <button type="button" id="PRO-{{ $_producto->IdArticulo }}"
                                                             data-id="{{ $_producto->IdArticulo }}"
                                                             {{ $deshabilidato }}
                                                             class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarProducto">
                                                             <span id="iconoAgregarPRO-{{ $_producto->IdArticulo }}">
                                                                 <i class="list-icon material-icons">add</i>Agregar
                                                                 (Agotado)
                                                             </span>
                                                             <span id="iconoCandadoPRO-{{ $_producto->IdArticulo }}"
                                                                 class=" d-none"><i
                                                                     class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>
                                                     @else
                                                         <button type="button"
                                                             id="botonAgregarArticuloPRO-{{ $_producto->IdArticulo }}"
                                                             data-id="{{ $_producto->IdArticulo }}"
                                                             class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarProducto"><span
                                                                 id="iconoAgregarPRO-{{ $_producto->IdArticulo }}">
                                                                 <i
                                                                     class="list-icon material-icons">add</i>Agregar</span>
                                                             <span id="iconoCandadoPRO-{{ $_producto->IdArticulo }}"
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
                                                 <li class="page-item"><a class="page-link" href="productos?page=1"
                                                         aria-label="Previous"><span aria-hidden="true"><i
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
                                             @elseif($productosDolares->lastPage() > 2)
                                                 @if ($productosDolares->currentPage() > $productosDolares->lastPage() - 2)
                                                     @for ($i = $productosDolares->currentPage() - 4; $i <= $productosDolares->lastPage(); $i++)
                                                         @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                             @if ($i == $productosDolares->currentPage())
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
                                             @if ($productosDolares->currentPage() >= 3 && $productosDolares->currentPage() <= $productosDolares->lastPage() - 2)
                                                 @for ($i = $productosDolares->currentPage() - 2; $i <= $productosDolares->currentPage() + 2; $i++)
                                                     @if ($i > 0 && $i <= $productosDolares->lastPage())
                                                         @if ($i == $productosDolares->currentPage())
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
                                 <div class="form-group">
                                     <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
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
                                                     <div class="col-6">$
                                                         <span id="s2-{{ $_servicio->IdArticulo }}"
                                                             class="product-price fs-16">{{ $_servicio->Precio }}</span>
                                                     </div>
                                                     @if ($sucExonerado == 1)
                                                         <div class="col-6">$
                                                             <span
                                                                 class="text-danger product-price fs-16">{{ number_format($_servicio->Precio / 1.18, 2) }}</span>
                                                         </div>
                                                     @endif
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
                                                     <label class="col-form-label-sm">Cantidad
                                                     </label>
                                                     <input id="s5-{{ $_servicio->IdArticulo }}" type="number"
                                                         min="1" value="1" class="text-center" />
                                                 </div>
                                                 <div class="form-group" hidden>
                                                     <label class="col-form-label-sm">Descuento
                                                     </label>
                                                     <input id="s3-{{ $_servicio->IdArticulo }}" type="text"
                                                         value="0.0" class="text-center" />
                                                 </div>
                                                 <div hidden>
                                                     <div class="form-group col-12">
                                                         <label class="col-form-label-sm">Costo</label>
                                                         <input id="s4-{{ $_servicio->IdArticulo }}"
                                                             value="{{ $_servicio->Costo }}" class=" text-center" />
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
                                                     {{-- <a class="bg-info color-white fs-12"
                                                                                            onclick="agregarServicioTabla({{ $_servicio->IdArticulo }})"
                                                                                            href="javascript:void(0);">
                                                                                            <i
                                                                                                class="list-icon material-icons">add</i>Agregar
                                                                                        </a> --}}
                                                     <button type="button"
                                                         id="botonAgregarArticuloSER-{{ $_servicio->IdArticulo }}"
                                                         data-id="{{ $_servicio->IdArticulo }}"
                                                         class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarServicio"><span
                                                             id="iconoAgregarSER-{{ $_servicio->IdArticulo }}">
                                                             <i class="list-icon material-icons">add</i>Agregar
                                                             Servicio</span>
                                                         <span id="iconoCandadoSER-{{ $_servicio->IdArticulo }}"
                                                             class=" d-none"><i
                                                                 class='bx bxs-lock-alt fs-18 mr-1'></i>Seleccionado</span></button>
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
                                                 <li class="page-item"><a class="page-link" href="servicios?page=1"
                                                         aria-label="Previous"><span aria-hidden="true"><i
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
                                             @elseif($serviciosDolares->lastPage() > 2)
                                                 @if ($serviciosDolares->currentPage() > $serviciosDolares->lastPage() - 2)
                                                     @for ($i = $serviciosDolares->currentPage() - 4; $i <= $serviciosDolares->lastPage(); $i++)
                                                         @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                             @if ($i == $serviciosDolares->currentPage())
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
                                             @if ($serviciosDolares->currentPage() >= 3 && $serviciosDolares->currentPage() <= $serviciosDolares->lastPage() - 2)
                                                 @for ($i = $serviciosDolares->currentPage() - 2; $i <= $serviciosDolares->currentPage() + 2; $i++)
                                                     @if ($i > 0 && $i <= $serviciosDolares->lastPage())
                                                         @if ($i == $serviciosDolares->currentPage())
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
                                             @if ($serviciosDolares->hasMorePages())
                                                 <li class="page-item"><a class="page-link"
                                                         href="{{ $serviciosDolares->nextPageUrl() }}"
                                                         aria-label="Next"><span aria-hidden="true"><i
                                                                 class="feather feather-chevron-right"></i></span></a>
                                                 </li>
                                                 <li class="page-item"><a class="page-link"
                                                         href="servicios?page={{ $serviciosDolares->lastPage() }}"
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
