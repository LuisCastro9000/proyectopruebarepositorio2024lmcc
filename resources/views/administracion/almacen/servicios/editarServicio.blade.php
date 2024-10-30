@extends('layouts.app')
@section('title', 'Editar Servicio')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Servicio</h6>
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
                                'url' => 'administracion/almacen/servicios/' . $servicio->IdArticulo,
                                'method' => 'PUT',
                                'files' => true,
                                'class' => 'form-material',
                            ]) !!}
                            {{ csrf_field() }}
                            <input type="hidden" name="inputCodigoInterno" value="{{ $servicio->CodigoInterno }}">
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Descripción" type="text"
                                            name="descripcion" value="{{ $servicio->Descripcion }}">
                                        <label for="descripcion">Descripción</label>
                                        <span class="text-danger font-size">{{ $errors->first('descripcion') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="tipoMoneda">Tipo de Moneda</label>
                                        @if ($servicio->IdTipoMoneda == 1)
                                            <input disabled class="form-control" value="Soles">
                                        @else
                                            <input disabled class="form-control" value="Dólares">
                                        @endif
                                        <input hidden name="tipoMoneda" value="{{ $servicio->IdTipoMoneda }}">
                                        <!--<select class="form-control" name="tipoMoneda" disabled>
                                                                                                                    @foreach ($tipoMonedas as $tipoMoneda)
    @if ($tipoMoneda->IdTipoMoneda < 3)
    @if ($servicio->IdTipoMoneda == $tipoMoneda->IdTipoMoneda)
    <option selected value="{{ $tipoMoneda->IdTipoMoneda }}">{{ $tipoMoneda->Nombre }}</option>
@else
    <option value="{{ $tipoMoneda->IdTipoMoneda }}">{{ $tipoMoneda->Nombre }}</option>
    @endif
    @endif
    @endforeach
                                                                                                                </select>
                                                                                                                <label for="tipoMoneda">Tipo de Moneda</label>-->
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Precio" type="text" name="precio"
                                            value="{{ $servicio->Precio }}" onchange="quitarPrecioIgv(this.value);">
                                        <label for="precio">Precio de Venta</label>
                                        <span class="text-danger font-size fs-12">Precio de Vneta incluido IGV</span>
                                        <span class="text-danger font-size">{{ $errors->first('precio') }}</span>
                                    </div>
                                </div>
                                {{-- @if ($exonerado == 1 && $sucExonerado == 1) --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-append">
                                                <div class="input-group-text bg-success rounded-left"><label
                                                        class="text-white">Precio de Venta sin
                                                        IGV</label></div>
                                            </div>
                                            <input id="preciosigv" class="form-control" name="precioSinIgv" type="text"
                                                value="{{ number_format($servicio->Precio / 1.18, 2) }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Costo" type="text" name="costo"
                                            value="{{ $costo }}" onchange="quitarCostoIgv(this.value);">
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
                                                        class="text-white">Precio de Costo sin
                                                        IGV</label></div>
                                            </div>
                                            <input id="costosigv" class="form-control" name="costoSinIgv" type="text"
                                                value="{{ $costoSinIgv }}" readonly>
                                        </div>
                                    </div>
                                </div>
                                {{-- @endif --}}
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
                                        <span class="text-danger fs-12">Peso máx. 300 Kb</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        @if (str_contains($servicio->Imagen, config('variablesGlobales.urlDominioAmazonS3')) ||
                                                str_contains($servicio->Imagen, config('variablesGlobales.urlImagenNotFound')))
                                            <img id="imgPrevia" src="{{ $servicio->Imagen }}" alt="Vista de Imagen"
                                                width="100%" />
                                        @else
                                            <img id="imgPrevia"
                                                src="{{ config('variablesGlobales.urlDominioAmazonS3') . $servicio->Imagen }}"
                                                alt="Vista de Imagen" width="100%" />
                                        @endif
                                        <input type="hidden" name="inputUrlImagenAnterior"
                                            value="{{ $servicio->Imagen }}">
                                    </div>
                                    <span class="text-danger font-size">{{ $errors->first('imagen') }}</span>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <input class="form-control" id="codBarra" placeholder="Código Barra"
                                            type="text" name="codBarra" value="{{ $servicio->Codigo }}">
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

                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                                <a href="../../servicios"><button class="btn btn-outline-default"
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
    <script src="{{ asset('assets/js/administracion/servicios.js') }}"></script>
    <script src="{{ asset('assets/js/general.js') }}"></script>
@stop
