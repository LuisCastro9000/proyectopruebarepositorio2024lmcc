  @extends('layouts.app')
  @section('title', 'Ingresos / Egresos')
  @section('content')
      <style>
          .no-activo {
              pointer-events: none;
              cursor: default;
          }
      </style>
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Detalles Ingresos / Egresos</h6>
              </div>
          </div>
          @if (session('status'))
              <div class="alert alert-success">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('status') }}
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
              <div class="row justify-content-center">
                  <div class="widget-bg">
                      <div class="widget-heading clearfix">
                          <div class="col-12 mr-b-20">
                              <div class="row">
                                  <div class="col-sm-6 mt-1">
                                      <a href="javascript:void(0);" data-toggle="modal" data-target=".bs-modal-ingreso"
                                          onclick="modalIngreso(1)"><button
                                              class="btn btn-block btn-outline-success ripple"><i
                                                  class="list-icon material-icons fs-22">add</i>Ingresos Soles</button></a>
                                  </div>
                                  @if ($subniveles->contains('IdSubNivel', 46))
                                      <div class="col-sm-6 mt-1">
                                          <a href="javascript:void(0);" data-toggle="modal" data-target=".bs-modal-ingreso"
                                              onclick="modalIngreso(2)"><button
                                                  class="btn btn-block btn-outline-success ripple"><i
                                                      class="list-icon material-icons fs-22">add</i>Ingresos
                                                  Dólares</button></a>
                                      </div>
                                  @endif
                                  <div class="col-sm-6 mt-1">
                                      <a href="javascript:void(0);" data-toggle="modal" data-target=".bs-modal-egreso"
                                          onclick="modalEgreso(1)"><button
                                              class="btn btn-block btn-outline-danger ripple"><i
                                                  class="list-icon material-icons fs-22">add</i>Egresos Soles</button></a>
                                  </div>
                                  @if ($subniveles->contains('IdSubNivel', 46))
                                      <div class="col-sm-6 mt-1">
                                          <a href="javascript:void(0);" data-toggle="modal" data-target=".bs-modal-egreso"
                                              onclick="modalEgreso(2)"><button
                                                  class="btn btn-block btn-outline-danger ripple"><i
                                                      class="list-icon material-icons fs-22">add</i>Egresos
                                                  Dólares</button></a>
                                      </div>
                                  @endif
                              </div>
                              {{-- Nuevo codgio 03/04/2023 --}}
                              <div class="row mt-4">
                                  @if ($subniveles->contains('IdSubNivel', 46))
                                      <div class="col-sm-6 col-md-3">
                                          <div class="card bg-success p-1 text-center fs-16">
                                              Total Ingresos Soles
                                              <br>
                                              <span class="fs-20">S/.
                                                  {{ number_format($totalIngresoSoles, '2', '.', ',') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-sm-6 col-md-3">
                                          <div class="card bg-success p-1 text-center fs-16">
                                              Total Ingresos Dólares <br>
                                              <span class="fs-20">$
                                                  {{ number_format($totalIngresoDolares, '2', '.', ',') }}</span>
                                          </div>
                                      </div>
                                  @else
                                      <div class="col-12 col-md-6">
                                          <div class="card bg-success p-1 text-center fs-16">
                                              Total Ingresos Soles
                                              <br>
                                              <span class="fs-20">S/.
                                                  {{ number_format($totalIngresoSoles, '2', '.', ',') }}</span>
                                          </div>
                                      </div>
                                  @endif
                                  @if ($subniveles->contains('IdSubNivel', 46))
                                      <div class="col-sm-6 col-md-3">
                                          <div class="card bg-danger p-1 text-center fs-16">
                                              Total Egresos Soles
                                              <br>
                                              <span class="fs-20">S/.
                                                  {{ number_format($totalEgresoSoles, '2', '.', ',') }}</span>
                                          </div>
                                      </div>
                                      <div class="col-sm-6 col-md-3">
                                          <div class="card bg-danger p-1 text-center fs-16">
                                              Total Egresos Dólares <br>
                                              <span class="fs-20">$
                                                  {{ number_format($totalEgresoDolares, '2', '.', ',') }}</span>
                                          </div>
                                      </div>
                                  @else
                                      <div class="col-12 col-md-6">
                                          <div class="card bg-danger p-1 text-center fs-16">
                                              Total Egresos Soles
                                              <br>
                                              <span class="fs-20">S/.
                                                  {{ number_format($totalEgresoSoles, '2', '.', ',') }}</span>
                                          </div>
                                      </div>
                                  @endif
                              </div>
                              {{-- Fin --}}
                              <div class="row mt-4">
                                  <div class="col-12">
                                      <section class="d-flex justify-content-sm-end justify-content-center">
                                          <button type="button" class="btn btn-primary btnEditarConClaveSupervisor">
                                              Ingresar Clave
                                          </button>
                                      </section>
                                  </div>
                              </div>
                              <br>
                              <table id="table" width="100%" class="table table-responsive-xl">
                                  <thead>
                                      <tr class="bg-primary">
                                          <th scope="col">Fecha - Hora</th>
                                          <th scope="col">Tipo</th>
                                          <th scope="col">Descripción</th>
                                          <th scope="col">Tipo Moneda</th>
                                          <th scope="col">Monto</th>
                                          <th scope="col" class="text-center">Editar</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($ingresosEgresos as $ingreEgre)
                                          <tr>
                                              <td>{{ $ingreEgre->Fecha }}</td>
                                              @if ($ingreEgre->Tipo == 'I')
                                                  <td>Ingreso</td>
                                              @else
                                                  <td>Egreso</td>
                                              @endif

                                              <input hidden type="text" class="form-control d-none"
                                                  id="id-{{ $ingreEgre->IdIngresEgreso }}"
                                                  value="{{ $ingreEgre->IdIngresEgreso }}">

                                              <td><span id="spanDescripcion-{{ $ingreEgre->IdIngresEgreso }}">
                                                      {{ $ingreEgre->Descripcion }}</span>
                                                  <input hidden type="text"
                                                      id="inputDescripcion-{{ $ingreEgre->IdIngresEgreso }}"
                                                      value="{{ $ingreEgre->Descripcion }}">
                                              </td>
                                              @if ($ingreEgre->IdTipoMoneda == 1)
                                                  <td>Soles</td>
                                              @else
                                                  <td>Dólares</td>
                                              @endif
                                              <td><span
                                                      id="spanMonto-{{ $ingreEgre->IdIngresEgreso }}">{{ $ingreEgre->Monto }}</span><input
                                                      hidden type="text" name=""
                                                      id="inputMonto-{{ $ingreEgre->IdIngresEgreso }}"
                                                      value="{{ $ingreEgre->Monto }}">
                                              </td>
                                              <td class="text-center" id="fila-{{ $ingreEgre->IdIngresEgreso }}">
                                                  <a class="fs-12 no-activo acciones" title="Editar"
                                                      onclick="editarEgresoIngreso({{ $ingreEgre->IdIngresEgreso }})"
                                                      href="javascript:void(0);">
                                                      <i class="list-icon material-icons" id="btnEditar">edit</i>
                                                  </a>

                                                  <a class="fs-12 d-none" id="cancelar-{{ $ingreEgre->IdIngresEgreso }}"
                                                      title="Cancelar Editar"
                                                      onclick=" cancelarEdicion({{ $ingreEgre->IdIngresEgreso }})"
                                                      href="javascript:void(0);">
                                                      <i class="list-icon material-icons" id="btnEditar">cancel</i>
                                                  </a>
                                                  @if ($ingreEgre->IdGastos != '')
                                                      <input type="text" value="{{ $ingreEgre->IdGastos }}"
                                                          id="inputIdGasto-{{ $ingreEgre->IdIngresEgreso }}" hidden>
                                                  @else
                                                      <input type="text" value="0"
                                                          id="inputIdGasto-{{ $ingreEgre->IdIngresEgreso }}" hidden>
                                                  @endif
                                                  <input type="text" value="" name=""
                                                      id="inputActualizarGasto-{{ $ingreEgre->IdIngresEgreso }}" hidden>
                                              </td>
                                          </tr>
                                      @endforeach
                                  </tbody>
                              </table>
                              <div class="row mt-4">
                                  <div class="col mb-3">
                                      <section class="d-flex justify-content-md-end justify-content-center">
                                          <button id="btnActualizar" type="button" class="btn btn-primary d-none"
                                              onclick="actualizarDatos()"> Guardar Cambios
                                          </button>
                                      </section>
                                  </div>
                                  <div class="col-12">
                                      <section class="d-flex justify-content-end">
                                          <span>Antes de Editar un Ingreso/Egreso debe Ingresar la CLAVE SUPERVISOR</span>
                                      </section>
                                  </div>
                              </div>
                          </div>
                      </div>
                      <!-- /.widget-heading -->

                      <!-- /.widget-body -->
                  </div>
                  <!-- /.widget-holder -->
              </div>
              <!-- /.row -->
          </div>
          <!-- /.widget-list -->
          {{-- Modal comprobar Permiso --}}
          @include('modal._modalValidaSupervisor')
          {{-- Fin --}}


          <div class="modal modal-primary fade bs-modal-ingreso" tabindex="-1" role="dialog"
              aria-labelledby="tituloIngreso" aria-hidden="true" style="display: none">
              <div class="modal-dialog modal-md">
                  <div class="modal-content form-material">
                      <div class="modal-header text-inverse">
                          <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                          <h6 class="modal-title" id="tituloIngreso">Generar Ingreso</h6>
                      </div>
                      <div class="modal-body">
                          <div class="container">
                              <div class="form-group">
                                  <label id="tituloMontoIngreso">Ingrese monto (S/):</label>
                                  <input id="montoIngreso" type="number" step="any" class="form-control"
                                      name="montoIngreso" />
                              </div>
                              <div class="form-group">
                                  <label>Descripción</label>
                                  <input id="descIngreso" type="text" step="any" class="form-control"
                                      name="descripcionIngreso" />
                              </div>
                              <input id="tipoMonedaIngreso" type="text" class="form-control" hidden />
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button id="btnIngreso" type="button" onclick="generarIngreso();" class="btn btn-primary"
                              data-dismiss="modal">Aceptar</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                      </div>
                  </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>

          <div class="modal modal-primary fade bs-modal-egreso" role="dialog" aria-labelledby="tituloEgreso"
              aria-hidden="true" style="display: none">
              <div class="modal-dialog modal-md">
                  <div class="modal-content form-material">
                      <div class="modal-header text-inverse">
                          <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                          <h6 class="modal-title" id="tituloEgreso">Generar Egreso</h6>
                      </div>
                      <div class="modal-body">
                          <div class="container">
                              <div class="form-group">
                                  <label id="tituloMontoEgreso">Ingrese monto (S/):</label>
                                  <input id="montoEgreso" type="number" step="any" class="form-control"
                                      name="montoEgreso" />
                              </div>
                              <div class="form-group">
                                  <label>Descripción</label>
                                  <input id="descEgreso" type="text" step="any" class="form-control"
                                      name="descripcionEgreso" />
                              </div>
                              {{-- Nuevo Cogido Registrar como gasto --}}
                              @if ($subpermisos->contains('IdSubPermisos', 31))
                                  <div id="contenedorEgresoGasto">
                                      <section class="custom-control custom-checkbox mb-4" id="contenedorCheckGasto">
                                          <input type="checkbox" class="custom-control-input" id="checkActivarGasto"
                                              name="checkGasto" value="0">
                                          <label class="custom-control-label text-danger" for="checkActivarGasto"
                                              id="movimientoBanco">Registrar simultáneamente este EGRESO como un gasto
                                          </label>
                                      </section>

                                      <section id="contenedorGastos" class="row d-none">
                                          <div class="col-12">
                                              <label for="exampleFormControlSelect1">Seleccionar Tipo de Gasto</label>
                                              <select class="custom-select" id="tipoGasto" name="tipoGasto">
                                                  <option value="0">-</option>
                                                  <option value="1">Fijo</option>
                                                  <option value="2">Variable</option>
                                              </select>
                                          </div>
                                          <div class="col-12 mt-3">
                                              <div class="form-group">
                                                  <small class="text-muted"><strong>SELECCIONE ITEM</strong></small>
                                                  <select id="listaGastos"
                                                      class="m-b-10 form-control select2-hidden-accessible"
                                                      name="listaGastos" data-placeholder="Seleccionar Opción"
                                                      data-toggle="select2" tabindex="-1" aria-hidden="true">
                                                  </select>
                                                  <span
                                                      class="text-danger font-size">{{ $errors->first('listaGastos') }}</span>
                                              </div>
                                          </div>
                                          <div class="col-12">
                                              <div class="form-group">
                                                  <label>Observación</label>
                                                  <textarea id="observacion" class="form-control" rows="4" name="observacion"></textarea>
                                                  <span
                                                      class="text-danger font-size">{{ $errors->first('observacion') }}</span>
                                              </div>
                                          </div>
                                      </section>
                                  </div>
                              @endif
                              {{-- Fin --}}
                              <input id="tipoMonedaEgreso" type="text" class="form-control" hidden />
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button id="btnEgreso" type="button" onclick="generarEgreso();" class="btn btn-primary"
                              data-dismiss="modal">Aceptar</button>
                          <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                      </div>
                  </div>
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
      </div>
      <!-- /.container -->
  @stop

  @section('scripts')

      <script src="{{ asset('assets/js/respuestaAjax/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
      <script src="{{ asset('assets/js/scriptGlobal/script.js?v=' . time()) }}"></script>
      {{-- Funciones nuevas registrar como gasto --}}
      <script>
          $("#checkActivarGasto").click(function() {
              if ($(this).is(':checked')) {
                  $('#contenedorGastos').removeClass('d-none');
              } else {
                  $('#contenedorGastos').addClass('d-none')
              }
          });

          $("#tipoGasto").on('change', function() {
              var tipo = $("#tipoGasto").val();
              $.ajax({
                  type: 'get',
                  url: 'traer-gastos',
                  data: {
                      'tipo': tipo
                  },
                  success: function(data) {
                      $('#listaGastos option').remove();
                      for (var i = 0; i < data.length; i++) {
                          $('#listaGastos').append('<option value="' + data[i][
                                  "IdListaGastos"
                              ] + '">' + data[i]["Descripcion"] +
                              '</option>');
                      }
                  }
              });
          });
      </script>
      {{-- Fin --}}
      <script>
          const isValidacionClaveSupervisorSuccess = () => {
              ocultarLoader('#btnValidarClave');
              $("#modalValidarClaveSupervisor").modal('hide');
              swal("Permiso Concedido", {
                  icon: "success",
                  buttons: false,
                  timer: 1500
              });
              $('#password').val("");
              $('.acciones').removeClass('no-activo');
              $('#btnActualizar').removeClass('d-none');
          };
      </script>

      <script>
          function editarEgresoIngreso(id) {
              if ($('#inputIdGasto-' + id).val() != 0) {
                  swal({
                      title: "Támbien desea actualizar el monto del gasto?",
                      text: "Este Egreso esta asociado a un gasto",
                      buttons: ["Cancelar", "Aceptar"],
                      //   dangerMode: true,
                      closeOnClickOutside: false,
                      closeOnEsc: false,
                  }).then((willActualizar) => {
                      if (willActualizar) {
                          $('#inputActualizarGasto-' + id).attr('value', 'activo');
                      } else {
                          $('#inputActualizarGasto-' + id).attr('value', 'desactivado');
                      }
                  })

              } else {
                  $('#inputActualizarGasto-' + id).attr('value', 'desactivado');
              }

              $('#spanMonto-' + id).addClass('d-none');
              $('#spanDescripcion-' + id).addClass('d-none');
              $("#inputMonto-" + id).removeAttr('hidden');
              $("#inputDescripcion-" + id).removeAttr('hidden');
              $('#inputActualizarGasto-' + id).attr('name', 'actualizarGasto[]');
              $('#inputIdGasto-' + id).attr('name', 'idGasto[]');
              $('#inputDescripcion-' + id).attr('name', 'Descripcion[]');
              $('#inputMonto-' + id).attr('name', 'Monto[]');
              $('#id-' + id).attr('name', 'id[]');
              $('#cancelar-' + id).removeClass('d-none')
          }


          function cancelarEdicion(id) {
              $('#inputDescripcion-' + id).removeAttr('name', 'Descripcion[]');
              $('#inputMonto-' + id).removeAttr('name', 'Monto[]');
              $('#id-' + id).removeAttr('name', 'id[]');

              $('#inputActualizarGasto-' + id).removeAttr('name', 'actualizarGasto[]');
              $('#inputIdGasto-' + id).removeAttr('name', 'idGasto[]');
              $('#spanMonto-' + id).removeClass('d-none');
              $('#spanDescripcion-' + id).removeClass('d-none');
              $("#inputMonto-" + id).attr('hidden', 'hidden');
              $("#inputDescripcion-" + id).attr('hidden', 'hidden');
              $('#cancelar-' + id).addClass('d-none')
          }

          function actualizarDatos() {

              var descripcion = $("input[name='Descripcion[]']").map(function() {
                  return $(this).val();
              }).get();
              var montos = $("input[name='Monto[]']").map(function() {
                  return $(this).val();
              }).get();
              var Ids = $("input[name='id[]']").map(function() {
                  return $(this).val();
              }).get();

              var actualizarGasto = $("input[name='actualizarGasto[]']").map(function() {
                  return $(this).val();
              }).get();

              var idGasto = $("input[name='idGasto[]']").map(function() {
                  return $(this).val();
              }).get();

              if (Ids == 0) {
                  swal({
                      title: "No hay cambios?",
                      icon: "error",
                  });
              } else {
                  swal({
                          title: "Estas seguro de Actualizar?",
                          text: "Una vez actualizado, no podrá recuperar los datos Anteriores!",
                          icon: "warning",
                          buttons: true,
                          dangerMode: true,
                      })
                      .then((willActualizar) => {
                          if (willActualizar) {
                              $.ajax({
                                  type: 'post',
                                  url: 'actualizar-egreso-ingreso',
                                  data: {
                                      "_token": "{{ csrf_token() }}",
                                      "monto": montos,
                                      "descripcion": descripcion,
                                      "id": Ids,
                                      "actualizarGasto": actualizarGasto,
                                      "idGasto": idGasto
                                  },
                                  success: function(data) {
                                      swal({
                                              title: "Se actualizo Correctamente!",
                                              icon: "success",
                                              button: "Entendido",
                                          })
                                          .then((Entendido) => {
                                              if (Entendido) {
                                                  window.location = 'ingresos-egresos';
                                              }
                                          });
                                  }
                              })
                          }
                      });
              }
          }
      </script>

      <script>
          function generarIngreso() {
              $("#btnIngreso").attr("disabled", true);
              $.ajax({
                  type: 'post',
                  url: 'generar-ingreso',
                  data: {
                      "_token": "{{ csrf_token() }}",
                      "montoIngreso": $('#montoIngreso').val(),
                      "descIngreso": $('#descIngreso').val(),
                      "tipoMoneda": $("#tipoMonedaIngreso").val()
                  },
                  success: function(data) {
                      //   alert(data);
                      //   window.location = 'ingresos-egresos';
                      $("#btnIngreso").attr("disabled", false);
                      if (data[0] == 'error') {
                          swal("Registro Fallido!", data[1], "error", {
                              button: "Entendido",
                          }).then((value) => {
                              $('.bs-modal-ingreso').modal('show');
                          });
                      } else {
                          $('.bs-modal-ingreso').modal('hide');
                          swal("Registro Exitoso!", "Los datos enviados han sido Almacenados", "success", {
                              button: "Entendido",
                          }).then((value) => {
                              window.location = 'ingresos-egresos';
                          });
                      }
                  }
              });
          }

          function generarEgreso() {
              $("#btnEgreso").attr("disabled", true);
              if ($('#checkActivarGasto').is(":checked")) {
                  $('#checkActivarGasto').val('1');
              } else {
                  $('#checkActivarGasto').val('0');
              }
              $.ajax({
                  type: 'post',
                  url: 'generar-egreso',
                  data: {
                      "_token": "{{ csrf_token() }}",
                      "montoEgreso": $('#montoEgreso').val(),
                      "descEgreso": $('#descEgreso').val(),
                      "tipoMoneda": $("#tipoMonedaEgreso").val(),
                      "checkActivarGasto": $('#checkActivarGasto').val(),
                      "tipoGasto": $('#tipoGasto').val(),
                      "idGasto": $('#listaGastos').val(),
                      "observacion": $('#observacion').val()
                  },
                  success: function(data) {
                    $("#btnEgreso").attr("disabled", false);
                      if (data[0] == 'error') {
                          swal("Registro Fallido!", data[1], "error", {
                              button: "Entendido",
                          }).then((value) => {
                              $('.bs-modal-egreso').modal('show');
                          });
                      } else {
                          $('.bs-modal-egreso').modal('hide');
                          swal("Registro Exitoso!", "Los datos enviados han sido Almacenados", "success", {
                              button: "Entendido",
                          }).then((value) => {
                              window.location = 'ingresos-egresos';
                          });
                      }
                  }
              });
          }

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

          function modalIngreso(tipo) {
              $("#tipoMonedaIngreso").val(tipo);
              if (tipo == 1) {
                  $("#tituloIngreso").text("Generar Ingreso Soles");
                  $("#tituloMontoIngreso").text("Ingrese Monto (S/)");
              } else {
                  $("#tituloIngreso").text("Generar Ingreso Dólares");
                  $("#tituloMontoIngreso").text("Ingrese Monto ($)");
              }
          }

          function modalEgreso(tipo) {
              $("#tipoMonedaEgreso").val(tipo);
              if (tipo == 1) {
                  $("#tituloEgreso").text("Generar Egreso Soles");
                  $("#tituloMontoEgreso").text("Ingrese Monto (S/)");
                  $("#contenedorEgresoGasto").removeClass("d-none");
              } else {
                  $("#tituloEgreso").text("Generar Egreso Dólares");
                  $("#tituloMontoEgreso").text("Ingrese Monto ($)");
                  $("#contenedorEgresoGasto").addClass("d-none");
                  $('#checkActivarGasto').prop("checked", false);
                  $('#contenedorGastos').addClass('d-none')
              }
          }
      </script>

      <script>
          $(function() {
              $(document).ready(function() {
                  $('#table').DataTable({
                      responsive: true,
                      "order": [
                          [0, "desc"]
                      ],
                      searching: false,
                      bPaginate: false,
                  });
              });
          });
      </script>
  @stop
