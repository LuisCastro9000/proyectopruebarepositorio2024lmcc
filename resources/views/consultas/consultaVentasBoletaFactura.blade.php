  @extends('layouts.app')
  @section('title', 'Consulta Ventas Boletas - Facturas')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Ventas</h6>
              </div>
              <!-- /.page-title-left -->
              <!--<div class="page-title-right">
                                                                                                                                                                                                        <div class="row mr-b-50 mt-2">
                                                                                                                                                                                                            <div class="col-12 mr-b-20 d-sm-block d-none">
                                                                                                                                                                                                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                            <div class="col-12 mr-b-20 d-sm-none d-block">
                                                                                                                                                                                                                <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
                                                                                                                                                                                                            </div>
                                                                                                                                                                                                        </div>
                                                                                                                                                                                                    </div>-->
              <!-- /.page-title-right -->
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

          {!! Form::open(['url' => '/consultas/ventas-boletas-facturas', 'method' => 'POST', 'files' => true]) !!}
          {{ csrf_field() }}
          <div class="row clearfix">
              <div class="col-md-4 mt-4 order-md-1">
                  <div class="form-group form-material">
                      <label>Tipo Pago</label>
                      <select id="tipoPago" class="form-control" name="tipoPago">
                          <option value="0">Todo</option>
                          <option value="1">Contado</option>
                          <option value="2">Crédito</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4 mt-4 order-md-2">
                  {{-- <div class="form-group form-material">
                      <label>Fecha</label>
                      <select id="idFecha" class="form-control" name="fecha">
                          <option value="0">Todo</option>
                          <option value="1">Hoy</option>
                          <option value="2">Ayer</option>
                          <option value="3">Esta semana</option>
                          <option value="4">Última semana</option>
                          <option value="5">Este mes</option>
                          <option value="6">Último mes</option>
                          <option value="7">Este año</option>
                          <option value="8">Último año</option>
                          <option value="9">Personalizar</option>
                      </select>
                  </div> --}}
                  <x-selectorFiltrosFechas obtenerDatos="false" class="form-material" />
              </div>
              <div class="col-md-3 mt-4 order-md-3 order-last">
                  <div class="form-group">
                      <br>
                      <button type="submit" class="btn btn-primary">Buscar</button>
                  </div>
              </div>
              {{-- <div class="col-md-3 mt-4 order-md-4">
                  <div id="Inicio" class="form-group">
                      <label class="form-control-label">Desde</label>
                      <div class="input-group">
                          <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                              data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-5">
                  <div id="Final" class="form-group">
                      <label class="form-control-label">Hasta</label>
                      <div class="input-group">
                          <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                              data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                      </div>
                  </div>
              </div> --}}
          </div>
          <x-inputFechasPersonalizadas mostrarBoton="false" />
          {!! Form::close() !!}
          <!-- /.page-title -->
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
                      <!--<div class="widget-heading clearfix">
                                                                                                                                                                                                                    <h5>TableSaw</h5>
                                                                                                                                                                                                                </div>-->
                      <!-- /.widget-heading -->
                      <div class="widget-body clearfix">
                          <!--<p>Listado de ventas</p>-->
                          <table id="table" class="table table-responsive-sm" style="width:100%">
                              <thead>
                                  <tr class="bg-primary">
                                      <th scope="col">Fecha</th>
                                      <th scope="col">Cliente</th>
                                      <th scope="col">Código</th>
                                      <th scope="col">Tipo Comprob.</th>
                                      <th scope="col">Tipo Pago</th>
                                      <th scope="col">Tipo de Venta</th>
                                      <th scope="col">Gener. Nota</th>
                                      <th scope="col">Importe</th>
                                      <th scope="col">Amortización</th>
                                      <th scope="col">Estado</th>
                                      <th scope="col">Tipo Moneda</th>
                                      <th scope="col">Codigo Error</th>
                                      <th scope="col">Opciones</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($facturasVentas as $factura)
                                      <tr>
                                          <td>{{ $factura->FechaCreacion }}</td>
                                          <td>{{ $factura->RazonSocial }}</td>
                                          <td>{{ $factura->Serie }}-{{ $factura->Numero }}</td>
                                          <td>{{ $factura->Descripcion }}</td>
                                          @if ($factura->IdTipoPago == 1)
                                              <td>Contado</td>
                                          @else
                                              <td>Crédito</td>
                                          @endif
                                          <td>{{ $factura->TipoVenta == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                          <td>{{ $factura->TipoNota }}</td>
                                          <td>{{ $factura->Total }}</td>
                                          <td>{{ $factura->Amortizacion }}</td>
                                          <td>{{ $factura->Estado }}</td>
                                          <td>{{ $factura->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                          <td class="text-center">
                                              {{ $factura->CodigoDoc == 0 ? '-' : $factura->CodigoDoc }}</td>
                                          <td class="text-center" width="15%">
                                              <a href="../operaciones/ventas/comprobante-generado/{{ $factura->IdVentas }}"
                                                  title="Detalles"><i class="list-icon material-icons">visibility</i></a>
                                              <a href="../operaciones/ventas/descargar/{{ $factura->IdVentas }}"
                                                  title="Descargar PDF"><i
                                                      class="list-icon material-icons">picture_as_pdf</i></a>
                                              @if ($factura->IdTipoComprobante != 3)
                                                  <a href="../operaciones/ventas/xml/{{ $rucEmpresa }}/{{ $factura->IdVentas }}"
                                                      title="Descargar XML"><i class="list-icon material-icons">code</i></a>
                                                  @if ($factura->Estado == 'Aceptado')
                                                      @if ($factura->IdTipoComprobante == 2)
                                                          <a href="../operaciones/ventas/cdr/{{ $rucEmpresa }}/{{ $factura->IdVentas }}"
                                                              title="Descargar CDR"><i
                                                                  class="list-icon material-icons">attach_file</i></a>
                                                      @endif
                                                      @if ($factura->Nota <= 0 && $factura->Guia == 0)
                                                          @if ($factura->FechaCreacion > $dateAtras)
                                                              <a href="#" data-toggle="modal"
                                                                  data-target=".bs-modal-sm-anular" title="Anular"
                                                                  onclick="anular({{ $factura->IdVentas }}, {{ $factura->IdTipoMoneda }})"><i
                                                                      class="list-icon material-icons text-danger">do_not_disturb</i></a>
                                                          @endif
                                                      @endif
                                                  @endif
                                              @else
                                                  @if ($factura->Estado != 'Baja Ticket')
                                                      @if ($factura->FechaCreacion > $dateAtrasTicket)
                                                          <a href="#" data-toggle="modal"
                                                              data-target=".bs-modal-sm-anular" title="Anular"
                                                              onclick="anular({{ $factura->IdVentas }}, {{ $factura->IdTipoMoneda }})"><i
                                                                  class="list-icon material-icons text-danger">do_not_disturb</i></a>
                                                      @endif
                                                  @endif
                                              @endif
                                              <a target="_blank" class="p-1"
                                                  href="../operaciones/ventas/comprobante-generado/W-{{ $factura->IdVentas }}"><img
                                                      class="logo-expand" alt="" width="25"
                                                      src="{{ asset('assets/img/whatsapp.png') }}" data-toggle="modal"
                                                      data-target="#modalWhatsapp"></a>
                                              @if (
                                                  $factura->Estado == 'Aceptado' &&
                                                      $factura->Nota == 0 &&
                                                      ($factura->IdTipoComprobante == 1 || $factura->IdTipoComprobante == 2))
                                                  <form action="{{ route('nota-credito.index') }}" method="POST"
                                                      class="d-inline">
                                                      @method('GET')
                                                      <input type="hidden" value="{{ $factura->IdVentas }}"
                                                          name="idVentas">
                                                      <button type="submit" class="btn btn-primary p-1">Generar
                                                          NC</button>
                                                  </form>
                                              @endif
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

      <div class="modal modal-primary fade bs-modal-sm-anular" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog modal-md">
              {!! Form::open([
                  'url' => '/consultas/ventas-boletas-facturas/anulando',
                  'method' => 'POST',
                  'files' => true,
                  'class' => 'form-material',
                  'id' => 'formBaja',
              ]) !!}
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Dar baja documento</h6>
                  </div>
                  <div class="modal-body">
                      <div class="container">
                          <div class="form-group">
                              <label>Descripción:</label>
                              <input id="inpDescripcion" class="form-control" name="descripcion" />
                          </div>
                          <div class="form-group">
                              <input type="checkbox" id="descontar" name="descontar"><span id="textoDescontar"
                                  class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Descontar monto de caja</span>
                          </div>
                          <div class="row justify-content-center" id="montos">
                              <div class="col-8">
                                  <label for="montoActual">Total actual en Caja</label>
                                  <div class="form-group">
                                      <input class="form-control" id="totalCaja" name="totalCaja" value=""
                                          readonly />
                                  </div>
                              </div>
                              <div class="col-8">
                                  <label for="numeroOperacion">Total a descontar</label>
                                  <div class="form-group">
                                      <input class="form-control" id="totalDescontar" name="totalDescontar"
                                          value="" readonly />
                                  </div>
                              </div>
                              <div class="col-8">
                                  <label for="detalle">Total Restante en Caja</label>
                                  <div class="form-group">
                                      <input class="form-control" id="totalRestante" name="totalRestante" value=""
                                          readonly />
                                  </div>
                              </div>
                          </div>
                          <label class="text-danger" id="mensaje"></label>
                          <input id="inpVenta" hidden class="form-control" name="idVenta" />
                          <input id="inpTipoMoneda" hidden class="form-control" name="tipoMoneda" />
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button id="btnEnvio" type="submit" class="btn btn-primary">Anular</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
              {!! Form::close() !!}
          </div>
      </div>

      <div class="modal modal-primary fade bs-modal-sm-enviar" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog modal-md">
              {!! Form::open([
                  'url' => '/consultas/ventas-boletas-facturas/enviando-sunat',
                  'method' => 'POST',
                  'files' => true,
                  'class' => 'form-material',
              ]) !!}
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Enviar Comprobante</h6>
                  </div>
                  <div class="modal-body">
                      <label>Desea enviar comprobante electrónico a Sunat ?</label>
                      <input id="idDocEnvio" hidden class="form-control" name="idDocEnvio" />
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Enviar</button>
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
                      <h6 class="text-success">Consultas de Ventas</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <label class="fs-14 negrita">Ventas del Mes</label>
                          <p class="fs-15negrita">Se mostraran solo las ventas de este mes....... Si desea ver ventas
                              anteriores utilize los filtros</p>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <div class="form-actions btn-list mt-3">
                          <button class="btn btn-info" type="button" data-dismiss="modal">Aceptar</button>
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
          $(function() {
              $("#montos").hide();
              var bandModal = <?php echo json_encode($IdTipoPago); ?>;

              if (bandModal === '') {
                  $("#mostrarmodal").modal("show");
              }
              var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
              $('#tipoPago option[value=' + idTipoPago + ']').prop('selected', true);
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

          function anular(id, tipoMoneda) {
              $("#mensaje").text("");
              $('#inpVenta').val(id);
              $("#montos").hide();
              $("#descontar").prop('checked', false);
              //$('#inpTipoMoneda').val(tipoMoneda);
              if (tipoMoneda == 1) {
                  var cajaTotal = <?php echo json_encode($cajaTotalSoles); ?>;
                  $('#totalCaja').val(cajaTotal);
                  //$("#descontar").show();
                  //$("textoDescontar").text("Descontar monto de caja");
              } else {
                  var cajaTotal = <?php echo json_encode($cajaTotalDolares); ?>;
                  $('#totalCaja').val(cajaTotal);
                  //$("#descontar").hide();
                  //$("textoDescontar").text("");
              }
          }

          $('#btnEnvio').on('click', function() {
              var myForm = $("form#formBaja");
              if (myForm) {
                  $('.bs-modal-sm-anular').hide();
                  utilidades.showLoadingOverlay();
                  $(this).attr('disabled', true);
                  $(myForm).submit();
              }
          });

          $("#descontar").click(function() {
              if ($("#descontar").is(':checked')) {
                  $.ajax({
                      type: 'get',
                      url: 'obtener-datos',
                      data: {
                          "idVentas": $('#inpVenta').val()
                      },
                      success: function(data) {
                          if (data[0]["IdTipoPago"] == 1) {
                              $("#montos").show();
                              $("#totalDescontar").val(redondeo(data[0]["Total"]));
                              var totalActual = $("#totalCaja").val();
                              var descontar = parseFloat(totalActual) - parseFloat(data[0]["Total"]);
                              $("#totalRestante").val(redondeo(descontar));
                              $("#totalCaja").val(redondeo(totalActual));
                          } else {
                              $("#mensaje").text("Las Facturas a Crédito no pueden descontar la caja");
                          }
                      }
                  });
              } else {
                  $("#mensaje").text("");
                  $("#montos").hide();
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
