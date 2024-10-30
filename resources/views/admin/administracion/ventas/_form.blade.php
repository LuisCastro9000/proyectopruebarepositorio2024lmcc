<div class="row">
    <div class="col-12 col-md-2">
        <div>
            <label for="inputSerie" class="form-label">Serie</label>
            <input type="text" id="inputSerie" class="form-control validacionOnInput" data-toggle="validarOnInput"
                data-alfanumerico="true" data-convertir-mayusculas="true">
            <span class="text-danger error d-none">Ingrese la serie</span>
        </div>
    </div>
    <div class="col-12 col-md-2">
        <div>
            <label for="inputNumero" class="form-label">Número</label>
            <input class="form-control" id="inputNumero" type="text" name="number" data-toggle="validarOnInput"
                data-numero-entero="true">
            <span class="text-danger error d-none">Ingrese el número</span>
        </div>
    </div>
    <div class="col-12 col-md-8">
        <label for="selectSucursal" class="form-label">Seleccione Sucursal</label>
        <select class="form-control select2 selectSucursal" data-toggle="select2">
            <option value="0">-</option>
            @foreach ($sucursales as $sucursal)
                <option value="{{ $sucursal->IdSucursal }}">
                    {{ $sucursal->NombreEmpresa . '  ---  ' . $sucursal->RucEmpresa . '  ---  ' . $sucursal->NombreSucursal }}
                </option>
            @endforeach
        </select>
        <span class="text-danger errorSucursal error d-none">Seleccione la sucursal</span>
    </div>
</div>
