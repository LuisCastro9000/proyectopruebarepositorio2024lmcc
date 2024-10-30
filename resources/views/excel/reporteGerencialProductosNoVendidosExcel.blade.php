<table id="table" class="table table-responsive-sm" style="width:100%">
     <thead>
         <tr class="bg-primary">
             <th align="center" scope="col" style="font-weight:bold">Descripcion</th>
             <th align="center" scope="col" style="font-weight:bold">Precio</th>
             <th align="center" scope="col" style="font-weight:bold">Stock</th>
             <th align="center" scope="col" style="font-weight:bold">Codigo Barra</th>
             {{-- <th align="center" scope="col" style="font-weight:bold">Tipo de Pago</th> --}}
             <th align="center" scope="col" style="font-weight:bold">Ubicacion</th>
         </tr>
     </thead>
 
 
     <tbody>
         @foreach ($reporteProductosNoVendidos as $reporte)
             <tr>
                 <td width="50" align="center">{{ $reporte->Descripcion }}</td>
                 <td width="20" align="center">{{ $reporte->Precio }}</td>
                 <td width="20" align="center">{{ $reporte->Stock }}</td>
                 <td width="20" align="center">{{ $reporte->Codigo }}</td>
                 <td width="20" align="center">{{ $reporte->Ubicacion }}</td>
             </tr>
         @endforeach
     </tbody>
 </table>