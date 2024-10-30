  @extends('layouts.app')
  @section('title', 'Editar Sucursal')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Editar Sucursal</h6>
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
                                  'url' => '/administracion/sucursales/' . $sucursal->IdSucursal,
                                  'method' => 'PUT',
                                  'files' => true,
                              ]) !!}
                              {{ csrf_field() }}
                              <div class="row form-material">
                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <input class="form-control" type="text" name="nombre"
                                              value="{{ $sucursal->Nombre }}">
                                          <label for="nombre">Nombre</label>
                                          <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                      </div>
                                  </div>

                                  <div class="col-md-6">
                                      <div class="form-group">
                                          <input class="form-control" type="text" name="codigoFiscal" placeholder="0001"
                                              value="{{ $sucursal->CodFiscal }}">
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
                                              <option value="0">-</option>
                                              @foreach ($departamentos as $departamento)
                                                  @if ($sucursal->IdDepartamento == $departamento->IdDepartamento)
                                                      <option selected value="{{ $departamento->IdDepartamento }}">
                                                          {{ $departamento->Nombre }}</option>
                                                  @else
                                                      <option value="{{ $departamento->IdDepartamento }}">
                                                          {{ $departamento->Nombre }}</option>
                                                  @endif
                                              @endforeach
                                          </select>
                                          <span class="text-danger font-size">{{ $errors->first('departamento') }}</span>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="provincia">Provincia</label>
                                          <select id="provincia" class="form-control" name="provincia">
                                              <option value="0">-</option>
                                              @foreach ($provincias as $provincia)
                                                  @if ($sucursal->IdProvincia == $provincia->IdProvincia)
                                                      <option selected value="{{ $provincia->IdProvincia }}">
                                                          {{ $provincia->Nombre }}</option>
                                                  @else
                                                      <option value="{{ $provincia->IdDepartamento }}">
                                                          {{ $provincia->Nombre }}</option>
                                                  @endif
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group">
                                          <label for="distrito">Distrito</label>
                                          <select id="distrito" class="form-control" name="distrito">
                                              <option value="0">-</option>
                                              @foreach ($distritos as $distrito)
                                                  @if ($sucursal->Ubigeo == $distrito->IdDistrito)
                                                      <option selected value="{{ $distrito->IdDistrito }}">
                                                          {{ $distrito->Nombre }}</option>
                                                  @else
                                                      <option value="{{ $distrito->IdDistrito }}">{{ $distrito->Nombre }}
                                                      </option>
                                                  @endif
                                              @endforeach
                                          </select>
                                      </div>
                                  </div>
                              </div>
                              <div class="row form-material">
                                  <div class="col-md-6" hidden>
                                      <div class="form-group">
                                          <input id="nomCiudad" class="form-control" type="text" name="ciudad"
                                              value="{{ $sucursal->Ciudad }}">
                                          <label for="ciudad">Ciudad</label>
                                      </div>
                                  </div>
                                  <div class="col-md-4">
                                      <div class="form-group form-material">
                                          <input class="form-control" type="text" name="telefono"
                                              value="{{ $sucursal->Telefono }}">
                                          <label for="telefono">Teléfono</label>
                                          <span class="text-danger font-size">{{ $errors->first('telefono') }}</span>
                                      </div>
                                  </div>
                                  @if ($datosEmpresa)
                                      @if ($datosEmpresa->Exonerado > 0)
                                          <div class="col-md-4">
                                              <div class="form-group form-material">
                                                  <label for="exonerar">Exonerar Facturación</label><br><br>
                                                  @if ($sucursal->Exonerado == 1)
                                                      <input type="checkbox" name="exonerar" checked><span
                                                          class="p-1 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                                  @else
                                                      <input type="checkbox" name="exonerar"><span
                                                          class="p-1 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                                  @endif
                                              </div>
                                          </div>
                                      @else
                                          <input hidden type="text" name="exonerar" value="0">
                                      @endif
                                  @else
                                      <div class="col-md-4">
                                          <div class="form-group form-material">
                                              <label for="exonerar">Exonerar Facturación</label><br><br>
                                              <input type="checkbox" name="exonerar"><span
                                                  class="p-1 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                          </div>
                                      </div>
                                  @endif

                                  {{-- Nuevo codigo --}}
                                  @if ($sucursal->Principal == 0)
                                      <div class="col-md-4">
                                          <div class="form-group">
                                              <label for="ocultar">Mostrar Dirección sucursal en los
                                                  documentos</label><br><br>
                                              @if ($sucursal->OcultarDireccion == 'E')
                                                  <input type="checkbox" name="ocultarDireccion" checked><span
                                                      class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                              @else
                                                  <input type="checkbox" name="ocultarDireccion"><span
                                                      class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                              @endif
                                          </div>
                                      </div>
                                  @endif
                                  {{-- Fin --}}
                                  <div class="col-md-8">
                                      <div class="form-group has-error">
                                          <input class="form-control" type="text" name="direccion"
                                              value="{{ $sucursal->Direccion }}">
                                          <label for="direccion">Dirección</label>
                                          <span class="text-danger font-size">{{ $errors->first('direccion') }}</span>
                                      </div>
                                  </div>
                              </div>
                              @if ($usuarioSelect->IdOperador == 1)
                                <fieldset class="fieldset fieldset--bordeCeleste">
                                    <legend class="legend legend--colorNegro">Inicio Comprobantes:</legend>
                                    @foreach ($inicioComprobante as $comprobante)
                                    <div class="row align-items-center">       
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="fs-15">{{$comprobante->DescripcionComprobante}}</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="serie[]" value="{{$comprobante->Serie}}" readonly>
                                                <label for="serieNC">Serie</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="correlativo[]" placeholder="ejem.: 00000001" value="{{$comprobante->Correlativo}}">
                                                <label for="correlativoNC">Correlativo</label>
                                            </div>
                                        </div>
                                        <input class="form-control" type="text" name="tipoComprobante[]" value="{{$comprobante->TipoComprobante}}" hidden>
                                    </div>
                                    @endforeach
                                </fieldset>
                                <input class="form-control" type="text" hidden name="IdOperadorUsuario"
                                value="{{ $usuarioSelect->IdOperador }}">
                            @endif
                              <div class="form-actions btn-list mt-3">
                                  <button class="btn btn-primary" type="submit">Actualizar</button>
                                  <a href="../../sucursales"><button class="btn btn-outline-default"
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
              var ciudad = '';
              $("#departamento").on('change', function() {
                  var departamento = $("#departamento").val();
                  $.ajax({
                      type: 'get',
                      url: 'edit/consultar-provincias',
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
                  $.ajax({
                      type: 'get',
                      url: 'edit/consultar-distritos',
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
