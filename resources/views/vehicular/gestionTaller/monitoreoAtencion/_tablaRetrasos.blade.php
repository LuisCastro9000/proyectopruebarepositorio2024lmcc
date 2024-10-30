@if ($atencionesRetrasadasTabla->isNotEmpty())
    <article class="text-right mb-3">
        <a target="_blank" href="{{ route('monitoreo-atencion.exportar-excel', ['opcion' => 'excelTiemposRetrasos']) }}">
            <span class="btn bg-excel ripple">
                <i class="list-icon material-icons fs-20">explicit</i>XCEL
            </span>
        </a>
    </article>
    <br><br>
    <article id="graficoAtencionesConRetraso"></article>
    <br><br>
    <article>
        <table id="tableRetrasos" class="table table-responsive-sm" style="width:100%">
            <thead>
                <tr class="bg-primary">
                    <th scope="col">Placa</th>
                    <th scope="col" class="text-center">Cliente</th>
                    <th scope="col" class="text-center">Fecha Inicio-Atención</th>
                    <th scope="col" class="text-center">Fecha Tentativa Final-Atencion</th>
                    <th scope="col" class="text-center">Tiempo Retraso Entrega</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($atencionesRetrasadasTabla as $atencion)
                    <tr>
                        <td>{{ $atencion->PlacaVehiculo }}</td>
                        <td class="text-center">{{ $atencion->RazonSocial }}</td>
                        <td class="text-center">{{ $atencion->FechaRegistro }}</td>
                        <td class="text-center">{{ $atencion->FechaFinAtencion }}</td>
                        <td class="text-center"><span
                                class="badge-autoncontrol badge-autoncontrol__danger">{{ $atencion->TiempoRetraso . ' ' . 'Dias' }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </article>
@else
    <p>NO hay Datos</p>
@endif
