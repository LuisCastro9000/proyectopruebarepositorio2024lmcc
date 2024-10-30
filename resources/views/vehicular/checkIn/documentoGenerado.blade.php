@extends('layouts.app')
@section('title', 'Detalle de Inventario')
@section('content')

    <style>
        .btn-color {
            background-color: rgba(246, 130, 31);
        }

        .color-icon {
            color: #ffff;
        }

        .contenedorImagen {
            width: 40%;
        }

        #imgFirma {
            width: 100%;
        }
    </style>
    @if (session('success'))
        <section class="mt-4">
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        </section>
    @endif

    <!-- Page Title Area -->
    <div class="container">
        <div class="d-flex justify-content-between align-items-center flex-wrap my-3">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Detalles de Inventario</h6>
            </div>

            <!-- /.page-title-left -->
            <div class="page-title-right">
                <div class=" d-flex flex-wrap">
                    <a class="p-1" href="{{ route('generarPdfInventario', [$inventario->IdCheckIn, 'imprimir']) }}"
                        target="_blank"><button class="btn btn-block btn-color ripple"><i
                                class="list-icon material-icons color-icon fs-20">print</i></button></a>
                    <a class="p-1"
                        href="{{ route('generarPdfInventario', [$inventario->IdCheckIn, 'descargar']) }}"><button
                            class="btn btn-block btn-primary ripple"><i
                                class="list-icon material-icons fs-20">picture_as_pdf</i></button></a>

                    <x-anchorButton class="btnWhatsApp" data-celular="{{ $inventario->Telefono }}" title="Enviar WhatsApp"
                        data-routename="{{ route('generarPdfInventario', [$inventario->IdCheckIn, 'whatsapp']) }}">
                        <img class="logo-expand" alt="Imagen-WhatsApp" width="40"
                            src="{{ asset('assets/img/whatsapp.png') }}">
                    </x-anchorButton>
                </div>
            </div>
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
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
                            <div class="ecommerce-invoice">
                                <div class="d-sm-flex">
                                    <div class="col-md-6">
                                        <h5>Documento: {{ $inventario->Serie }}-{{ $inventario->Correlativo }}</h5>
                                    </div>
                                    <div class="col-md-6 text-right d-none d-sm-block"><strong>CLIENTE:</strong>
                                        {{ $inventario->RazonSocial }}
                                        <br><strong>NRO. DOCUMENTO:</strong> {{ $inventario->NumeroDocumento }}
                                    </div>
                                    <div class="col-md-6 d-block d-sm-none"><strong>CLIENTE:</strong>
                                        {{ $inventario->RazonSocial }}
                                        <br><strong>NRO. DOCUMENTO:</strong> {{ $inventario->NumeroDocumento }}
                                    </div>
                                </div>
                                <!-- /.row -->
                                <div class="border container">
                                    <table width="100%">
                                        <tr>
                                            <td><strong>ATENCIÓN:</strong> {{ $inventario->Nombre }}</td>
                                            <td><strong>PLACA VEHICULAR:</strong> {{ $inventario->Placa }}</td>
                                        </tr>
                                        <tr>
                                            <td><strong>FECHA ATENCIÓN:</strong> <span
                                                    class="text-muted">{{ $inventario->FechaEmision }}</span></td>
                                            <td><strong>NIVEL GASOLINA:</strong> {{ $inventario->NivelGasolina }} %</td>
                                        </tr>
                                        <tr>
                                            <td><strong>COLOR : </strong> {{ $inventario->Color }} </td>
                                            <td><strong>AÑO: </strong> {{ $inventario->Anio }} </td>
                                        </tr>
                                        <tr>
                                            <td><strong>MARCA: </strong> {{ $inventario->NombreMarca }} </td>
                                            <td><strong>MODELO : </strong> {{ $inventario->NombreModelo }} </td>
                                        </tr>
                                        <tr>
                                            <td><strong>KILOMETRAJE : </strong> {{ $inventario->Kilometraje }} </td>
                                            <td><strong>HOROMETRO: </strong> {{ $inventario->HorometroInicial }} </td>
                                        </tr>
                                        <tr>
                                            <td><strong>VENC. SOAT : </strong> {{ $inventario->FechaSoat }} </td>
                                            <td><strong>VENC. REV. TÉCNICA: </strong> {{ $inventario->FechaRevTecnica }}
                                            </td>
                                        </tr>
                                    </table>
                                    <div class="mt-1">
                                        <h6>Autorizaciones</h6>
                                        @if ($autorizaciones[0])
                                            - <span>Autorizo conducir mi vehículo para pruebas en exteriores del
                                                Taller</span><br>
                                        @endif
                                        @if ($autorizaciones[1])
                                            - <span>Autorizo enviar mi vehículo para trabajos de terceros en Talleres de su
                                                elección</span><br>
                                        @endif
                                        @if ($autorizaciones[2])
                                            - <span>Declaro que no existen elementos de valor dentro del vehículo</span><br>
                                        @endif
                                        @if ($autorizaciones[3])
                                            - <span>Acepto retirar mi vehículo en un máximo de
                                                {{ $autorizaciones[3]->Dias }} días, luego de finalizado el servicio;
                                                caso
                                                contrario asumiré un costo de S/ {{ $autorizaciones[3]->Monto }} diarios
                                                por cochera (interna y/o externa)</span>
                                        @endif
                                    </div>
                                </div>
                                <!-- /.row -->
                                <hr class="border-0">
                                @if (count($accesoriosExternos) > 0)
                                    <table align="center" id="table1" class="table table-bordered table-responsive-sm"
                                        style="width: 80%">
                                        <thead>
                                            <tr class="bg-info text-white" align="center">
                                                <th colspan="4"><label class="fs-16 text-white">Accesorios
                                                        Externos</label>
                                                </th>
                                            </tr>
                                            <tr class="bg-info text-white" align="center">
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accesoriosExternos as $accExt)
                                                <tr>
                                                    <td><label class="fs-14">{{ $accExt->Descripcion }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">{{ $accExt->Cantidad }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">
                                                            @if ($accExt->Estado == 1)
                                                                Bueno
                                                            @elseif($accExt->Estado == 2)
                                                                Regular
                                                            @else
                                                                Malo
                                                            @endif
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if (count($accesoriosInternos) > 0)
                                    <table align="center" id="table2" class="table table-bordered table-responsive-sm"
                                        style="width: 80%">
                                        <thead>
                                            <tr class="bg-info text-white" align="center">
                                                <th colspan="4"><label class="fs-16 text-white">Accesorios
                                                        Internos</label>
                                                </th>
                                            </tr>
                                            <tr class="bg-info text-white" align="center">
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($accesoriosInternos as $accInt)
                                                <tr>
                                                    <td><label class="fs-14">{{ $accInt->Descripcion }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">{{ $accInt->Cantidad }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">
                                                            @if ($accInt->Estado == 1)
                                                                Bueno
                                                            @elseif($accInt->Estado == 2)
                                                                Regular
                                                            @else
                                                                Malo
                                                            @endif
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if (count($herramientas) > 0)
                                    <table align="center" id="table3" class="table table-bordered table-responsive-sm"
                                        style="width: 80%">
                                        <thead>
                                            <tr class="bg-info text-white" align="center">
                                                <th colspan="4"><label class="fs-16 text-white">Herramientas</label></th>
                                            </tr>
                                            <tr class="bg-info text-white" align="center">
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($herramientas as $herramien)
                                                <tr>
                                                    <td><label class="fs-14">{{ $herramien->Descripcion }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">{{ $herramien->Cantidad }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">
                                                            @if ($herramien->Estado == 1)
                                                                Bueno
                                                            @elseif($herramien->Estado == 2)
                                                                Regular
                                                            @else
                                                                Malo
                                                            @endif
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                @if (count($documentosVehiculo) > 0)
                                    <table align="center" id="table4" class="table table-bordered table-responsive-sm"
                                        style="width: 80%">
                                        <thead>
                                            <tr class="bg-info text-white" align="center">
                                                <th colspan="4"><label class="fs-16 text-white">Documentos
                                                        Vehículo</label>
                                                </th>
                                            </tr>
                                            <tr class="bg-info text-white" align="center">
                                                <th>Descripción</th>
                                                <th>Cantidad</th>
                                                <th>Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($documentosVehiculo as $docVehiculo)
                                                <tr>
                                                    <td><label class="fs-14">{{ $docVehiculo->Descripcion }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">{{ $docVehiculo->Cantidad }}</label>
                                                    </td>
                                                    <td align="center">
                                                        <label class="fs-14">
                                                            @if ($docVehiculo->Estado == 1)
                                                                Bueno
                                                            @elseif($docVehiculo->Estado == 2)
                                                                Regular
                                                            @else
                                                                Malo
                                                            @endif
                                                        </label>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                <div class="row mt-4">
                                    <div class="col-lg-12 col-md-12">
                                        <div class="form-group">
                                            <label>Observación</label>
                                            <textarea class="form-control" rows="4">{{ $inventario->Observacion }}</textarea>
                                        </div>
                                    </div>
                                </div>
                                {{-- imagens digitales --}}
                                <div class="row justify-content-center">
                                    @if ($inventario->ImagenCarro != null)
                                        <div class="col-lg-6 col-md-12">
                                            <div class="card">
                                                <div class="card-body">
                                                    <img class="card-img-top" src="{{ $inventario->ImagenCarro }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    @if ($inventario->Imagen != null)
                                        <div class="col-lg-6 col-md-12 mt-3 mt-lg-0">
                                            <div class="card h-100">
                                                <div class="card-body d-flex align-items-center justify-content-center">
                                                    <section class="contenedorImagen">
                                                        <img id="imgFirma" class=""
                                                            src="{{ $inventario->Imagen }}" alt="">
                                                    </section>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                </div>

                                {{-- Fin --}}
                                <div class="form-actions btn-list mt-3">
                                    <a href="../../check-in"><button class="btn btn-primary"
                                            type="button">Volver</button></a>
                                </div>

                                <!-- /.row -->
                            </div>
                            <!-- /.ecommerce-invoice -->
                        </div>
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>

            {{-- Modal enviar pdf x Whatsapp --}}
            @include('modal._modalEnviarWhatsAppFromIndex')
            {{-- Fin --}}
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->



@stop


@section('scripts')
    <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
    <script type="text/javascript">
        $(function() {
            $('#table1').DataTable({
                responsive: true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
            $('#table2').DataTable({
                responsive: true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });

            $('#table3').DataTable({
                responsive: true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });

            $('#table4').DataTable({
                responsive: true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
        });
    </script>
@stop
