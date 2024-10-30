@extends('layouts.app')
@section('title', 'Editar Inventario')
@section('content')

    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Inventario</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
    </div>
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    {!! Form::open([
                        'url' => 'vehicular/CheckIn/actualizar-check-list',
                        'method' => 'POST',
                        'files' => true,
                        'class' => 'form-material',
                    ]) !!}
                    {{ csrf_field() }}
                    <input type="hidden" name="idCheckIn" value="{{ $datosInventario->IdCheckIn }}">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <select class="m-b-10 form-control select2-hidden-accessible" id="clientes" name="cliente"
                                    data-placeholder="Choose" data-toggle="select2" tabindex="-1" aria-hidden="true">
                                    <option value="{{ $datosInventario->IdCliente }}">{{ $datosInventario->RazonSocial }}
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Serie</label></div>
                                    </div>
                                    <input id="serie" class="form-control" placeholder="Serie"
                                        value="{{ $datosInventario->Serie }}" type="text" name="serie" maxlength="4"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Correlativo</label></div>
                                    </div>
                                    <input id="numero" class="form-control" placeholder="correlativo"
                                        value="{{ $datosInventario->Correlativo }}" type="text" maxlength="8"
                                        name="correlativo" readonly>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Color</label></div>
                                    </div>
                                    <input id="color" class="form-control" type="text"
                                        value="{{ $datosInventario->Color }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Año</label></div>
                                    </div>
                                    <input id="anio" class="form-control" type="text" name="anio"
                                        value="{{ $datosInventario->Anio }}" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label>Kilometraje</label>
                                <input id="anio" class="form-control" type="text" name="kilometraje"
                                    value="{{ $datosInventario->Kilometraje }}">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="selectVentaRapida">Autorizaciones</label>
                        </div>

                        <div class="col-md-6">
                            @if ($autorizacionesUno != null)
                                <input id="chboxAuto1" name="chboxAuto1" class="chboxAuto mt-3" type="checkbox" checked />
                                <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo
                                    conducir mi
                                    vehículo para pruebas en
                                    exteriores del Taller</span>
                            @else
                                <input id="chboxAuto1" name="chboxAuto1" class="chboxAuto mt-3" type="checkbox">
                                <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo conducir
                                    mi
                                    vehículo para pruebas en
                                    exteriores del Taller</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if ($autorizacionesDos != null)
                                <input id="chboxAuto2" name="chboxAuto2" class="chboxAuto mt-3" type="checkbox" checked />
                                <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo enviar mi vehículo para trabajos
                                    de
                                    terceros en Talleres de su elección</span>
                            @else
                                <input id="chboxAuto2" name="chboxAuto2" class="chboxAuto mt-3" type="checkbox" /> <span
                                    class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo enviar mi vehículo para trabajos
                                    de
                                    terceros en Talleres de su elección</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if ($autorizacionesTres != null)
                                <input id="chboxAuto3" name="chboxAuto3" class="chboxAuto mt-3" type="checkbox"
                                    checked /> <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Declaro que no existen
                                    elementos de valor
                                    dentro del vehículo</span>
                            @else
                                <input id="chboxAuto3" name="chboxAuto3" class="chboxAuto mt-3" type="checkbox" /> <span
                                    class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Declaro que no existen elementos de valor
                                    dentro del vehículo</span>
                            @endif
                        </div>
                        <div class="col-md-6">
                            @if ($autorizacionesCuatro != null)
                                <input id="chboxAuto4" name="chboxAuto4" class="chboxAuto mt-3" type="checkbox"
                                    checked /> <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Acepto retirar el
                                    vehículo en un máximo de
                                </span> <input style="width:40px" name="Dias"
                                    value="{{ $autorizacionesCuatro->Dias }}"> <span
                                    class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">días, luego de finalizado el servicio;
                                    caso
                                    contrario asumiré un costo de S/ </span> <input style="width:40px" name="Monto"
                                    value="{{ $autorizacionesCuatro->Monto }}">
                                <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">diarios por cochera (interna y/o
                                    externa)</span>
                            @else
                                <input id="chboxAuto4" name="chboxAuto4" class="chboxAuto mt-3" type="checkbox" /> <span
                                    class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Acepto retirar el vehículo en un máximo de
                                </span> <input style="width:40px" name="Dias" disabled> <span
                                    class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">días, luego de finalizado el servicio;
                                    caso
                                    contrario asumiré un costo de S/ </span> <input style="width:40px" name="Monto"
                                    disabled>
                                <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">diarios por cochera (interna y/o
                                    externa)</span>
                            @endif
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="selectVentaRapida">Nivel de Gasolina</label><br>
                                <div class="radiobox mt-4">
                                    <label>
                                        @if ($datosInventario->NivelGasolina == 25)
                                            <input type="radio" name="radioNivelGasolina" checked value="25"
                                                checked> <span class="label-text m-4">25 %</span>
                                        @else
                                            <input type="radio" name="radioNivelGasolina" checked value="25"> <span
                                                class="label-text m-4">25 %</span>
                                        @endif
                                    </label>
                                    <label>
                                        @if ($datosInventario->NivelGasolina == 50)
                                            <input type="radio" name="radioNivelGasolina" value="50" checked> <span
                                                class="label-text m-4">50 %</span>
                                        @else
                                            <input type="radio" name="radioNivelGasolina" value="50"> <span
                                                class="label-text m-4">50 %</span>
                                        @endif
                                    </label>
                                    <label>
                                        @if ($datosInventario->NivelGasolina == 75)
                                            <input type="radio" name="radioNivelGasolina" value="75" checked> <span
                                                class="label-text m-4">75 %</span>
                                        @else
                                            <input type="radio" name="radioNivelGasolina" value="75"> <span
                                                class="label-text m-4">75 %</span>
                                        @endif
                                    </label>
                                    <label>
                                        @if ($datosInventario->NivelGasolina == 100)
                                            <input type="radio" name="radioNivelGasolina" value="100" checked> <span
                                                class="label-text m-4">100 %</span>
                                        @else
                                            <input type="radio" name="radioNivelGasolina" value="100"> <span
                                                class="label-text m-4">100 %</span>
                                        @endif
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <table id="table1" class="table" width="100%">
                        <thead>
                            <tr class="text-md-center">
                                <th colspan="4"><label class="fs-16">Accesorios Externos</label></th>
                            </tr>
                            <tr align="center">
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($accesoriosExt as $accExt)
                                <tr>
                                    <td align="center">
                                        @if ($accExt->Estado != 'E')
                                            <input id="{{ $accExt->IdDescripcionCheckIn }}"
                                                name="checkAccesoriosExternos[]"
                                                class="chbox accesoriosExternos{{ $accExt->IdDescripcionCheckIn }}"
                                                type="checkbox" value="{{ $accExt->IdDescripcionCheckIn }}" checked />
                                        @else
                                            <input id="{{ $accExt->IdDescripcionCheckIn }}"
                                                name="checkAccesoriosExternos[]" class="chbox" type="checkbox"
                                                value="{{ $accExt->IdDescripcionCheckIn }}" />
                                        @endif
                                    </td>
                                    <td><label>{{ $accExt->Descripcion }}</label></td>
                                    <td align="center">
                                        @if ($accExt->Estado != 'E')
                                            <input id="input{{ $accExt->IdDescripcionCheckIn }}" style="width:80px"
                                                name="input{{ $accExt->IdDescripcionCheckIn }}" type="number"
                                                value="{{ $accExt->Cantidad }}" />
                                        @else
                                            <input id="input{{ $accExt->IdDescripcionCheckIn }}" style="width:80px"
                                                name="input{{ $accExt->IdDescripcionCheckIn }}" type="number"
                                                value="{{ $accExt->Cantidad }}" disabled />
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($accExt->Estado != 'E')
                                            <div class="radiobox" style="width: 380px;">
                                                <label>
                                                    @if ($accExt->Estado == 1)
                                                        <input type="radio"
                                                            name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                            value="1" checked> <span
                                                            class="label-text m-4">Bueno</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                            value="1"> <span class="label-text m-4">Bueno</span>
                                                    @endif
                                                </label>
                                                <label>
                                                    @if ($accExt->Estado == 2)
                                                        <input type="radio"
                                                            name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                            value="2" checked> <span
                                                            class="label-text m-4">Regular</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                            value="2"> <span class="label-text m-4">Regular</span>
                                                    @endif
                                                </label>
                                                <label>
                                                    @if ($accExt->Estado == 3)
                                                        <input type="radio"
                                                            name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                            value="3" checked> <span
                                                            class="label-text m-4">Malo</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                            value="3"> <span class="label-text m-4">Malo</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @else
                                            <div class="radiobox" style="width: 380px;">
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                        value="1" disabled> <span class="label-text m-4">Bueno</span>
                                                </label>
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                        value="2" disabled> <span
                                                        class="label-text m-4">Regular</span>
                                                </label>
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                                        value="3" disabled> <span class="label-text m-4">Malo</span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach

                        </tbody>
                    </table>
                    <br>
                    <input type="hidden" name="inputTipoVehiculo" value="{{ $tipoVehiculo }}">
                    @if ($tipoVehiculo == 'Vehiculo')
                        <table id="table2" class="table" width="100%">
                            <thead>
                                <tr class="text-md-center">
                                    <th colspan="4"><label class="fs-16">Accesorios Internos</label></th>
                                </tr>
                                <tr align="center">
                                    <th>&nbsp;</th>
                                    <th>&nbsp;</th>
                                    <th>Cantidad</th>
                                    <th>Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($accesoriosInt as $accInt)
                                    <tr>
                                        <td align="center">
                                            @if ($accInt->Estado != 'E')
                                                <input id="{{ $accInt->IdDescripcionCheckIn }}"
                                                    name="checkAccesoriosInternos[]"
                                                    value="{{ $accInt->IdDescripcionCheckIn }}"
                                                    class="chbox accesoriosInternos{{ $accInt->IdDescripcionCheckIn }}"
                                                    type="checkbox" checked />
                                            @else
                                                <input id="{{ $accInt->IdDescripcionCheckIn }}"
                                                    name="checkAccesoriosInternos[]"
                                                    value="{{ $accInt->IdDescripcionCheckIn }}" class="chbox"
                                                    type="checkbox" />
                                            @endif
                                        </td>
                                        <td><label>{{ $accInt->Descripcion }}</label></td>
                                        <td align="center">
                                            @if ($accInt->Estado != 'E')
                                                <input id="input{{ $accInt->IdDescripcionCheckIn }}" style="width:80px"
                                                    name="input{{ $accInt->IdDescripcionCheckIn }}" type="number"
                                                    value="{{ $accInt->Cantidad }}" />
                                            @else
                                                <input id="input{{ $accInt->IdDescripcionCheckIn }}" style="width:80px"
                                                    name="input{{ $accInt->IdDescripcionCheckIn }}" type="number"
                                                    value="{{ $accInt->Cantidad }}" disabled />
                                            @endif
                                        </td>
                                        <td align="center">
                                            @if ($accInt->Estado != 'E')
                                                <div class="radiobox" style="width: 380px;">
                                                    <label>
                                                        @if ($accInt->Estado == 1)
                                                            <input type="radio"
                                                                name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                                value="1" checked> <span
                                                                class="label-text m-4">Bueno</span>
                                                        @else
                                                            <input type="radio"
                                                                name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                                value="1"> <span class="label-text m-4">Bueno</span>
                                                        @endif
                                                    </label>
                                                    <label>
                                                        @if ($accInt->Estado == 2)
                                                            <input type="radio"
                                                                name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                                checked value="2"> <span
                                                                class="label-text m-4">Regular</span>
                                                        @else
                                                            <input type="radio"
                                                                name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                                value="2"> <span
                                                                class="label-text m-4">Regular</span>
                                                        @endif
                                                    </label>
                                                    <label>
                                                        @if ($accInt->Estado == 3)
                                                            <input type="radio"
                                                                name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                                value="3" checked> <span
                                                                class="label-text m-4">Malo</span>
                                                        @else
                                                            <input type="radio"
                                                                name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                                value="3"> <span class="label-text m-4">Malo</span>
                                                        @endif
                                                    </label>
                                                </div>
                                            @else
                                                <div class="radiobox" style="width: 380px;">
                                                    <label>
                                                        <input type="radio"
                                                            name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                            value="1" disabled> <span
                                                            class="label-text m-4">Bueno</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio"
                                                            name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                            value="2" disabled> <span
                                                            class="label-text m-4">Regular</span>
                                                    </label>
                                                    <label>
                                                        <input type="radio"
                                                            name="radioOption{{ $accInt->IdDescripcionCheckIn }}"
                                                            value="3" disabled> <span
                                                            class="label-text m-4">Malo</span>
                                                    </label>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <br>
                    @endif
                    <table id="table3" class="table" width="100%">
                        <thead>
                            <tr class="text-md-center">
                                <th colspan="4"><label class="fs-16">Herramientas</label></th>
                            </tr>
                            <tr align="center">
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($herramientas as $herramienta)
                                <tr>
                                    <td align="center">
                                        @if ($herramienta->Estado != 'E')
                                            <input id="{{ $herramienta->IdDescripcionCheckIn }}"
                                                name="checkHerramientas[]"
                                                class="chbox herramientas{{ $herramienta->IdDescripcionCheckIn }}"
                                                value="{{ $herramienta->IdDescripcionCheckIn }}" type="checkbox"
                                                checked />
                                        @else
                                            <input id="{{ $herramienta->IdDescripcionCheckIn }}"
                                                name="checkHerramientas[]"
                                                value="{{ $herramienta->IdDescripcionCheckIn }}" class="chbox"
                                                type="checkbox" />
                                        @endif
                                    </td>
                                    <td><label>{{ $herramienta->Descripcion }}</label></td>
                                    <td align="center">
                                        @if ($herramienta->Estado != 'E')
                                            <input id="input{{ $herramienta->IdDescripcionCheckIn }}" style="width:80px"
                                                name="input{{ $herramienta->IdDescripcionCheckIn }}" type="number"
                                                value="{{ $herramienta->Cantidad }}" />
                                        @else
                                            <input id="input{{ $herramienta->IdDescripcionCheckIn }}" style="width:80px"
                                                name="input{{ $herramienta->IdDescripcionCheckIn }}" type="number"
                                                value="{{ $herramienta->Cantidad }}" disabled />
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($herramienta->Estado != 'E')
                                            <div class="radiobox" style="width: 380px;">
                                                <label>
                                                    @if ($herramienta->Estado == 1)
                                                        <input type="radio"
                                                            name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                            value="1" checked> <span
                                                            class="label-text m-4">Bueno</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                            value="1"> <span class="label-text m-4">Bueno</span>
                                                    @endif
                                                </label>
                                                <label>
                                                    @if ($herramienta->Estado == 2)
                                                        <input type="radio"
                                                            name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                            checked value="2"> <span
                                                            class="label-text m-4">Regular</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                            value="2"> <span class="label-text m-4">Regular</span>
                                                    @endif
                                                </label>
                                                <label>
                                                    @if ($herramienta->Estado == 3)
                                                        <input type="radio"
                                                            name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                            value="3" checked> <span
                                                            class="label-text m-4">Malo</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                            value="3"> <span class="label-text m-4">Malo</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @else
                                            <div class="radiobox" style="width: 380px;">
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                        value="1" disabled> <span class="label-text m-4">Bueno</span>
                                                </label>
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                        value="2" disabled> <span
                                                        class="label-text m-4">Regular</span>
                                                </label>
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $herramienta->IdDescripcionCheckIn }}"
                                                        value="3" disabled> <span class="label-text m-4">Malo</span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <table id="table4" class="table" width="100%">
                        <thead>
                            <tr class="text-md-center">
                                <th colspan="4"><label class="fs-16">Documento Vehículo</label></th>
                            </tr>
                            <tr align="center">
                                <th>&nbsp;</th>
                                <th>&nbsp;</th>
                                <th>Cantidad</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($docVehiculos as $docVehi)
                                <tr>
                                    <td align="center">
                                        @if ($docVehi->Estado != 'E')
                                            <input id="{{ $docVehi->IdDescripcionCheckIn }}" name="checkDocumentos[]"
                                                value="{{ $docVehi->IdDescripcionCheckIn }}"
                                                class="chbox documentos{{ $docVehi->IdDescripcionCheckIn }}"
                                                type="checkbox" checked />
                                        @else
                                            <input id="{{ $docVehi->IdDescripcionCheckIn }}" name="checkDocumentos[]"
                                                value="{{ $docVehi->IdDescripcionCheckIn }}" class="chbox"
                                                type="checkbox" />
                                        @endif
                                    </td>
                                    <td><label>{{ $docVehi->Descripcion }}</label></td>
                                    <td align="center">
                                        @if ($docVehi->Estado != 'E')
                                            <input id="input{{ $docVehi->IdDescripcionCheckIn }}" style="width:80px"
                                                name="input{{ $docVehi->IdDescripcionCheckIn }}" type="number"
                                                value="{{ $docVehi->Cantidad }}" />
                                        @else
                                            <input id="input{{ $docVehi->IdDescripcionCheckIn }}" style="width:80px"
                                                name="input{{ $docVehi->IdDescripcionCheckIn }}" type="number"
                                                value="{{ $docVehi->Cantidad }}" disabled />
                                        @endif
                                    </td>
                                    <td align="center">
                                        @if ($docVehi->Estado != 'E')
                                            <div class="radiobox" style="width: 380px;">
                                                <label>
                                                    @if ($docVehi->Estado == 1)
                                                        <input type="radio"
                                                            name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                            value="1" checked> <span
                                                            class="label-text m-4">Bueno</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                            value="1"> <span class="label-text m-4">Bueno</span>
                                                    @endif
                                                </label>
                                                <label>
                                                    @if ($docVehi->Estado == 2)
                                                        <input type="radio"
                                                            name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                            value="2" checked> <span
                                                            class="label-text m-4">Regular</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                            value="2"> <span class="label-text m-4">Regular</span>
                                                    @endif
                                                </label>
                                                <label>
                                                    @if ($docVehi->Estado == 3)
                                                        <input type="radio"
                                                            name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                            value="3" checked> <span
                                                            class="label-text m-4">Malo</span>
                                                    @else
                                                        <input type="radio"
                                                            name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                            value="3"> <span class="label-text m-4">Malo</span>
                                                    @endif
                                                </label>
                                            </div>
                                        @else
                                            <div class="radiobox" style="width: 380px;">
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                        value="1" disabled> <span class="label-text m-4">Bueno</span>
                                                </label>
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                        value="2" disabled> <span
                                                        class="label-text m-4">Regular</span>
                                                </label>
                                                <label>
                                                    <input type="radio"
                                                        name="radioOption{{ $docVehi->IdDescripcionCheckIn }}"
                                                        value="3" disabled> <span class="label-text m-4">Malo</span>
                                                </label>
                                            </div>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea id="observacion" class="form-control pt-4" rows="5" name="observacion">{{ $datosInventario->Observacion }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 text-center">
                            {{-- <button type="submit" class="btn btn--verde">Actualizar Datos</button> --}}
                            <x-button class="btn-primary">
                                Actualizar Datos
                            </x-button>
                        </div>
                    </div>
                    {!! Form::close() !!}

                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>

@stop

@section('scripts')
    <script>
        $("#clientes").change(function() {
            var idCliente = $('#clientes').val();
            $.ajax({
                type: 'get',
                url: 'data-vehiculo',
                data: {
                    'IdVehiculo': idCliente
                },
                success: function(result) {
                    $('#color').val(result[0]["Color"]);
                    $('#anio').val(result[0]["Anio"]);
                }
            });
        });

        // Nuevas Funciones

        $(function() {

            for (let index = 1; index <= 14; index++) {
                if ($('.accesoriosExternos' + index).attr('checked')) {
                    $('input[name="input' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);

                } else {
                    $('input[name="input' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                }
            }

            for (let index = 15; index <= 21; index++) {
                if ($('.accesoriosInternos' + index).attr('checked')) {
                    $('input[name="input' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);

                } else {
                    $('input[name="input' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                }
            }

            for (let index = 22; index <= 24; index++) {
                if ($('.herramientas' + index).attr('checked')) {
                    $('input[name="input' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);

                } else {
                    $('input[name="input' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                }
            }

            for (let index = 25; index <= 28; index++) {
                if ($('.documentos' + index).attr('checked')) {
                    $('input[name="input' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);
                    $('input[name="radioOption' + index + '"]').prop('disabled', false);

                } else {
                    $('input[name="input' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                    $('input[name="radioOption' + index + '"]').prop('disabled', true);
                }
            }
        })

        // Fin

        $(".chbox").click(function() {
            var id = $(this).attr("id");
            if ($('#' + id).is(':checked')) {
                $('input[name="input' + id + '"]').prop('disabled', false);
                $('input[name="radioOption' + id + '"]').prop('disabled', false);
                $('input[name="radioOption' + id + '"]').prop('disabled', false);
                $('input[name="radioOption' + id + '"]').prop('disabled', false);
            } else {
                $('input[name="input' + id + '"]').prop('disabled', true);
                $('input[name="radioOption' + id + '"]').prop('disabled', true);
                $('input[name="radioOption' + id + '"]').prop('disabled', true);
                $('input[name="radioOption' + id + '"]').prop('disabled', true);
            }
        });

        $(".chboxAuto").click(function() {
            var id = $(this).attr("id");
            if (id == 'chboxAuto4') {
                if ($('#' + id).is(':checked')) {
                    $('input[name="Dias"]').prop('disabled', false);
                    $('input[name="Monto"]').prop('disabled', false);
                } else {
                    $('input[name="Dias"]').prop('disabled', true);
                    $('input[name="Monto"]').prop('disabled', true);
                }
            }
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $('#table1').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
            $('#table2').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });

            $('#table3').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });

            $('#table4').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
        });
    </script>

@stop
