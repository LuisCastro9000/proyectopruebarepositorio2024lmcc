@extends('layouts.app')
@section('title', 'Documento Control Calidad')
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-info mt-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        <section
            class="d-flex justify-content-center flex-wrap align-items-end justify-content-sm-between flex-column flex-sm-row my-4">
            <section>
                <h6>Detalle Control de Calidad Vehicular</h6>
            </section>
            <section class="d-flex align-items-center flex-wrap justify-content-center">
                @if ($controlCalidad->ImagenFirmaCliente != null)
                    <a class="p-1"
                        href="{{ route('imprimirControlCalidad', [$controlCalidad->IdControlCalidad, 'imprimir']) }}"
                        target="_blank"><button class="btn btn-block btn-autocontrol-naranja ripple"><i
                                class="list-icon material-icons color-icon fs-20">print</i></button></a>
                    <a class="p-1"
                        href="{{ route('imprimirControlCalidad', [$controlCalidad->IdControlCalidad, 'descargar']) }}"
                        target="_blank"><button class="btn btn-block btn-primary ripple"><i
                                class="list-icon material-icons fs-20">picture_as_pdf</i></button></a>
                    <a target="_blank" class="p-1" href="" data-target="#modalEnviarWhatsApp"
                        data-toggle="modal"><img class="logo-expand" alt="" width="40"
                            src="{{ asset('assets/img/whatsapp.png') }}"></a>
                @else
                    <a class="mr-2 btn btn-autocontrol-naranja ripple btnImprimirPdf btnEnviarPdf{{ $controlCalidad->IdControlCalidad }}"
                        id="btnImprimirPdf" href="#" data-enlace="imprimir"
                        data-id="{{ $controlCalidad->IdControlCalidad }}" data-whatsapp="descargar"><i
                            class="list-icon material-icons color-icon fs-20">print</i></a>
                    <a class="mr-1 btn btn-primary ripple btnDescargarPdf btnEnviarPdf{{ $controlCalidad->IdControlCalidad }}"
                        id="btnDescargarPdf" href="#" data-enlace="descargar"
                        data-id="{{ $controlCalidad->IdControlCalidad }}" data-whatsapp="descargar"><i
                            class="list-icon material-icons fs-20">picture_as_pdf</i></a>

                    <a class="btnEnviarWhatsApp btnEnviarPdf{{ $controlCalidad->IdControlCalidad }}" id="btnEnviarWhatsApp"
                        data-enlace="whatsApp" data-id="{{ $controlCalidad->IdControlCalidad }}"
                        data-celular="{{ $controlCalidad->Telefono }}" data-idcliente="{{ $controlCalidad->IdCliente }}"
                        href=""><img class="logo-expand" alt="" width="40" btnEnviarPdf
                            src="{{ asset('assets/img/whatsapp.png') }}"></a>
                @endif
                {{-- boton de ejemplo --}}
                {{-- <a class="p-1" id="btnImprimirPdf"
                    href="{{ route('imprimirControlCalidad', [$controlCalidad->IdControlCalidad, 'imprimir1']) }}"
                    target="_blank"><button class="btn btn-block btn-autocontrol-naranja ripple">Pdf Ejemplo</button></a> --}}
                {{-- fin --}}

            </section>
        </section>
        {{-- seccion detalle --}}
        <section class="jumbotron bg-white text-secondary">
            <div class="d-flex justify-content-between flex-wrap">
                <h5>{{ $controlCalidad->Serie }}-{{ $controlCalidad->Numero }}</h5>
                <div class="text-md-right">
                    <strong>CLIENTE:</strong> {{ $controlCalidad->RazonSocial }}
                    <br><strong>NRO. DOCUMENTO:</strong> {{ $controlCalidad->NumeroDocumento }}
                </div>
            </div>
            <hr class="my-2">
            <div class="d-flex justify-content-between flex-wrap borderDashed borderDashed--celeste my-4">
                <section class="d-flex flex-column">
                    <span><strong>FECHA INFORME CONTROL DE CALIDAD:</strong> {{ $controlCalidad->FechaCreacion }}</span>
                    <span><strong>USUARIO SISTEMA:</strong> {{ $controlCalidad->NombreAsesorComercial }}</span>
                    <span><strong>MECÁNICO A CARGO:</strong> {{ $controlCalidad->NombreOperario }}</span>
                    <span><strong>TIPO DE ATENCIÓN:</strong> {{ $controlCalidad->TipoAtencion }}</span>
                </section>

                <section class="d-flex flex-column">
                    <span><strong>PLACA VEHICULAR:</strong> {{ $controlCalidad->PlacaVehiculo }}</span>
                    <span><strong>COLOR:</strong> {{ $controlCalidad->Color }}</span>
                    <span><strong>AÑO/MODELO:</strong>
                        {{ $controlCalidad->Anio }}/{{ $controlCalidad->NombreModelo }}</span>
                    <span><strong>KM DE INGRESO:</strong> {{ $controlCalidad->Kilometraje }}</span>
                </section>
            </div>
            <br>
            <div class="d-flex justify-content-around flex-wrap">
                <span class="badge badge-success fs-16"><i class='bx bxs-smile fs-28'></i><br>Satisfactorio</span>
                <span class="badge badge-warning fs-16"><i class='bx bxs-meh fs-28'></i><br>Atención Futura</span>
                <span class="badge badge-danger fs-16"><i class='bx bxs-sad fs-28'></i><br>Atención
                    Inmediata</span>

            </div>
            {{-- Seccion detalle de las partes del vehiculo --}}
            <section class="col-12 mt-4">
                <div class="card rounded-0">
                    {{-- Bado del Vehiculo --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Bajo del Vehículo
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosDebajoVehiculo as $item)
                                    <tr>
                                        <th>{{ $item->Descripcion }}</th>
                                        <th class="text-center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Futura')
                                                <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                            @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>{{ $item->EstadoParte }}</span>
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- Bajo del Capo --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Bajo del Capo
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosBajoCapo as $item)
                                    <tr>
                                        <th>{{ $item->Descripcion }}</th>
                                        <th class="text-center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Futura')
                                                <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                            @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>{{ $item->EstadoParte }}</span>
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- Liquidos --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Nivel de Liquido
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosFluidos as $item)
                                    <tr>
                                        <th>{{ $item->Descripcion }}</th>
                                        <th class="text-center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Futura')
                                                <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                            @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>{{ $item->EstadoParte }}</span>
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}


                    {{-- Filtros --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Filtros
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosFiltros as $item)
                                    <tr>
                                        <th>{{ $item->Descripcion }}</th>
                                        <th class="text-center">
                                            @if ($item->EstadoParte == 'Satisfactorio')
                                                <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Futura')
                                                <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                            @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                            @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                <span>{{ $item->EstadoParte }}</span>
                                            @endif
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- Frenos --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Frenos
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info">Posición</th>
                                    <th class="text-info">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                    <th class="text-center text-info">Medida</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosFrenos as $item)
                                    @if ($item->Posicion == 'FrontalDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Discos')
                                                <th rowspan="2" class="align-middle">FD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                <span>{{ $item->Medida }}</span>
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'FrontalIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Discos')
                                                <th rowspan="2" class="align-middle">FI</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                <span>{{ $item->Medida }}</span>
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'TraseraDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Discos')
                                                <th rowspan="4" class="align-middle">TD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                <span>{{ $item->Medida }}</span>
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'TraseraIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Discos')
                                                <th rowspan="4" class="align-middle">TI</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                            <th class="text-center">
                                                <span>{{ $item->Medida }}</span>
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- LimpiaParabrisas --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Sistema de LimpiaParabrisas
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info">Posición</th>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosLimpiaParabrisas as $item)
                                    @if ($item->Posicion == 'FrontalDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Escobilla/Goma')
                                                <th rowspan="3" class="align-middle">FD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'FrontalIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Escobilla/Goma')
                                                <th rowspan="3" class="align-middle">FD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'Trasera')
                                        <tr>
                                            @if ($item->Descripcion == 'Escobilla/Goma')
                                                <th rowspan="3" class="align-middle">T</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- Llantas --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Llantas
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info">Posición</th>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosNeumaticos as $item)
                                    @if ($item->Posicion == 'FrontalDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Alineación')
                                                <th rowspan="2" class="align-middle">FD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'FrontalIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Alineación')
                                                <th rowspan="2" class="align-middle">FI</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'TraseraDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Alineación')
                                                <th rowspan="2" class="align-middle">TD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'TraseraIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Alineación')
                                                <th rowspan="2" class="align-middle">TI</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- Luces --}}
                    <article>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Luces
                        </div>
                        <table width="100%" class="table table-responsive-md">
                            <thead>
                                <tr>
                                    <th class="text-info">Posición</th>
                                    <th class="text-info" style="width:350px">Descripción</th>
                                    <th class="text-center text-info">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($controlCalidad->datosLuces as $item)
                                    @if ($item->Posicion == 'FrontalDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Intermitente/Direccionales')
                                                <th rowspan="2" class="align-middle">FD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'FrontalIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Intermitente/Direccionales')
                                                <th rowspan="2" class="align-middle">FI</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'TraseraDerecha')
                                        <tr>
                                            @if ($item->Descripcion == 'Retroceso')
                                                <th rowspan="3" class="align-middle">TD</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                    @if ($item->Posicion == 'TraseraIzquierda')
                                        <tr>
                                            @if ($item->Descripcion == 'Retroceso')
                                                <th rowspan="3" class="align-middle">TI</th>
                                            @endif
                                            <th>{{ $item->Descripcion }}</th>
                                            <th class="text-center">
                                                @if ($item->EstadoParte == 'Satisfactorio')
                                                    <span><i class='bx bxs-smile fs-28  text-success'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Futura')
                                                    <span><i class='bx bxs-meh fs-28  text-warning'></i></span>
                                                @elseif ($item->EstadoParte == 'Atencion Inmediata')
                                                    <span><i class='bx bxs-sad fs-28  text-danger'></i></span>
                                                @elseif ($item->EstadoParte == 'Sin Inspeccion')
                                                    <span>{{ $item->EstadoParte }}</span>
                                                @endif
                                            </th>
                                        </tr>
                                    @endif
                                @endforeach
                            </tbody>
                        </table>
                    </article>
                    {{-- Fin --}}

                    {{-- seccion Recomendaciones --}}
                    <section>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Recomendaciones
                        </div>
                        <div class="p-4">
                            <p><i class="material-icons">done</i> {{ $controlCalidad->Recomendacion }}</p>
                        </div>
                    </section>
                    {{-- Fin --}}
                    {{-- seccion Diagnostico --}}
                    <section>
                        <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                            Diagnóstico
                        </div>
                        <div class="p-4">
                            <p><i class="material-icons">done</i> {{ $controlCalidad->Diagnostico }}</p>
                        </div>
                    </section>
                    {{-- Fin --}}

                    {{-- seccion imagen Firma --}}
                    @if (
                        $controlCalidad->ImagenFirmaCliente != null &&
                            !str_contains($controlCalidad->ImagenFirmaCliente, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                        <section>
                            <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                                Firma
                            </div>
                            <article class="p-4">
                                <div class="m-auto" style="width:150px; height:75px">
                                    @if (str_contains($controlCalidad->ImagenFirmaCliente, config('variablesGlobales.urlDominioAmazonS3')))
                                        <img src="{{ $controlCalidad->ImagenFirmaCliente }}" alt="Imagen Firma"
                                            style="width:100%; height:100%">
                                    @else
                                        <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $controlCalidad->ImagenFirmaCliente }}"
                                            alt="Imagen Firma" style="width:100%; height:100%">
                                    @endif
                                </div>
                            </article>
                        </section>
                    @endif
                    {{-- Fin --}}
                </div>
            </section>
        </section>
        {{-- Fin --}}


        {{-- Modal Dibujar Firma --}}
        @include('modal._modalFirmaDigital')
        {{-- Fin --}}

        {{-- Modal enviar pdf x Whatsapp --}}
        @include('modal._modalEnviarWhatsApp')
        {{-- Fin --}}
    </div>
@stop
@section('scripts')
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/controlCalidadVehicular/verificarFirma.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/firmaDigital/script.js?v=' . time()) }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        $(document).ready(function() {
            $('#formularioCrearFirmaDigital').submit(function(e) {
                e.preventDefault();
                let inputCodigoFirma = $('#inputCodigoFirma').val();
                let inputIdControlCalidad = $('#inputIdControl').val();
                let inputDescripcionEnlace = $('#inputDescripcionEnlace').val();
                let inputIdCliente = $('#btnEnviarWhatsApp').data('idcliente');
                $.ajax({
                    type: 'post',
                    url: 'ajax/guardar-firma-digital',
                    data: {
                        "_token": "{{ csrf_token() }}",
                        "inputCodigoFirma": inputCodigoFirma,
                        "inputIdCliente": inputIdCliente,
                        "inputIdControlCalidad": inputIdControlCalidad
                    },
                    success: function(data) {
                        if (data[0] == 'succes') {
                            $(".btnEnviarPdf" + inputIdControlCalidad).attr(
                                "data-contienefirma", 'si');
                            $('#modalDibujarFirma').modal('hide');
                            respuestaSuccesAjax(data[1]);
                            setTimeout(() => {
                                if (inputDescripcionEnlace == 'descargar' ||
                                    inputDescripcionEnlace == 'imprimir') {
                                    var url =
                                        "{{ route('imprimirControlCalidad', [':id', ':enlace']) }}";
                                    url = url.replace(':id', inputIdControlCalidad);
                                    url = url.replace(':enlace',
                                        inputDescripcionEnlace);
                                    return window.open(url, '_blank');
                                } else {
                                    return $('#modalEnviarWhatsApp').modal('show');
                                }
                            }, 500);
                        } else {
                            respuestaErrorAjax(data[1]);
                        }
                    }
                });
            });
        });

        function redireccionarVista(id, enlace) {
            var url = "{{ route('imprimirControlCalidad', [':id', ':enlace']) }}";
            url = url.replace(':id', id);
            url = url.replace(':enlace', enlace);
            // location.href = url;
            window.open(url, '_blank');
        }
    </script>
@stop
