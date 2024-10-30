  @extends('layouts.app')
  @section('title', 'Crear Cliente')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Crear Cliente</h6>
              </div>
              <!-- /.page-title-left -->
          </div>
          @if ($errors->any())
              <div class="alert alert-danger" role="alert">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  @foreach ($errors->all() as $error)
                      <li>{{ $error }}</li>
                  @endforeach
              </div>
          @endif
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
                              <div class="row form-material">
                                  <div class="col-md-5">
                                      <div class="form-group">
                                          <select id="tipoDoc" class="form-control" name="tipoDocumento">
                                              @foreach ($tipoDoc as $doc)
                                                  <option value="{{ $doc->IdTipoDocumento }}"
                                                      {{ old('tipoDocumento') == $doc->IdTipoDocumento ? 'selected' : '' }}>
                                                      {{ $doc->Descripcion }}</option>
                                              @endforeach
                                          </select>
                                          <label for="tipoDoc">Tipo Documento</label>
                                      </div>
                                  </div>
                                  <div class="col-md-5">
                                      <div class="form-group">
                                          <input id="numDoc" class="form-control" placeholder="Número de Documento"
                                              type="text" name="numDoc" maxlength="12" value="{{ old('numDoc') }}">
                                          <label for="numDoc">Número de Documento</label>
                                          <span class="text-danger font-size">{{ $errors->first('numDoc') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-2">
                                      <button id="consultar" class="btn btn-primary">Buscar</button>
                                  </div>
                              </div>

                              {!! Form::open(['url' => 'administracion/staff/guardar-cliente', 'method' => 'POST', 'files' => true]) !!}
                              {{ csrf_field() }}
                              <div class="row">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="razonSocial">Nombre o Razón Social</label>
                                          <input id="razonSocial" class="form-control" type="text" name="razonSocial"
                                              value="{{ old('razonSocial') }}">
                                          <span class="text-danger font-size">{{ $errors->first('razonSocial') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <label for="nombreComercial">Nombre Comercial</label>
                                          <input id="nombreComercial" class="form-control" type="text"
                                              name="nombreComercial" value="{{ old('nombreComercial') }}">
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
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="direccion">Dirección</label>
                                          <input id="direccion" class="form-control" type="text" name="direccion"
                                              maxlength="100" value="{{ old('direccion') }}">
                                          <span class="text-danger font-size">{{ $errors->first('direccion') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="telefono">Teléfono</label>
                                          <input id="telefono" class="form-control" type="text" name="telefono"
                                              value="{{ old('telefono') }}">
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="personaContacto">Persona de Contacto</label>
                                          <input class="form-control" type="text" name="personaContacto"
                                              value="{{ old('personaContacto') }}">
                                          <span
                                              class="text-danger font-size">{{ $errors->first('personaContacto') }}</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="row">
                                  <div class="col-md-2 col-6">
                                      <label for="condicion">Condición</label>
                                      <p id="condicion">-</p>
                                  </div>
                                  <div class="col-md-2 col-6">
                                      <label for="estado">Estado</label>
                                      <p id="estado">-</p>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="email">Email</label>
                                          <input id="email" class="form-control" type="email" name="email"
                                              value="{{ old('email') }}">
                                          <span class="text-danger font-size">{{ $errors->first('email') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <h5 class="box-title mr-b-0">Saldo (Credito)</h5>
                                      <div class="form-group">
                                          <div class="radiobox">
                                              <label id="con">
                                                  <input type="radio" name="radioOpcion" value="0" checked> <span
                                                      class="label-text">Ilimitado</span>
                                              </label>
                                          </div>
                                          <!-- /.radiobox -->
                                          <div class="radiobox radio-success">
                                              <label id="sin">
                                                  <input type="radio" name="radioOpcion" value="1"> <span
                                                      class="label-text">Con Monto</span>
                                                  <input id="saldo" hidden class="form-control" type="number"
                                                      step="any" name="saldoCredito"><small hidden
                                                      class="text-muted"><strong>Escriba el monto del
                                                          Cliente(obligatorio)</strong></small>
                                              </label>
                                          </div>
                                      </div>
                                      <!-- /.form-group -->
                                  </div>
                                  <input id="tipoDocumento" hidden class="form-control" type="text"
                                      name="tipoDocumento">
                                  <input id="nroDocumento" hidden class="form-control" type="text"
                                      name="nroDocumento">
                              </div>

                              <div class="form-actions btn-list mt-3">
                                  <button class="btn btn-primary" type="submit">Crear</button>
                                  <a href="../clientes"><button class="btn btn-outline-default"
                                          type="button">Cancelar</button></a>
                                  <a href="https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias"
                                      target="_blank"><button class="btn btn-outline-danger" type="button">Consultar
                                          RUC</button></a>
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
      {{-- <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script> --}}
      <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
      <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>
      <script>
          $('#tipoDoc').change(function() {
              var option = $('#tipoDoc option:selected').val();
              if (option == 3 || option == 4) {
                  $('#consultar').attr('disabled', 'disabled');
              } else {
                  $('#consultar').removeAttr('disabled');
              }
          })
      </script>
      <script>
          $(function() {
              $('#consultar').on('click', function() {
                  var tipDoc = $("#tipoDoc option:selected").val();
                  var numdoc = $("#numDoc").val();
                  $.ajax({
                      type: 'get',
                      url: 'create/consultar-clientes',
                      data: {
                          'idDoc': tipDoc,
                          'numDoc': numdoc
                      },
                      success: function(data) {
                          $('#departamento option[value="0"]').prop('selected', true);
                          $('#distrito option').remove();
                          $('#provincia option').remove();
                          if ((data[0]) == 1) {
                              if (tipDoc == 1) {
                                  //$('#tipoDocumento').val(data[1]);
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
                          respuestaInfoAjax("Consulta no Disponible!",
                              "Servicio de consultas en servidores externos de RUC/DNI no se encuentra disponible, vuelva a intentar en unos minutos y/o puede ingresar sus datos digitandolos manualmente para la creación del cliente."
                          )
                      }


                  });
              });
          });
          $("#numDoc").keyup(function() {
              var numeroDoc = $("#numDoc").val();
              var tipDoc = $("#tipoDoc option:selected").val();
              $("#nroDocumento").val(numeroDoc);
              $("#tipoDocumento").val(tipDoc);
          });
          $("#tipoDoc").on('change', function() {
              var numeroDoc = $("#numDoc").val();
              var tipDoc = $("#tipoDoc option:selected").val();
              $("#nroDocumento").val(numeroDoc);
              $("#tipoDocumento").val(tipDoc);
          });
      </script>
      <script>
          $(function() {
              $("#departamento").on('change', function() {
                  var departamento = $("#departamento").val();

                  $.ajax({
                      type: 'get',
                      url: 'create/consultar-provincias',
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
                                  //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
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
                      url: 'create/consultar-distritos',
                      data: {
                          'provincia': provincia
                      },
                      success: function(data) {
                          if (data.length > 0) {
                              $('#distrito option').remove();
                              $('#distrito').append('<option value="0">-</option>');
                              for (var i = 0; i < data.length; i++) {
                                  //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
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
          $("#sin").click(function() {
              $("#saldo").removeAttr("hidden");
              $(".text-muted").removeAttr("hidden");

          });

          $("#con").click(function() {
              $("#saldo").prop("hidden", "true");
              $(".text-muted").prop("hidden", "true");
          })
      </script>
  @stop
