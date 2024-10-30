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
          @if (session('error'))
              <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('error') }}
              </div>
          @endif
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
                                  'url' => '/administracion/almacen/productos',
                                  'method' => 'POST',
                                  'files' => true,
                                  'class' => 'form-material',
                              ]) !!}
                              {{ csrf_field() }}
                              <div class="row">
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <input class="form-control" id="nombre" placeholder="Descripción"
                                              type="text" name="descripcion" maxlength="250">
                                          <label for="descripcion">Descripción</label>
                                          <span class="text-danger font-size">{{ $errors->first('descripcion') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-12">
                                      <div class="form-group">
                                          <textarea class="form-control" id="detalle" rows="3" name="detalle" maxlength="200"></textarea>
                                          <label for="detalle">Detalle</label>
                                          <span class="text-danger font-size">{{ $errors->first('detalle') }}</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="marca">Marca</label>
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="marca"
                                              name="marca" data-placeholder="Marca" data-toggle="select2" tabindex="-1"
                                              aria-hidden="true">
                                              <option value="">-</option>
                                              @foreach ($marcas as $marca)
                                                  <option value="{{ $marca->IdMarca }}">{{ $marca->Nombre }}</option>
                                              @endforeach
                                          </select>
                                          <span class="text-danger font-size">{{ $errors->first('marca') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="categoria">Categoría</label>
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="categoria"
                                              name="categoria" data-placeholder="Categoria" data-toggle="select2"
                                              tabindex="-1" aria-hidden="true">
                                              <option value="">-</option>
                                              @foreach ($categorias as $categoria)
                                                  <option value="{{ $categoria->IdCategoria }}">{{ $categoria->Nombre }}
                                                  </option>
                                              @endforeach
                                          </select>
                                          <span class="text-danger font-size">{{ $errors->first('categoria') }}</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          @if ($subniveles->contains('IdSubNivel', 46))
                                              <select id="tipoMoneda" class="form-control" name="tipoMoneda">
                                                  @foreach ($tipoMonedas as $tipoMoneda)
                                                      @if ($tipoMoneda->IdTipoMoneda < 3)
                                                          <option value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                              {{ $tipoMoneda->Nombre }}</option>
                                                      @endif
                                                  @endforeach
                                              </select>
                                          @else
                                              <select id="tipoMoneda" class="form-control" name="tipoMoneda" disabled>
                                                  @foreach ($tipoMonedas as $tipoMoneda)
                                                      @if ($tipoMoneda->IdTipoMoneda < 3)
                                                          <option value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                              {{ $tipoMoneda->Nombre }}</option>
                                                      @endif
                                                  @endforeach
                                              </select>
                                          @endif
                                          <label for="tipoMoneda">Tipo de Moneda</label>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Stock" type="number" name="stock"
                                              min="0" step="any">
                                          <label for="stock">Stock</label>
                                          <span class="text-danger font-size">{{ $errors->first('stock') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <select class="form-control" name="uniMedida">
                                              @foreach ($unidadMedidas as $uniMedida)
                                                  <option value="{{ $uniMedida->IdUnidadMedida }}">
                                                      {{ $uniMedida->Nombre }}</option>
                                              @endforeach
                                          </select>
                                          <label for="uniMedida">Unidad de Medida</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-5">
                                      <div class="form-group">
                                          <input class="form-control" type="number" name="precio" step="any"
                                              onchange="quitarPrecioIgv(this.value);">
                                          <label for="precio">Precio de Venta al público</label>
                                          <span class="text-danger font-size fs-12">Ingrese Precio de Venta incluido
                                              IGV</span>
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
                                              <input id="preciosigv" class="form-control" type="text" readonly>
                                          </div>
                                      </div>
                                  </div>
                                  {{-- @endif --}}
                                  <div class="col-md-5">
                                      <div class="form-group">
                                          <input class="form-control" type="number" name="costo" step="any"
                                              onchange="quitarCostoIgv(this.value);">
                                          <label for="costo">Precio de Costo</label>
                                          <span class="text-danger font-size fs-12">Ingrese Precio de Costo incluido
                                              IGV</span>
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
                                              <input id="costosigv" class="form-control" type="text" readonly>
                                          </div>
                                      </div>
                                  </div>
                                  {{-- @endif --}}
                                  @if ($exonerado == 1 && $sucExonerado == 1)
                                      <div class="col-md-5">
                                          <select id="tipoOperacion" class="form-control" name="tipoOperacion">
                                              <option selected value="1">Crear con IGV</option>
                                              <option value="2">Crear sin IGV</option>
                                          </select>
                                      </div>
                                  @else
                                      <input id="tipoOperacion" type="text" name="tipoOperacion" value="1"
                                          hidden>
                                  @endif
                                  <div class="col-md-5">
                                      <div class="form-group">
                                          <input class="form-control" type="text" name="ubicacion" maxlength="=50">
                                          <label for="ubicacion">Ubicación</label>
                                      </div>
                                  </div>
                              </div>

                              <div class="widget-body">
                                  <h5 class="box-title mr-b-0">Ventas por Mayor</h5>
                                  <hr>
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" type="number" name="ventaMayor1"
                                                  value="0" min="0">
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
                                  </div>
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
                                              <input class="form-control" type="text" name="precioTipo"
                                                  value="">
                                              <label for="descuentoTipo">Nuevo Precio (en Soles)
                                          </div>
                                      </div>
                                      <div hidden>
                                          <div class="form-group">
                                              <input id="nombreTipo" class="form-control" type="text"
                                                  name="nombreTipo" value="Caja">
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
                                          <span class="text-danger fs-12">Peso máx. 300 Kb</span>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <img id="imgPrevia" src="{{ config('variablesGlobales.urlImagenDefault') }}"
                                              alt="Vista de Imagen" width="100%" />
                                      </div>
                                      <span class="text-danger font-size">{{ $errors->first('imagen') }}</span>
                                  </div>
                                  <div class="col-md-2">
                                      <div class="form-group">
                                          <input class="form-control" id="codBarra" type="text" name="codBarra">
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
                                  <input hidden type="text" id="valorCambio" name="valorCambio" class="form-control"
                                      value="0">
                              </div>


                              <div class="form-actions btn-list mt-3">
                                  <button id="btnCrear" class="btn btn-primary" type="submit">Crear</button>
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
      <script src="{{ asset('assets/js/administracion/productos.js') }}"></script>
      <script src="{{ asset('assets/js/general.js') }}"></script>
  @stop
