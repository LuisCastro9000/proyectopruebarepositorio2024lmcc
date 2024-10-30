  @extends('layouts.app')
  @section('title', 'Reportes Bancos')
  @section('content')
      <style>
          .card-bg {
              color: #287BF7;
              border-radius: 10px !important;
          }

          .card-bg--montoActual {
              color: #1266F1;
              border-radius: 10px !important;
              background-color: #B8D1FB;
          }

          .card-bg--datoOcho {
              background-color: #e4e6e7;
          }

          .card_datos {
              font-size: 28px;
          }
      </style>
      <div class="container">
          @if (session('status'))
              <div class="alert alert-success mt-3">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('status') }}
              </div>
          @endif
          @if (session('error'))
              <div class="alert alert-danger mt-3">
                  <button type="button" class="close" data-dismiss="alert">&times;</button>
                  {{ session('error') }}
              </div>
          @endif

          {!! Form::open(['url' => '/reportes/financieros/bancos', 'method' => 'POST', 'files' => true]) !!}
          {{ csrf_field() }}

          {{-- <div class="col-md-4 mt-4 order-md-3 order-last" style="background-color: #54639A">
                  <div class="form-group container ">
                      <br>
                      <button type="submit" class="btn btn-primary">Buscar</button>
                      <a class="ml-5" target="_blank"
                          href='{{ url("reportes/financieros/excel-bancos/$idBanco/$fecha/$fechaInicial/$fechaFinal") }}'>
                          <span class="btn bg-excel ripple">
                              <i class="list-icon material-icons fs-20">explicit</i>XCEL
                          </span>
                      </a>
                  </div>
              </div> --}}
          <div class="row">
              <div class="col-lg-4 mt-4 ">
                  <div class="form-group form-material">
                      <label>Cuenta Bancaria</label>
                      <select class="form-control" id="cuentaBancaria" name="cuentaBancaria">
                          <option value="0">Seleccione cuenta bancaria</option>
                          @foreach ($cuentas as $banco)
                              @if ($idBanco == $banco->IdBanco)
                                  <option value="{{ $banco->IdBanco }}" selected>{{ $banco->Banco }} -
                                      {{ $banco->NumeroCuenta }}</option>
                              @else
                                  <option value="{{ $banco->IdBanco }}">{{ $banco->Banco }} -
                                      {{ $banco->NumeroCuenta }}
                                  </option>
                              @endif
                          @endforeach
                      </select>
                  </div>
              </div>
              <div class="col-lg-3 mt-4 ">
                  <x-selectorFiltrosFechas obtenerDatos='false' class='form-material' />
              </div>

              <div class="col-lg-2 col-sm-4 col-12 mt-0 mt-md-4 text-center">
                  <br>
                  <a class="" href="https://www.youtube.com/watch?v=Tk86-UzfmF0" target="_blank">
                      <span class="btn btn-autocontrol-naranja ripple text-white">
                          Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                      </span>
                  </a>
              </div>

              <div class="col-lg-1 col-sm-2 col-6 mt-0 mt-md-4  text-center">
                  <div class="form-group">
                      <br>
                      <button type="submit" class="btn btn-primary">Buscar</button>
                  </div>
              </div>

              <div class="col-lg-1 col-sm-2 col-6 mt-0 mt-md-4  text-center">
                  <br>
                  <a class="" target="_blank"
                      href='{{ url("reportes/financieros/excel-bancos/$idBanco/$fecha/$ini/$fin") }}'>
                      <span class="btn bg-excel ripple">
                          <i class="list-icon material-icons fs-20">explicit</i>XCEL
                      </span>
                  </a>
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
              @if ($idBanco > 0)
                  <div class="row mb-3">
                      <div class="col-12 col-lg-4">
                          <div class="card card-bg card-bg--datoOcho">
                              <div class="card-body text-center">
                                  @if ($idTipoMoneda == 1)
                                      <span class=" card_datos font-weight-bold">S/</span>
                                  @else
                                      <span class=" card_datos font-weight-bold">$ </span>
                                  @endif
                                  <span
                                      class="card_datos font-weight-bold">{{ number_format($totalDetalleCuentaIngreso, 2, '.', ',') }}</span><br>
                                  <span class=" text-dark font-weight-bold">Total de Ingreso</span>
                              </div>
                          </div>
                      </div>
                      <div class="col-12 col-lg-4">
                          <div class="card card-bg--montoActual">
                              <div class="card-body text-center">
                                  @if ($idTipoMoneda == 1)
                                      <span class=" card_datos font-weight-bold">S/</span>
                                  @else
                                      <span class=" card_datos font-weight-bold">$ </span>
                                  @endif
                                  <span
                                      class="card_datos font-weight-bold">{{ number_format($montoActualCuenta, 2, '.', ',') }}</span><br>
                                  <span class="font-weight-bold text-dark">Monto Actual</span>
                              </div>
                          </div>
                      </div>
                      <div class="col-12 col-lg-4">
                          <div class="card card-bg card-bg--datoOcho">
                              <div class="card-body text-center">
                                  @if ($idTipoMoneda == 1)
                                      <span class=" card_datos font-weight-bold">S/</span>
                                  @else
                                      <span class=" card_datos font-weight-bold">$ </span>
                                  @endif
                                  <span
                                      class="card_datos font-weight-bold">{{ number_format($totalDetalleCuentaSalida, 2, '.', ',') }}</span><br>
                                  <span class=" text-dark font-weight-bold">Total de Salida</span>
                              </div>
                          </div>
                      </div>
                  </div>
                  <div class="row mb-3">
                      @if (count($detallesCuenta) >= 1)
                          <div class="col-12 col-lg-6">
                              <div class="card">
                                  <div id="graficoBancoIngresos">
                                  </div>
                              </div>
                          </div>
                          <div class="col-12 col-lg-6 mt-4 mt-lg-0">
                              <div class="card">
                                  <div id="graficoBancoSalida">
                                  </div>
                              </div>
                          </div>
                      @else
                          <div class="col-12">
                              <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                  <strong>No se han realizado Ingresos y Salidas,</strong> aplique otros filtros para
                                  obtener la informacion esperada..
                                  <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                      <span aria-hidden="true">&times;</span>
                                  </button>
                              </div>
                          </div>
                      @endif
                  </div>
              @endif

              <div class="row">
                  <div class="col-md-12 widget-holder">
                      <div class="widget-bg">
                          <!-- /.widget-heading -->
                          <div class="widget-body clearfix">
                              <!--<p>Listado de ventas</p>-->
                              <table id="table" class="table table-responsive-sm" style="width:100%">
                                  <thead>
                                      <tr class="bg-primary">
                                          {{-- <th hidden scope="col">Id</th> --}}
                                          <th scope="col">Fecha Transferencia</th>
                                          <th scope="col">Nro Operación</th>
                                          <th scope="col">Detalle</th>
                                          <th scope="col">Tipo Movimiento</th>
                                          <th scope="col">Ingreso</th>
                                          <th scope="col">Salida</th>
                                          <th scope="col">Monto Actual</th>
                                          <th scope="col">Sucursal</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($detallesCuenta as $detalleCuenta)
                                          <tr>
                                              {{-- <td hidden>{{ $detalleCuenta->IdBancoDetalles }}</td> --}}
                                              <td>{{ $detalleCuenta->FechaPago }}</td>
                                              <td>{{ $detalleCuenta->NumeroOperacion }}</td>
                                              <td>{{ $detalleCuenta->Detalle }}</td>
                                              <td>{{ $detalleCuenta->TipoMovimiento }}</td>
                                              <td>{{ $detalleCuenta->Entrada }}</td>
                                              <td>{{ $detalleCuenta->Salida }}</td>
                                              <td>{{ number_format($detalleCuenta->SaldoActualCalculado, 2, '.', ',') }}
                                              </td>
                                              <td>{{ $detalleCuenta->NombreSucursal }}</td>
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
          var nombreTipoMovimientoEntrada = <?php echo json_encode($nombreTipoMovimientoEntrada); ?>;
          var totalTipoMovimientoEntrada = <?php echo json_encode($totalTipoMovimientoEntrada); ?>;
          var options = {
              series: totalTipoMovimientoEntrada,
              chart: {
                  type: 'polarArea',
                  height: 460
              },
              labels: nombreTipoMovimientoEntrada,
              fill: {
                  opacity: 1
              },
              title: {
                  text: 'Total de Ingreso x Tipo de Movimiento',
                  margin: 50,
                  align: 'center'
              },
              colors: ['#1B1464', '#FECC6C', '#12CBC4', '#6794DC', '#5CEDBC', '#A897E1', '#FF8899', '#5CB7FC'],
              stroke: {
                  width: 1,
                  colors: undefined
              },
              yaxis: {
                  show: false
              },
              legend: {
                  position: 'bottom'
              },
              plotOptions: {
                  polarArea: {
                      rings: {
                          strokeWidth: 0
                      },
                      spokes: {
                          strokeWidth: 0
                      },
                  }
              }
          };

          var chart = new ApexCharts(document.querySelector("#graficoBancoIngresos"), options);
          chart.render();


          var nombreTipoMovimientoSalida = <?php echo json_encode($nombreTipoMovimientoSalida); ?>;
          var totalTipoMovimientoSalida = <?php echo json_encode($totalTipoMovimientoSalida); ?>;
          var options = {
              series: totalTipoMovimientoSalida,
              chart: {
                  type: 'polarArea',
                  height: 460
              },
              labels: nombreTipoMovimientoSalida,
              fill: {
                  opacity: 1
              },
              title: {
                  text: 'Total de Salida x Tipo de Movimiento',
                  margin: 50,
                  align: 'center'
              },
              colors: ['#5CEDBC', '#A897E1', '#FF8899', '#FECC6C', '#5CB7FC'],
              stroke: {
                  width: 1,
                  colors: undefined
              },
              yaxis: {
                  show: false
              },
              legend: {
                  position: 'bottom'
              },
              plotOptions: {
                  polarArea: {
                      rings: {
                          strokeWidth: 0
                      },
                      spokes: {
                          strokeWidth: 0
                      },
                  }
              }
          };

          var chart = new ApexCharts(document.querySelector("#graficoBancoSalida"), options);
          chart.render();
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
          function redondeo(num) {
              /*var flotante = parseFloat(numero);
              var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
              return resultado;*/
              if (num == 0 || num == "0.00") return "0.00";
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
  @stop
