 <table id="table" class="table table-responsive-sm" style="width:100%">
     <thead>
         <tr class="bg-primary">
             <th align="center" scope="col" style="font-weight:bold">NOMBRE</th>
             <th align="center" scope="col" style="font-weight:bold">UBICACIÓN</th>
             <th align="center" scope="col" style="font-weight:bold">STOCK</th>
             <th align="center" scope="col" style="font-weight:bold">PRECIO UNIDAD</th>
             <th align="center" scope="col" style="font-weight:bold">COSTO UNIDAD</th>
             <th align="center" scope="col" style="font-weight:bold">UNIDAD DE MEDIDA</th>
             <th align="center" scope="col" style="font-weight:bold">PRECIO POR MAYOR</th>
             <th align="center" scope="col" style="font-weight:bold">CANTIDAD POR MAYOR</th>
             <th align="center" scope="col" style="font-weight:bold">CODIGO BARRA</th>
             <th align="center" scope="col" style="font-weight:bold">CATEGORIA</th>
             <th align="center" scope="col" style="font-weight:bold">MARCA</th>
             <th align="center" scope="col" style="font-weight:bold">TIPO MONEDA</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($productos as $producto)
             <tr>
                 <td width="60" align="center">{{ $producto->Descripcion }}</td>
                 <td width="30" align="center">{{ $producto->Ubicacion }}</td>
                 <td width="15" align="center">{{ $producto->Stock }}</td>
                 <td width="20" align="center">{{ $producto->Precio }}</td>
                 <td width="25" align="center">{{ $producto->Costo }}</td>
                 <td width="25" align="center">{{ $producto->UM }}</td>
                 <td width="30" align="center"> {{ $producto->PrecioDescuento1 }}</td>
                 <td width="30" align="center">{{ $producto->VentaMayor1 }} </td>
                 <td width="20" align="center">{{ $producto->Codigo }}</td>
                 <td width="20" align="center">{{ $producto->Categoria }}</td>
                 <td width="20" align="center">{{ $producto->Marca }}</td>
                 <td width="20" align="center">{{ $producto->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
             </tr>
         @endforeach
     </tbody>
 </table>
