<table id="table" class="table table-responsive-sm" style="width:100%">
<thead>
<tr class="bg-primary">
    <th scope="col" align="center" style="font-weight:bold">Descripción</th>    
    <th scope="col" align="center" style="font-weight:bold">Marca</th> 
    <th scope="col" align="center" style="font-weight:bold">Categoría</th>    
    <th scope="col" align="center" style="font-weight:bold">Código</th>
    <th scope="col" align="center" style="font-weight:bold">Ubicación</th>
    <th scope="col" align="center" style="font-weight:bold">FechaCreacionProducto</th>
    <th scope="col" align="center" style="font-weight:bold">UltimaFechaCompra</th>
    <th scope="col" align="center" style="font-weight:bold">UltimaFechaVenta</th>
    <th scope="col" align="center" style="font-weight:bold">Unidad de Medida</th>    
    <th scope="col" align="center" style="font-weight:bold">Stock</th>
    <th scope="col" align="center" style="font-weight:bold">Tipo Moneda</th>
    <th scope="col" align="center" style="font-weight:bold">Costo</th>
    <th scope="col" align="center" style="font-weight:bold">Precio</th>

</tr>
</thead>
<tbody>
    @foreach($reporteStock as $stock)
    <tr>
        <td width="50" align="center">{{$stock->Descripcion}}</td>
        <td width="20" align="center">{{$stock->Marca}}</td>
        <td width="20" align="center">{{$stock->Categoria}}</td>
        <td width="20" align="center">{{$stock->Codigo}}</td>
        <td width="30" align="center">{{$stock->Ubicacion}}</td>
        <td width="25" align="center">{{$stock->fechaCreacionArticulo}}</td>
        <td width="25" align="center">{{$stock->ultimaFechaCompra}}</td>
        <td width="25" align="center">{{$stock->ultimaFechaVenta}}</td>
        <td width="20" align="center">{{$stock->UnidadMedida}}</td>
        <td width="15" align="center">{{$stock->Stock}}</td>
        <td width="15" align="center">{{$stock->TipoMoneda}}</td>
        <td width="20" align="center">{{$stock->Costo}}</td>
        <td width="20" align="center">{{$stock->Precio}}</td>

    </tr>
    @endforeach
</tbody>
</table>