<table id="table" class="table table-responsive-sm" style="width:100%">
    <thead>
        <tr class="bg-primary">
            <th scope="col" align="center">Fecha de Traspaso</th>    
            <th scope="col" align="center">Usuario</th> 
            <th scope="col" align="center">Producto</th>
            <th scope="col" align="center">Cantidad Entrada</th>
            <th scope="col" align="center">Cantidad Salida</th> 
        </tr>
    </thead>
        <tbody>
            @foreach($traspasos as $traspaso)
            <tr>
                <td width="20" align="center">{{$traspaso->fechaTraspaso}}</td>
                <td width="50" align="center">{{$traspaso->Nombre}}</td>
                <td width="60" align="center">{{$traspaso->Producto}}</td>
                @if($tipoTraspaso == 1)
                <td width="20" align="center">-</td>
                <td width="20" align="center">{{$traspaso->Cantidad}}</td>
                @else
                <td width="20" align="center">{{$traspaso->Cantidad}}</td>
                <td width="20" align="center">-</td>
                @endif
            </tr>
            @endforeach
        </tbody>
</table>