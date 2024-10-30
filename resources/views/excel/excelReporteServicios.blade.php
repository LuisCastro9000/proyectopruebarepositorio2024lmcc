 <table id="table" class="table table-responsive-sm" style="width:100%">
     <thead>
         <tr class="bg-primary">
             <th align="center" scope="col" style="font-weight:bold">NOMBRE</th>
             <th align="center" scope="col" style="font-weight:bold">PRECIO DE VENTA</th>
             <th align="center" scope="col" style="font-weight:bold">PRECIO DE COSTO</th>
             <th align="center" scope="col" style="font-weight:bold">TIPO MONEDA</th>
             <th align="center" scope="col" style="font-weight:bold">CODIGO BARRA</th>
         </tr>
     </thead>
     <tbody>
         @foreach ($servicios as $servicio)
             <tr>
                 <td width="60" align="center">{{ $servicio->Descripcion }}</td>
                 <td width="20" align="center">{{ $servicio->Precio }}</td>
                 <td width="25" align="center">{{ $servicio->Costo }}</td>
                 <td width="20" align="center">{{ $servicio->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                 <td width="20" align="center">{{ $servicio->Codigo }}</td>
             </tr>
         @endforeach
     </tbody>
 </table>
