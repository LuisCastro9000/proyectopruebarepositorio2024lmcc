@extends('layouts.app')
@section('title', 'Editar Control Calidad')
@section('content')
    <div class="container">
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <section class="mt-4">
            <h6 class="font-weight-bolder">Editar Control de calidad</h6>
        </section><br>

        {!! Form::open([
            'url' => '/vehicular/control-calidad/' . $controlCalidad->IdControlCalidad,
            'method' => 'Post',
            'files' => true,
            'name' => 'formularioControlCalidad',
        ]) !!}
        {{ csrf_field() }}
        {{ method_field('PUT') }}

        {{-- <form action="{{ route('controlCalidad.update', $controlCalidad->IdControlCalidad) }}" method="POST"
            name="formActualizarControlCalidad">
            @csrf
            @method('PUT') --}}

        {{-- seccion check firma --}}
        <article class="d-flex flex-wrap justify-content-between my-4">
            <div class="custom-control custom-checkbox">
                @if ($controlCalidad->OpcionFirmaAsesor == 'Activado')
                    <input type="checkbox" id="checkFirmaAsesorComercial" class="custom-control-input"
                        name="checkFirmaAsesorComercial" value="{{ $controlCalidad->OpcionFirmaAsesor }}" checked>
                @else
                    <input type="checkbox" id="checkFirmaAsesorComercial" class="custom-control-input"
                        name="checkFirmaAsesorComercial" value="Activado">
                @endif
                <label class="custom-control-label" for="checkFirmaAsesorComercial">Agregar Firma del Asesor
                    comercial en la Impresión del control de calidad</label>
            </div>
            <div class="custom-control custom-checkbox">
                @if ($controlCalidad->OpcionFirmaMecanico == 'Activado')
                    <input type="checkbox" class="custom-control-input" name="checkFirmaMecanico" id="checkFirmaMecanico"
                        value="{{ $controlCalidad->OpcionFirmaMecanico }}" checked>
                @else
                    <input type="checkbox" class="custom-control-input" name="checkFirmaMecanico" id="checkFirmaMecanico"
                        value="Activado">
                @endif
                <label class="custom-control-label" for="checkFirmaMecanico">Agregar Firma del Mecánico en la
                    Impresión del control de calidad</label>
            </div>
        </article>
        {{-- Fin --}}

        {{-- Seccion Detalles --}}
        <section class="mt-4">
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
                                        Inspección</span>
                                </th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosDebajoVehiculo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idPartesBajoVehiculo[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInferiores1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInferiores{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                <th class="text-info" style="min-width:300px">
                                    Descripción
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-secondary fs-14">Sin Inspección</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning fs-14">Atención Futura</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger fs-14">Atención Inmediata</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosDentroVehiculo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idPartesDentroVehiculo[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesInterior1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesInterior{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                <th class="text-info" style="min-width:300px">
                                    Descripción
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-secondary fs-14">Sin Inspección</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning fs-14">Atención Futura</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger fs-14">Atención Inmediata</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosBajoCapo as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idPartesBajoCapo[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio"
                                                id="radioEstadoPartesBajoCapo1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoPartesBajoCapo{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                <th class="text-info" style="min-width:300px">
                                    Descripción
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-secondary fs-14">Sin Inspección</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning fs-14">Atención Futura</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger fs-14">Atención Inmediata</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosFluidos as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idNivelesLiquido[]"
                                            value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioNivelLiquido1-{{ $item->IdParteVehiculo }}"
                                                name="radioNivelLiquido{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                <th class="text-info" style="min-width:300px">
                                    Descripción
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-secondary fs-14">Sin Inspección</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning fs-14">Atención Futura</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger fs-14">Atención Inmediata</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosFiltros as $item)
                                <tr>
                                    <th>{{ $item->Descripcion }}
                                        <input type="hidden" name="idFiltros[]" value="{{ $item->IdParteVehiculo }}">
                                    </th>
                                    <td class="text-center">
                                        <div
                                            class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                            <input type="radio" id="radioEstadoFiltro1-{{ $item->IdParteVehiculo }}"
                                                name="radioEstadoFiltro{{ $item->IdParteVehiculo }}"
                                                value="Sin Inspeccion"
                                                class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
            <article class="card rounded">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Frenos
                </div>
                <div class="card-body">
                    <section class="d-flex justify-content-center">
                        <img src="{{ asset('/assets/img/Control-de-calidad/FrenosVehiculo.png') }}"
                            alt="Imagen de Frenos" style="width: 400px; height:250px" class="rounded-circle">
                    </section><br>
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="width:100px;min-width:100px">Posición</th>
                                <th class="text-info" style="min-width:150px">
                                    Descripción
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-secondary fs-14">Sin Inspección</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning fs-14">Atención Futura</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger fs-14">Atención Inmediata</span>
                                </th>
                                <th class="text-center text-info">Medida(mm)</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosFrenos as $item)
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
                                                <input type="radio" id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion radioDesactivarMedida"
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
                                                    value="Satisfactorio" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata radioActivarMedida"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}">
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Discos')
                                            <th rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }} </th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion radioDesactivarMedida"
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
                                                    value="Satisfactorio" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata radioActivarMedida"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}">
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
                                                <input type="radio"
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion radioDesactivarMedida"
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
                                                    value="Satisfactorio" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata radioActivarMedida"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}">
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Discos')
                                            <th rowspan="4" class="align-middle">TI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }} </th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoFreno1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoFreno{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion radioDesactivarMedida"
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
                                                    value="Satisfactorio" data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura radioActivarMedida"
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
                                                    data-idcontrol="{{ $item->IdParteVehiculo }}"
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata radioActivarMedida"
                                                    @if (old('radioEstadoFreno' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoFreno4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                        <td class="d-flex justify-content-center">
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                id="inputMedida{{ $item->IdParteVehiculo }}" type="text"
                                                name="radioMedida{{ $item->IdParteVehiculo }}">
                                        </td>
                                    </tr>
                                @endif
                            @endforeach

                        </tbody>
                    </table>
                </div>
            </article>>
            {{-- Fin --}}
            {{-- LimpiaParabrisas --}}
            <article class="card rounded-0">
                <div class="card-header text-center bg-griss font-weight-bold">
                    LimpiaParabrisas
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="width:100px;min-width:100px">Posición</th>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span>
                                </th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosLimpiaParabrisas as $item)
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
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                            <th rowspan="3" class="align-middle">TC</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLimpiaparabrisas1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLimpiaparabrisas{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
            <article class="card rounded">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Llantas
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="width:100px;min-width:100px">Posición</th>
                                <th class="text-info" style="min-width:150px">
                                    Descripción
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-secondary fs-14">Sin Inspección</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-warning fs-14">Atención Futura</span>
                                </th>
                                <th class="text-center">
                                    <span class="badge badge-danger fs-14">Atención Inmediata</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody>

                            @foreach ($controlCalidad->datosNeumaticos as $item)
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
                                                <input type="radio"
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                                <input type="radio"
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                                <input type="radio"
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                                                <input type="radio"
                                                    id="radioEstadoLlanta1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLlanta{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
                    <section>
                        <div class="row">
                            <article
                                class="d-flex flex-column justify-content-center align-items-end col-6 col-md-3 order-1 order-md-0">
                                @foreach ($controlCalidad->datosPresionNeumatico as $item)
                                    <input type="hidden" name="idPresionNeumatico[]"
                                        value="{{ $item->IdParteVehiculo }}">
                                    @if ($item->Posicion == 'FrontalDerecha')
                                        <div class="text-center">
                                            <span>FD</span>
                                            <input style="width: 80px" class="form-control form-control-sm mb-1"
                                                type="text" placeholder="FD"
                                                name="radioPresionNeumatico{{ $item->IdParteVehiculo }}"
                                                value="{{ $item->Presion }}">
                                        </div>
                                    @endif
                                    @if ($item->Posicion == 'FrontalIzquierda')
                                        <div class="text-center">
                                            <span>FI</span>
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                type="text" placeholder="FI"
                                                name="radioPresionNeumatico{{ $item->IdParteVehiculo }}"
                                                value="{{ $item->Presion }}">
                                        </div>
                                    @endif
                                @endforeach
                            </article>
                            <article class="col-12 col-md-6 d-flex justify-content-center">
                                <img class="img-fluid" style="min-width: 250px"
                                    src="{{ asset('/assets/img/Control-de-calidad/LlantasVehiculo.png') }}"
                                    alt="Alineación y Balanceo">
                            </article>
                            <article
                                class="d-flex flex-column justify-content-center align-items-start col-6 col-md-3  order-1 order-md-0">
                                @foreach ($controlCalidad->datosPresionNeumatico as $item)
                                    @if ($item->Posicion == 'TraseraDerecha')
                                        <div class="text-center">
                                            <span>TD</span>
                                            <input style="width: 80px" class="form-control form-control-sm mb-1"
                                                type="text" placeholder="TD"
                                                name="radioPresionNeumatico{{ $item->IdParteVehiculo }}"
                                                value="{{ $item->Presion }}">
                                        </div>
                                    @endif
                                    @if ($item->Posicion == 'TraseraIzquierda')
                                        <div class="text-center">
                                            <span>TI</span>
                                            <input style="width: 80px" class="form-control form-control-sm"
                                                type="text" placeholder="TI"
                                                name="radioPresionNeumatico{{ $item->IdParteVehiculo }}"
                                                value="{{ $item->Presion }}">
                                        </div>
                                    @endif
                                @endforeach
                            </article>
                        </div>
                    </section>
                </div>
            </article>
            {{-- Fin --}}
            {{-- Luces --}}
            <article class="card rounded">
                <div class="card-header text-center bg-griss font-weight-bold">
                    Luces
                </div>
                <div class="card-body">
                    <table width="100%" class="table table-responsive-lg">
                        <thead>
                            <tr>
                                <th class="text-info" style="width:100px;min-width:100px">Posición</th>
                                <th class="text-info" style="min-width:300px">Descripción</th>
                                <th class="text-center"><span class="badge badge-secondary fs-14">Sin
                                        Inspección</span>
                                </th>
                                <th class="text-center"><span class="badge badge-success fs-14">Satisfactorio</span>
                                </th>
                                <th class="text-center"><span class="badge badge-warning fs-14">Atención
                                        Futura</span></th>
                                <th class="text-center"><span class="badge badge-danger fs-14">Atención
                                        Inmediata</span></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($controlCalidad->datosLuces as $item)
                                <input type="hidden" name="idLuces[]" value="{{ $item->IdParteVehiculo }}">
                                @if ($item->Posicion == 'FrontalDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Intermitente/Direccionales')
                                            <th rowspan="2" class="align-middle">FD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'FrontalIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Intermitente/Direccionales')
                                            <th rowspan="2" class="align-middle">FI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraDerecha')
                                    <tr>
                                        @if ($item->Descripcion == 'Retroceso')
                                            <th rowspan="3" class="align-middle">TD</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
                                                    @if (old('radioEstadoLuces' . $item->IdParteVehiculo) == 'Atencion Inmediata') checked @endif>
                                                <label class="custom-control-label p-0"
                                                    for="radioEstadoLuces4-{{ $item->IdParteVehiculo }}"></label>
                                            </div>
                                        </td>
                                    </tr>
                                @endif
                                @if ($item->Posicion == 'TraseraIzquierda')
                                    <tr>
                                        @if ($item->Descripcion == 'Retroceso')
                                            <th rowspan="3" class="align-middle">TI</th>
                                        @endif
                                        <th>{{ $item->Descripcion }}</th>
                                        <td class="text-center">
                                            <div
                                                class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                                <input type="radio"
                                                    id="radioEstadoLuces1-{{ $item->IdParteVehiculo }}"
                                                    name="radioEstadoLuces{{ $item->IdParteVehiculo }}"
                                                    value="Sin Inspeccion"
                                                    class="custom-control-input radioEstadoSinInspeccion{{ $item->IdParteVehiculo }} radioEstadoSinInspeccion"
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
                                                    class="custom-control-input radioEstadoSatisfactorio{{ $item->IdParteVehiculo }} radioEstadoSatisfactorio"
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
                                                    class="custom-control-input radioEstadoAtencionFutura{{ $item->IdParteVehiculo }} radioEstadoAtencionFutura"
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
                                                    class="custom-control-input radioEstadoAtencionInmediata{{ $item->IdParteVehiculo }} radioEstadoAtencionInmediata"
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
        </section>
        <div class="mt-4">
            <div class="form-group">
                <textarea id="diagnostico" class="form-control" rows="7" name="diagnostico" maxlength="1500">{{ $controlCalidad->Diagnostico }}</textarea>
                <label>Diagnóstico del Servicio</label>
            </div>
        </div>
        <div class="mt-4">
            <div class="form-group">
                <textarea id="recomendaciones" class="form-control" rows="7" name="recomendaciones" maxlength="1500">{{ $controlCalidad->Recomendacion }}</textarea>
                <label>Recomendaciones</label>
            </div>
        </div>
        <div class="col-12">
            <section class="d-flex justify-content-end mt-3">
                <input type="hidden" name="radioEstadoPrioridad" id="inputRadioEstadoPrioridad">
                <button id="btnControlCalidad" class="btn btn-primary " type="submit">Actualizar
                    Control de Calidad</button>
            </section>
        </div>
        {{-- </form> --}}
        {!! Form::close() !!}
    </div>
@stop

@section('scripts')
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/controlCalidadVehicular/controlCalidad.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/controlCalidadVehicular/scriptGeneral.js?v=' . time()) }}"></script>
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
    <script>
        const controlCalidad = @json($controlCalidad);
        $(function checkearRadio() {
            let arrayGrupoPartesVehiculo = ['datosDebajoVehiculo', 'datosDentroVehiculo', 'datosBajoCapo',
                'datosFiltros', 'datosFluidos', 'datosFrenos', 'datosLimpiaParabrisas', 'datosNeumaticos',
                'datosLuces'
            ];
            arrayGrupoPartesVehiculo.forEach(element => {
                chekear(element)
            });
        })

        function chekear($grupoPartesVehiculo) {
            controlCalidad[$grupoPartesVehiculo].forEach(element => {
                if (element.EstadoParte == 'Sin Inspeccion') {
                    $('.radioEstadoSinInspeccion' + element.IdParteVehiculo).attr(
                        'checked', 'true');
                    if ($grupoPartesVehiculo == 'datosFrenos') {
                        document.getElementById('inputMedida' + element.IdParteVehiculo).disabled = true;
                    }
                }
                if (element.EstadoParte == 'Satisfactorio') {
                    $('.radioEstadoSatisfactorio' + element.IdParteVehiculo).attr(
                        'checked', 'true');
                }
                if (element.EstadoParte == 'Atencion Futura') {
                    $('.radioEstadoAtencionFutura' + element.IdParteVehiculo).attr(
                        'checked', 'true');
                }
                if (element.EstadoParte == 'Atencion Inmediata') {
                    $('.radioEstadoAtencionInmediata' + element.IdParteVehiculo).attr(
                        'checked', 'true');
                }

                if ($grupoPartesVehiculo == 'datosFrenos') {
                    $('input[name=radioMedida' + element.IdParteVehiculo + ']').val(element.Medida);
                }
            });
        }
    </script>
@endsection
