  @extends('layouts.app')
  @section('title', 'Reporte de proveedores')
  @section('content')
      <style>
          .card-bg {
              color: #ffff;
              border-radius: 10px !important;
          }

          .card-bg--soles {
              background-image: linear-gradient(to right bottom, #0c73b3 0%, #363795 51%, #005C97 100%);
          }

          .card-bg--dolares {
              background: #6441A5;
              /* fallback for old browsers */
              background: -webkit-linear-gradientt (to right bottom, #b17ddf, #6441A5, #481d97);
              /* Chrome 10-25, Safari 5.1-6 */
              background: linear-gradient(to right bottom, #b17ddf, #6441A5, #481d97);
              /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */

          }

          .card_datos {
              font-size: 28px;
          }

          .card-datos--color {
              color: #FFFF;
          }

          .separator-dashed {
              border-style: dashed;
              border-width: .2px;
              color: #EBECF3;
          }

          .card-bg--datoUno {
              background-color: #54639A;
          }

          .card-bg--datoDos {
              background-color: #16B29C;
          }

          .card-bg--datoTres {
              background-color: #7F59EF;
          }

          .card-bg--datoCuatro {
              background-color: #5283FA;
          }

          .card-bg--datoCinco {
              background-color: #D65D66;
          }

          .card-bg--datoSeis {
              background-color: #00ACC2;
          }

          .card-bg--datoSiete {
              background-color: #7F59EF;
          }

          .card-bg--datoOcho {
              background-color: #3F51B5;
          }
      </style>
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
          {!! Form::open(['url' => '/reportes/compras/proveedores', 'method' => 'POST', 'files' => true]) !!}
          {{ csrf_field() }}
          <div class="row clearfix">
              {{-- <div class="col-md-3 mt-4 order-md-0">
                  <div class="form-group form-material">
                      <label>Proveedor</label>
                      <input id="list" type="text" list="contenido" name="proveedor" class="form-control AvenirMedium lista"
                          style="font-size:14px;" value="{{ $inputproveedor }}" />
                      <datalist id="contenido">
                          @foreach ($proveedores as $proveedor)
                              <option value="{{ $proveedor->Nombre }}"></option>
                          @endforeach
                      </datalist>
                  </div>
              </div> --}}

              <div class="col-md-3 mt-4 order-md-0">
                  <div class="form-group form-material">
                      <label>Proveedor</label>
                      <select class="m-b-10 form-control select2-hidden-accessible" id="proveedor" name="proveedor"
                          data-placeholder="Seleccione proveedor" data-toggle="select2" tabindex="-1" aria-hidden="true">
                          <option value="0">Seleccione Proveedor</option>
                          @foreach ($proveedores as $proveedor)
                              @if ($inputproveedor == $proveedor->IdProveedor)
                                  <option value="{{ $proveedor->IdProveedor }} " selected>{{ $proveedor->Nombre }}
                                  </option>
                              @else
                                  <option value="{{ $proveedor->IdProveedor }} ">{{ $proveedor->Nombre }}</option>
                              @endif
                          @endforeach
                      </select>
                  </div>
              </div>

              <div class="col-md-3 mt-4 order-md-1">
                  <div class="form-group form-material">
                      <label>Tipo Pago</label>
                      <select id="tipoPago" class="form-control" name="tipoPago">
                          <option value="0">Todo</option>
                          <option value="1">Contado</option>
                          <option value="2">Crédito</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-2">
                  <x-selectorFiltrosFechas obtenerDatos='false' />
              </div>
              <div class="col-md-1 col-6 mt-4 order-md-3 col-4 text-center">
                  <div class="form-group">
                      <br>
                      <?php $inputproveedor = $inputproveedor == '' ? 0 : $inputproveedor; ?>
                      <button type="submit" class="btn btn-primary">Buscar</button>
                  </div>
              </div>
              <div class="col-md-1 col-6 mt-4 order-md-4 text-center">
                  <div class="form-group">
                      <br>
                      <a class="p-0" target="_blank"
                          href='{{ url("reportes/compras/excel-proveedores/$inputproveedor/$IdTipoPago/$fecha/$ini/$fin") }}'>
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
      <!-- /.container -->
      <!-- =================================== -->
      <!-- Different data widgets ============ -->
      <!-- =================================== -->
      <div class="container">
          <div class="widget-list">

              <section class="graficos">
                  @if (count($reporteProveedores) >= 1)
                      @if ($inputproveedor > 1)
                          @if ($fecha == '1' || $fecha == '2' || $fecha == '3')
                              <section class="my-3">
                                  <nav>
                                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                                              href="#nav-home" role="tab" aria-controls="nav-home"
                                              aria-selected="true">Reporte
                                              en Soles</a>
                                          <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                                              href="#nav-profile" role="tab" aria-controls="nav-profile"
                                              aria-selected="false">Reporte en Dolares</a>
                                      </div>
                                  </nav>
                                  <div class="tab-content" id="nav-tabContent">
                                      {{-- Reporte en soles --}}
                                      <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                          aria-labelledby="nav-home-tab">
                                          {{-- Cuadros de datos Unico Proveedor --}}
                                          <div class="row">
                                              <div class="col-12 text-center mb-3">
                                                  <h6> {{ $nombreProveedoresSoles }}</h6>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg">
                                                      <span>Total Compras: Contado</span>
                                                      <span class="card_datos font-weight-bold">S/.
                                                          {{ number_format($comprasContado, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg">
                                                      <span>Total Compras: Crédito</span>
                                                      <span class="card_datos font-weight-bold">S/.
                                                          {{ number_format($comprasCredito, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                          {{-- Grafico soles --}}
                                          <div class="row my-4">
                                              <div class="col-12">
                                                  <div class="card">
                                                      <section class="d-flex justify-content-center">
                                                          <div id="graficoProveedoresSoles"></div>
                                                      </section>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                      </div>
                                      {{-- Reporte en dolares --}}
                                      <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                          aria-labelledby="nav-profile-tab">
                                          {{-- Cuadros de datos Unico Proveedor --}}
                                          <div class="row">
                                              <div class="col-12 text-center mb-3">
                                                  <h6>{{ $nombreProveedoresDolares }}</h6>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg card-bg--dolares">
                                                      <span>Total Compras: Contado</span>
                                                      <span class="card_datos font-weight-bold">$
                                                          {{ number_format($comprasContadoDolares, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg card-bg--dolares">
                                                      <span>Total Compras: Crédito</span>
                                                      <span class="card_datos font-weight-bold">$
                                                          {{ number_format($comprasCreditoDolares, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                          {{-- Grafico Dolaress --}}
                                          <div class="row my-4">
                                              <div class="col-12">
                                                  <div class="card">
                                                      <section class="d-flex justify-content-center">
                                                          <div id="graficoProveedoresDolares"></div>
                                                      </section>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                      </div>
                                  </div>
                              </section>
                          @else
                              {{-- Reporte unico Proveedor --}}
                              <section class="my-3">
                                  <nav>
                                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                                              href="#nav-home" role="tab" aria-controls="nav-home"
                                              aria-selected="true">Reporte
                                              en Soles</a>
                                          <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                                              href="#nav-profile" role="tab" aria-controls="nav-profile"
                                              aria-selected="false">Reporte en Dolares</a>
                                          <a class="ml-2" href="https://youtu.be/eiZsIDNi5Og" target="_blank">
                                              <span class="btn btn-autocontrol-naranja ripple text-white">
                                                  Video Instructivo <i
                                                      class="list-icon material-icons fs-24 color-icon">videocam</i>
                                              </span>
                                          </a>
                                      </div>
                                  </nav>
                                  <div class="tab-content" id="nav-tabContent">
                                      {{-- Reporte en soles --}}
                                      <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                          aria-labelledby="nav-home-tab">
                                          {{-- Cuadros de datos Unico Proveedor --}}
                                          <div class="row">
                                              <div class="col-12 text-center mb-3">
                                                  <h6>{{ $nombreProveedores }}</h6>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg card-bg--soles">
                                                      <span>Total Compras: Contado</span>
                                                      <span class="card_datos font-weight-bold">S/.
                                                          {{ number_format($comprasContado, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg card-bg--soles">
                                                      <span>Total Compras: Crédito</span>
                                                      <span class="card_datos font-weight-bold">S/.
                                                          {{ number_format($comprasCredito, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                          {{-- Grafico soles --}}
                                          <div class="row my-4">
                                              <div class="col-12">
                                                  <div id="gaficoUnicoProveedor"></div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                      </div>
                                      {{-- Reporte en dolares --}}
                                      <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                          aria-labelledby="nav-profile-tab">
                                          {{-- Cuadros de datos Unico Proveedor --}}
                                          <div class="row">
                                              <div class="col-12 text-center mb-3">
                                                  <h6> {{ $nombreProveedores }}</h6>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg card-bg--dolares">
                                                      <span>Total Compras: Contado</span>
                                                      <span class="card_datos font-weight-bold">$
                                                          {{ number_format($comprasContadoDolares, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                              <div class="col-md-6">
                                                  <div class="card p-3 text-center rounded card-bg card-bg--dolares">
                                                      <span>Total Compras: Crédito</span>
                                                      <span class="card_datos font-weight-bold">$
                                                          {{ number_format($comprasCreditoDolares, 2, '.', ',') }}</span>
                                                  </div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                          {{-- Grafico Dolaress --}}
                                          <div class="row my-4">
                                              <div class="col-12">
                                                  <div id="gaficoUnicoProveedorDolares"></div>
                                              </div>
                                          </div>
                                          {{-- Fin --}}
                                      </div>
                                  </div>
                              </section>
                          @endif
                      @else
                          {{-- Reporte todos los proveedores --}}
                          <div class="row my-4">
                              <div class="col-12">
                                  <nav>
                                      <div class="nav nav-tabs" id="nav-tab" role="tablist">
                                          <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab"
                                              href="#nav-home" role="tab" aria-controls="nav-home"
                                              aria-selected="true">Reporte Soles</a>
                                          <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab"
                                              href="#nav-profile" role="tab" aria-controls="nav-profile"
                                              aria-selected="false">Reporte Dólares</a>
                                          <a class="ml-2" href="https://youtu.be/eiZsIDNi5Og" target="_blank">
                                              <span class="btn btn-autocontrol-naranja ripple text-white">
                                                  Video Instructivo <i
                                                      class="list-icon material-icons fs-24 color-icon">videocam</i>
                                              </span>
                                          </a>
                                  </nav>
                                  <div class="tab-content" id="nav-tabContent">
                                      {{-- Reporte proveedores soles --}}
                                      <div class="tab-pane fade show active" id="nav-home" role="tabpanel"
                                          aria-labelledby="nav-home-tab">
                                          <div class="row align-items-center">
                                              <div class="col">
                                                  <section class="d-flex justify-content-center">
                                                      <div id="graficoTodosLosProveedoresSoles"></div>
                                                  </section>
                                              </div>
                                              <div class="col">
                                                  <div class="card">
                                                      <ul class="list-group list-group-flush">
                                                          <div class=" text-center py-3 card-bg card-bg--datoUno">
                                                              <span> Total compras Realizadas</span><br>
                                                              <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ $totalComprasRealizadasSoles }}</span>
                                                          </div>
                                                          <div class="separator-dashed"></div>
                                                          <div class=" text-center py-3 card-bg card-bg--datoDos">
                                                              <span> Monto total de Compras (Contado-Crédito)</span><br>
                                                              S/. <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ number_format($comprasSumaTotal, 2, '.', ',') }}</span>
                                                          </div>
                                                          <div class="separator-dashed"></div>
                                                          <div class=" text-center py-3 card-bg card-bg--datoTres">
                                                              <span>Total Compras: contado</span><br>
                                                              S/. <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ number_format($comprasContado, 2, '.', ',') }}</span>
                                                          </div>
                                                          <div class="separator-dashed"></div>
                                                          <div class=" text-center py-3 card-bg card-bg--datoCuatro">
                                                              <span>Total Compras: Crédito</span><br>
                                                              S/. <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ number_format($comprasCredito, 2, '.', ',') }}</span>
                                                          </div>
                                                      </ul>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                      {{-- Reporte proveedores dolares --}}
                                      <div class="tab-pane fade" id="nav-profile" role="tabpanel"
                                          aria-labelledby="nav-profile-tab">
                                          <div class="row align-items-center">
                                              @if (count($gaficoProveedorDolares) >= 1)
                                                  <div class="col">
                                                      <section class="d-flex justify-content-center">
                                                          <div id="graficoTodosLosProveedoresDolares"></div>
                                                      </section>
                                                  </div>
                                              @endif
                                              <div class="col">
                                                  <div class="card">
                                                      <ul class="list-group list-group-flush">
                                                          <div class=" text-center py-3 card-bg card-bg--datoCinco">
                                                              <span> Total compras Realizadas</span><br>
                                                              <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ $totalComprasRealizadasDolares }}</span>
                                                          </div>
                                                          <div class="separator-dashed"></div>
                                                          <div class=" text-center py-3 card-bg card-bg--datoSeis">
                                                              <span> Monto total de Compras (Contado-Crédito)</span><br>
                                                              $ <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ number_format($comprasSumaTotalDolares, 2, '.', ',') }}</span>
                                                          </div>
                                                          <div class="separator-dashed"></div>
                                                          <div class=" text-center py-3 card-bg card-bg--datoSiete">
                                                              <span>Total Compras: contado</span><br>
                                                              $ <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ number_format($comprasContadoDolares, 2, '.', ',') }}</span>
                                                          </div>
                                                          <div class="separator-dashed"></div>
                                                          <div class=" text-center py-3 card-bg card-bg--datoOcho">
                                                              <span>Total Compras: Crédito</span><br>
                                                              $ <span
                                                                  class="card_datos card-datos--color font-weight-bold">{{ number_format($comprasCreditoDolares, 2, '.', ',') }}</span>
                                                          </div>
                                                      </ul>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      @endif
                  @else
                      <div class="col-md-12 col-12  m-auto">
                          <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>No se encontraron Datos!</strong> Por Favor aplique otros filtros para realizar su
                              consulta.
                              <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                  <span aria-hidden="true">&times;</span>
                              </button>
                          </div>
                      </div>
                  @endif

              </section>

              <div class="row justify-content-end">
                  {{-- <div class="col-md-3">
                      <div id="comprasContado" class="form-group">
                          <label class="form-control-label">Total Compras: Contado</label>
                          <div class="input-group">
                              <input id="cContado" type="text" class="form-control">
                          </div>
                      </div>
                  </div>
                  <div class="col-md-3">
                      <div id="comprasCredito" class="form-group">
                          <label class="form-control-label">Total Compras: Crédito</label>
                          <div class="input-group">
                              <input id="cCredito" type="text" class="form-control">
                          </div>
                      </div>
                  </div> --}}
                  <div class="col-md-12 widget-holder">
                      <div class="widget-bg">
                          <!-- /.widget-heading -->
                          <div class="widget-body clearfix">
                              <!--<p>Listado de ventas</p>-->
                              <table id="table" class="table table-responsive-sm" style="width:100%">
                                  <thead>
                                      <tr class="bg-primary">
                                          <th scope="col">Fecha Emitida</th>
                                          <th scope="col">Proveedor</th>
                                          <th scope="col">Documento</th>
                                          <th scope="col">Tipo Comprob.</th>
                                          <th scope="col">Codigo</th>
                                          <th scope="col">Tipo de Pago</th>
                                          <th scope="col">Tipo de Compra</th>
                                          <th scope="col">Tipo Moneda</th>
                                          <th scope="col">Total Costo</th>
                                          <th scope="col">Estado</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($reporteProveedores as $reportProveedor)
                                          <tr>
                                              <td>{{ $reportProveedor->FechaCreacion }}</td>
                                              <td>{{ $reportProveedor->Nombres }}</td>
                                              <td>{{ $reportProveedor->Documento }}</td>
                                              <td>{{ $reportProveedor->Descripcion }}</td>
                                              <td>{{ $reportProveedor->Serie }} - {{ $reportProveedor->Numero }}</td>
                                              @if ($reportProveedor->IdTipoPago == 1)
                                                  <td>Contado</td>
                                              @else
                                                  <td>Crédito</td>
                                              @endif
                                              <td>{{ $reportProveedor->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                              <td>{{ $reportProveedor->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                              <td>{{ $reportProveedor->Total }}</td>
                                              <td>{{ $reportProveedor->Estado }}</td>
                                          </tr>
                                      @endforeach
                                  </tbody>
                              </table>
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

      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="text-success">Reportes de Proveedores - Productos</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <label class="fs-14 negrita">Reporte del Mes</label>
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
      {{-- SCRIPT DEL NUEVO GRAFICO  REPORTE VENTAS --}}
      <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
      {{-- FIN --}}
      <script>
          $(function() {
              var bandModal = <?php echo json_encode($IdTipoPago); ?>;
              if (bandModal == '') {
                  $("#mostrarmodal").modal("show");
              }
              $('#comprasContado').hide();
              $('#comprasCredito').hide();
              var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;
              var proveedor = <?php echo json_encode($inputproveedor); ?>;
              if (proveedor != '' && proveedor != null) {
                  var comprasContado = <?php echo json_encode($comprasContado); ?>;
                  var comprasCredito = <?php echo json_encode($comprasCredito); ?>;
                  $('#comprasContado').show();
                  $('#comprasCredito').show();
                  $('#cContado').val(redondeo(comprasContado));
                  $('#cCredito').val(redondeo(comprasCredito));
              }
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
      </script>

      {{-- Grafico todos los proveedores Soles --}}
      <script>
          var totalComprasProveedoresSoles = <?php echo json_encode($totalComprasProveedoresSoles); ?>;
          var nombresProveedoresSoles = <?php echo json_encode($nombreProveedoresSoles); ?>;
          var options = {
              series: totalComprasProveedoresSoles,
              chart: {
                  type: 'polarArea',
                  width: 560
              },
              labels: nombresProveedoresSoles,
              legend: {
                  position: 'bottom'
              },
              yaxis: {
                  show: false
              },
              stroke: {
                  colors: ['#fff']
              },
              fill: {
                  opacity: 0.8
              },
              responsive: [{
                      breakpoint: 300,
                      options: {
                          chart: {
                              width: 300
                          },
                          legend: {
                              position: 'bottom'
                          }
                      }
                  },
                  {
                      breakpoint: 600,
                      options: {
                          chart: {
                              width: 300
                          },
                          legend: {
                              position: 'bottom'
                          }
                      }
                  }
              ]
          };

          var chart = new ApexCharts(document.querySelector("#graficoTodosLosProveedoresSoles"), options);
          chart.render();
      </script>

      {{-- Grafico todos los proveedores Dolares --}}
      <script>
          var totalComprasProveedoresDolares = <?php echo json_encode($totalComprasProveedoresDolares); ?>;
          var nombresProveedoresDolares = <?php echo json_encode($nombreProveedoresDolares); ?>;
          var options = {
              series: totalComprasProveedoresDolares,
              chart: {
                  type: 'polarArea',
                  width: 560
              },
              labels: nombresProveedoresDolares,
              legend: {
                  position: 'bottom'
              },
              yaxis: {
                  show: false
              },
              stroke: {
                  colors: ['#fff']
              },
              fill: {
                  opacity: 0.8
              },
              responsive: [{
                      breakpoint: 300,
                      options: {
                          chart: {
                              width: 380
                          },
                          legend: {
                              position: 'bottom'
                          }
                      }
                  },
                  {
                      breakpoint: 600,
                      options: {
                          chart: {
                              width: 400
                          },
                          legend: {
                              position: 'bottom'
                          }
                      }
                  }
              ]
          };

          var chart = new ApexCharts(document.querySelector("#graficoTodosLosProveedoresDolares"), options);
          chart.render();
      </script>

      {{-- Grafico unico proveedores soles --}}
      <script>
          var unicoProveedor = <?php echo json_encode($arrayUnicoProveedor); ?>;
          var fechas = <?php echo json_encode($arrayFechasFiltros); ?>;
          var options = {
              series: [{
                  name: 'Total',
                  data: unicoProveedor,
              }],
              chart: {
                  type: 'area',
                  height: 350,
                  zoom: {
                      enabled: false
                  }
              },
              colors: ['#0D9AF5'],
              dataLabels: {
                  enabled: false
              },
              stroke: {
                  curve: 'straight'
              },
              xaxis: {
                  // type: 'category',
                  categories: fechas,
                  // labels: {
                  //     formatter: function(value, timestamp) {
                  //         return new Date(timestamp) // The formatter function overrides format property
                  //     },
                  // }
              },

              yaxis: {
                  opposite: false,
              },
              legend: {
                  horizontalAlign: 'right',
              }
          };

          var chart = new ApexCharts(document.querySelector("#gaficoUnicoProveedor"), options);
          chart.render();
      </script>

      {{-- Grafico unico proveedores Dolares --}}
      <script>
          var unicoProveedor = <?php echo json_encode($arrayUnicoProveedorDolares); ?>;
          var fechas = <?php echo json_encode($arrayFechasFiltrosDolares); ?>;
          var options = {
              series: [{
                  name: 'Total',
                  data: unicoProveedor,
              }],
              chart: {
                  type: 'area',
                  height: 350,
                  zoom: {
                      enabled: false
                  }
              },
              colors: ['#0D9AF5'],
              dataLabels: {
                  enabled: false
              },
              stroke: {
                  curve: 'straight'
              },
              xaxis: {
                  // type: 'category',
                  categories: fechas,
                  // labels: {
                  //     formatter: function(value, timestamp) {
                  //         return new Date(timestamp) // The formatter function overrides format property
                  //     },
                  // }
              },
              yaxis: {
                  opposite: false,
              },
              legend: {
                  horizontalAlign: 'right',
              }
          };

          var chart = new ApexCharts(document.querySelector("#gaficoUnicoProveedorDolares"), options);
          chart.render();
      </script>
  @stop
