@if (count($datosVehiculos) >= 1)
    <section>
        <div class="col-12 mt-5">
            <div id="graficoEstadosAtenciones">
            </div>
        </div>
    </section>
    <section class="jumbotron bg-jumbotron--white">
        <table id="table" class="table table-responsive-sm" style="width:100%">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Fecha</th>
                    <th scope="col">Cliente</th>
                    <th scope="col">Placa</th>
                    <th scope="col">RUC/DNI</th>
                    <th scope="col">Mecánico</th>
                    <th scope="col">Tipo Atención</th>
                    <th scope="col" class="text-center">Estado</th>
                    <th scope="col" class="text-center">Tiempo Restante para Entrega</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($datosVehiculos as $vehiculo)
                    <tr>
                        <td>{{ $vehiculo->FechaCreacion }}</td>
                        <td>{{ $vehiculo->RazonSocial }}</td>
                        <td>{{ $vehiculo->PlacaVehiculo }}</td>
                        <td>{{ $vehiculo->NumeroDocumento }}</td>
                        <td>{{ $vehiculo->NombreOperario }}</td>
                        <td>{{ $vehiculo->TipoAtencion }}</td>
                        <td class="text-center">
                            <span
                                class="{{ $datosEstado[$vehiculo->IdEstadoCotizacion]['claseCss'] }}">{{ $datosEstado[$vehiculo->IdEstadoCotizacion]['estado'] }}</span>
                        </td>
                        <td class="text-center">{{ $vehiculo->TiempoRestanteAtencion }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </section>
@else
    <br><br><br>
    <div class="text-center fs-20 text-danger">No Hay resultados..</div>
@endif
