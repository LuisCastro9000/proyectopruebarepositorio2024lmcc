<table id="table" class="table table-responsive-sm" style="width:100%">
<thead>
    <tr class="bg-primary">
        <th scope="col" align="center">Fecha Registro</th>
        <th scope="col" align="center">Usuario</th>    
        <th scope="col" align="center">Tipo</th>   
        <th scope="col" align="center">Tipo Moneda</th>  
        <th scope="col" align="center">Monto</th>
        <th scope="col" align="center">Descripción</th>
    </tr>
</thead>
    <tbody>
        @foreach($ingresosEgresos as $ingresoegreso)
        <tr>
            <td width="25" align="center">{{$ingresoegreso->Fecha}}</td>
            <td width="40"  align="center">{{$ingresoegreso->Nombre}}</td>
            <td width="20"  align="center">@if($ingresoegreso->Tipo == 'I')
                    Ingreso
                @else
                    Egreso
                @endif
            </td>
            <td width="20" align="center">@if($ingresoegreso->IdTipoMoneda == 1)
                    Soles
                @else
                    Dólares
                @endif
            </td>
            <td width="20" align="center">{{$ingresoegreso->Monto}}</td>
            <td width="60"  align="center">{{$ingresoegreso->Descripcion}}</td>
        </tr>
        @endforeach
    </tbody>
    </table>