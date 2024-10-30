@extends('layouts.app')
@section('title', 'CheckList-Moto')
@section('content')
    <div class="container">
        <section class="bg-jumbotron--white">
            <div class="row mt-4">
                <div class="col-md-12 widget-holder">
                    {!! Form::open([
                        'url' => '/vehicular/checkList-moto',
                        'method' => 'POST',
                        'files' => true,
                        'class' => '',
                        'id' => 'myform',
                    ]) !!}
                    {{ csrf_field() }}

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="formGroupExampleInput">Seleccione el Cliente</label>
                                <select class="form-control select2-hidden-accessible" id="clientes" name="cliente"
                                    data-placeholder="Choose" data-toggle="select2" tabindex="-1" aria-hidden="true">
                                    <option value="0">-</option>
                                    @foreach ($clientes as $cliente)
                                        <option value="{{ $cliente->IdCliente }}">{{ $cliente->RazonSocial }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="serie">Serie</label>
                                <input type="text" class="form-control py-2" id="serie" placeholder="Serie"
                                    value="{{ $nuevoCorrelativo->Serie }}" readonly>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                <label for="correlativo">Correlativo</label>
                                <input type="text" class="form-control py-2" id="correlativo" placeholder="Correlativo"
                                    value="{{ $nuevoCorrelativo->Numero }}" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-4">
                            <div class="form-group">
                                {{-- <label for="color">Color</label> --}}
                                <input type="text" class="form-control py-2" id="color" placeholder="Color">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {{-- <label for="anio">Año</label> --}}
                                <input type="text" class="form-control py-2" id="anio" placeholder="Año">
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="form-group">
                                {{-- <label for="kilometraje">Kilometraje</label> --}}
                                <input type="text" class="form-control py-2" id="kilometraje" placeholder="Kilometraje">
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <label for="selectVentaRapida" class="fs-16">Autorizaciones</label>
                        </div>
                        <div class="col-md-6">
                            <input id="chboxAuto1" name="chboxAuto1" class="chboxAuto mt-3" type="checkbox" /> <span
                                class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo conducir mi moto para pruebas en
                                exteriores del Taller</span>
                        </div>
                        <div class="col-md-6">
                            <input id="chboxAuto2" name="chboxAuto2" class="chboxAuto mt-3" type="checkbox" /> <span
                                class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo enviar mi moto para trabajos de
                                terceros en Talleres de su elección</span>
                        </div>
                        {{-- <div class="col-md-6">
                            <input id="chboxAuto3" name="chboxAuto3" class="chboxAuto mt-3" type="checkbox" /> <span
                                class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Declaro que no dejo elementos de valor</span>
                        </div> --}}
                        <div class="col-md-6">
                            <input id="chboxAuto4" name="chboxAuto4" class="chboxAuto mt-3" type="checkbox" /> <span
                                class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Acepto retirar el moto en un máximo de
                            </span> <input style="width:40px" name="Dias" disabled> <span
                                class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">días, luego de finalizado el servicio; caso
                                contrario asumiré un costo de S/ </span> <input style="width:40px" name="Monto" disabled>
                            <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">diarios por cochera (interna y/o
                                externa)</span>
                        </div>
                    </div>
                    <br><br>
                    <div class="row">
                        <div class="col-md-12 text-center">
                            <div class="form-group">
                                <label for="selectVentaRapida" class="fs-16">Nivel de Gasolina</label><br>
                                <div class="radiobox mt-4">
                                    <label>
                                        <input type="radio" name="radioNivelGasolina" checked value="25"> <span
                                            class="label-text m-4">25 %</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="radioNivelGasolina" value="50"> <span
                                            class="label-text m-4">50 %</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="radioNivelGasolina" value="75"> <span
                                            class="label-text m-4">75 %</span>
                                    </label>
                                    <label>
                                        <input type="radio" name="radioNivelGasolina" value="100"> <span
                                            class="label-text m-4">100 %</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <br><br>
                    <table id="table1" class="table" width="100%">
                        <thead>
                            <tr class="text-md-center">
                                <th colspan="6"><label class="fs-16">Partes</label></th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Descripción</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datosPartes as $parte)
                                <tr>
                                    <td align="center"><input id="1" name="chbox[]" class="chbox"
                                            type="checkbox" />
                                    </td>
                                    <td><label>{{ $parte->Descripcion }}</label></td>
                                    <td align="center">
                                        <div class="radiobox">
                                            <label>
                                                <input type="radio" name="radioOption" value="1"> <span
                                                    class="label-text m-4">Bueno</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="radioOption" checked value="2">
                                                <span class="label-text m-4">Regular</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="radioOption" value="3"> <span
                                                    class="label-text m-4">Malo</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <table id="table1" class="table" width="100%">
                        <thead>
                            <tr class="text-md-center">
                                <th colspan="6"><label class="fs-16">Herramientas</label></th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Descripción</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datosHerramientas as $herramienta)
                                <tr>
                                    <td align="center"><input id="1" name="chbox" class="chbox"
                                            type="checkbox" />
                                    </td>
                                    <td><label>{{ $herramienta->Descripcion }}</label></td>
                                    <td align="center">
                                        <div class="radiobox">
                                            <label>
                                                <input type="radio" name="radioOption" value="1" disabled> <span
                                                    class="label-text m-4">Bueno</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="radioOption" checked value="2" disabled>
                                                <span class="label-text m-4">Regular</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="radioOption" value="3" disabled> <span
                                                    class="label-text m-4">Malo</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <br>
                    <table id="table1" class="table" width="100%">
                        <thead>
                            <tr class="text-md-center">
                                <th colspan="6"><label class="fs-16">Documentos</label></th>
                            </tr>
                            <tr>
                                <th>&nbsp;</th>
                                <th>Descripción</th>
                                <th class="text-center">Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($datosDocumentos as $documento)
                                <tr>
                                    <td align="center"><input id="1" name="chbox" class="chbox"
                                            type="checkbox" />
                                    </td>
                                    <td><label>{{ $documento->Descripcion }}</label></td>
                                    <td align="center">
                                        <div class="radiobox">
                                            <label>
                                                <input type="radio" name="radioOption" value="1" disabled> <span
                                                    class="label-text m-4">Bueno</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="radioOption" checked value="2" disabled>
                                                <span class="label-text m-4">Regular</span>
                                            </label>
                                            <label>
                                                <input type="radio" name="radioOption" value="3" disabled> <span
                                                    class="label-text m-4">Malo</span>
                                            </label>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            {{-- <tr>
                                <td align="center"><input id="1" name="chbox" class="chbox"
                                        type="checkbox" />
                                </td>
                                <td align="center"><label>Brevete o licencia de conducción</label></td>
                                <td align="center">
                                    <div class="radiobox">
                                        <label>
                                            <input type="radio" name="radioOption" value="1" disabled> <span
                                                class="label-text m-4">Bueno</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="radioOption" checked value="2" disabled>
                                            <span class="label-text m-4">Regular</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="radioOption" value="3" disabled> <span
                                                class="label-text m-4">Malo</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><input id="1" name="chbox" class="chbox"
                                        type="checkbox" />
                                </td>
                                <td align="center"><label>Tarjeta de propiedad</label></td>
                                <td align="center">
                                    <div class="radiobox">
                                        <label>
                                            <input type="radio" name="radioOption" value="1" disabled> <span
                                                class="label-text m-4">Bueno</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="radioOption" checked value="2" disabled>
                                            <span class="label-text m-4">Regular</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="radioOption" value="3" disabled> <span
                                                class="label-text m-4">Malo</span>
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td align="center"><input id="1" name="chbox" class="chbox"
                                        type="checkbox" />
                                </td>
                                <td align="center"><label>SOAT</label></td>
                                <td align="center">
                                    <div class="radiobox">
                                        <label>
                                            <input type="radio" name="radioOption" value="1" disabled> <span
                                                class="label-text m-4">Bueno</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="radioOption" checked value="2" disabled>
                                            <span class="label-text m-4">Regular</span>
                                        </label>
                                        <label>
                                            <input type="radio" name="radioOption" value="3" disabled> <span
                                                class="label-text m-4">Malo</span>
                                        </label>
                                    </div>
                                </td>
                            </tr> --}}
                        </tbody>
                    </table>
                    <div class="row mt-4">
                        <div class="col-lg-12 col-md-12">
                            <div class="form-group">
                                <label>Observación</label>
                                <textarea id="observacion" class="form-control" rows="5" name="observacion"></textarea>
                            </div>
                        </div>
                        <div class="col-12">
                            <section class="d-flex justify-content-end mt-3">
                                <button id="btnGenerar" class="btn btn-primary " type="submit">Generar
                                    Inventario</button>
                            </section>
                        </div>
                    </div>
                    {!! Form::close() !!}



                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>
        </section>
    </div>
@stop

@section('scripts')
    <script>
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });
    </script>


@stop
