  @extends('layouts.app')
  @section('title', 'Reportes Clientes con Morosidad')
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
          {!! Form::open([
              'url' => '/reportes/cobranzas/clientes-morosos',
              'id' => 'formObtenerDatos',
              'method' => 'POST',
              'files' => true,
          ]) !!}
          {{ csrf_field() }}
          <div class="row clearfix">
              <div class="col-md-10 mt-4">
                  <x-selectorFiltrosFechas metodoObtenerDatos='submit' />
              </div>
              <div class="col-md-2 mt-4 form-group align-self-end">
                  <a target="_blank" href='{{ url("reportes/cobranzas/excel-clientes-morosos/$fecha/$ini/$fin") }}'>
                      <span class="btn bg-excel ripple">
                          <i class="list-icon material-icons fs-20">explicit</i>Excel
                      </span>
                  </a>
              </div>
          </div>
          <x-inputFechasPersonalizadas />
          {!! Form::close() !!}
      </div>
      <!-- /.container -->
      <!-- =================================== -->
      <!-- Different data widgets ============ -->
      <!-- =================================== -->
      <div class="container">
          <div class="widget-list">
              <div class="row">
                  <div class="col-md-12 widget-holder">
                      <div class="row justify-content-center align-items-center">
                          <div class="col-md-2 col-sm-4 col-6 p-1">
                              <div class="text-center" style="background-color: #008000;">
                                  <strong class="fs-14 text-white">Pendiente</strong>
                                  <br>
                                  <small class="fs-12 text-white">0 días</small>
                              </div>
                          </div>
                          <div class="col-md-2 col-sm-4 col-6 p-1">
                              <div class="text-center" style="background-color: #77b300;">
                                  <strong class="fs-14 text-white">Problema Potencial</strong>
                                  <br>
                                  <small class="fs-12 text-white">1 - 15 días</small>
                              </div>
                          </div>
                          <div class="col-md-2 col-sm-4 col-6 p-1">
                              <div class="text-center" style="background-color: #c5c528;">
                                  <strong class="fs-14 text-white">Deficiente</strong>
                                  <br>
                                  <small class="fs-12 text-white">16 - 30 días</small>
                              </div>
                          </div>
                          <div class="col-md-2 col-sm-4 col-6 p-1">
                              <div class="text-center" style="background-color: #ff9900;">
                                  <strong class="fs-14 text-white">Dudoso</strong>
                                  <br>
                                  <small class="fs-12 text-white">31 - 60 días</small>
                              </div>
                          </div>
                          <div class="col-md-2 col-sm-4 col-6 p-1">
                              <div class="text-center" style="background-color: #ff0000;">
                                  <strong class="fs-14 text-white">Pérdida</strong>
                                  <br>
                                  <small class="fs-12 text-white">61 a más días</small>
                              </div>
                          </div>
                      </div>
                      <div class="widget-bg">
                          <!-- /.widget-heading -->
                          <div class="widget-body clearfix">
                              <!--<p>Listado de ventas</p>-->
                              <table id="table" class="table table-responsive-sm" style="width:100%">
                                  <thead>
                                      <tr class="bg-primary">
                                          <th scope="col">Cliente</th>
                                          <th scope="col">Documento</th>
                                          <th scope="col">Última Fecha Pagada</th>
                                          <th scope="col">Tipo Moneda</th>
                                          <th scope="col">Importe Inicial</th>
                                          <th scope="col">Deuda Actual</th>
                                          <th scope="col">Días Atrasados</th>
                                          <th scope="col">Estado</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($clientesMorosos as $clienteMoroso)
                                          <tr>
                                              <td>{{ $clienteMoroso->Cliente }}</td>
                                              <td>{{ $clienteMoroso->Serie . '-' . $clienteMoroso->Numero }}
                                                  {{ $clienteMoroso->Nota == 1 ? ' : N. Credito' : '' }} </td>
                                              <td>{{ $clienteMoroso->FechaPago }}</td>
                                              <td>{{ $clienteMoroso->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                              <td>{{ $clienteMoroso->Importe }}</td>
                                              <td>{{ $clienteMoroso->Deuda }}</td>
                                              <td>{{ $clienteMoroso->DiasPasados }}</td>
                                              <td><span class="badge fs-11 text-white"
                                                      style="background-color: {{ $clienteMoroso->Color }}">{{ $clienteMoroso->NombreEstado }}</span>
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
