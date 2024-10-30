<div class="datosTabla">
    @php
        if ($idDocumento == 1) {
            $tituloUno = 'Resumen Boletas Pendientes';
            $tituloDos = 'Resumen N. Crédito Pendiente';
            $tituloTres = 'Resumen Bajas Pendientes';
            $urlUno = url('/reportes/facturacion/resumen-diario');
            $urlDos = url('/reportes/facturacion/resumen-diario');
        }
        if ($idDocumento == 2) {
            $tituloUno = 'Facturas Pendientes';
            $tituloDos = 'N. de crédito Pendientes';
            $tituloTres = 'Bajas Pendientes';
            $urlUno = url('/reportes/facturacion/facturas-pendientes');
            $urlDos = url('/reportes/facturacion/baja-documentos');
        }
        if ($idDocumento == 4) {
            $tituloUno = 'Boletas Pendientes';
        }
    @endphp

    <section class="row justify-content-center">
        @if ($idDocumento == 3)
            <article class="col-12 col-md-4">
                <div class="card badge-autoncontrol__primary">
                    <div class="card-body text-center p-2">
                        <span class="d-block fs-16 font-weight-bolder text-capitalize">Guías de Remision
                            Pendientes</span>
                        <span
                            class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesGuias['Baja'] ?? '0' }}</span>
                        <a href="{{ url('reportes/facturacion/guias-remision-pendientes') }}"><span
                                class="badge badge-warning">Click Aqui <br> Ir al módulo de
                                reenvío</span></a>
                    </div>
                </div>
            </article>
        @elseif ($idDocumento == 4)
            <article class="col-12 col-md-4">
                <div class="card-body card badge-autoncontrol__primary text-center p-2">
                    <span class="d-block fs-16 font-weight-bolder text-capitalize mb-3">{{ $tituloUno }}</span>
                    <article class="px-4">
                        <div class="d-flex justify-content-between align-items-end">
                            <span class="">Soles</span>
                            <span
                                class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesSoles['Pendiente'] ?? '0' }}</span>
                        </div>
                        <span class="separator separator__dashed--gris"></span>
                        <div class="d-flex justify-content-between align-items-end">
                            <span>Dólares</span>
                            <span
                                class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesDolares['Pendiente'] ?? '0' }}</span>
                        </div>
                        <span class="separator separator__dashed--gris mb-3"></span>
                    </article>
                    <form action="reportes/facturacion/emitir-resumen-diario" method="POST">
                        @method('GET')
                        <input type="hidden" name="fechaBoletaPendientes"
                            value="{{ $fechaParaObtenerBoletasPendientes }}">
                        <button class="badge badge-warning border-0 cursor-pointer">Click Aqui <br> Ir al módulo de
                            reenvío</button>
                    </form>
                </div>
            </article>
        @else
            <article class="col-12 col-md-4">
                <div class="card badge-autoncontrol__primary">
                    <div class="card-body text-center p-2">
                        <span class="d-block fs-16 font-weight-bolder text-capitalize mb-3">{{ $tituloUno }}</span>
                        <article class="px-4">
                            <div class="d-flex justify-content-between align-items-end">
                                <span class="">Soles</span>
                                <span
                                    class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesSoles['Pendiente'] ?? '0' }}</span>
                            </div>
                            <span class="separator separator__dashed--gris"></span>
                            <div class="d-flex justify-content-between align-items-end">
                                <span>Dólares</span>
                                <span
                                    class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesDolares['Pendiente'] ?? '0' }}</span>
                            </div>
                            <span class="separator separator__dashed--gris mb-3"></span>
                        </article>
                        <a href="{{ $urlUno }}"><span class="badge badge-warning">Click Aqui <br> Ir al módulo de
                                reenvío</span></a>
                    </div>
                </div>
            </article>
            <article class="col-12 col-md-4">
                <div class="card badge-autoncontrol__primary">
                    <div class="card-body text-center p-2">
                        <span class="d-block fs-16 font-weight-bolder text-capitalize mb-3">{{ $tituloDos }}</span>
                        <article class="px-4">
                            <div class="d-flex justify-content-between align-items-end">
                                <span class="">Soles</span>
                                <span
                                    class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesSoles['NotaCredito'] ?? '0' }}</span>
                            </div>
                            <span class="separator separator__dashed--gris"></span>
                            <div class="d-flex justify-content-between align-items-end">
                                <span>Dólares</span>
                                <span
                                    class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesDolares['NotaCredito'] ?? '0' }}</span>
                            </div>
                            <span class="separator separator__dashed--gris mb-3"></span>
                        </article>
                        <a href="{{ $urlUno }}"><span class="badge badge-warning">Click Aqui <br> Ir al módulo de
                                reenvío</span></a>
                    </div>
                </div>
            </article>
            <article class="col-12 col-md-4">
                <div class="card badge-autoncontrol__primary">
                    <div class="card-body text-center p-2">
                        <span class="d-block fs-16 font-weight-bolder text-capitalize mb-3">{{ $tituloTres }}</span>
                        <article class="px-4">
                            <div class="d-flex justify-content-between align-items-end">
                                <span class="">Soles</span>
                                <span
                                    class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesSoles['Baja'] ?? '0' }}</span>
                            </div>
                            <span class="separator separator__dashed--gris"></span>
                            <div class="d-flex justify-content-between align-items-end">
                                <span>Dólares</span>
                                <span
                                    class="d-block font-weight-bold fs-18">{{ $totalDocumentoPendientesDolares['Baja'] ?? '0' }}</span>
                            </div>
                            <span class="separator separator__dashed--gris mb-3"></span>
                        </article>
                        <a href="{{ $urlDos }}"><span class="badge badge-warning">Click Aqui <br> Ir al módulo de
                                reenvío</span></a>
                    </div>
                </div>
            </article>
        @endif


    </section>
    <br><br>
    <table id="table" class="table table-responsive-sm" style="width:100%">
        <thead>
            <tr class="bg-primary">
                <th scope="col">FechaCreación</th>
                <th scope="col">Documento</th>
                <th scope="col" class="text-center">CorrelativoDocumento</th>
                @if ($idDocumento != 3)
                    <th scope="col" class="text-center">TipoMoneda</th>
                @endif
                <th scope="col" class="text-center">Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datosDocumento as $factura)
                <tr>
                    <td>{{ $factura->FechaCreacion }}</td>
                    <td>{{ $factura->Documento }}</td>
                    <td class="text-center">{{ $factura->CorrelativoDocumento }}</td>
                    @if ($idDocumento != 3)
                        <td class="text-center">{{ $factura->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                    @endif
                    <td class="text-center"><span
                            class="badge-autoncontrol badge-autoncontrol__danger">{{ $factura->Estado }}</span></td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
