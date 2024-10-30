<section class="datosTipoVehiculo">
    <table width="100%" class="table table-responsive-lg">
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
                    <td align="center"><input id="{{ $accExt->IdDescripcionCheckIn }}" name="checkAccesoriosExternos[]"
                            class="chbox" type="checkbox" value="{{ $accExt->IdDescripcionCheckIn }}" /></td>
                    <td><label>{{ $accExt->Descripcion }}</label></td>
                    <td align="center">
                        <input id="input{{ $accExt->IdDescripcionCheckIn }}" style="width:80px"
                            name="input{{ $accExt->IdDescripcionCheckIn }}" type="number"
                            value="{{ $accExt->Cantidad }}" disabled />
                    </td>
                    <td align="center">
                        <div class="d-flex justify-content-around" style="width: 380px;">
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption1-{{ $accExt->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input" name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                    value="1" disabled>
                                <label class="custom-control-label"
                                    for="radioOption1-{{ $accExt->IdDescripcionCheckIn }}">Bueno</label>
                            </div>
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption2-{{ $accExt->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input" name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                    value="2" checked disabled>
                                <label class="custom-control-label"
                                    for="radioOption2-{{ $accExt->IdDescripcionCheckIn }}">Regular</label>
                            </div>
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption3-{{ $accExt->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input" name="radioOption{{ $accExt->IdDescripcionCheckIn }}"
                                    value="3" disabled>
                                <label class="custom-control-label"
                                    for="radioOption3-{{ $accExt->IdDescripcionCheckIn }}">Malo</label>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach

        </tbody>
    </table>
    <br>
    @if ($tipoVehiculo == 'vehiculo')
        <table width="100%" class="table table-responsive-lg">
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
                        <td align="center"><input id="{{ $accInt->IdDescripcionCheckIn }}"
                                name="checkAccesoriosInternos[]" value="{{ $accInt->IdDescripcionCheckIn }}"
                                class="chbox" type="checkbox" /></td>
                        <td><label>{{ $accInt->Descripcion }}</label></td>
                        <td align="center">
                            <input id="input{{ $accInt->IdDescripcionCheckIn }}" style="width:80px"
                                name="input{{ $accInt->IdDescripcionCheckIn }}" type="number"
                                value="{{ $accInt->Cantidad }}" disabled />
                        </td>
                        <td align="center">
                            <div class="d-flex justify-content-around" style="width: 380px;">
                                <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                    <input id="radioOption1-{{ $accInt->IdDescripcionCheckIn }}" type="radio"
                                        class="custom-control-input"
                                        name="radioOption{{ $accInt->IdDescripcionCheckIn }}" value="1" disabled>
                                    <label class="custom-control-label"
                                        for="radioOption1-{{ $accInt->IdDescripcionCheckIn }}">Bueno</label>
                                </div>
                                <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                    <input id="radioOption2-{{ $accInt->IdDescripcionCheckIn }}" type="radio"
                                        class="custom-control-input"
                                        name="radioOption{{ $accInt->IdDescripcionCheckIn }}" value="2" checked
                                        disabled>
                                    <label class="custom-control-label"
                                        for="radioOption2-{{ $accInt->IdDescripcionCheckIn }}">Regular</label>
                                </div>
                                <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                    <input id="radioOption3-{{ $accInt->IdDescripcionCheckIn }}" type="radio"
                                        class="custom-control-input"
                                        name="radioOption{{ $accInt->IdDescripcionCheckIn }}" value="3" disabled>
                                    <label class="custom-control-label"
                                        for="radioOption3-{{ $accInt->IdDescripcionCheckIn }}">Malo</label>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <br>
    @endif
    <table width="100%" class="table table-responsive-lg">
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
                    <td align="center"><input id="{{ $herramienta->IdDescripcionCheckIn }}" name="checkHerramientas[]"
                            value="{{ $herramienta->IdDescripcionCheckIn }}" class="chbox" type="checkbox" />
                    </td>
                    <td><label>{{ $herramienta->Descripcion }}</label></td>
                    <td align="center">
                        <input id="input{{ $herramienta->IdDescripcionCheckIn }}" style="width:80px"
                            name="input{{ $herramienta->IdDescripcionCheckIn }}" type="number"
                            value="{{ $herramienta->Cantidad }}" disabled />
                    </td>
                    <td align="center">
                        <div class="d-flex justify-content-around" style="width: 380px;">
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption1-{{ $herramienta->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input"
                                    name="radioOption{{ $herramienta->IdDescripcionCheckIn }}" value="1"
                                    disabled>
                                <label class="custom-control-label"
                                    for="radioOption1-{{ $herramienta->IdDescripcionCheckIn }}">Bueno</label>
                            </div>
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption2-{{ $accExt->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input"
                                    name="radioOption{{ $herramienta->IdDescripcionCheckIn }}" value="2" checked
                                    disabled>
                                <label class="custom-control-label"
                                    for="radioOption2-{{ $herramienta->IdDescripcionCheckIn }}">Regular</label>
                            </div>
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption3-{{ $herramienta->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input"
                                    name="radioOption{{ $herramienta->IdDescripcionCheckIn }}" value="3"
                                    disabled>
                                <label class="custom-control-label"
                                    for="radioOption3-{{ $herramienta->IdDescripcionCheckIn }}">Malo</label>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <br>
    <table width="100%" class="table table-responsive-lg">
        <thead>
            <tr class="text-md-center">
                <th colspan="4"><label class="fs-16">Documentos Vehículo</label></th>
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
                    <td align="center"><input id="{{ $docVehi->IdDescripcionCheckIn }}" name="checkDocumentos[]"
                            value="{{ $docVehi->IdDescripcionCheckIn }}" class="chbox" type="checkbox" />
                    </td>
                    <td><label>{{ $docVehi->Descripcion }}</label></td>
                    <td align="center">
                        <input id="input{{ $docVehi->IdDescripcionCheckIn }}" style="width:80px"
                            name="input{{ $docVehi->IdDescripcionCheckIn }}" type="number"
                            value="{{ $docVehi->Cantidad }}" disabled />
                    </td>
                    <td align="center">
                        <div class="d-flex justify-content-around" style="width: 380px;">
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption1-{{ $docVehi->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input"
                                    name="radioOption{{ $docVehi->IdDescripcionCheckIn }}" value="1" disabled>
                                <label class="custom-control-label"
                                    for="radioOption1-{{ $docVehi->IdDescripcionCheckIn }}">Bueno</label>
                            </div>
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption2-{{ $accExt->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input"
                                    name="radioOption{{ $docVehi->IdDescripcionCheckIn }}" value="2" checked
                                    disabled>
                                <label class="custom-control-label"
                                    for="radioOption2-{{ $docVehi->IdDescripcionCheckIn }}">Regular</label>
                            </div>
                            <div class="custom-control custom-radio d-flex justify-content-center align-self-end">
                                <input id="radioOption3-{{ $docVehi->IdDescripcionCheckIn }}" type="radio"
                                    class="custom-control-input"
                                    name="radioOption{{ $docVehi->IdDescripcionCheckIn }}" value="3" disabled>
                                <label class="custom-control-label"
                                    for="radioOption3-{{ $docVehi->IdDescripcionCheckIn }}">Malo</label>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="row mt-4">
        <div class="col-lg-12 col-md-12">
            <div class="form-group">
                <label>Observación</label>
                <textarea id="observacion" class="form-control" rows="5" name="observacion"></textarea>
            </div>
        </div>

    </div>
</section>
