  @extends('layouts.app')
  @section('title', 'Consulta Guía Remisión')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Guías de Remisión</h6>
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

          {!! Form::open([
              'url' => '/consultas/guias-remision',
              'method' => 'POST',
              'id' => 'formObtenerDatos',
              'files' => true,
          ]) !!}
          {{ csrf_field() }}
          <div class="row clearfix">
              <div class="col-md-12 mt-4 order-md-1">
                  <x-selectorFiltrosFechas />
              </div>
          </div>
          <x-inputFechasPersonalizadas />
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
                  <div class="col-md-12 widget-holder">
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
                                          <th scope="col">Fecha Emitida</th>
                                          <th scope="col">Fecha Traslado</th>
                                          <th scope="col">Cliente</th>
                                          <th scope="col">Documento</th>
                                          <th scope="col">Nro. Guia</th>
                                          <th scope="col">Motivo</th>
                                          <th scope="col">Estado</th>
                                          <th scope="col">Cód. Error</th>
                                          <th scope="col">Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($guiasAceptados as $guiaAceptado)
                                          <tr>
                                              <td>{{ $guiaAceptado->FechaEmision }}</td>
                                              <td>{{ $guiaAceptado->FechaTraslado }}</td>
                                              <td>{{ $guiaAceptado->Nombres }}</td>
                                              <td>{{ $guiaAceptado->DocumentoVenta }}</td>
                                              <td>{{ $guiaAceptado->Serie }}-{{ $guiaAceptado->Numero }}</td>
                                              <td>{{ $guiaAceptado->Motivo }}</td>
                                              <td>{{ $guiaAceptado->Estado }}</td>
                                              <td>{{ $guiaAceptado->codigoError == 0 ? '' : $guiaAceptado->codigoError }}
                                              </td>
                                              <td class="text-center">
                                                  <a href="../consultas/guias-remision/detalles/{{ $guiaAceptado->IdGuiaRemision }}"
                                                      title="Detalles"><i
                                                          class="list-icon material-icons">visibility</i></a>
                                                  <a href="../consultas/guias-remision/descargar/{{ $guiaAceptado->IdGuiaRemision }}"
                                                      title="Descargar PDF"><i
                                                          class="list-icon material-icons">picture_as_pdf</i></a>
                                                  <a href="../consultas/guias-remision/xml/{{ $rucEmpresa }}/{{ $guiaAceptado->IdGuiaRemision }}"
                                                      title="Descargar XML"><i class="list-icon material-icons">code</i></a>
                                                  @if ($guiaAceptado->RutaCdr != null)
                                                      <a href="../consultas/guias-remision/cdr/{{ $rucEmpresa }}/{{ $guiaAceptado->IdGuiaRemision }}"
                                                          title="Descargar CDR"><i
                                                              class="list-icon material-icons">attach_file</i></a>
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
                      <h6 class="text-success">Consultas Guías de Remisión</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <label class="fs-14 negrita">Reporte de último mes</label>
                          <p class="fs-15negrita">Se mostraran los reportes del último mes....... Si desea ver las guías de
                              remisiones anteriores utilize los filtros</p>
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
              var idTipoPago = <?php echo json_encode($IdTipoPago); ?>;

              if (idTipoPago == '') {
                  $("#mostrarmodal").modal("show");
              }
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
  @stop
