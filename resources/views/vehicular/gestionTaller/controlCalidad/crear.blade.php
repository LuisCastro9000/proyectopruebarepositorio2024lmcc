@extends('layouts.app')
@section('title', 'Crear Control Calidad')
@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <section class="mt-4">
            <div class="row">
                <div class="col-12">
                    <section class="breadcrumb d-flex justify-content-between align-items-center bg-verde">
                        <span class="font-weight-bold fs-18">Control de Calidad Vehicular</span>
                        <span><i class='bx bxs-car-mechanic fs-30 rounded-circle'></i></span>
                    </section>
                </div>
                <div class="col">
                    <p class="text-danger text-justify fs-16">-Para crear un control de
                        calidad, la cotización debe tener asignado un Operario, si no le figura su cotización, edite y
                        asigne un Operario.</p>

                    <p class="text-danger text-justify fs-16">-En caso desee agregar las firmas en el PDF,
                        verificar que el Asesor Comercial y Mecánico tengan creado su respectiva firma</p>
                </div>
            </div>
        </section>

        {!! Form::open([
            'url' => '/vehicular/control-calidad',
            'method' => 'POST',
            'files' => true,
            'name' => 'formularioControlCalidad',
            // 'class' => 'form-material',
        ]) !!}
        {{ csrf_field() }}
        <div class="row justify-content-md-center my-4">
            <div class="col-12 col-md-6 order-md-0">
                <div class="form-group form-material">
                    <select class="m-b-10 form-control select2-hidden-accessible" id="placa" name="selectVehiculo"
                        data-placeholder="Seleccione placa" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Placa de Vehículo</option>
                        @foreach ($cotizaciones as $coti)
                            <option value="{{ $coti->IdVehiculo }}">{{ $coti->RazonSocial }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text"><label>Serie</label></div>
                        </div>
                        <input class="form-control" type="text" name="serie" value="{{ $serie }}" readonly>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-3">
                <div class="form-group">
                    <div class="input-group">
                        <div class="input-group-append">
                            <div class="input-group-text"><label>Numero</label></div>
                        </div>
                        <input class="form-control" type="text" name="numero" value="{{ $numero }}" readonly>
                    </div>
                </div>
            </div>
        </div>
        {{-- section detalles --}}
        <section class="mt-4">
            {{-- seccion datos control calidad --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Datos
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Mecánico</label></div>
                                    </div>
                                    <input id="inputMecanico" class="form-control" type="text" name="inputMecanico"
                                        readonly>
                                    <input id="inputIdMecanico" class="form-control" type="hidden" name="inputIdMecanico"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Tipo de Atención</label></div>
                                    </div>
                                    <input id="inputAtencion" class="form-control" type="text" name="inputAtencion"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Km Ingreso</label></div>
                                    </div>
                                    <input id="inputKilometraje" class="form-control" type="text" name="inputKilomatraje"
                                        readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Placa</label></div>
                                    </div>
                                    <input id="placaVehiculo" class="form-control" type="text" name="placa" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Color</label></div>
                                    </div>
                                    <input id="color" class="form-control" type="text" name="color" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Año</label></div>
                                    </div>
                                    <input id="anio" class="form-control" type="text" name="anio" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>F. Cotización</label></div>
                                    </div>
                                    <input id="inputFechaCotizacion" class="form-control" type="text"
                                        name="fechaCotizacion" readonly>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>EStado Cotización</label></div>
                                    </div>
                                    <input id="inputEstadoCotizacion" class="form-control" type="text"
                                        name="estadoCotizacion" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </article>
            {{-- Fin --}}

            {{-- seccion check firma --}}
            <article class="d-flex flex-wrap justify-content-between my-4">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" id="checkFirmaAsesorComercial" class="custom-control-input"
                        name="checkFirmaAsesorComercial" value="Activado">
                    <label class="custom-control-label" for="checkFirmaAsesorComercial">Agregar Firma del Asesor
                        comercial en la Impresión del control de calidad</label>
                </div>
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" name="checkFirmaMecanico"
                        id="checkFirmaMecanico" value="Activado">
                    <label class="custom-control-label" for="checkFirmaMecanico">Agregar Firma del Mecánico en la
                        Impresión del control de calidad</label>
                </div>
            </article>
            {{-- Fin --}}

            {{-- Bajo del vehiculo --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Bajo del Vehículo
                </div>
                <div class="card-body">
                    <table width="100%" class="table tableControlCalidad">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span></th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($debajoVehiculo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idPartesBajoVehiculo[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" checked
                                                id="radioEstadoPartesInferiores1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInferiores{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion"
                                                @if (old('radioEstadoPartesInferiores' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInferiores1-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInferiores2-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInferiores{{ $item->IdParteVehiculo }}"
                                                value="Satisfactorio"
                                                class="custom-control-input radioEstadoSatisfactorio"
                                                @if (old('radioEstadoPartesInferiores' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInferiores2-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInferiores3-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInferiores{{ $item->IdParteVehiculo }}"
                                                value="Atencion Futura"
                                                class="custom-control-input radioEstadoAtencionFutura"
                                                @if (old('radioEstadoPartesInferiores' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInferiores3-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInferiores4-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInferiores{{ $item->IdParteVehiculo }}"
                                                value="Atencion Inmediata"
                                                class="custom-control-input radioEstadoAtencionInmediata"
                                                @if (old('radioEstadoPartesInferiores' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInferiores4-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- FIn --}}

            {{-- Dentro del vehiculo --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Dentro del Vehículo
                </div>
                <div class="card-body">
                    <table width="100%" class="table tableControlCalidad">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span></th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($dentroVehiculo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idPartesDentroVehiculo[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" checked
                                                id="radioEstadoPartesInterior1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInterior{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion"
                                                @if (old('radioEstadoPartesInterior' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInterior1-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInterior2-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInterior{{ $item->IdParteVehiculo }}"
                                                value="Satisfactorio"
                                                class="custom-control-input radioEstadoSatisfactorio"
                                                @if (old('radioEstadoPartesInterior' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInterior2-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInterior3-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInterior{{ $item->IdParteVehiculo }}"
                                                value="Atencion Futura"
                                                class="custom-control-input radioEstadoAtencionFutura"
                                                @if (old('radioEstadoPartesInterior' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInterior3-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInterior4-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInterior{{ $item->IdParteVehiculo }}"
                                                value="Atencion Inmediata"
                                                class="custom-control-input radioEstadoAtencionInmediata"
                                                @if (old('radioEstadoPartesInterior' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesInterior4-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- FIn --}}

            {{-- Compartimiento del motor --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Compartimiento del Motor
                </div>
                <div class="card-body">
                    <table width="100%" class="table tableControlCalidad">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span></th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($partesBajoCapo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idPartesBajoCapo[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" checked
                                                id="radioEstadoPartesBajoCapo1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesBajoCapo{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion"
                                                @if (old('radioEstadoPartesBajoCapo' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesBajoCapo1-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesBajoCapo2-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesBajoCapo{{ $item->IdParteVehiculo }}"
                                                value="Satisfactorio"
                                                class="custom-control-input radioEstadoSatisfactorio"
                                                @if (old('radioEstadoPartesBajoCapo' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesBajoCapo2-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesBajoCapo3-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesBajoCapo{{ $item->IdParteVehiculo }}"
                                                value="Atencion Futura"
                                                class="custom-control-input radioEstadoAtencionFutura"
                                                @if (old('radioEstadoPartesBajoCapo' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesBajoCapo3-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesBajoCapo4-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesBajoCapo{{ $item->IdParteVehiculo }}"
                                                value="Atencion Inmediata"
                                                class="custom-control-input radioEstadoAtencionInmediata"
                                                @if (old('radioEstadoPartesBajoCapo' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoPartesBajoCapo4-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- Fin --}}

            {{-- Fluidos --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Fluidos
                </div>
                <div class="card-body">
                    <table width="100%" class="table tableControlCalidad">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span></th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($nivelesLiquido as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idNivelesLiquido[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" checked
                                                id="radioNivelLiquido1-{{ $item->IdParteVehiculo }}"
                                                name="radioNivelLiquido{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion"
                                                @if (old('radioNivelLiquido' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioNivelLiquido1-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioNivelLiquido2-{{ $item->IdParteVehiculo }}"
                                                name="radioNivelLiquido{{ $item->IdParteVehiculo }}"
                                                value="Satisfactorio"
                                                class="custom-control-input radioEstadoSatisfactorio"
                                                @if (old('radioNivelLiquido' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioNivelLiquido2-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioNivelLiquido3-{{ $item->IdParteVehiculo }}"
                                                name="radioNivelLiquido{{ $item->IdParteVehiculo }}"
                                                value="Atencion Futura"
                                                class="custom-control-input radioEstadoAtencionFutura"
                                                @if (old('radioNivelLiquido' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioNivelLiquido3-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioNivelLiquido4-{{ $item->IdParteVehiculo }}"
                                                name="radioNivelLiquido{{ $item->IdParteVehiculo }}"
                                                value="Atencion Inmediata"
                                                class="custom-control-input radioEstadoAtencionInmediata"
                                                @if (old('radioNivelLiquido' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioNivelLiquido4-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- Fin --}}

            {{-- Filtros --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Filtros
                </div>
                <div class="card-body">
                    <table width="100%" class="table tableControlCalidad">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span></th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($filtrosVehiculo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idFiltros[]" value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" checked
                                                id="radioEstadoFiltro1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoFiltro{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion"
                                                @if (old('radioEstadoFiltro' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoFiltro1-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioEstadoFiltro2-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoFiltro{{ $item->IdParteVehiculo }}"
                                                value="Satisfactorio"
                                                class="custom-control-input radioEstadoSatisfactorio"
                                                @if (old('radioEstadoFiltro' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoFiltro2-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioEstadoFiltro3-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoFiltro{{ $item->IdParteVehiculo }}"
                                                value="Atencion Futura"
                                                class="custom-control-input radioEstadoAtencionFutura"
                                                @if (old('radioEstadoFiltro' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoFiltro3-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioEstadoFiltro4-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoFiltro{{ $item->IdParteVehiculo }}"
                                                value="Atencion Inmediata"
                                                class="custom-control-input radioEstadoAtencionInmediata"
                                                @if (old('radioEstadoFiltro' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                            <label class="custom-control-label p-0"
                                                for="radioEstadoFiltro4-{{ $item->IdParteVehiculo }}"></label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- Fin --}}

            {{-- Frenos --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Frenos
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-center">
                        <img src="{{ asset('/assets/img/Control-de-calidad/FrenosVehiculo.png') }}"
                            alt="Imagen de Frenos" style="width: 400px; height:250px" class="rounded-circle">
                    </section><br>
                    <table width="100%" class="table table-responsive-xl mb-4 tableControlCalidad">
                        <thead>
                            <tr>
                                <th class="text-info">Posición</th>
                                <th class="text-info">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span></th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                                <th class="text-center text-info">Medida(mm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($frenosVehiculo as $item)
                                <input type="hidden" name="idFrenos[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Discos')
                                            <th rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }} </th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="radioDesactivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" id="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio" class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" id="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" id="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}" disabled>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Discos')
                                            <th rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="radioDesactivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio" class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}" disabled>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Discos')
                                            <th rowspan="4" class="align-middle">TD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }} </th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="radioDesactivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio" class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}" disabled>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Discos')
                                            <th rowspan="4" class="align-middle">TI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="radioDesactivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio" class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="radioActivarMedida custom-control-input"
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}" disabled>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- Fin --}}

            {{-- LimpiaParabrisas --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Sistema de LimpiaParabrisas
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="width:100px;min-width:100px">Posición</th>
                                <th class="text-info" style="width:200px;min-width:200px">Descripción</th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-secondary fs-14">Sin Inspección</span></th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-success fs-14 w-80">Satisfactorio</span>
                                </th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-warning fs-14 w-80">Atención
                                        Futura</span></th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($limpiaParabrisas as $item)
                                <input type="hidden" name="idlimpiaparabrisas[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Escobilla/Goma')
                                            <th rowspan="3" class="align-middle">FD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Escobilla/Goma')
                                            <th rowspan="3" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'Trasera')
                                    <tr>
                                        @if ($item->Descripcion == 'Escobilla/Goma')
                                            <th rowspan="3" class="align-middle">T</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLimpiaparabrisas' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLimpiaparabrisas4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </article>
            {{-- Fin --}}

            {{-- Neumáticos --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Llantas
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="width:100px;min-width:100px">Posición</th>
                                <th class="text-info" style="width:200px;min-width:200px">Descripción</th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-secondary fs-14">Sin Inspección</span></th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-success fs-14 w-80">Satisfactorio</span>
                                </th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-warning fs-14 w-80">Atención
                                        Futura</span></th>
                                <th class="text-center w-20" style="min-width:200px"><span
                                        class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($llantas as $item)
                                <input type="hidden" name="idLlantas[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Alineación')
                                            <th rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Alineación')
                                            <th rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Alineación')
                                            <th rowspan="2" class="align-middle">TD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Alineación')
                                            <th rowspan="2" class="align-middle">TI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLlanta' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLlanta4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                    <br><br>
                    <h6 class="text-center">Ingrese Presión de neumáticos</h6>
                    {{-- <section class="d-flex justify-content-center mb-4">
                        <article class="d-flex flex-column justify-content-center">
                            @foreach ($presionNeumatico as $item)
                                <input type="hidden" name="idPresionNeumatico[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <input style="width: 80px" class="form-control form-control-sm mb-4" type="text"
                                        placeholder="FD" name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <input style="width: 80px" class="form-control form-control-sm" type="text"
                                        placeholder="FI" name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                @endif
                            @endforeach
                        </article>
                        <img src="{{ asset('/assets/img/Control-de-calidad/LlantasVehiculo.png') }}"
                            alt="Alineación y Balanceo" style="width: 450px; height:250px">
                        <article class="d-flex flex-column justify-content-center">
                            @foreach ($presionNeumatico as $item)
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <input style="width: 80px" class="form-control form-control-sm mb-4" type="text"
                                        placeholder="TD" name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <input style="width: 80px" class="form-control form-control-sm" type="text"
                                        placeholder="TI" name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                @endif
                            @endforeach
                        </article>
                    </section> --}}

                    {{-- Codigo de prueba --}}
                    <section>
                        <div class="row">
                            <article
                                class="d-flex flex-column justify-content-center align-items-end col-6 col-md-3 order-1 order-md-0 flex-grow">
                                @foreach ($presionNeumatico as $item)
                                    <input type="hidden" name="idPresionNeumatico[]"
                                        value="{{ $item->IdParteVehiculo }}">
                                    @if ($item->Posicion == 'FrontalDerecha')
                                        <input style="width: 80px" class="form-control form-control-sm mb-4"
                                            type="text" placeholder="FD"
                                            name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                    @endif
                                    @if ($item->Posicion == 'FrontalIzquierda')
                                        <input style="width: 80px" class="form-control form-control-sm" type="text"
                                            placeholder="FI" name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                    @endif
                                @endforeach
                            </article>
                            <article class="col-12 col-md-6 d-flex justify-content-center">
                                <img class="img-fluid" style="min-width: 250px"
                                    src="{{ asset('/assets/img/Control-de-calidad/LlantasVehiculo.png') }}"
                                    alt="Alineación y Balanceo">
                            </article>
                            <article
                                class="d-flex flex-column justify-content-center align-items-stard col-6 col-md-3  order-1 order-md-0">
                                @foreach ($presionNeumatico as $item)
                                    @if ($item->Posicion == 'TraseraDerecha')
                                        <input style="width: 80px" class="form-control form-control-sm mb-4"
                                            type="text" placeholder="TD"
                                            name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                    @endif
                                    @if ($item->Posicion == 'TraseraIzquierda')
                                        <input style="width: 80px" class="form-control form-control-sm" type="text"
                                            placeholder="TI" name="radioPresionNeumatico{{ $item->IdParteVehiculo }}">
                                    @endif
                                @endforeach
                            </article>
                        </div>
                    </section>
                    {{-- Fin --}}
                </div>
            </article>
            {{-- Fin --}}

            {{-- Luces --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Luces
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:100px">Posición</th>
                                <th class="text-info" style="min-width:200px">Descripción</th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-secondary fs-14">Sin Inspección</span></th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-success fs-14 w-80">Satisfactorio</span>
                                </th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-warning fs-14 w-80">Atención
                                        Futura</span></th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($luces as $item)
                                <input type="hidden" name="idLuces[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Faro')
                                            <th rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Faro')
                                            <th rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Luz de Freno')
                                            <th rowspan="3" class="align-middle">TD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Luz de Freno')
                                            <th rowspan="3" class="align-middle">TI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article>
            {{-- Fin --}}


            {{-- CODIGO DE PRUEBA --}}
            {{-- <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Luces
                </div>
                <div class="card-body">
                    <table class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="min-width:100px">Posición</th>
                                <th class="text-info" style="min-width:200px">Descripción</th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-secondary fs-14">Sin
                                        Inspección</span>
                                </th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center" style="min-width:200px"><span
                                        class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($luces as $item)
                                <input type="hidden" name="idLuces[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Faro')
                                            <th rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Faro')
                                            <th rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Luz de Freno')
                                            <th rowspan="3" class="align-middle">TD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Luz de Freno')
                                            <th rowspan="3" class="align-middle">TI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio" checked
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Sin Inspeccion') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Satisfactorio"
                                                    class="custom-control-input radioEstadoSatisfactorio"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Satisfactorio') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces2-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Futura"
                                                    class="custom-control-input radioEstadoAtencionFutura"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Futura') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces3-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Atencion Inmediata"
                                                    class="custom-control-input radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </article> --}}
            {{-- FIN --}}

            <article class="row mt-4">
                {{-- Seccion Diagnóstico --}}
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <textarea id="diagnostico" class="form-control" rows="7" name="diagnostico" maxlength="1500"></textarea>
                        <label>Diagnóstico del Servicio</label>
                    </div>
                </div>
                {{-- Seccion Recomendaciones --}}
                <div class="col-12 col-md-6">
                    <div class="form-group">
                        <textarea id="recomendaciones" class="form-control" rows="7" name="recomendaciones" maxlength="1500"></textarea>
                        <label>Recomendaciones</label>
                    </div>
                </div>
            </article>

            <article class="d-flex justify-content-end mt-3">
                <input type="hidden" name="radioEstadoPrioridad" id="inputRadioEstadoPrioridad">
                <input type="hidden" name="idCotizacion" id="inputIdCotizacion">
                <button id="btnControlCalidad" class="btn btn-primary " type="submit">Generar
                    Control de Calidad</button>
            </article>
            {{-- </div> --}}
        </section>
        {{-- Fin --}}
        {!! Form::close() !!}
        <!-- Modal -->
        <div class="modal fade" id="modalSeleccionarCotizacion" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="form-group text-center mt-3">
                        <h6>VEHÍCULO CON COTIZACIONES ASOCIADAS</h6>
                        <hr>
                        <section id="contenidoModal mt-4">
                            <label>Seleccione la Cotización con la desea realizar el control de calidad</label>
                            <select id="selectCotizacion" class="form-control">
                                <option>-</option>
                            </select>
                        </section>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/controlCalidadVehicular/scriptGeneral.js?v=' . time()) }}"></script>
    <script>
        let arrayCotizaciones = '';

        function openModal() {
            $('#modalSeleccionarCotizacion').modal('show');
        }

        function closeModal() {
            $('#modalSeleccionarCotizacion').modal('hide');
        }

        $('#placa').change(function() {
            $.showLoading({
                name: 'circle-fade',
            });
            $('#seccionCotizaciones').find('#seccionEstadoCotizaciones').remove();
            $('#seccionCotizaciones').append('<div id="seccionEstadoCotizaciones"></div>')
            const placa = $(this).val();
            console.log('soy placa de vehiculo  ' + placa);
            $('#selectCotizacion').empty();
            $('#selectCotizacion').append('<option>-</option>');
            $.ajax({
                type: 'get',
                url: 'crear/obtener-datos-vehiculo',
                data: {
                    'placa': placa
                },
                success: function(data) {
                    console.log(data);
                    if (data.length == 1) {
                        if (data[0].AplicaControlCalidad == 'Si') {
                            swal("Error!", "Este Vehículo ya tiene aplicado un control de calidad",
                                "info");
                        } else {
                            $('#placaVehiculo').val(data[0]["PlacaVehiculo"]);
                            $('#color').val(data[0]["Color"]);
                            $('#anio').val(data[0]["Anio"]);
                            $('#inputMecanico').val(data[0]["NombreOperario"]);
                            $('#inputIdMecanico').val(data[0]["IdOperario"]);
                            $('#inputAtencion').val(data[0]["TipoAtencion"]);
                            $('#inputIdCotizacion').val(data[0]["IdCotizacion"]);
                            $('#inputFechaCotizacion').val(data[0]["FechaCreacion"]);
                            $('#inputKilometraje').val(data[0]["Kilometraje"]);
                            let resultado = evaluarEstadoCotizacion(data[0].IdEstadoCotizacion);
                            $('#inputEstadoCotizacion').val(resultado.estadoCotizacion);
                        }
                    } else {
                        arrayCotizaciones = data;
                        let mensaje = ' - Aplicado control Calidad';
                        let disabled = 'disabled'
                        data.forEach(data => {
                            let resultado = evaluarEstadoCotizacion(data.IdEstadoCotizacion);
                            if (data.AplicaControlCalidad == 'Si') {
                                mensaje = ' - Aplicado control Calidad';
                                disabled = 'disabled';
                                resultado.textColor = 'text-danger'
                            } else {
                                mensaje = '';
                                disabled = '';
                            }
                            $('#selectCotizacion').append(
                                '<option ' + disabled + ' class=' + resultado.textColor +
                                ' value=' + data.IdCotizacion + '>' +
                                data.Serie +
                                '-' + data.Numero + '->Estado:' + resultado
                                .estadoCotizacion +
                                mensaje +
                                '</option>')
                        });
                        openModal();
                    }
                    $.hideLoading();
                }
            })
        })


        $('#selectCotizacion').change(function() {
            const id = $(this).val();
            datos = arrayCotizaciones.filter(item => item.IdCotizacion == id);
            console.log(datos);
            $('#placaVehiculo').val(datos[0].PlacaVehiculo);
            $('#color').val(datos[0].Color);
            $('#anio').val(datos[0].Anio);
            $('#inputMecanico').val(datos[0].NombreOperario);
            $('#inputIdMecanico').val(datos[0].IdOperario);
            $('#inputAtencion').val(datos[0].TipoAtencion);
            $('#inputIdCotizacion').val(datos[0].IdCotizacion);
            $('#inputFechaCotizacion').val(datos[0].FechaCreacion);
            let resultado = evaluarEstadoCotizacion(datos[0].IdEstadoCotizacion);
            $('#inputEstadoCotizacion').val(resultado.estadoCotizacion);
            // $('#checkFirmaMecanico').val(datos[0].ImagenFirmaCliente);
            $('#inputKilometraje').val(datos[0].Kilometraje);
            closeModal();
        })

        function evaluarEstadoCotizacion(idEstado) {
            let textColor = '';
            let estadoCotizacion = '';
            if (idEstado == 2) {
                textColor = 'text-amarillo';
                estadoCotizacion = 'Proceso';
                return {
                    textColor: textColor,
                    estadoCotizacion: estadoCotizacion
                };
            } else {
                textColor = 'text-success';
                estadoCotizacion = 'Finalizado';
                return {
                    textColor: textColor,
                    estadoCotizacion: estadoCotizacion
                };
            }
        }
    </script>

    <script type="text/javascript">
        $(function() {
            $('.tableControlCalidad').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
        });
    </script>
@stop
