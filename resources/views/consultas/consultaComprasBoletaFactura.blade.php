  @extends('layouts.app')
  @section('title', 'Consultas Compras Boletas - Facturas')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Compras</h6>
              </div>
              <!-- /.page-title-left -->
              <!--<div class="page-title-right">
                                                            <div class="row mr-b-50 mt-2">
                                                                <div class="col-12 mr-b-20 d-sm-block d-none">
                                                                    <a href="../operaciones/compras/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                                                                </div>
                                                                <div class="col-12 mr-b-20 d-sm-none d-block">
                                                                    <a href="../operaciones/compras/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
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

          {!! Form::open(['url' => '/consultas/compras-boletas-facturas', 'method' => 'POST', 'files' => true]) !!}
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
                  <x-selectorFiltrosFechas obtenerDatos="false" class="form-material" />
              </div>
              <div class="col-md-3 mt-4 order-md-3 order-last">
                  <div class="form-group">
                      <br>
                      <button type="submit" class="btn btn-primary">Buscar</button>
                  </div>
              </div>
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
          <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#compras" role="tab"
                      aria-controls="nav-home" aria-selected="true">Compras</a>
                  <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#ordenesCompra" role="tab"
                      aria-controls="nav-profile" aria-selected="false">Ordenes de Compra</a>
              </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="compras" role="tabpanel" aria-labelledby="nav-home-tab">
                  <table id="table" class="table table-responsive-sm" style="width:100%">
                      <thead>
                          <tr class="bg-primary">
                              <th scope="col">Fecha</th>
                              <th scope="col">Proveedor</th>
                              <th scope="col">Código</th>
                              <th scope="col">Tipo de Pago</th>
                              <th scope="col">Tipo de Compra</th>
                              <th scope="col">Total</th>
                              <th scope="col">Estado</th>
                              <th scope="col">Opciones</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($comprobanteCompras as $compCompras)
                              <tr>
                                  <td>{{ $compCompras->FechaCreacion }}</td>
                                  <td>{{ $compCompras->Nombres }}</td>
                                  <td>{{ $compCompras->Serie }}-{{ $compCompras->Numero }}</td>
                                  @if ($compCompras->IdTipoPago == 1)
                                      <td>Contado</td>
                                  @else
                                      <td>Crédito</td>
                                  @endif
                                  <td>{{ $compCompras->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                  <td>{{ $compCompras->Total }}</td>
                                  <td>{{ $compCompras->Estado }}</td>
                                  <td class="text-center">
                                      <a href="../operaciones/compras/comprobante-generado/{{ $compCompras->IdCompras }}"><i
                                              class="list-icon material-icons">visibility</i></a>
                                      <a href="../operaciones/compras/descargar/{{ $compCompras->IdCompras }}"><i
                                              class="list-icon material-icons">picture_as_pdf</i></a>
                                  </td>
                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
              <div class="tab-pane fade" id="ordenesCompra" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <table id="tableOrdenesDeCompra" class="table table-responsive-sm" style="width:100%">
                      <thead>
                          <tr class="bg-primary">
                              <th scope="col">Fecha Emision</th>
                              <th scope="col">Proveedor</th>
                              <th scope="col">Ruc</th>
                              <th scope="col">Codigo</th>
                              <th scope="col">Tipo de Moneda</th>
                              <th scope="col">Tipo de Pago</th>
                              <th scope="col">Total</th>
                              <th scope="col">Tipo de Compra</th>
                              <th scope="col">Estado</th>
                              <th scope="col">Ver Detalle</th>
                          </tr>
                      </thead>
                      <tbody>
                          @foreach ($comprobanteOrdenesCompras as $item)
                              <tr>
                                  <td>{{ $item->FechaEmision }}</td>
                                  <td>{{ $item->NombreProveedor }}</td>
                                  <td>{{ $item->NumeroDocumento }}</td>
                                  <td>{{ $item->Serie }}-{{ $item->Numero }}</td>
                                  <td>{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                                  <td>{{ $item->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                  <td>{{ $item->Total }}</td>
                                  <td>{{ $item->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                  <td>{{ $item->Estado }}</td>
                                  {{-- <td> <a
                                          href="../operaciones/compras/comprobante-orden-compra/C-{{ $item->IdOrdenCompra }}"><i
                                              class="list-icon material-icons">visibility</i></a></td> --}}
                                  <td> <a href="{{ route('ordenDeCompra.show', $item->IdOrdenCompra) }}"><i
                                              class="list-icon material-icons">visibility</i></a></td>

                              </tr>
                          @endforeach
                      </tbody>
                  </table>
              </div>
          </div>
      </div>

      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="text-success">Consultas de Compras</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <label class="fs-14 negrita">Compras del Mes</label>
                          <p class="fs-15negrita">Se mostraran solo las compras de este mes....... Si desea ver compras
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
      <!-- /.container -->
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

          $(function() {
              $(document).ready(function() {
                  $('#tableOrdenesDeCompra').DataTable({
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
