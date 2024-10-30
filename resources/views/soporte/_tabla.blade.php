<div class="col datosTabla">
    <table id="table" class="table table-bordered table-responsive-sm" style="width: 100%">
        <thead>
            <tr class="bg-primary">
                <th scope="col" class="text-center">FechaEmisón</th>
                <th scope="col" class="text-center">Codigo Factura</th>
                <th scope="col" class="text-center">Total</th>
                <th scope="col" class="text-center">Descargar Archivo</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($facturas as $factura)
                <tr>
                    <td class="text-center"><span class="badge badge-warning fs-14">{{ $factura->FechaCreacion }}</span>
                    </td>
                    <td class="text-center">{{ $factura->CorrelativoFactura }}</td>
                    <td class="text-center">{{ $factura->TotalFactura }}</td>
                    <td class="text-center">
                        <a href="{{ route('descargarFacturaClienteErp', [$factura->IdVentas, $factura->CodigoClienteFacturador, 'PDF']) }}"
                            target="_blank" title="Descargar PDF"><img src="{{ asset('assets/img/iconoPdf.png') }}"
                                alt="Pdf" height="30px"></a>
                        <a href="{{ route('descargarFacturaClienteErp', [$factura->IdVentas, $factura->CodigoClienteFacturador, 'Xml']) }}"
                            title="Descargar XML" class="px-2"><img src="{{ asset('assets/img/iconoXml.png') }}"
                                alt="Xml" height="30px"></a>
                        <a href="{{ route('descargarFacturaClienteErp', [$factura->IdVentas, $factura->CodigoClienteFacturador, 'Cdr']) }}"
                            title="Descargar CDR"><img src="{{ asset('assets/img/iconoCdr.png') }}" alt="Cdr"
                                height="30px"></a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
