@extends('layouts.app')
  @section('title', 'Lista anticipos')
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
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Anticipos</h6>
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
          <!--    /operaciones/cotizacion/--->
          {!! Form::open(['url' => '/operaciones/ventas/anticipos', 'method' => 'POST', 'files' => true]) !!}
          {{ csrf_field() }}
          <div class="row ">
              <div class="col-md-6 mt-4 order-md-2">
                  <div class="form-group form-material">
                      <label>Fecha</label>
                      <select id="idFecha" class="form-control" name="fecha">
                          <!--<option value="0">Todo</option>-->
                          <option value="1">Hoy</option>
                          <option value="2">Ayer</option>
                          <option value="3">Semana Actual</option>
                          <option value="4">Semana Anterior</option>
                          <option value="5">Mes Actual</option>
                          <option value="6">Mes Anterior</option>
                          <option value="7">Año Actual</option>
                          <option value="8">Año Anterior</option>
                          <option value="9">Personalizar</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4 col-6 mt-4 order-md-2 text-center ">
                  <div class="form-groupr">
                      <br>
                      <button type="submit" class="btn btn-success">Buscar</button>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-4">
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
                                      <th scope="col">Importe</th>
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
                                          <td>{{ $factura->Total }}</td>
                                          <td>{{ $factura->Estado }}</td>
                                          <td>{{ $factura->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                          <td class="text-center">
                                              {{ $factura->CodigoDoc == 0 ? '-' : $factura->CodigoDoc }}</td>
                                          <td class="text-center" width="15%">
                                              <a href="../ventas/comprobante-generado/{{ $factura->IdVentas }}"
                                                  title="Detalles"><i class="list-icon material-icons">visibility</i></a>
                                              <a href="../ventas/descargar/{{ $factura->IdVentas }}"
                                                  title="Descargar PDF"><i
                                                      class="list-icon material-icons">picture_as_pdf</i></a>
                                              @if ($factura->IdTipoComprobante != 3)
                                                  <a href="../ventas/xml/{{ $rucEmpresa }}/{{ $factura->IdVentas }}"
                                                      title="Descargar XML"><i
                                                          class="list-icon material-icons">code</i></a>
                                                  @if ($factura->Estado == 'Aceptado')
                                                      @if ($factura->IdTipoComprobante == 2)
                                                          <a href="../ventas/cdr/{{ $rucEmpresa }}/{{ $factura->IdVentas }}"
                                                              title="Descargar CDR"><i
                                                                  class="list-icon material-icons">attach_file</i></a>
                                                      @endif
                                                  @endif
                                              @endif
                                              <a target="_blank" class="p-1"
                                                  href="../ventas/comprobante-generado/W-{{ $factura->IdVentas }}"><img
                                                      class="logo-expand" alt="" width="25"
                                                      src="{{ asset('assets/img/whatsapp.png') }}" data-toggle="modal"
                                                      data-target="#modalWhatsapp"></a>
                                                @if($factura->Estado == 'Aceptado')
                                                    @if($factura->Anticipo == 1)
                                                    <a href="../ventas/anticipos/completar-anticipo/{{ $factura->IdVentas }}"
                                                    title="Completar Anticipo"><button class="btn btn-success"><i class="list-icon material-icons">arrow_forward</i></button></a>
                                                    @else
                                                    <button class="btn btn-primary"><i class="list-icon material-icons">check</i></button>
                                                    @endif
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


      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="text-success">Listado de Anticipos</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <label class="fs-14 negrita">Anticipos del Mes</label>
                          <p class="fs-15negrita">Se mostraran solo las facturas con anticipos de este mes....... Si desea ver
                              facturas con anticipos anteriores utilize los filtros</p>
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
  @stop

  @section('scripts')
      <script>
          $(function() {
              var bandModal = <?php echo json_encode($IdTipoPago); ?>;

              if (bandModal === '') {
                  $("#mostrarmodal").modal("show");
              }
              $('#Inicio').hide();
              $('#Final').hide();
              var fecha = <?php echo json_encode($fecha); ?>;
              if (fecha == '9') {
                  var fechaIni = <?php echo json_encode($fechaInicial); ?>;
                  var fechaFin = <?php echo json_encode($fechaFinal); ?>;
                  $('#Inicio').show();
                  $('#Final').show();
                  $('#datepickerIni').val(fechaIni);
                  $('#datepickerFin').val(fechaFin);
              }
              $('#idFecha option[value=' + fecha + ']').prop('selected', true);
          });

          function redondeo(num) {

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
      <script>
          $(function() {
              $("#idFecha").on('change', function() {
                  var valor = $("#idFecha").val();
                  if (valor == "9") {
                      $('#Inicio').show();
                      $('#Final').show();
                  } else {
                      $('#Inicio').hide();
                      $('#Final').hide();
                      $('#datepickerIni').val('');
                      $('#datepickerFin').val('');
                  }
              });
          });
      </script>
  @stop
