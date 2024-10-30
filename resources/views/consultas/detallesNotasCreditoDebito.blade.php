  @extends('layouts.app')
  @section('title', 'Detalles Notas Créditos - Débitos')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Detalles de Notas Créditos y Débitos</h6>
              </div>
              <div class="page-title-right">
                  <div class="row mr-b-50 mt-2">
                      <div class="col-12 mr-b-20 d-flex">
                          <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-print"><button
                                  class="btn btn-block btn-primary ripple"><i
                                      class="list-icon material-icons fs-20">print</i></button></a>
                          <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-primary"
                              onclick="cargarCorreo()"><button class="btn btn-block btn-primary ripple"><i
                                      class="list-icon material-icons fs-20">mail</i></button></a>
                      </div>
                  </div>
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
                              <div class="ecommerce-invoice">
                                  <div class="d-sm-flex">
                                      <div class="col-md-6">
                                          <h6>{{ $notaSelect->TipoNota }}: {{ $notaSelect->Serie }}-{{ $numeroCeroIzq }}
                                              </h5>
                                      </div>
                                      <div class="col-md-6 text-right d-none d-sm-block"><strong>CAJERO:</strong>
                                          {{ $notaSelect->Usuario }}
                                          <br><strong>SUCURSAL:</strong> {{ $notaSelect->Sucursal }}
                                          <br><strong>DIRECCIÓN:</strong> {{ $notaSelect->Local }}
                                          <br><strong>CIUDAD:</strong> {{ $notaSelect->Ciudad }}
                                      </div>
                                      <div class="col-md-6 d-block d-sm-none"><strong>CAJERO:</strong>
                                          {{ $notaSelect->Usuario }}
                                          <br><strong>SUCURSAL:</strong> {{ $notaSelect->Sucursal }}
                                          <br><strong>DIRECCIÓN:</strong> {{ $notaSelect->Local }}
                                          <br><strong>CIUDAD:</strong> {{ $notaSelect->Ciudad }}
                                      </div>
                                  </div>
                                  <!-- /.row -->
                                  <hr>
                                  <div class="d-sm-flex">
                                      <div class="col-md-6 mt-2">
                                          <h6>Cliente:</h6>
                                          <strong>NOMBRES:</strong> {{ $notaSelect->Nombres }}
                                          <br><strong>RAZ. SOCIAL:</strong> {{ $notaSelect->RazonSocial }}
                                          <br><strong>{{ $notaSelect->TipoDoc }}:</strong>
                                          {{ $notaSelect->NumeroDocumento }}
                                          <br><strong>DIRECCIÓN:</strong> {{ $notaSelect->DirCliente }}
                                      </div>
                                      <div class="col-md-6 text-right mt-2 d-none d-sm-block">
                                          <h6>Detalles:</h6>
                                          <strong>FECHA:</strong> <span class="text-muted">{{ $formatoFecha }}</span>
                                          <br><strong>HORA:</strong> <span class="text-muted">{{ $formatoHora }}</span>
                                          <br><strong>TOTAL:</strong> <span
                                              class="text-muted">{{ $notaSelect->Total }}</span>
                                          <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                              @if ($notaSelect->IdTipoMoneda == 1)
                                                  Soles
                                              @else
                                                  Dólares
                                              @endif
                                          </span>
                                          <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                              @if ($notaSelect->TipoVenta == 1)
                                                  Gravada
                                              @else
                                                  Exonerada
                                              @endif
                                          </span>
                                      </div>
                                      <div class="col-md-6 mt-2 d-block d-sm-none">
                                          <h6>Detalles:</h6>
                                          <strong>FECHA:</strong> <span class="text-muted">{{ $formatoFecha }}</span>
                                          <br><strong>HORA:</strong> <span class="text-muted">{{ $formatoHora }}</span>
                                          <br><strong>TOTAL:</strong> <span
                                              class="text-muted">{{ $notaSelect->Total }}</span>
                                          <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                              @if ($notaSelect->IdTipoMoneda == 1)
                                                  Soles
                                              @else
                                                  Dólares
                                              @endif
                                          </span>
                                          <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                              @if ($notaSelect->TipoVenta == 1)
                                                  Gravada
                                              @else
                                                  Exonerada
                                              @endif
                                          </span>
                                      </div>
                                  </div>
                                  <hr>
                                  <div class="d-sm-flex">
                                      <div class="col-md-6 mt-2">
                                          <h6>Documento Modificado:</h6>
                                          <strong>DOCUMENTO:</strong> {{ $notaSelect->DocModificado }}
                                          @if ($notaSelect->IdDocModificado == 1)
                                              <br><strong>TIPO COMPROB.:</strong> BOLETA
                                          @else
                                              <br><strong>TIPO COMPROB.:</strong> FACTURA
                                          @endif
                                          <br><strong>MOTIVO:</strong> {{ $notaSelect->Motivo }}
                                          @if ($notaSelect->IdMotivo == 23)
                                              <br><strong>FECHA PAGO MODIFICADO:</strong> {{ $fechaPago }}
                                          @endif
                                      </div>
                                  </div>
                                  <!-- /.row -->
                                  <hr class="border-0">
                                  <table id="table" class="table table-bordered table-responsive-sm"
                                      style="width: 100%">
                                      <thead>
                                          <tr class="bg-primary-dark text-white">
                                              <th class="text-center">Código</th>
                                              <th>Descripción</th>
                                              <th class="text-center">Uni/Medida</th>
                                              <th class="text-center">Precio Venta</th>
                                              <th class="text-center">Descuento</th>
                                              <th class="text-center">Cantidad</th>
                                              <th class="text-center">Importe</th>
                                          </tr>
                                      </thead>
                                      <tbody>
                                          @foreach ($items as $item)
                                              @if ($item->Gratuito == 1)
                                                  @php $backgroundColor = 'background-color: #d3d3d3'; @endphp
                                              @else
                                                  @php $backgroundColor = 'background-color: none'; @endphp
                                              @endif
                                              <tr style="{{ $backgroundColor }}">
                                                  <td scope="row">{{ $item->Cod }}</td>
                                                  <td>{{ $item->Descrip }}</td>
                                                  <td>{{ $item->UniMedida }}</td>
                                                  <td>{{ $item->PrecioVenta }}</td>
                                                  <td>{{ $item->Descuento }}</td>
                                                  <td>{{ $item->Cantidad }}</td>
                                                  <td>{{ $item->Total }}</td>
                                              </tr>
                                          @endforeach
                                      </tbody>
                                  </table>
                                  <div class="row mt-4">
                                      <div class="col-md-8">
                                      </div>
                                      <div class="col-md-4 invoice-sum">
                                          <ul class="list-unstyled">
                                              <li>
                                                  @if ($ventaSelect->TipoVenta == 1)
                                                      OP. GRAVADAS:
                                                  @else
                                                      OP. EXONERADAS:
                                                  @endif
                                                  {{ number_format($notaSelect->Subtotal, 2, '.', ',') }}
                                              </li>
                                              <li>OP GRATUITAS: {{ number_format($notaSelect->Gratuita, 2, '.', ',') }}
                                              </li>
                                              <li>DESCUENTO: {{ number_format($notaSelect->Descuento, 2, '.', ',') }}</li>
                                              <li>
                                                  @if ($ventaSelect->TipoVenta == 1)
                                                      IGV(18%):
                                                  @else
                                                      IGV(0%):
                                                  @endif
                                                  {{ number_format($notaSelect->IGV, 2, '.', ',') }}
                                              </li>
                                              <li><strong>TOTAL :
                                                      {{ number_format($notaSelect->Total, 2, '.', ',') }}</strong>
                                              </li>
                                          </ul>
                                      </div>
                                  </div>
                                  <div class="form-actions btn-list mt-3">
                                      <a href="../../notas-credito-debito"><button class="btn btn-primary"
                                              type="button">Volver</button></a>
                                  </div>
                                  <!-- /.row -->
                              </div>
                              <!-- /.ecommerce-invoice -->
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
      <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog modal-sm">
              {!! Form::open([
                  'url' => '/consultas/notas-credito-debito/enviar-correo/' . $notaSelect->IdCreditoDebito,
                  'method' => 'POST',
                  'files' => true,
                  'class' => 'form-material',
              ]) !!}
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                  </div>
                  <div class="modal-body">
                      <div class="container">
                          <label>Correo cliente:</label>
                          <input id="inpCorreo" class="form-control" name="correo" />
                          <input id="inpCliente" hidden class="form-control" name="cliente" />
                          <input id="inpComprobante" hidden class="form-control" name="comprobante" />
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Enviar</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
              {!! Form::close() !!}
          </div>
      </div>

      <div class="modal modal-primary fade bs-modal-sm-print" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog modal-sm">
              {!! Form::open([
                  'url' => '/consultas/notas-credito-debito/imprimir/' . $notaSelect->IdCreditoDebito,
                  'method' => 'POST',
                  'files' => true,
                  'class' => 'form-material',
                  'target' => '_blank',
              ]) !!}
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Imprimir comprobante</h6>
                  </div>
                  <div class="modal-body">
                      <div class="container">
                          <label>Seleccionar tipo de impresión:</label>
                          <select id="selectImpre" class="form-control" name="selectImpre">
                              <option value="1">A4</option>
                              <option value="2">A5</option>
                          </select>
                      </div>
                  </div>
                  <div class="modal-footer">
                      <button type="submit" class="btn btn-primary">Imprimir</button>
                      <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
              {!! Form::close() !!}
              <!-- /.modal-content -->
          </div>
          <!-- /.modal-dialog -->
      </div>

      <div class="modal modal-primary fade bs-modal-primary" id="mostrarmodal" tabindex="-1" role="dialog"
          aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
          <div class="modal-dialog">
              {!! Form::open([
                  'url' => '/consultas/notas-credito-debito/descontar/' . $notaSelect->IdCreditoDebito,
                  'method' => 'POST',
                  'files' => true,
                  'id' => 'formRegistrarDescuento',
              ]) !!}
              <input type="hidden">
              <div class="modal-content">
                  <div class="modal-header text-inverse">
                      <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                      <h6 class="modal-title" id="mySmallModalLabel2">Desea descontar el monto?</h6>
                  </div>
                  <div class="modal-body px-2">
                      <section id="caja">
                          <div class="row">
                              <div class="col-12 pl-0">
                                  <div class="mt-4 input-switch">
                                      <input type="checkbox" id="switchDescontarCaja" name="switchDescontarCaja"
                                          data-switch="bool" />
                                      <label for="switchDescontarCaja" data-on-label="Si" data-off-label="No"></label>
                                      <span class="ml-2">Descontar Monto de
                                          Caja</span>
                                  </div>
                              </div>
                          </div>

                          <div id="row-montosCaja" class="row pt-4" style=" background-color: #f8f8f8">
                              <div class="col-4">
                                  <div class="form-group">
                                      <label for="montoActual">Caja Actual</label>
                                      <input class="form-control" id="totalCaja" name="totalCaja"
                                          value="{{ $cajaTotal }}" readonly />
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group">
                                      <label for="numeroOperacion">Descuento</label>
                                      <input class="form-control" id="totalDescontar" name="totalDescontar"
                                          value="{{ $notaSelect->Total }}" readonly />
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group">
                                      <label for="detalle">Caja Restante</label>
                                      <input class="form-control" id="totalRestante" name="totalRestante"
                                          value="{{ $cajaTotal - $notaSelect->Total }}" readonly />
                                  </div>
                              </div>
                              <input class="form-control" value="{{ $notaSelect->IdCreditoDebito }}"
                                  name="idNotaCredito" hidden />
                          </div>
                      </section>
                      <section id="cuenta">
                          <div class="row">
                              <div class="col-12 pl-0">
                                  <div class="mt-4 input-switch">
                                      <input type="checkbox" id="switchDescontarCuenta" name="switchDescontarCuenta"
                                          data-switch="bool" />
                                      <label for="switchDescontarCuenta" data-on-label="Si" data-off-label="No"></label>
                                      <span class="ml-2">Descontar Monto de Cuenta</span>
                                  </div>
                              </div>
                          </div>
                          <div id="row-montosCuenta" class="row pt-4" style=" background-color: #f8f8f8">
                              <div class="form-group col-12">
                                  <select class="custom-select" id="selectCuentas" name="selectBanco" disabled>
                                      <option value="0">Seleccione Cuenta</option>
                                      @foreach ($listaBancos as $banco)
                                          <option value="{{ $banco->IdBanco }}">
                                              {{ $banco->Banco }} - {{ $banco->NumeroCuenta }}</option>
                                      @endforeach
                                  </select>
                              </div>
                              <div class="col-12">
                                  <div class="form-group">
                                      <label for="montoActual">Número de operación</label>
                                      <input type="number" class="form-control" id="numeroOperacion"
                                          name="numeroOperacion" disabled />
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group">
                                      <label for="montoActual">Cuenta Actual</label>
                                      <input class="form-control" id="totalCuenta" name="totalCuenta" readonly />
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group">
                                      <label for="totalCuentaDescontar">Descuento</label>
                                      <input class="form-control" id="totalCuentaDescontar" name="totalCuentaDescontar"
                                          value="{{ $notaSelect->Total }}" readonly />
                                  </div>
                              </div>
                              <div class="col-4">
                                  <div class="form-group">
                                      <label for="detalle">Cuenta Restante</label>
                                      <input class="form-control" id="totalCuentaRestante" name="totalCuentaRestante"
                                          readonly />
                                  </div>
                              </div>
                              <input class="form-control" value="{{ $notaSelect->IdCreditoDebito }}"
                                  name="idNotaCredito" hidden />
                          </div>
                          <span class="text-danger error d-flex justify-content-center align-items-center"></span>
                      </section>
                  </div>
                  <div class="modal-footer">
                      <button id="btnProcesarDescuento" type="submit" class="btn btn-primary">OK</button>
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
              var tipo = <?php echo json_encode($tipo); ?>;
              var ventaSelect = <?php echo json_encode($ventaSelect); ?>;
              if (ventaSelect["IdTipoPago"] == 1) {
                  if (tipo == 1) {
                      $("#mostrarmodal").modal("show");
                  }
              }

              //   codigo nueva actualizacion 2024-02-21
              $('#switchDescontarCaja').change(function(event) {
                  $('#switchDescontarCuenta').prop('disabled', $(this).is(":checked"));
              });

              $('#switchDescontarCuenta').change(function(event) {
                  $('#switchDescontarCaja').prop('disabled', $(this).is(":checked"));
                  $("#selectCuentas").prop('disabled', !$(this).is(":checked"));
                  $("#numeroOperacion").prop('disabled', !$(this).is(":checked"));
              });


              $('#selectCuentas').change(function(event) {
                  $('#mensaje').addClass('d-none');
                  const idBanco = $(this).val();
                  if (idBanco != '0') {
                      $.ajax({
                          type: 'GET',
                          url: "{{ route('notas-credito.obtener-monto-cuenta') }}",
                          data: {
                              'idBanco': idBanco,
                          },

                          success: function(data) {
                              $('#totalCuenta').val(data.datosCuenta.MontoActual);
                              const restanteCuenta = data.datosCuenta.MontoActual -
                                  {{ $notaSelect->Total }};
                              $("#totalCuentaRestante").val(restanteCuenta);
                          }
                      })
                  } else {
                      $('#totalCuenta').val(0);
                      $("#totalCuentaRestante").val({{ $notaSelect->Total }});
                  }
              })

              $('#btnProcesarDescuento').click(function(e) {
                  e.preventDefault();
                  const switchMarcados = $('#switchDescontarCaja:checked, #switchDescontarCuenta:checked');
                  if (switchMarcados.length === 0) {
                      $('.error').removeClass('d-none').text(
                          "Por favor, selecciona al menos un Switch antes de enviar");
                      return false;
                  }

                  if ($('#switchDescontarCaja').is(':checked')) {
                      const totalCajaRestante = parseFloat($("#totalRestante").val());
                      if (totalCajaRestante < 0) {
                          $('.error').removeClass('d-none').text(
                              "El saldo en la caja no es suficiente para procesar el descuento.");
                          return false;
                      }
                  }

                  if ($('#switchDescontarCuenta').is(':checked')) {
                      const totalCuentaRestante = parseFloat($("#totalCuentaRestante").val());
                      const valueSelectCuenta = $('#selectCuentas').val();
                      if (valueSelectCuenta == '0') {
                          $('.error').removeClass('d-none').text(
                              "Seleccione una cuenta bancaria, para procesar el descuento.");
                          return false;
                      }
                      if (totalCuentaRestante < 0) {
                          $('.error').removeClass('d-none').text(
                              "El saldo en la cuenta no es suficiente para procesar el descuento.");
                          return false;
                      }
                  }

                  $('#formRegistrarDescuento').submit();
              })

              $('#selectCuentas, #switchDescontarCaja, #switchDescontarCuenta').change(function() {
                  $('.error').addClass('d-none').text('');
              })

          });
      </script>
      <script>
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

          function cargarCorreo() {
              var venta = <?php echo json_encode($notaSelect); ?>;
              var numCeroIzq = <?php echo json_encode($numeroCeroIzq); ?>;
              $('#inpCorreo').val(venta['Email']);
              $('#inpCliente').val(venta['Nombres']);
              $('#inpComprobante').val(venta['Serie'] + '-' + numCeroIzq);
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
  @stop
