  @extends('layouts.app')
  @section('title', 'Crear Vehiculo')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Crear Vehículo</h6>
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
                              {!! Form::open(['url' => '/administracion/vehicular/salvar', 'method' => 'POST', 'class' => 'form-material']) !!}
                              {{ csrf_field() }}
                              <div class="row">
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="cliente"
                                              name="cliente" data-placeholder="Cliente" data-toggle="select2" tabindex="-1"
                                              aria-hidden="true">
                                              <option value="">-</option>
                                              @foreach ($clientes as $cliente)
                                                  <option value="{{ $cliente->IdCliente }}"
                                                      {{ old('cliente') == $cliente->IdCliente ? 'selected' : '' }}>
                                                      {{ $cliente->Nombre }}</option>
                                              @endforeach
                                          </select>
                                          <small class="text-muted"><strong>Seleccione el Cliente</strong></small>
                                          <span class="text-danger font-size">{{ $errors->first('cliente') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="tipoVehiculo"
                                              name="tipoVehiculo" data-placeholder="Tipo" data-toggle="select2"
                                              tabindex="-1" aria-hidden="true">
                                              <option value="1">Vehículo de 4 ruedas</option>
                                              <option value="2">Vehículo de 2 ruedas</option>
                                          </select>
                                          <small class="text-muted"><strong>Seleccione Tipo Vehiculo</strong></small>
                                          <span class="text-danger font-size">{{ $errors->first('tipoVehiculo') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div id="auto" class="form-group">
                                          <input id="placa" class="form-control" placeholder="Placa Auto" type="text"
                                              value="{{ old('placa') }}" name="placa" maxlength="7">
                                          <label for="nombre">Placa</label>
                                          <span class="text-danger font-size">{{ $errors->first('placa') }}</span>
                                      </div>
                                      <div id="moto" class="form-group">
                                          <input class="form-control" placeholder="Placa Moto" type="text"
                                              value="{{ old('placaMoto') }}" name="placaMoto">
                                          <label for="nombre">Placa</label>
                                          <span class="text-danger font-size">{{ $errors->first('placaMoto') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Chasis / VIN" type="text"
                                              value="{{ old('chasis') }}" name="chasis">
                                          <label for="nombre">Chasis / VIN</label>
                                          <span class="text-danger font-size">{{ $errors->first('chasis') }}</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  @if ($modulosSelect->contains('IdModulo', 5))
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <select class="m-b-10 form-control select2-hidden-accessible" id="seguro"
                                                  name="seguro" data-placeholder="Seguros" data-toggle="select2"
                                                  tabindex="-1" aria-hidden="true">
                                                  <option value="2">Sin Seguro</option>
                                                  @foreach ($seguros as $seguro)
                                                      <option value="{{ $seguro->IdSeguro }}"
                                                          {{ old('seguro') == $seguro->IdSeguro ? 'selected' : '' }}>
                                                          {{ $seguro->Descripcion }}</option>
                                                  @endforeach
                                              </select>
                                              <small class="text-muted"><strong>Seleccione Seguro Vehicular</strong></small>
                                              <span class="text-danger font-size">{{ $errors->first('seguro') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <select class="m-b-10 form-control select2-hidden-accessible" id="anio"
                                                  name="anio" data-placeholder="Año" data-toggle="select2" tabindex="-1"
                                                  aria-hidden="true">
                                                  @foreach ($arrayAnio as $anio)
                                                      <option value="{{ $anio }}">{{ $anio }}</option>
                                                  @endforeach
                                              </select>
                                              <small class="text-muted"><strong>Seleccione año</strong></small>
                                              <span class="text-danger font-size">{{ $errors->first('anio') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="Color" type="text"
                                                  value="{{ old('color') }}" name="color">
                                              <label>Color</label>
                                              <span class="text-danger font-size">{{ $errors->first('color') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-3">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="Nro Motor" type="text"
                                                  value="{{ old('motor') }}" name="motor">
                                              <label>Nro Motor</label>
                                          </div>
                                      </div>
                                  @else
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <select class="m-b-10 form-control select2-hidden-accessible" id="anio"
                                                  name="anio" data-placeholder="Año" data-toggle="select2"
                                                  tabindex="-1" aria-hidden="true">
                                                  @foreach ($arrayAnio as $anio)
                                                      <option value="{{ $anio }}">{{ $anio }}</option>
                                                  @endforeach
                                              </select>
                                              <small class="text-muted"><strong>Seleccione año</strong></small>
                                              <span class="text-danger font-size">{{ $errors->first('anio') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="Color" type="text"
                                                  value="{{ old('color') }}" name="color">
                                              <label>Color</label>
                                              <span class="text-danger font-size">{{ $errors->first('color') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <input class="form-control" placeholder="Nro Motor" type="text"
                                                  value="{{ old('motor') }}" name="motor">
                                              <label>Nro Motor</label>
                                          </div>
                                      </div>
                                  @endif
                              </div>
                              <div class="row" hidden>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Kilometros" type="text"
                                              value="{{ old('kilometraje') }}" name="kilometraje">
                                          <label>Kilometro. Inicial</label>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Horometro" type="text"
                                              value="{{ old('horometro') }}" name="horometro">
                                          <label>Horometro. Inicial</label>
                                      </div>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="marca"
                                              name="marca" data-placeholder="Marca" data-toggle="select2"
                                              tabindex="-1" aria-hidden="true">
                                              <option value="">-</option>
                                              @foreach ($marcas as $marca)
                                                  <option value="{{ $marca->IdMarcaGeneral }}"
                                                      {{ old('marca') == $marca->IdMarcaGeneral ? 'selected' : '' }}>
                                                      {{ $marca->NombreMarca }}</option>
                                              @endforeach
                                          </select>
                                          <small class="text-muted"><strong>Seleccione La Marca</strong></small>
                                          <span class="text-danger font-size">{{ $errors->first('marca') }}</span>
                                      </div>
                                  </div>

                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="modelo"
                                              name="modelo" data-placeholder="Modelo" data-toggle="select2"
                                              tabindex="-1" aria-hidden="true">
                                              <option value="">-</option>
                                              @foreach ($modelos as $modelo)
                                                  <option value="{{ $modelo->IdModeloGeneral }}"
                                                      {{ old('modelo') == $modelo->IdModeloGeneral ? 'selected' : '' }}>
                                                      {{ $modelo->NombreModelo }}</option>
                                              @endforeach
                                          </select>
                                          <small class="text-muted"><strong>Seleccione El Modelo</strong></small>
                                          <span class="text-danger font-size">{{ $errors->first('modelo') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <select class="m-b-10 form-control select2-hidden-accessible" id="tipo"
                                              name="tipo" data-placeholder="Tipo" data-toggle="select2"
                                              tabindex="-1" aria-hidden="true">
                                              <option value=""></option>
                                              @foreach ($tipos as $tipo)
                                                  <option value="{{ $tipo->IdTipoGeneral }}"
                                                      {{ old('tipo') == $tipo->IdTipoGeneral ? 'selected' : '' }}>
                                                      {{ $tipo->NombreTipo }}</option>
                                              @endforeach
                                          </select>
                                          <small class="text-muted"><strong>Seleccione El Tipo</strong></small>
                                          <span class="text-danger font-size">{{ $errors->first('tipo') }}</span>
                                      </div>
                                  </div>
                              </div>

                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <small class="text-muted"><strong>Fecha Venc. Soat</strong></small>
                                          <div class="input-group">
                                              <input id="fechaSoat" type="date"
                                                  data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                  class="form-control" name="fechaSoat">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <small class="text-muted"><strong>Fecha Revis. Técnica</strong></small>
                                          <div class="input-group">
                                              <input id="fechaRevTecnica" type="date"
                                                  data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                  class="form-control" name="fechaRevTecnica">
                                          </div>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group mt-4">
                                          <input class="form-control" placeholder="Nro Flota" type="text"
                                              name="flota">
                                          <label>Nro Flota</label>
                                      </div>
                                  </div>
                              </div>
                              @if ($modulosSelect->contains('IdModulo', 6))
                                  <div class="row">
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <small class="text-muted"><strong>Certificación Anual</strong></small>
                                              <div class="input-group">
                                                  <input id="fechaCertAnual" type="date"
                                                      data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                      class="form-control" name="fechaCertAnual">
                                              </div>
                                          </div>
                                      </div>
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <small class="text-muted"><strong>Prueba Quinquenal</strong></small>
                                              <div class="input-group">
                                                  <input id="fechaPrueQuin" type="date"
                                                      data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                      class="form-control" name="fechaPrueQuin">
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              @endif
                              <div class="row">
                                  <div class="col-mb-4">
                                      <div class="form-group">
                                          <div class="radiobox radio-success mr-10">
                                              <h5 class="box-title mr-b-0">Estado</h5>
                                              <label>
                                                  <input type="radio" name="radioOpcion" value="1" checked> <span
                                                      class="label-text">Activo</span>
                                              </label>
                                          </div>
                                          <!-- /.radiobox -->
                                          <div class="radiobox mr-10">
                                              <label>
                                                  <input type="radio" name="radioOpcion" value="0"> <span
                                                      class="label-text">Desactivado</span>
                                              </label>
                                          </div>
                                          <!-- /.radiobox -->
                                      </div>
                                      <!-- /.form-group -->
                                  </div>

                                  <div class="col-md-7">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Observacion" type="text"
                                              name="nota">
                                          <label for="direccion">Observacion</label>
                                      </div>
                                  </div>
                                  <div class="col-md-3">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Ingrese el Número de Días"
                                              type="text" name="periodoMantenimientoKm"
                                              value="{{ old('periodoMantenimientoKm') }}">
                                          <label>Periodo de Mantenimiento por Km</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-actions btn-list mt-3">
                                  <button class="btn btn-info" type="submit">Crear</button>
                                  <a href="../vehicular/registrar"><button class="btn btn-outline-default"
                                          type="button">Cancelar</button></a>
                                  <a href="https://www.sunarp.gob.pe/seccion/servicios/detalles/0/c3.html"
                                      target="_blank"><button class="btn btn-outline-danger" type="button">Consulta
                                          Vehicular</button></a>
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
              var fechaFuturo = <?php echo json_encode($fechaFuturo); ?>;
              var today = new Date();
              var dd = today.getDate();
              var mm = today.getMonth() + 1;

              var yyyy = today.getFullYear();
              if (dd < 10) {
                  dd = '0' + dd;
              }
              if (mm < 10) {
                  mm = '0' + mm;
              }
              var today = yyyy + '-' + mm + '-' + dd;
              $("#fechaSoat").val(fechaFuturo);
              $("#fechaRevTecnica").val(fechaFuturo);
              $("#fechaCertAnual").val(fechaFuturo);
              $("#fechaPrueQuin").val(fechaFuturo);
          });
      </script>
      <script>
          $(function() {
              $("#moto").hide();
              $('#placa').keyup(function() {
                  var val = this.value.replace(/[^a-zA-Z0-9]/g, '')
                  var newVal = '';
                  while (val.length > 3) {
                      newVal += val.substr(0, 3) + '-';
                      val = val.substr(3);
                  }
                  newVal += val;
                  this.value = newVal;
              });

              $("#tipoVehiculo").on('change', function() {
                  var valor = $("#tipoVehiculo").val();
                  if (valor == "1") {
                      $("#auto").show();
                      $("#moto").hide();
                  } else {
                      $("#auto").hide();
                      $("#moto").show();
                  }
              });
          });
      </script>
  @stop
