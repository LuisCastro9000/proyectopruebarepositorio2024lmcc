  @extends('layouts.app')
  @section('title', 'Crear Sucursal')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Crear Sucursal</h6>
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
                              {!! Form::open(['url' => '/administracion/sucursales', 'method' => 'POST', 'files' => true]) !!}
                              {{ csrf_field() }}
                              <div class="row form-material">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <input class="form-control" type="text" name="nombre"
                                              value="{{ old('nombre') }}">
                                          <label for="nombre">Nombre</label>
                                          <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <input class="form-control" type="text" name="codigoFiscal" placeholder="0001"
                                              value="{{ old('codigoFiscal') }}">
                                          <label for="nombre">Codigo Fiscal</label>
                                          <span class="text-danger font-size">{{ $errors->first('codigoFiscal') }}</span>
                                          <small>Ingrese el codigo de 4 digitos de la sucursal </small>
                                      </div>
                                  </div>

                              </div>
                              <div class="row">
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="departamento">Departamento</label>
                                          <select id="departamento" class="form-control" name="departamento">
                                              <option value="">-</option>
                                              @foreach ($departamentos as $departamento)
                                                  <option value="{{ $departamento->IdDepartamento }}">
                                                      {{ $departamento->Nombre }}</option>
                                              @endforeach
                                          </select>
                                          <span class="text-danger font-size">{{ $errors->first('departamento') }}</span>
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
                                  <div class="col-md-6 form-material" hidden>
                                      <div class="form-group">
                                          <input id="nomCiudad" class="form-control" value="" placeholder="Ciudad"
                                              type="text" name="ciudad">
                                          <label for="ciudad">Ciudad</label>
                                      </div>
                                  </div>
                                  <div class="col-md-4 form-material">
                                      <div class="form-group">
                                          <input class="form-control" placeholder="Teléfono" type="text" name="telefono"
                                              value="{{ old('telefono') }}">
                                          <label for="telefono">Teléfono</label>
                                          <span class="text-danger font-size">{{ $errors->first('telefono') }}</span>
                                      </div>
                                  </div>
                                  @if ($datosEmpresa)
                                      @if ($datosEmpresa->Exonerado > 0)
                                          <div class="col-md-4">
                                              <div class="form-group">
                                                  <label for="exonerar">Exonerar Facturación</label><br>
                                                  <input type="checkbox" name="exonerar"><span
                                                      class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                              </div>
                                          </div>
                                      @endif
                                  @else
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <label for="exonerar">Exonerar Facturación</label><br>
                                              <input type="checkbox" name="exonerar"><span
                                                  class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                          </div>
                                      </div>
                                  @endif

                                  {{-- Nuevo codigo --}}
                                  {{-- @if ($idUsuario != 1) --}}
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="ocultar">Mostrar dirección sucursal en los documentos</label><br>
                                          <input type="checkbox" name="ocultarDireccion"><span
                                              class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                      </div>
                                  </div>
                                  {{-- @endif --}}
                                  {{-- Fin --}}


                                  <div class="col-md-12 form-material">
                                      <div class="form-group has-error">
                                          <input class="form-control" placeholder="Dirección" type="text"
                                              name="direccion" value="{{ old('direccion') }}">
                                          <label for="direccion">Dirección</label>
                                          <span class="text-danger font-size">{{ $errors->first('direccion') }}</span>
                                      </div>
                                  </div>
                              </div>
                              <div class="form-actions btn-list mt-3">
                                  <button class="btn btn-primary" type="submit">Agregar</button>
                                  <a href="../sucursales"><button class="btn btn-outline-default"
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
          var ciudad = '';
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
                  ciudad = $('#provincia option:selected').html();
                  $("#nomCiudad").val(ciudad);
                  //alert(ciudad);
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
  @stop
