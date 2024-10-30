@extends('layouts.app')
@section('title', 'Editar Producto')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Producto</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <!-- /.page-title -->
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            {!! Form::open([
                                'url' => 'administracion/almacen/productos/' . $producto->IdArticulo,
                                'method' => 'PUT',
                                'files' => true,
                                'class' => 'form-material',
                            ]) !!}
                            {{ csrf_field() }}
                            <input type="hidden" name="inputCodigoInterno" value="{{ $producto->CodigoInterno }}">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <input class="form-control" id="nombre" placeholder="Descripción" type="text"
                                            name="descripcion" value="{{ $producto->Descripcion }}" maxlength="250">
                                        <label for="descripcion">Descripción</label>
                                        <span class="text-danger font-size">{{ $errors->first('descripcion') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <textarea class="form-control" id="detalle" rows="3" name="detalle" maxlength="200">{{ $producto->Detalle }}</textarea>
                                        <label for="detalle">Detalle</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="m-b-10 form-control select2-hidden-accessible" id="marca"
                                            name="marca" data-placeholder="Marca" data-toggle="select2" tabindex="-1"
                                            aria-hidden="true">
                                            @foreach ($marcas as $marca)
                                                @if ($producto->IdMarca == $marca->IdMarca)
                                                    <option selected value="{{ $marca->IdMarca }}">{{ $marca->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $marca->IdMarca }}">{{ $marca->Nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="text-danger font-size">{{ $errors->first('marca') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="m-b-10 form-control select2-hidden-accessible" id="categoria"
                                            name="categoria" data-placeholder="Categoria" data-toggle="select2"
                                            tabindex="-1" aria-hidden="true">
                                            @foreach ($categorias as $categoria)
                                                @if ($producto->IdCategoria == $categoria->IdCategoria)
                                                    <option selected value="{{ $categoria->IdCategoria }}">
                                                        {{ $categoria->Nombre }}</option>
                                                @else
                                                    <option value="{{ $categoria->IdCategoria }}">{{ $categoria->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <span class="text-danger font-size">{{ $errors->first('categoria') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" disabled name="tipoMoneda">
                                            @foreach ($tipoMonedas as $tipoMoneda)
                                                @if ($tipoMoneda->IdTipoMoneda < 3)
                                                    @if ($producto->IdTipoMoneda == $tipoMoneda->IdTipoMoneda)
                                                        <option selected value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                            {{ $tipoMoneda->Nombre }}</option>
                                                    @else
                                                        <option value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                            {{ $tipoMoneda->Nombre }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="tipoMoneda">Tipo de Moneda</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control text-black" readonly placeholder="Stock" type="text"
                                            name="stock" value="{{ $producto->Stock }}" min="0">
                                        <label for="stock">Stock</label>
                                        <span class="text-danger font-size">{{ $errors->first('stock') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="uniMedida">
                                            @foreach ($unidadMedidas as $uniMedida)
                                                @if ($producto->IdUnidadMedida == $uniMedida->IdUnidadMedida)
                                                    <option selected value="{{ $uniMedida->IdUnidadMedida }}">
                                                        {{ $uniMedida->Nombre }}</option>
                                                @else
                                                    <option value="{{ $uniMedida->IdUnidadMedida }}">
                                                        {{ $uniMedida->Nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="uniMedida">Unidad de Medida</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Precio" type="number" name="precio"
                                            value="{{ $producto->Precio }}" step="any"
                                            onchange="quitarPrecioIgv(this.value);">
                                        <label for="precio">Precio de Venta al público</label>
                                        <span class="text-danger font-size fs-12">Precio de Venta incluido IGV</span>
                                        <span class="text-danger font-size">{{ $errors->first('precio') }}</span>
                                    </div>
                                </div>
                                {{-- @if ($exonerado == 1 && $sucExonerado == 1) --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text bg-success rounded-left"><label
                                                        class="text-white">Precio de Venta
                                                        sin IGV</label></div>
                                            </div>
                                            <input id="preciosigv" class="form-control"
                                                value="{{ number_format($producto->Precio / 1.18, 2) }}" type="text"
                                                readonly>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Costo" type="number" name="costo"
                                            value="{{ $producto->Costo }}" step="any"
                                            onchange="quitarCostoIgv(this.value);">
                                        <label for="costo">Precio de Costo</label>
                                        <span class="text-danger font-size fs-12">Precio de Costo incluido IGV</span>
                                        <span class="text-danger font-size">{{ $errors->first('costo') }}</span>
                                    </div>
                                </div>
                                {{-- @if ($exonerado == 1 && $sucExonerado == 1) --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text bg-success rounded-left"><label
                                                        class="text-white">Precio de Costo
                                                        sin IGV</label></div>
                                            </div>
                                            <input id="costosigv" class="form-control" type="text"
                                                value="{{ number_format($producto->Costo / 1.18, 2) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
                                @if ($exonerado == 1 && $sucExonerado == 1)
                                    <div class="col-md-4">
                                        <select id="tipoOperacion" class="form-control" name="tipoOperacion">
                                            @if ($producto->TipoOperacion == 1)
                                                <option selected value="1">Crear con IGV</option>
                                                <option value="2">Crear sin IGV</option>
                                            @else
                                                <option value="1">Crear con IGV</option>
                                                <option selected value="2">Crear sin IGV</option>
                                            @endif
                                        </select>
                                    </div>
                                @else
                                    <input id="tipoOperacion" type="text" name="tipoOperacion" value="1" hidden>
                                @endif
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="ubicacion"
                                            value="{{ $producto->Ubicacion }}" maxlength="=50">
                                        <label for="ubicacion">Ubicación</label>
                                    </div>
                                </div>
                                @if ($subniveles->contains('IdSubNivel', 46) && $producto->IdTipoMoneda == 2)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" readonly
                                                value="{{ $producto->ValorTipoCambio }}">
                                            <label for="ubicacion">Tipo de Cambio Promedio</label>
                                        </div>
                                    </div>
                                @endif
                            </div>

                            <div class="widget-body">
                                <h5 class="box-title mr-b-0">Ventas por Mayor</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="number" name="ventaMayor1"
                                                value="{{ $producto->VentaMayor1 }}" min="0">
                                            <label for="ventaMayor1">Mayor o igual que (unidades)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="precioDescuento1"
                                                value="{{ $producto->PrecioDescuento1 }}">
                                            <label for="descuento1">Nuevo Precio ( en soles )</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="uniMedidaMayor" class="form-control" name="uniMedidaMayor">
                                                @foreach ($undiadesPorMayor as $uniPorMayor)
                                                    @if ($producto->IdTipoUnidad == $uniPorMayor->IdUnidadMedida)
                                                        <option selected value="{{ $uniPorMayor->IdUnidadMedida }}">
                                                            {{ $uniPorMayor->Nombre }}</option>
                                                    @else
                                                        <option value="{{ $uniPorMayor->IdUnidadMedida }}">
                                                            {{ $uniPorMayor->Nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <label for="uniMedidaMayor">Tipo de Venta</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="cantidadTipo"
                                                value="{{ $producto->CantidadTipo }}" min="0">
                                            <label for="cantidadTipo">Cantidad por tipo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="precioTipo"
                                                value="{{ $producto->PrecioTipo }}">
                                            <label for="descuentoTipo">Nuevo Precio (en Soles)
                                        </div>
                                    </div>
                                    <div hidden>
                                        <div class="form-group">
                                            <input id="nombreTipo" class="form-control" type="text" name="nombreTipo"
                                                value="{{ $producto->NombreTipo }}">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    @if ($producto->Imagen !== null && $producto->Imagen != config('variablesGlobales.urlImagenNotFound'))
                                        <div class="custom-control custom-checkbox mb-3">
                                            <input type="checkbox" class="custom-control-input" id="customCheck1"
                                                name="checkEliminarImagenAnterior" value="chekeado">
                                            <label class="custom-control-label fs-14 text-secondary"
                                                for="customCheck1">Eliminar
                                                Imagen</label>
                                        </div>
                                    @endif
                                    <div class="form-group">
                                        <div class="fileUpload btnCambiarFotoPerfil">
                                            <i class="list-icon material-icons">image</i><span class="hide-menu">Cargar
                                                Nueva Imagen</span>
                                            <input id="archivo" class="upload btn" type="file" name="imagen"
                                                accept=".png, .jpg, .jpeg, .gif" />
                                        </div>
                                        <span class="text-danger fs-12">Peso máx. 300 Kb</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if (str_contains($producto->Imagen, config('variablesGlobales.urlDominioAmazonS3')) ||
                                                str_contains($producto->Imagen, config('variablesGlobales.urlImagenNotFound')))
                                            <img id="imgPrevia" src="{{ $producto->Imagen }}" alt="Vista de Imagen"
                                                width="100%" />
                                        @else
                                            <img id="imgPrevia"
                                                src="{{ config('variablesGlobales.urlDominioAmazonS3') . $producto->Imagen }}"
                                                alt="Vista de Imagen" width="100%" />
                                        @endif
                                        <input type="hidden" name="inputUrlImagenAnterior"
                                            value="{{ $producto->Imagen }}">
                                    </div>
                                    <span class="text-danger font-size">{{ $errors->first('imagen') }}</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input class="form-control" id="codBarra" placeholder="Código Barra"
                                            type="text" name="codBarra" value="{{ $producto->Codigo }}">
                                        <label for="codBarra">Código Barra</label>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div id="print">
                                            <svg id="barcode"></svg>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                                <a href="../../productos"><button class="btn btn-outline-default"
                                        type="button">Cancelar</button></a>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('assets/js/administracion/productos.js') }}"></script>
    <script src="{{ asset('assets/js/general.js') }}"></script>
@stop
