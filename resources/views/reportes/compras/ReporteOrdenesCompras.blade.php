  @extends('layouts.app')
  @section('title', 'Reporte Ordenes de Compras')
  @section('content')
      <div class="container">
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
          {!! Form::open(['url' => '/reportes/compras/reporte-ordenes-compra', 'method' => 'POST', 'files' => true]) !!}
          {{ csrf_field() }}
          <div class="row clearfix">
              <div class="col-md-4 mt-4">
                  <div class="form-group form-material">
                      <label>Proveedor</label>
                      <select class="m-b-10 form-control select2-hidden-accessible" id="proveedor" name="proveedor"
                          data-placeholder="Seleccione proveedor" data-toggle="select2" tabindex="-1" aria-hidden="true">
                          <option value="0">Seleccione Proveedor</option>
                          @foreach ($proveedores as $proveedor)
                              @if ($idProveedor == $proveedor->IdProveedor)
                                  <option value="{{ $proveedor->IdProveedor }} " selected>{{ $proveedor->Nombre }}
                                  </option>
                              @else
                                  <option value="{{ $proveedor->IdProveedor }} ">{{ $proveedor->Nombre }}</option>
                              @endif
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="col-md-4 mt-4">
                  <x-selectorFiltrosFechas obtenerDatos='false' />
              </div>
              <div class="col-3 col-md-1">
                  <div class="form-group">
                      <br><br>
                      <button type="submit" class="btn btn-primary">Buscar</button>
                  </div>
              </div>

              <div class="col-md-1 col-6 mt-4 text-center">
                  <div class="form-group">
                      <br>
                      <a class="p-0" target="_blank"
                          href='{{ url("reportes/compras/exportar-excel-orden/$idProveedor/$fecha/$ini/$fin") }}'>
                          <span class="btn bg-excel ripple">
                              <i class="list-icon material-icons fs-20">explicit</i>XCEL
                          </span>
                      </a>
                  </div>
              </div>
          </div>
          <x-inputFechasPersonalizadas mostrarBoton='false' />
          {!! Form::close() !!}
      </div>

      <div class="container">
          {{-- Nuevo codigo --}}
          <nav>
              <div class="nav nav-tabs" id="nav-tab" role="tablist">
                  <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#OrdenCompraSoles"
                      role="tab" aria-controls="nav-home" aria-selected="true">Orden de Compra en Soles</a>
                  <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#OrdenCompraDolares"
                      role="tab" aria-controls="nav-profile" aria-selected="false">Orden de Compra en Dólares</a>
              </div>
          </nav>
          <div class="tab-content" id="nav-tabContent">
              <div class="tab-pane fade show active" id="OrdenCompraSoles" role="tabpanel" aria-labelledby="nav-home-tab">
                  <div class="row my-3 justify-content-center">
                      @if ($idProveedor == 0)
                          <div class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad de Ordenes de Compras <br> Pendientes</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraPendientesSoles }}</span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad de Ordenes de Compras <br> Facturadas</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraFacturadosSoles }}</span>
                                  </div>
                              </div>
                          </div>
                      @else
                          <section class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad de Orden de Compras Pendientes</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraPendientesSoles }}</span>
                                      <hr>
                                      <span class="fs-16">Monto Total</span><br>
                                      <span class="font-weight-bold fs-30">S/.
                                          {{ number_format($montoOrdenComprasPendientesSoles, 2, '.', ',') }}
                                      </span>
                                  </div>
                              </div>
                          </section>
                          <section class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad. de Orden de Compras Facturadas</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraFacturadosSoles }}</span>
                                      <hr>
                                      <span class="fs-16">Monto Total</span><br>
                                      <span class="font-weight-bold fs-30">S/.
                                          {{ number_format($montoOrdenComprasFacturadasSoles, 2, '.', ',') }}
                                      </span>
                                  </div>
                              </div>
                          </section>
                      @endif
                      <div class="col-12 mt-5">
                          <table id="tableSoles" class="table table-responsive-sm" style="width:100%">
                              <thead>
                                  <tr class="bg-primary">
                                      <th scope="col">Codigo</th>
                                      <th scope="col">Fecha Emitida</th>
                                      <th scope="col">Fecha Recepción</th>
                                      <th scope="col">Proveedor</th>
                                      <th scope="col">Ruc</th>
                                      <th scope="col">Total</th>
                                      <th scope="col">Tipo de Moneda</th>
                                      <th scope="col">Tipo de Pago</th>
                                      <th scope="col">Tipo Compra</th>
                                      <th scope="col">Estado</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($listaOrdenCompraSoles as $item)
                                      <tr>
                                          <td>{{ $item->Serie }}-{{ $item->Numero }}</td>
                                          <td>{{ $item->FechaEmision }}</td>
                                          <td>{{ $item->FechaRecepcion }}</td>
                                          <td>{{ $item->NombreProveedor }}</td>
                                          <td>{{ $item->NumeroDocumento }}</td>
                                          <td>{{ $item->Total }}</td>
                                          <td>{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                          <td>{{ $item->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                          <td>{{ $item->TipoCompra == 1 ? 'Gravada' : 'Exonerado' }}</td>
                                          <td>{{ $item->Estado }}</td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
              <div class="tab-pane fade" id="OrdenCompraDolares" role="tabpanel" aria-labelledby="nav-profile-tab">
                  <div class="row my-3 justify-content-center">
                      @if ($idProveedor == 0)
                          <div class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad de Ordenes de Compras <br> Pendientes</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraPendientesDolares }}</span>
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad de Ordenes de Compras <br> Facturadas</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraFacturadosDolares }}</span>
                                  </div>
                              </div>
                          </div>
                      @else
                          <section class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad de Orden de Compras Pendientes</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraPendientesDolares }}</span>
                                      <hr>
                                      <span class="fs-16">Monto Total</span><br>
                                      <span class="font-weight-bold fs-30">$.
                                          {{ number_format($montoOrdenComprasPendientesDolares, 2, '.', ',') }}
                                      </span>
                                  </div>
                              </div>
                          </section>
                          <section class="col-12 col-md-4">
                              <div class="card card-bg--celeste">
                                  <div class="card-body text-center">
                                      <span class="fs-16">Cantidad. de Orden de Compras Facturadas</span><br>
                                      <span class="font-weight-bold fs-30">{{ $cantOrdenCompraFacturadosDolares }}</span>
                                      <hr>
                                      <span class="fs-16">Monto Total</span><br>
                                      <span class="font-weight-bold fs-30">$.
                                          {{ number_format($montoOrdenComprasFacturadasDolares, 2, '.', ',') }}
                                      </span>
                                  </div>
                              </div>
                          </section>
                      @endif
                      <div class="col-12 mt-5">
                          <table id="tableDolares" class="table table-responsive-sm" style="width:100%">
                              <thead>
                                  <tr class="bg-primary">
                                      <th scope="col">Codigo</th>
                                      <th scope="col">Fecha Emitida</th>
                                      <th scope="col">Fecha Recepción</th>
                                      <th scope="col">Proveedor</th>
                                      <th scope="col">Ruc</th>
                                      <th scope="col">Total</th>
                                      <th scope="col">Tipo de Moneda</th>
                                      <th scope="col">Tipo de Pago</th>
                                      <th scope="col">Tipo Compra</th>
                                      <th scope="col">Estado</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($listaOrdenCompraDolares as $item)
                                      <tr>
                                          <td>{{ $item->Serie }}-{{ $item->Numero }}</td>
                                          <td>{{ $item->FechaEmision }}</td>
                                          <td>{{ $item->FechaRecepcion }}</td>
                                          <td>{{ $item->NombreProveedor }}</td>
                                          <td>{{ $item->NumeroDocumento }}</td>
                                          <td>{{ $item->Total }}</td>
                                          <td>{{ $item->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                          <td>{{ $item->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                          <td>{{ $item->TipoCompra == 1 ? 'Gravada' : 'Exonerado' }}</td>
                                          <td>{{ $item->Estado }}</td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                  </div>
              </div>
          </div>
          {{-- Fin --}}
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
      <script type="text/javascript">
          $(function() {
              $(document).ready(function() {
                  $('#tableSoles').DataTable({
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
                  $('#tableDolares').DataTable({
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
