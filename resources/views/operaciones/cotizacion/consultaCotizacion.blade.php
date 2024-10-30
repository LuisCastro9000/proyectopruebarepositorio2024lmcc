  @extends('layouts.app')
  @section('title', 'Consulta Cotizacion')
  @section('content')

      <style>
          @media (max-width: 639px) {
              .block-boton {
                  width: 100%;
                  display: block;
              }
          }
      </style>

      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Cotizaciones</h6>
              </div>
          </div>
          @if (session('status'))
              <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('status') }}
              </div>
          @endif
          @if (session('error'))
              <div class="alert alert-danger">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('error') }}
              </div>
          @endif

          {!! Form::open([
              'url' => '/operaciones/cotizacion/consultar-cotizacion',
              'method' => 'POST',
              'id' => 'formulario',
              'files' => true,
          ]) !!}
          {{ csrf_field() }}
          <div class="row ">
              <div class="col-md-3 mt-4 order-md-0">
                  <div class="form-group form-material">
                      <label>Seleccionar Tipo de Atención</label>
                      <select id="tipoAtencion" class="form-control" name="tipoAtencion">
                          <option value="5">Todo</option>
                          @foreach ($tiposAtenciones as $tipoAtencion)
                              @if ($tipoAtencion->IdTipoAtencion == $idTipoAtencion)
                                  <option selected value="{{ $tipoAtencion->IdTipoAtencion }}">
                                      {{ $tipoAtencion->Descripcion }}</option>
                              @else
                                  <option value="{{ $tipoAtencion->IdTipoAtencion }}">{{ $tipoAtencion->Descripcion }}
                                  </option>
                              @endif
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-1">
                  <div class="form-group form-material">
                      <label>Seleccionar Estado Cotización</label>
                      <select id="idEstadoCotizacion" class="form-control" name="estadoCotizacion">
                          <option value="5">Todo</option>
                          @foreach ($estadosCotis as $estado)
                              @if ($estado->IdEstadoCotizacion == $idEstadoCotizacion)
                                  <option selected value="{{ $estado->IdEstadoCotizacion }}">{{ $estado->Descripcion }}
                                  </option>
                              @else
                                  <option value="{{ $estado->IdEstadoCotizacion }}">{{ $estado->Descripcion }}</option>
                              @endif
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-2">
                  <x-selectorFiltrosFechas obtenerDatos='false' class="form-material" />
              </div>
              <div class="col-md-1 col-6 mt-4 order-md-2 text-center ">
                  <div class="form-groupr">
                      <br>
                      <button type="submit" class="btn btn-success">Buscar</button>
                  </div>
              </div>
              <div class="col-md-1 col-6 mt-4 order-md-2 text-center">
                  <div class="form-group ">
                      <br>
                      <a target="_blank"
                          href='{{ url("operaciones/cotizacion/excel-cotizacion/$idTipoAtencion/$idEstadoCotizacion/$fecha/$ini/$fin") }}'>
                          <span class="btn bg-excel ripple">
                              <i class="list-icon material-icons fs-20">explicit</i>XCEL
                          </span>
                      </a>
                  </div>
              </div>
          </div>
          <x-inputFechasPersonalizadas mostrarBoton='false' />
          <div class="row">
              <div class="col-12 d-flex flex-wrap justify-content-center justify-content-md-start">
                  <input hidden id="inputEstadoCotizacion" name="inputEstadoCotizacion" value="">
                  <button class="btn bg-abierto btnConsultarEstado" type="submit" value="1"><span>Abierto
                          ({{ $countAbierto }})</span></button>
                  <button class="btn bg-enProceso btnConsultarEstado" type="submit" value="2"><span>Proceso
                          ({{ $countEnProceso }})</span></button>
                  <button class="btn bg-finalizado btnConsultarEstado" type="submit" value="3"><span>Finalizado
                          ({{ $countFinalizado }})</span></button>
                  <button class="btn bg-cerrado btnConsultarEstado" type="submit" value="4"><span>Cerrado
                          ({{ $countCerrado }})</span></button>
                  <button class="btn bg-baja btnConsultarEstado" type="submit" value="6">Baja(<span
                          id="spanCantidadBaja">{{ $countBaja }}</span>)</button>
              </div>
          </div>
          {!! Form::close() !!}
      </div>
      <!-- /.container -->
      <!-- =================================== -->
      <!-- Different data widgets ============ -->
      <!-- =================================== -->
      <div class="container">
          <div class="widget-list">
              <div class="row">
                  <!--<div class="col-md-12 widget-holder">-->
                  <div class="widget-bg">
                      <!-- /.widget-heading -->
                      <div class="widget-body clearfix">
                          <!--<p>Listado de ventas</p>-->
                          <table id="table" class="table table-responsive-sm" style="width:100%">
                              <thead>
                                  <tr class="bg-success">
                                      <th scope="col">Fecha</th>
                                      <th scope="col">Cliente</th>
                                      @if ($modulosSelect->contains('IdModulo', 5))
                                          <th scope="col">Seguro</th>
                                      @endif
                                      <th scope="col">Placa</th>
                                      <th scope="col">Kilom.</th>
                                      <th scope="col">RUC/DNI</th>
                                      <th scope="col">Código</th>
                                      <th scope="col">Importe</th>
                                      <th scope="col">Año</th>
                                      <th scope="col">Marca</th>
                                      <th scope="col">Estado</th>
                                      <th scope="col">Modelo</th>
                                      <th scope="col">Tipo Atención</th>
                                      <th scope="col">Tipo Moneda</th>
                                      <th scope="col">Tipo Operación</th>
                                      <th scope="col">Observación</th>
                                      <th scope="col">Chasis/Vin</th>
                                      <th scope="col">CódigoInventario</th>
                                      <th scope="col">Opciones</th>
                                  </tr>

                              </thead>
                              <tbody>
                                  @foreach ($cotizaciones as $cotizacion)
                                      <tr>
                                          <td>{{ $cotizacion->FechaCreacion }}</td>
                                          <td>
                                              @if ($cotizacion->TipoCotizacion == 2)
                                                  <i class='material-icons'>time_to_leave</i> -
                                              @endif {{ $cotizacion->RazonSocial }}
                                          </td>
                                          @if ($modulosSelect->contains('IdModulo', 5))
                                              <td>{{ $cotizacion->Seguro }}</td>
                                          @endif
                                          <td>{{ $cotizacion->Placa }}</td>
                                          <td>{{ $cotizacion->Campo1 }}</td>
                                          <td>{{ $cotizacion->NumeroDocumento }}</td>
                                          <td>{{ $cotizacion->Serie }}-{{ $cotizacion->Numero }}</td>
                                          <td>{{ $cotizacion->Total }}</td>
                                          <td>{{ $cotizacion->Anio }}</td>
                                          <td>{{ $cotizacion->Marca }}</td>
                                          <td><span
                                                  class="btnEstadoCotizacion{{ $cotizacion->IdCotizacion }} {{ $cotizacion->Color }}">{{ $cotizacion->EstadoCoti }}</span>
                                          </td>
                                          <td>{{ $cotizacion->Modelo }}</td>
                                          <td>{{ $cotizacion->Atencion }}</td>
                                          <td>{{ $cotizacion->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                          <td>{{ $cotizacion->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                          <td>{{ $cotizacion->Observacion }}</td>
                                          <td>{{ $cotizacion->ChasisVehiculo }}</td>
                                          <td>{{ $cotizacion->CodigoInventario }}</td>
                                          <td class="text-center" width="15%">
                                              <section class="seccionAccion{{ $cotizacion->IdCotizacion }}">
                                                  <a href="../cotizacion/comprobante-generado/{{ $cotizacion->IdCotizacion }}"
                                                      title="Detalles"><i
                                                          class="list-icon material-icons">visibility</i></a>
                                                  @if ($cotizacion->IdEstadoCotizacion == 1 || $cotizacion->IdEstadoCotizacion == 2)
                                                      @if ($rol == 2 || $rol == 8)
                                                          <a href="../cotizacion/editar/{{ $cotizacion->IdCotizacion }}"
                                                              title="Editar"><i
                                                                  class="list-icon material-icons">edit</i></a>
                                                      @endif
                                                      @if ($cotizacion->PaquetePromocional == 0)
                                                          <a href="#" data-toggle="modal"
                                                              data-target=".bs-modal-lg-amortizar" title="Amortizar"
                                                              onclick="amortizar('{{ $cotizacion->IdCotizacion }}', '{{ $cotizacion->IdTipoMoneda }}', '{{ 'Pago Amortización ' . $cotizacion->Serie . '-' . $cotizacion->Numero . ' (' . $cotizacion->RazonSocial . ')' }}')"><i
                                                                  class="list-icon material-icons">credit_card</i></a>
                                                      @endif
                                                  @endif
                                                  @if ($cotizacion->IdEstadoCotizacion == 3)
                                                      <a href="../cotizacion/editar/{{ $cotizacion->IdCotizacion }}"
                                                          title="Editar descuentos"><i
                                                              class="list-icon material-icons">monetization_on</i></a>
                                                  @endif
                                                  @if ($cotizacion->TipoCotizacion == 2)
                                                      @if ($rol == 2 || $rol == 8 || $rol == 9)
                                                          <a href="../cotizacion/estados-cotizacion/{{ $cotizacion->IdCotizacion }}"
                                                              title="Estados"><i
                                                                  class="list-icon material-icons">build</i></a>
                                                      @endif
                                                  @endif
                                                  @if ($cotizacion->IdEstadoCotizacion == 1)
                                                      <a href="#" data-toggle="modal"
                                                          data-target=".bs-modal-sm-darBaja" title="Anular"
                                                          onclick="darBaja({{ $cotizacion->IdCotizacion }})"><i
                                                              class="list-icon material-icons text-danger">do_not_disturb</i></a>
                                                  @endif
                                                  <a target="_blank" class="p-1"
                                                      href="../cotizacion/comprobante-generado/W-{{ $cotizacion->IdCotizacion }}"><img
                                                          class="logo-expand" alt="" width="25"
                                                          src="{{ asset('assets/img/whatsapp.png') }}"
                                                          data-toggle="modal" data-target="#modalWhatsapp"></a>
                                                  @if ($cotizacion->IdEstadoCotizacion === 6)
                                                      {{-- Nuevo boton Activar Baja --}}
                                                      {{-- <a href="#"
                                                          class="btnReactivarCotizacion btnReactivarCotizacion{{ $cotizacion->IdCotizacion }}"
                                                          title="Reactivar Cotización"
                                                          data-info-cotizacion='{"IdCotizacion": {{ $cotizacion->IdCotizacion }}, "TipoCotizacion":{{ $cotizacion->TipoCotizacion }}, "Rol": {{ $rol }}}'><i
                                                              class='list-icon material-icons bx bxs-analyse'></i></a> --}}
                                                      <a href="#"
                                                          class="btnReactivarCotizacion btnReactivarCotizacion{{ $cotizacion->IdCotizacion }}"
                                                          title="Reactivar Cotización"
                                                          data-info-cotizacion='{"IdCotizacion": {{ $cotizacion->IdCotizacion }}, "TipoCotizacion":{{ $cotizacion->TipoCotizacion }}, "Rol": {{ $rol }}}'><i
                                                              class='list-icon material-icons bx bx-toggle-left fs-40 text-danger'></i></a>
                                                      <a href="#"
                                                          class="iconoActivo{{ $cotizacion->IdCotizacion }} d-none text-danger"><i
                                                              class='ist-icon material-icons fs-40 bx bxs-toggle-right'></i></a>
                                                  @endif
                                                  <a href="{{ route('operaciones.cotizacion.duplicar', [$cotizacion->IdCotizacion]) }}"
                                                      title="Duplicar Cotización"><i
                                                          class="list-icon material-icons">content_copy</i></a>
                                              </section>
                                          </td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                      <!-- /.widget-body -->
                  </div>
                  <!-- /.widget-bg -->
                  <!--</div>-->
                  <!-- /.widget-holder -->
              </div>
              <!-- /.row -->
          </div>
          <!-- /.widget-list -->
      </div>
      <!-- /.container -->

      <div class="modal modal-primary fade bs-modal-lg-amortizar" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog modal-sm">
              {!! Form::open([
                  'url' => '/operaciones/cotizacion/consultar-cotizacion/amortizar',
                  'method' => 'POST',
                  'files' => true,
                  'id' => 'myform',
                  'class' => 'form-material',
              ]) !!}
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Amortizar Cotización</h6>
                  </div>
                  <div class="modal-body">
                      <div class="container">
                          <div class="row mt-4">
                              <div class="col-md-12">
                                  <div class="form-group">
                                      <select class="form-control" id="tipoPago" name="tipoPago">
                                          <option value="1" selected>Pago Efectivo</option>
                                          <option value="2">POS</option>
                                          <option value="3">Transferencia</option>
                                      </select>
                                      <label>Seleccionar Forma de Pago</label>
                                  </div>
                                  <div id="efectivo" class="form-group">
                                      <label class="form-control-label">Monto Pagado(Efectivo)</label>
                                      <div class="input-group">
                                          <input id="pagoEfec" type="number" step="any" class="form-control"
                                              name="pagoEfectivo">
                                      </div>
                                  </div>
                              </div>
                              <div id="tarjeta" class="col-md-12">
                                  @if ($modulosSelect->contains('IdModulo', 2))
                                      <div class="form-group">
                                          <select id="tipoTarjeta" class="form-control" name="tipoTarjeta">
                                              <option value="1">Visa</option>
                                              <option value="2">MasterCard</option>
                                          </select>
                                          <label class="form-control-label">Tarjeta Crédito/Débito</label>
                                      </div>
                                      <div class="form-group">
                                          <label class="form-control-label">4 últimos dígitos</label>
                                          <div class="input-group">
                                              <input id="numTarjeta" type="text" class="form-control"
                                                  name="numTarjeta" minlength="4" maxlength="4">
                                          </div>
                                      </div>
                                      <div class="form-group">
                                          <label class="form-control-label">Monto Pagado(Con tarjeta)</label>
                                          <div class="input-group">
                                              <input id="pagoTarjeta" type="number" step="any" class="form-control"
                                                  name="pagoTarjeta">
                                          </div>
                                      </div>
                                  @endif
                              </div>
                              <div class="col-md-12" id="cuentaCorriente">
                                  <div class="form-group">
                                      <select class="form-control" id="cuentaBancaria" name="cuentaBancaria">
                                      </select>
                                      <label>Cuenta Bancaria</label>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-control-label">Numero Operación</label>
                                      <div class="input-group">
                                          <input id="nroOperacion" type="text" class="form-control"
                                              name="nroOperacion" disabled>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label class="form-control-label">Monto (Cuenta Bancaria)</label>
                                      <div class="input-group">
                                          <input id="pagoCuenta" type="number" step="any" class="form-control"
                                              name="montoCuenta" disabled>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          <input type="hidden" name="detalleMovimientoCuenta" id="detalleMovimientoCuenta">
                          <input id="inpCotizacion" hidden class="form-control" name="idCotizacion" />
                          <input id="inpTipoMoneda" hidden class="form-control" name="idTipoMoneda" />
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button id="btnEnvio" type="submit" class="btn btn-primary">Aceptar</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
              {!! Form::close() !!}
          </div>
      </div>

      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="text-success">Consultas de Cotizacion</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <label class="fs-14 negrita">Cotizaciones del Mes</label>
                          <p class="fs-15negrita">Se mostraran solo las cotizaciones de este mes....... Si desea ver
                              cotizaciones anteriores utilize los filtros</p>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <div class="form-actions btn-list mt-3">
                          <button class="btn btn-success" type="button" data-dismiss="modal">Aceptar</button>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <div class="modal modal-primary fade bs-modal-sm-darBaja" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog modal-md">
              {!! Form::open([
                  'url' => '/operaciones/cotizacion/consultar-cotizacion/dar-baja',
                  'method' => 'POST',
                  'files' => true,
                  'class' => 'form-material',
              ]) !!}
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Dar baja cotización</h6>
                  </div>
                  <div class="modal-body">
                      <div class="container">
                          <text>Desea dar de Baja esta Cotización?</text>
                          <br>
                          <text class="text-danger fs-12">Importante: Una vez dado de baja la cotización ya no se podrá
                              revertir</text>
                          <input id="inpCotiBaja" hidden name="idBajaCotizacion" />
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Dar baja</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
              {!! Form::close() !!}
          </div>
      </div>

      @include('modal._modalValidaSupervisor')
      <div class="modal fade" id="modalReactivarCotizacion" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-body">
                      <div class="form-group text-center mt-3">
                          <h6 for="formGroupExampleInput">Estas seguro de Reactivar la Cotización?</h6>
                          <button type="button" class="btn btn-secondary btnCancelarReactivacion"
                              data-dismiss="modal">Cancelar</button>
                          <x-buttonLoader nombreClase='btnLoaderReactivarCotizacion' accion='ReactivarCotizacion'>
                              @slot('textoBoton', 'Aceptar')
                              @slot('textoLoader', 'Reactivando')
                          </x-buttonLoader>
                      </div>
                  </div>
              </div>
          </div>
      </div>
  @stop

  <!-- Estas variables son usadas en el archivo assets/js/utilidades/scriptFechasPersonalizadas.js-->
  @section('variablesJs')
      <script>
          const variablesBlade = {
              fecha: @json($fecha),
              fechaInicial: @json($fechaInicial),
              fechaFinal: @json($fechaFinal),
          }
      </script>
  @endsection

  @section('scripts')
      <script>
          let infoCotizacion = '';
          $(document).on('click', function(e) {
              if (e.target.matches('.btnCancelarReactivacion') || e.target.matches(
                      '.btnCancelarReactivacion *')) {
                  $('.btnReactivarCotizacion').removeClass('d-none');
                  $(`.iconoActivo${infoCotizacion.IdCotizacion}`).addClass('d-none');
                  $('#password').val("");
              }

              if (e.target.matches('.btnReactivarCotizacion') || e.target.matches(
                      '.btnReactivarCotizacion *')) {
                  e.preventDefault();
                  $('#modalValidarClaveSupervisor').modal('show');
                  const elementoPadre = $(e.target).closest('.btnReactivarCotizacion');
                  infoCotizacion = $(elementoPadre).data('infoCotizacion');
              }

              if (e.target.matches('#btnValidarClave') || e.target.matches('#btnValidarClave *')) {
                  showButtonLoader('#btnValidarClave');
                  $('#textoMensaje').text('');
                  $('#password').removeClass('border-danger');
                  const password = $('#password').val();
                  const elementoPadre = $(e.target).closest('#btnValidarClave');
                  const uriValidarClaveSupervisor = elementoPadre.data('rutaValidarSupervisor')
                  if (password === '') {
                      hideButtonLoader('#btnValidarClave');
                      $('#textoMensaje').text('Por favor ingrese la clave')
                      $('#password').addClass('border-danger');
                      $('#password').focus();
                      return false;
                  }
                  $.ajax({
                      type: "get",
                      url: uriValidarClaveSupervisor,
                      data: {
                          'password': password
                      },
                      success: function(data) {
                          if (data[0] == 'Success') {
                              $('.btnReactivarCotizacion').addClass('d-none');
                              $(`.iconoActivo${infoCotizacion.IdCotizacion}`).removeClass('d-none');
                              hideButtonLoader('#btnValidarClave');
                              $('#modalValidarClaveSupervisor').modal('hide');
                              $('#modalReactivarCotizacion').modal({
                                  backdrop: 'static',
                                  keyboard: false
                              }).modal('show');
                          } else {
                              hideButtonLoader('#btnValidarClave');
                              $('#textoMensaje').text('Error la clave no coincide')
                              $('#password').val("");
                              $('#password').focus();
                              $('#password').addClass('border-danger');
                          }
                      }
                  })
              }

              if (e.target.matches('.btnLoaderReactivarCotizacion') || e.target.matches(
                      '.btnLoaderReactivarCotizacion *')) {
                  showButtonLoader('.btnLoaderReactivarCotizacion');
                  $.ajax({
                      type: "put",
                      url: "{{ route('cotizacion.revertir-estado') }}",
                      data: {
                          "_token": "{{ csrf_token() }}",
                          'idCotizacion': infoCotizacion.IdCotizacion
                      },
                      success: function(data) {
                          if (data.respuesta == 'error') {
                              alert('No se puedo Reactivar Cotización');
                              return false;
                          }
                          if (data.respuesta == 'success') {
                              hideButtonLoader('.btnLoaderReactivarCotizacion');
                              const cantidadBajas = $('#spanCantidadBaja').text();
                              const nuevaCantidadBajas = cantidadBajas - 1;
                              $('#spanCantidadBaja').text(nuevaCantidadBajas)

                              $(`.btnEstadoCotizacion${infoCotizacion.IdCotizacion}`).removeClass(
                                  'bg-baja');
                              $(`.btnEstadoCotizacion${infoCotizacion.IdCotizacion}`).addClass(
                                  'bg-abierto');
                              $(`.btnEstadoCotizacion${infoCotizacion.IdCotizacion}`).text('Abierto');
                              $(`.iconoActivo${infoCotizacion.IdCotizacion}`).remove();
                              const seccionAccion = $(`.seccionAccion${infoCotizacion.IdCotizacion}`);
                              seccionAccion.append(
                                  `<a href="#" data-toggle="modal" data-target=".bs-modal-sm-darBaja" title="Anular" onclick="darBaja(${infoCotizacion.IdCotizacion})"><i class="list-icon material-icons text-danger">do_not_disturb</i></a>`
                              );
                              if (infoCotizacion.Rol == 2 || infoCotizacion.Rol == 8) {
                                  seccionAccion.append(
                                      `<a href="../cotizacion/editar/${infoCotizacion.IdCotizacion}" title="Editar"><i class="list-icon material-icons">edit</i></a>`
                                  );
                              }
                              //   if (infoCotizacion.TipoCotizacion == 2 && (infoCotizacion.Rol == 2 ||
                              //           infoCotizacion.Rol == 8 || infoCotizacion.Rol == 9)) {
                              //       seccionAccion.append(
                              //           `<a href="../cotizacion/estados-cotizacion/${infoCotizacion.IdCotizacion}" title="Estados"><i class="list-icon material-icons">build</i></a>`
                              //       );
                              //   }
                              $('#modalReactivarCotizacion').modal('hide');
                          }
                      }
                  })
              }
          })
      </script>
      <script>
          $('.btnConsultarEstado').click(function(e) {
              e.preventDefault();
              const idCotizacion = $(this).val();
              $("#inputEstadoCotizacion").val(idCotizacion);
              $('#formulario').submit();
          })
      </script>
      <script>
          $(function() {
              var bandModal = <?php echo json_encode($IdTipoPago); ?>;

              if (bandModal === '') {
                  $("#mostrarmodal").modal("show");
              }
              $('#tarjeta').hide();
              $('#cuentaCorriente').hide();
          });

          function redondeo(num) {
              /*var flotante = parseFloat(numero);
              var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
              return resultado;*/

              if (!num || num == 'NaN') return '-';
              if (num == 'Infinity') return '&#x221e;';
              num = num.toString().replace(/\$|\,/g, '');
              if (isNaN(num))
                  num = "0";
              sign = (num == (num = Math.abs(num)));
              num = Math.floor(num * 100 + 0.50000000001);
              cents = num % 100;
              num = Math.floor(num / 100).toString();
              if (cents < 10)
                  cents = "0" + cents;
              for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                  num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
              return (((sign) ? '' : '-') + num + '.' + cents);
          }

          function amortizar(id, tipoMoneda, detalle) {
              $('#inpCotizacion').val(id);
              $('#inpTipoMoneda').val(tipoMoneda);
              if (tipoMoneda == 1) {
                  cuentas = <?php echo json_encode($cuentasSoles); ?>;
              } else {
                  cuentas = <?php echo json_encode($cuentasDolares); ?>;
              }
              $('#detalleMovimientoCuenta').val(detalle);
              $('#cuentaBancaria option').remove();
              $('#cuentaBancaria').append('<option selected value="0">Seleccione cuenta bancaria</option>');
              for (var j = 0; j < cuentas.length; j++) {
                  $('#cuentaBancaria').append('<option value="' + cuentas[j]["IdBanco"] + '">' + cuentas[j]["Banco"] + ' - ' +
                      cuentas[j]["NumeroCuenta"] + '</option>');
              }
          }

          function filtroDirecto(estado) {
              //$('#btnEnvio').on('click', function () {
              /*var myForm = $("form#myform");
              if(myForm) {
                  $(this).attr('disabled', true);
                  $(myForm).submit();
              } */
              //});
              //$('<form action="consultar-cotizacion" method="POST"><input type="text" value="'+estado+'" name="estadoCotizacion"><input></form>').appendTo('body').submit();
              $.ajax({
                  type: 'post',
                  url: 'consultar-cotizacion-ajax',
                  data: {
                      'estado': estado
                  },
                  success: function(result) {
                      //console.log(result);
                      /*if(result.error)
                        					{
                        						$('#serie').val('');
                        				    	$('#numero').val('');

                        						alert('Seleccione el Motivo');
                        					}
                        					else
                        					{
                        				    	$('#serie').val(result.serie);
                        				    	$('#numero').val(result.numero);
                        					}*/
                  }
              });
          }

          function darBaja(id) {
              $('#inpCotiBaja').val(id);
          }

          $("#cuentaBancaria").on('change', function() {
              var tipoBan = $("#cuentaBancaria").val();
              if (tipoBan == "0") {
                  $('#pagoCuenta').attr("disabled", true);
                  $('#nroOperacion').attr("disabled", true);
                  $('#pagoCuenta').val('0');
              } else {
                  $('#pagoCuenta').attr("disabled", false);
                  $('#nroOperacion').attr("disabled", false);
              }
          });

          $("#tipoPago").on('change', function() {
              var tipo = $("#tipoPago").val();
              if (tipo == "1") {
                  $('#efectivo').show();
                  $('#tarjeta').hide();
                  $('#cuentaCorriente').hide();
              }
              if (tipo == "2") {
                  $('#efectivo').hide();
                  $('#tarjeta').show();
                  $('#cuentaCorriente').hide();
              }
              if (tipo == "3") {
                  $('#efectivo').hide();
                  $('#tarjeta').hide();
                  $('#cuentaCorriente').show();
              }

          });

          $('#btnEnvio').on('click', function() {
              var myForm = $("form#myform");
              if (myForm) {
                  $(this).attr('disabled', true);
                  $(myForm).submit();
              }
          });
      </script>
      <script type="text/javascript">
          $(function() {
              $(document).ready(function() {
                  $('#table').DataTable({
                      responsive: true,
                      "order": [
                          [0, "desc"]
                      ],
                      language: {
                          processing: "Procesando...",
                          search: "Buscar:",
                          lengthMenu: "Mostrar _MENU_ registros",
                          info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                          infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                          infoFiltered: "",
                          infoPostFix: "",
                          loadingRecords: "Cargando...",
                          zeroRecords: "No se encontraron resultados",
                          emptyTable: "Ningún dato disponible en esta tabla",
                          paginate: {
                              first: "Primero",
                              previous: "Anterior",
                              next: "Siguiente",
                              last: "Último"
                          },
                          aria: {
                              sortAscending: ": Activar para ordenar la columna de manera ascendente",
                              sortDescending: ": Activar para ordenar la columna de manera descendente"
                          }
                      }
                  });
              });
          });
      </script>
  @stop
