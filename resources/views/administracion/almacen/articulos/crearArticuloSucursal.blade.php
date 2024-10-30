@extends('layouts.app')
@section('title', 'Crear Producto')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Crear Producto</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
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
                                'url' => '/administracion/almacen/guardar-sucursal',
                                'method' => 'POST',
                                'files' => true,
                                'class' => 'form-material',
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" value="{{ $datos->Descripcion }}" id="nombre"
                                            readonly placeholder="Descripción" type="text" name="descripcion">
                                        <label for="descripcion">Descripción</label>
                                        <span class="text-danger font-size">{{ $errors->first('descripcion') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="marca">
                                            @foreach ($marcas as $marca)
                                                <option value="{{ $marca->IdMarca }}">{{ $marca->Nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label for="marca">Marca</label>
                                        <span class="text-danger font-size">{{ $errors->first('marca') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="categoria">
                                            @foreach ($categorias as $categoria)
                                                <option value="{{ $categoria->IdCategoria }}">{{ $categoria->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="categoria">Categoría</label>
                                        <span class="text-danger font-size">{{ $errors->first('categoria') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Stock" type="number" name="stock"
                                            min="0">
                                        <label for="stock">Stock</label>
                                        <span class="text-danger font-size">{{ $errors->first('stock') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Precio" type="text" name="precio">
                                        <label for="precio">Precio (S/)</label>
                                        <span class="text-danger font-size">{{ $errors->first('precio') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="uniMedida">
                                            @foreach ($unidadMedidas as $uniMedida)
                                                <option value="{{ $uniMedida->IdUnidadMedida }}">{{ $uniMedida->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="uniMedida">Unidad de Medida</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="exonerado">
                                            <option value="1">18</option>
                                            <option value="2">0</option>
                                        </select>
                                        <label for="exonerado">Exonerado de IGV (%)</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Costo" type="text" name="costo">
                                        <label for="costo">Costo (S/)</label>
                                        <span class="text-danger font-size">{{ $errors->first('costo') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="widget-body">
                                <h5 class="box-title mr-b-0">Ventas por Mayor</h5>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="number" name="ventaMayor1" value="0"
                                                min="0">
                                            <label for="ventaMayor1">Mayor o igual que (unidades)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="precioDescuento1"
                                                value="">
                                            <label for="descuento1">Nuevo Precio ( en soles )</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!--<div class="form-group">
                                                            <input class="form-control" type="number" name="descuento1" value="" min="0">
                                                            <label for="descuento1">Descuento (%)</label>
                                                        </div> -->
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="number" name="ventaMayor2" value="0"
                                                min="0">
                                            <label for="ventaMayor2">Mayor o igual que (unidades)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="precioDescuento2"
                                                value="">
                                            <label for="descuento2">Nuevo Precio ( en soles )</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!--     <div class="form-group">
                                                            <input class="form-control" type="number" name="descuento2" value="" min="0">
                                                            <label for="descuento2">Descuento (%)</label>
                                                        </div> -->
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="number" name="ventaMayor3" value="0"
                                                min="0">
                                            <label for="ventaMayor3">Mayor o igual que (unidades)</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="precioDescuento3"
                                                value="">
                                            <label for="descuento3">Nuevo Precio ( en Soles )</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <!-- <div class="form-group">
                                                            <input class="form-control" type="number" name="descuento3" value="" min="0">
                                                            <label for="descuento3">Descuento (%)</label>
                                                        </div> -->
                                    </div>
                                </div>
                                <hr>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <select id="uniMedidaMayor" class="form-control" name="uniMedidaMayor">
                                                @foreach ($undiadesPorMayor as $uniPorMayor)
                                                    <option value="{{ $uniPorMayor->IdUnidadMedida }}">
                                                        {{ $uniPorMayor->Nombre }}</option>
                                                @endforeach
                                            </select>
                                            <label for="uniMedidaMayor">Tipo de Venta</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="cantidadTipo"
                                                value="0" min="0">
                                            <label for="cantidadTipo">Cantidad por tipo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="precioTipo" value="">
                                            <label for="descuentoTipo">Nuevo Precio (en Soles)
                                                <!--<input class="form-control" type="text" name="descuentoTipo" value="0" min="0">
                                                            <label for="descuentoTipo">Descuento por tipo(%)</label> -->
                                        </div>
                                    </div>
                                    <div hidden>
                                        <div class="form-group">
                                            <input id="nombreTipo" class="form-control" type="text" name="nombreTipo"
                                                value="Caja">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="row align-items-center">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <div class="fileUpload btnCambiarFotoPerfil">
                                            <i class="list-icon material-icons">image</i><span class="hide-menu">Cargar
                                                Imagen</span>
                                            <input id="archivo" class="upload btn" type="file" name="imagen"
                                                accept=".png, .jpg, .jpeg, .gif" />
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <img id="imgPrevia" src="" alt="Vista de Imagen" width="100%" />
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input class="form-control" id="codBarra"
                                            value="{{ $datos->Codigo ? $datos->Codigo : $datos->CodigoInterno }}"
                                            placeholder="Código Barra" type="text" name="codBarra">
                                        <label for="codBarra">Código Barra</label>
                                    </div>
                                </div>
                                <div class="col-md-1">
                                    <div class="form-group">
                                        <button class="btn btn-info generar" type="button">Generar</button>
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
                            <input type="hidden" value="{{ $datos->CodigoInterno }}" name="matriz" />
                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-primary" type="submit">Crear</button>
                                <a href="../productos"><button class="btn btn-outline-default"
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
    <script>
        $(function() {
            $('#archivo').change(function(e) {
                addImage(e);
            });

            function addImage(e) {
                var file = e.target.files[0],
                    imageType = /image.*/;

                if (!file.type.match(imageType))
                    return;

                var reader = new FileReader();
                reader.onload = fileOnload;
                reader.readAsDataURL(file);
            }

            function fileOnload(e) {
                var result = e.target.result;
                $('#imgPrevia').attr("src", result);
            }

            $(document).on('click', '.generar', function() {
                var codigo = $("#codBarra").val();
                JsBarcode("#barcode", codigo);
                $("#print").show();
            });

        });
    </script>
@stop
