  @extends('layouts.app')
  @section('title', 'Crear Vehiculo')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix mt-3">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Crear Vehículo</h6>
              </div>
              <!-- /.page-title-left -->
              <div class="page-title-rigth">
                  <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-lg-cliente"><button
                          class="btn btn-info"><i class="list-icon material-icons fs-26">person_add</i>Crear
                          Cliente</button></a>
              </div>
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
                              {!! Form::open(['url' => '/vehicular/administracion/salvar', 'method' => 'POST', 'class' => 'form-material']) !!}
                              {{ csrf_field() }}
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="">
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
                                  <div class="col-md-2">
                                      <div id="auto" class="form-group">
                                          <input id="placa" class="form-control" placeholder="Placa Auto" type="text"
                                              value="{{ old('placa') }}" name="placa" maxlength="7">
                                          <label for="nombre">Placa</label>
                                          <span class="text-danger font-size">{{ $errors->first('placa') }}</span>
                                      </div>
                                      <div id="moto" class="form-group">
                                          <input class="form-control" placeholder="Placa Moto" type="text"
                                              value="{{ old('placaMoto') }}" name="placaMoto" maxlength="7">
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
                                  <div class="col-12 col-md-4">
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

                                  <div class="col-12 col-md-8">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Observacion" type="text"
                                              name="nota">
                                          <label for="direccion">Observacion</label>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-actions btn-list mt-3">
                                  <button class="btn btn-info" type="submit">Crear</button>
                                  <a href="../administracion/lista-vehiculos"><button class="btn btn-outline-default"
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
                      {{-- MODAL PARA CREAR CLIENTE --}}
                      <div class="modal fade bs-modal-lg-cliente" tabindex="-1" role="dialog"
                          aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h6>Crear Cliente</h6>
                                  </div>
                                  <div class="modal-body">
                                      <div id="mensaje">

                                      </div>
                                      <div class="widget-body clearfix contenedorInput">
                                          <div class="row form-material">
                                              <div class="col-md-5">
                                                  <div class="form-group">
                                                      <select id="tipoDoc" class="form-control" name="tipoDocumento">
                                                          @foreach ($tipoDoc as $doc)
                                                              <option value="{{ $doc->IdTipoDocumento }}">
                                                                  {{ $doc->Descripcion }}</option>
                                                          @endforeach
                                                      </select>
                                                      <label for="tipoDoc">Tipo Documento</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-5">
                                                  <div class="form-group">
                                                      <input id="numDoc" class="form-control"
                                                          placeholder="Número de Documento" type="text" name="numDoc"
                                                          maxlength="12">
                                                      <label for="numDoc">Número de Documento</label>
                                                  </div>
                                              </div>
                                              <div class="col-md-2">
                                                  <button id="consultar" class="btn btn-primary">Buscar</button>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label for="nombre">Nombre / Razón social</label>
                                                      <input id="razonSocial" class="form-control"
                                                          placeholder="Razón Social" type="text" name="razonSocial">
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group has-error">
                                                      <label for="razonSocial">Nombre Comercial</label>
                                                      <input id="nombre" class="form-control" placeholder="Nombre"
                                                          type="text" name="nombreComercial">
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="row">
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <label for="departamento">Departamento</label>
                                                      <select id="departamento" class="form-control" name="departamento">
                                                          <option value="0">-</option>
                                                          @foreach ($departamentos as $departamento)
                                                              <option value="{{ $departamento->IdDepartamento }}">
                                                                  {{ $departamento->Nombre }}</option>
                                                          @endforeach
                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <label for="provincia">Provincia</label>
                                                      <select id="provincia" class="form-control" name="provincia">

                                                      </select>
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <label for="distrito">Distrito</label>
                                                      <select id="distrito" class="form-control" name="distrito">

                                                      </select>
                                                  </div>
                                              </div>
                                          </div>

                                          <div class="row">
                                              <div class="col-md-8">
                                                  <div class="form-group">
                                                      <label for="direccion">Dirección</label>
                                                      <input id="direccion" class="form-control" placeholder="Dirección"
                                                          type="text" name="direccion">
                                                  </div>
                                              </div>
                                              <div class="col-md-4">
                                                  <div class="form-group">
                                                      <label for="telefono">Teléfono</label>
                                                      <input id="telefono" class="form-control" placeholder="Teléfono"
                                                          type="text" name="telefono">

                                                  </div>
                                              </div>
                                          </div>
                                          <div class="row">
                                              <div class="col-md-3 col-6">
                                                  <label for="condicion">Condición</label>
                                                  <p id="condicion">-</p>
                                              </div>
                                              <div class="col-md-3 col-6">
                                                  <label for="estado">Estado</label>
                                                  <p id="estado">-</p>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="form-group">
                                                      <label for="email">Email</label>
                                                      <input id="email" class="form-control" placeholder="Email"
                                                          type="email" name="email">
                                                  </div>
                                              </div>
                                          </div>
                                          <div class="form-actions btn-list mt-3">
                                              <button class="btn btn-primary" onclick="crearCliente();"
                                                  type="button">Crear</button>
                                              <button type="button" class="btn btn-default ripple text-left"
                                                  data-dismiss="modal">Cancelar</button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                      {{-- FIN --}}
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
      {{-- Nueva funcion --}}
      <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
      <script>
          $(function() {
              $("#departamento").on('change', function() {
                  var departamento = $("#departamento").val();

                  $.ajax({
                      type: 'get',
                      url: 'crear/consultar-provincias',
                      data: {
                          'departamento': departamento
                      },
                      success: function(data) {
                          if (data.length > 0) {
                              $('#distrito option').remove();
                              $('#provincia option').remove();
                              $('#distrito').append('<option value="0">-</option>');
                              $('#provincia').append('<option value="0">-</option>');
                              for (var i = 0; i < data.length; i++) {
                                  $('#provincia').append('<option value="' + data[i][
                                          "IdProvincia"
                                      ] + '">' + data[i]["Nombre"] +
                                      '</option>');
                              }
                          } else {
                              $('#provincia option').remove();
                              $('#distrito option').remove();
                          }
                      }
                  });
              });

              $("#provincia").on('change', function() {
                  var provincia = $("#provincia").val();
                  $.ajax({
                      type: 'get',
                      url: 'crear/consultar-distritos',
                      data: {
                          'provincia': provincia
                      },
                      success: function(data) {
                          if (data.length > 0) {
                              $('#distrito option').remove();
                              $('#distrito').append('<option value="0">-</option>');
                              for (var i = 0; i < data.length; i++) {
                                  $('#distrito').append('<option value="' + data[i][
                                      "IdDistrito"
                                  ] + '">' + data[i]["Nombre"] + '</option>');
                              }
                          } else {
                              $('#distrito option').remove();
                          }
                      }
                  });
              });
          });
      </script>

      <script>
          $(function() {
              $('#consultar').on('click', function() {
                  var tipDoc = $("#tipoDoc option:selected").val();
                  var numdoc = $("#numDoc").val();
                  $.ajax({
                      type: 'get',
                      url: 'crear/consultar-clientes',
                      data: {
                          'idDoc': tipDoc,
                          'numDoc': numdoc
                      },
                      success: function(data) {
                          console.log(data);
                          $('#departamento option[value="0"]').prop('selected', true);
                          $('#distrito option').remove();
                          $('#provincia option').remove();

                          if ((data[0]) == 1) {
                              if (tipDoc == 1) {
                                  $('#nroDocumento').val(data[2]);
                                  $('#razonSocial').val(data[3]);
                                  $('#nombreComercial').val("-");
                                  $('#direccion').val("-");
                                  $('#condicion').text("-");
                                  $('#estado').text("-");
                              }
                              if (tipDoc == 2) {
                                  //$('#tipoDocumento').val(data[1]);
                                  $('#nroDocumento').val(data[2]);
                                  $('#razonSocial').val(data[3]);
                                  $('#nombreComercial').val(data[4]);
                                  $('#direccion').val(data[5]);
                                  $('#condicion').text(data[9]);
                                  $('#estado').text(data[10]);
                                  if (data[6] != null) {
                                      $('#departamento option[value="' + data[6] + '"]').prop(
                                          'selected', true);

                                      $('#provincia').append('<option value="0">-</option>');
                                      for (var i = 0; i < data[7][1].length; i++) {
                                          if (data[7][1][i]["IdProvincia"] == data[7][0]) {
                                              $('#provincia').append('<option selected value="' +
                                                  data[7][1][i]["IdProvincia"] + '">' + data[
                                                      7][1][i]["Nombre"] + '</option>');
                                          } else {
                                              $('#provincia').append('<option value="' + data[7][
                                                      1
                                                  ][i]["IdProvincia"] + '">' + data[7][1]
                                                  [i]["Nombre"] + '</option>');
                                          }
                                      }

                                      $('#distrito').append('<option value="0">-</option>');
                                      for (var j = 0; j < data[8][1].length; j++) {
                                          if (data[8][1][j]["IdDistrito"] == data[8][0]) {
                                              $('#distrito').append('<option selected value="' +
                                                  data[8][1][j]["IdDistrito"] + '">' + data[8]
                                                  [1][j]["Nombre"] + '</option>');
                                          } else {
                                              $('#distrito').append('<option value="' + data[8][1]
                                                  [j]["IdDistrito"] + '">' + data[8][1][j][
                                                      "Nombre"
                                                  ] + '</option>');
                                          }
                                      }
                                  }
                              }
                              if (tipDoc == 3) {
                                  alert("El Servicio no funciona para Pasaportes");
                              }
                          } else {
                              $('#nombreComercial').val("");
                              $('#razonSocial').val("");
                              $('#direccion').val("");
                              $('#condicion').text("-");
                              $('#estado').text("-");
                              alert(data[3]);
                          }
                      },
                      error: function(jqXHR, textStatus, errorThrown) {
                          alert("servicio no disponible");
                      }
                  });
              });

          });
      </script>


      <script>
          function getClientes(cliente) {
              $('#cliente option').remove();
              $('#cliente').append('<option value="0">-</option>');
              $('#cliente').append('<option selected value="' + cliente[0]["IdCliente"] + '">' +
                  cliente[0]["Nombre"] + '</option>');

          }

          $('#tipoDoc').change(function() {
              if ($('#tipoDoc option:selected').val() == 3 || $('#tipoDoc option:selected').val() == 4) {
                  $('#consultar').attr('disabled', 'disabled')
              } else {
                  $('#consultar').removeAttr('disabled')
              }
          });

          function CerrarModal() {
              $(".bs-modal-lg-cliente").modal('hide');
          }

          function resetearFormulario() {
              $(".contenedorInput select").each(function() {
                  this.selectedIndex = 0
              });
              $(".contenedorInput input[type=text], input[type=email]").each(function() {
                  this.value = ''
              });
          }

          function crearCliente() {
              $.ajax({
                  type: 'post',
                  url: 'crear-cliente',
                  data: {
                      "_token": "{{ csrf_token() }}",
                      "nombreComercial": $('#nombre').val(),
                      "razonSocial": $("#razonSocial").val(),
                      "tipoDoc": $("#tipoDoc option:selected").val(),
                      "numDoc": $("#numDoc").val(),
                      "direccion": $("#direccion").val(),
                      "telefono": $("#telefono").val(),
                      "email": $("#email").val(),
                      "departamento": $("#departamento").val(),
                      "provincia": $("#provincia").val(),
                      "distrito": $("#distrito").val()
                  },
                  success: function(data) {
                      if (data[0]["IdCliente"] == '' || data[0]["IdCliente"] == null) {
                          swal({
                              text: data,
                              icon: "error",
                              button: "Entendido",
                          })
                      } else {
                          swal({
                                  text: "El registro de cliente fue un éxito",
                                  icon: "success",
                                  button: "Entendido",
                              })
                              .then((Entendido) => {
                                  resetearFormulario();
                                  CerrarModal();
                                  getClientes(data);
                              });
                      }
                  }
              });
          }
      </script>

      {{-- Fin --}}

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
