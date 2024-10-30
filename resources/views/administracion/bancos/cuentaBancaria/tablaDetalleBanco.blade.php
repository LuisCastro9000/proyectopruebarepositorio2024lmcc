<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            {{-- <th scope="col">Id</th> --}}
            <th scope="col">Fecha Trans.</th>
            <th scope="col">Nro Operación</th>
            <th scope="col">Detalle</th>
            <th scope="col">Tipo Movimiento</th>
            <th scope="col">Ingreso</th>
            <th scope="col">Salida</th>
            {{-- <th scope="col">Monto Actual</th> --}}
            <th scope="col">Monto Actual</th>
            <th scope="col">Sucursal</th>
            <th scope="col">Editar</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($detallesCuentaCorriente as $detalle)
            <tr>
                {{-- <td>{{ $detalle->IdBancoDetalles }}</td> --}}
                <td>{{ $detalle->FechaPago }}</td>
                <td>{{ $detalle->NumeroOperacion }}</td>
                <td>{{ $detalle->Detalle }}</td>
                <td>{{ $detalle->TipoMovimiento }}</td>
                <td style="color:green;">+
                    {{ number_format($detalle->Entrada, 2, '.', ',') }}
                </td>
                <td style="color: red;">- {{ number_format($detalle->Salida, 2, '.', ',') }}
                </td>
                {{-- <td style="color: #1a1a1a;">{{ $detalle->MontoActual }}</td> --}}
                <td>{{ number_format($detalle->SaldoActualCalculado, 2, '.', ',') }}</td>
                <td>{{ $detalle->NombreSucursal }}</td>
                @if ($detalle->TipoMovimiento == 'Registro Salida' || $detalle->TipoMovimiento == 'Registro Ingreso')
                    <td class="text-center"><button class="btn btn-primary px-1 py-1 btnEditarConClaveSupervisor"
                            data-id-registro="{{ $detalle->IdBancoDetalles }}"><i
                                class="list-icon material-icons">edit</i></button></td>
                @else
                    <td></td>
                @endif
            </tr>
        @endforeach
    </tbody>
</table>
