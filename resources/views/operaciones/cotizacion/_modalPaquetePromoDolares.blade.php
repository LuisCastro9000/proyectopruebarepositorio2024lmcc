<div class="modal fade bs-modal-lg-paquetesPromocionales-dolares" role="dialog" aria-labelledby="myLargeModalLabel"
    aria-hidden="true" style="display: none">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <section class="mb-4 text-center">
                <h6 class="text-uppercase font-weight-bold text-muted">Paquetes
                    Promocionales
                </h6>
                <hr>
            </section>
            <div class="modal-body">
                <div class="tabs tabs-bordered">
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab-paquetesPromocionales-dolares">
                            <div class="clearfix">
                                <div class="form-group">
                                    <i class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                    <input type="search" id="inputBuscarPaquetePromocionalDolares" name="textoBuscar"
                                        placeholder="Buscar grupo..."
                                        class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                </div>

                                <!-- Products List -->
                                <div id="cardPaquetesPromocionalesDolares" class="ecommerce-products list-unstyled row">
                                    @foreach ($paquetesPromocionalesDolares as $paquete)
                                        <div class="product col-12 col-md-6">
                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-12">
                                                        <span id="nombre-{{ $paquete->IdPaquetePromocional }}"
                                                            class="product-title font-weight-bold fs-16">{{ $paquete->NombrePaquete }}</span>
                                                    </div>
                                                    <div class="col-12">
                                                        <div class="col-6">$
                                                            <span id="precio-{{ $paquete->IdPaquetePromocional }}"
                                                                class="product-price fs-16">{{ $paquete->Total }}</span>
                                                        </div>
                                                        @if ($sucExonerado == 1)
                                                            <div class="col-6">$
                                                                <span
                                                                    class="text-danger product-price fs-16">{{ number_format($paquete->Total / 1.18, 2) }}</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="col-12" hidden>
                                                        <input id="costo-{{ $paquete->IdPaquetePromocional }}"
                                                            value="{{ $paquete->Costo }}"
                                                            class="form-control text-center" />
                                                    </div>
                                                    <div class="col-12" hidden>
                                                        <input id="etiqueta-{{ $paquete->IdPaquetePromocional }}"
                                                            value="{{ $paquete->Etiqueta }}"
                                                            class="form-control text-center" />
                                                    </div>
                                                    <div class="col-12" hidden>
                                                        <input id="idTipoMoneda-{{ $paquete->IdPaquetePromocional }}"
                                                            value="{{ $paquete->IdTipoMoneda }}"
                                                            class="form-control text-center" />
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-footer">
                                                <div class="product-info col-12">
                                                    <button type="button"
                                                        data-id="{{ $paquete->IdPaquetePromocional }}"
                                                        id="botonAgregarArticuloPAQ-{{ $paquete->IdPaquetePromocional }}"
                                                        class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarPaquetePromo">
                                                        <span
                                                            id="iconoAgregarPAQ-{{ $paquete->IdPaquetePromocional }}">
                                                            <i class="list-icon material-icons">add</i>Agregar</span>
                                                        <span id="iconoCandadoPAQ-{{ $paquete->IdPaquetePromocional }}"
                                                            class=" d-none"><i
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
                                        <ul id="paginasPaquetesPomocionalesDolares"
                                            class="pagination pagination-md d-flex justify-content-center pagPaquetesPomocionalesDolares">
                                            @if ($paquetesPromocionalesDolares->onFirstPage())
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
                                                        href="paquetesPromocionales?page=1" aria-label="Previous"><span
                                                            aria-hidden="true"><i
                                                                class="feather feather-chevrons-left"></i></span></a>
                                                </li>
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $paquetesPromocionalesDolares->previousPageUrl() }}"
                                                        aria-label="Previous"><span aria-hidden="true"><i
                                                                class="feather feather-chevron-left"></i></span></a>
                                                </li>
                                            @endif

                                            @if ($paquetesPromocionalesDolares->currentPage() < 3)
                                                @for ($i = 1; $i <= 5; $i++)
                                                    @if ($i > 0 && $i <= $paquetesPromocionalesDolares->lastPage())
                                                        @if ($i == $paquetesPromocionalesDolares->currentPage())
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="paquetesPromocionales?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @elseif($paquetesPromocionalesDolares->lastPage() > 2)
                                                @if ($paquetesPromocionalesDolares->currentPage() > $paquetesPromocionalesDolares->lastPage() - 2)
                                                    @for ($i = $$paquetesPromocionalesDolares > currentPage() - 4; $i <= $paquetesPromocionalesDolares->lastPage(); $i++)
                                                        @if ($i > 0 && $i <= $paquetesPromocionalesDolares->lastPage())
                                                            @if ($i == $paquetesPromocionalesDolares->currentPage())
                                                                <li class="page-item active">
                                                                    <a class="page-link"
                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                </li>
                                                            @else
                                                                <li class="page-item">
                                                                    <a class="page-link"
                                                                        href="paquetesPromocionales?page={{ $i }}">{{ $i }}</a>
                                                                </li>
                                                            @endif
                                                        @endif
                                                    @endfor
                                                @endif
                                            @endif
                                            @if (
                                                $paquetesPromocionalesDolares->currentPage() >= 3 &&
                                                    $paquetesPromocionalesDolares->currentPage() <= $paquetesPromocionalesDolares->lastPage() - 2)
                                                @for ($i = $paquetesPromocionalesDolares->currentPage() - 2; $i <= $paquetesPromocionalesDolares->currentPage() + 2; $i++)
                                                    @if ($i > 0 && $i <= $paquetesPromocionalesDolares->lastPage())
                                                        @if ($i == $paquetesPromocionalesDolares->currentPage())
                                                            <li class="page-item active">
                                                                <a class="page-link"
                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                            </li>
                                                        @else
                                                            <li class="page-item">
                                                                <a class="page-link"
                                                                    href="paquetesPromocionales?page={{ $i }}">{{ $i }}</a>
                                                            </li>
                                                        @endif
                                                    @endif
                                                @endfor
                                            @endif
                                            @if ($paquetesPromocionalesDolares->hasMorePages())
                                                <li class="page-item"><a class="page-link"
                                                        href="{{ $paquetesPromocionalesDolares->nextPageUrl() }}"
                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                class="feather feather-chevron-right"></i></span></a>
                                                </li>
                                                <li class="page-item"><a class="page-link"
                                                        href="paquetesPromocionales?page={{ $paquetesPromocionalesDolares->lastPage() }}"
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
