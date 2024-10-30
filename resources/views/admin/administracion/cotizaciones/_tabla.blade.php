<div class="table-responsive">
    <table id="tablaCotizaciones" class="table table-centered mb-0">
        <thead class="table-light">
            <tr>
                <th>FechaCreación</th>
                <th>Sucursal</th>
                <th>Cotización</th>
                <th>Tipo-Cotización</th>
                <th>Estado Actual</th>
                <th style="min-width: 150px;max-with:60px">Seleccione Estado</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($cotizaciones as $cotizacion)
                <tr>
                    <td>{{ $cotizacion->FechaCreacion }}</td>
                    <td>{{ $cotizacion->NombreSucursal }}</td>
                    <td>{{ $cotizacion->Serie . '-' . $cotizacion->Numero }}</td>
                    <td>{{ $cotizacion->TipoCotizacion == 1 ? 'Comercial' : 'vehicular' }}</td>
                    <td>
                        <span
                            class="{{ $datosEstados[$cotizacion->IdEstadoCotizacion]['claseCss'] }} font-16 spanEstado">{{ $datosEstados[$cotizacion->IdEstadoCotizacion]['nombre'] }}</span>
                    </td>
                    <td>
                        @if ($cotizacion->TipoCotizacion == 1)
                            <select id="selectEstados" class="form-select" id="example-select"
                                data-id="{{ $cotizacion->IdCotizacion }}" data-sucursal="{{ $cotizacion->IdSucursal }}"
                                data-correlativo="{{ $cotizacion->Serie . '-' . $cotizacion->Numero }}"
                                data-estado-cotizacion="{{ $cotizacion->IdEstadoCotizacion }}">
                                <option value="1">Abierto</option>
                                <option value="4">Cerrado</option>
                                <option value="6">Baja</option>
                            </select>
                        @else
                            <select id="selectEstados" class="form-select" id="example-select"
                                data-id="{{ $cotizacion->IdCotizacion }}"
                                data-sucursal="{{ $cotizacion->IdSucursal }}"
                                data-correlativo="{{ $cotizacion->Serie . '-' . $cotizacion->Numero }}"
                                data-estado-cotizacion="{{ $cotizacion->IdEstadoCotizacion }}">
                                <option value="1">Abierto</option>
                                <option value="2">Proceso</option>
                                <option value="3">Finalizado</option>
                                <option value="4">Cerrado</option>
                                <option value="6">Baja</option>
                            </select>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
