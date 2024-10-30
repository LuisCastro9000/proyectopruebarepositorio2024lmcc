<div class="modal fade bs-modal-lg-grupos-soles" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true"
    style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <div class="tabs tabs-bordered">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-grupos-soles">
                            <div class="clearfix">
                                <div class="form-group">
                                    <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                    <input type="search" id="inputBuscarGruposSoles" name="textoBuscar"
                                        placeholder="Buscar grupo..."
                                        class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                </div>

                                <!-- Products List -->
                                <div id="cardXGrupoSoles" class="ecommerce-products list-unstyled row">
                                    @foreach ($listaGruposSoles as $grupo)
                                        <div class="product col-12 col-md-6">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span id="p1-{{ $grupo->IdGrupo }}"
                                                            class="product-title font-weight-bold fs-16">{{ $grupo->NombreGrupo }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="product-info col-12">
                                                    <button type="button" data-id="{{ $grupo->IdGrupo }}"
                                                        id="botonAgregarArticuloGRUPO-{{ $grupo->IdGrupo }}"
                                                        class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarGrupo">
                                                        <span id="iconoAgregarGRUPO-{{ $grupo->IdGrupo }}">
                                                            <i class="list-icon material-icons">add</i>Agregar</span>
                                                        <span id="iconoCandadoGRUPO-{{ $grupo->IdGrupo }}"
                                                            class="d-none"><i
                                                                class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <!-- /.ecommerce-products -->
                                <!-- Product Navigation -->
                                <div class="col-md-12">
                                    <nav aria-label="Page navigation">
                                        <ul id="paginasGruposSoles"
                                            class="pagination pagination-md d-flex justify-content-center pagGruposSoles">
                                            @if ($listaGruposSoles->onFirstPage())
                                                <li class="page-item"><a class="page-link disabled"
                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                class="feather feather-chevrons-left"></i></span></a>
                                                </li>
                                                <li class="page-item"><a class="page-link disabled"
                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                class="feather feather-chevron-left"></i></span></a>
                                                </li>
                                            @else
                                                <li class="page-item"><a class="page-link" href="grupos?page=1"
                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                class="feather feather-chevrons-left"></i></span></a>
                                                </li>
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $listaGruposSoles->previousPageUrl() }}"
                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                class="feather feather-chevron-left"></i></span></a>
                                                </li>
                                            @endif

                                            @if ($listaGruposSoles->currentPage() < 3)
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i > 0 && $i <= $listaGruposSoles->lastPage())
                                                        @if ($i == $listaGruposSoles->currentPage())
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="grupos?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @elseif($listaGruposSoles->lastPage() > 2)
                                                @if ($listaGruposSoles->currentPage() > $listaGruposSoles->lastPage() - 2)
                                                    @for ($i = $listaGruposSoles->currentPage() - 4; $i <= $listaGruposSoles->lastPage(); $i++)
                                                        @if ($i > 0 && $i <= $listaGruposSoles->lastPage())
                                                            @if ($i == $listaGruposSoles->currentPage())
                                                                <li class="page-item active">
                                                                    <a class="page-link"
                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                </li>
                                                            @else
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="grupos?page={{ $i }}">{{ $i }}</a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                @endif
                                            @endif
                                            @if ($listaGruposSoles->currentPage() >= 3 && $listaGruposSoles->currentPage() <= $listaGruposSoles->lastPage() - 2)
                                                @for ($i = $listaGruposSoles->currentPage() - 2; $i <= $listaGruposSoles->currentPage() + 2; $i++)
                                                    @if ($i > 0 && $i <= $listaGruposSoles->lastPage())
                                                        @if ($i == $listaGruposSoles->currentPage())
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="grupos?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endif
                                            @if ($listaGruposSoles->hasMorePages())
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $listaGruposSoles->nextPageUrl() }}"
                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                class="feather feather-chevron-right"></i></span></a>
                                                </li>
                                                <li class="page-item"><a class="page-link"
                                                        href="grupos?page={{ $listaGruposSoles->lastPage() }}"
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
