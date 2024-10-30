  @extends('layouts.app')
  @section('title', 'Reportes Créditos Vencidos')
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
              'url' => '/reportes/cobranzas/creditos-vencidos',
              'id' => 'formObtenerDatos',
              'method' => 'POST',
              'files' => true,
          ]) !!}
          {{ csrf_field() }}
          <div class="row ">
              <div class="col-md-10 mt-4">
                  <x-selectorFiltrosFechas metodoObtenerDatos='submit' />
              </div>
              <div class="col-md-2 mt-4 form-group align-self-end">
                  <a target="_blank" href='{{ url("reportes/cobranzas/excel-creditos-Vencidos/$fecha/$ini/$fin") }}'>
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
                      <div class="widget-bg">
                          <!-- /.widget-heading -->
                          <div class="widget-body clearfix">
                              <!--<p>Listado de ventas</p>-->
                              <table id="table" class="table table-responsive-sm" style="width:100%">
                                  <thead>
                                      <tr class="bg-primary">
                                          <th scope="col">Cliente</th>
                                          <th scope="col">Documento</th>
                                          <th scope="col">Fecha Emisión</th>
                                          <th scope="col">Fecha Vencimiento</th>
                                          <th scope="col">Últ. Fecha Pagada</th>
                                          <th scope="col">Tipo Moneda</th>
                                          <th scope="col">Importe</th>
                                          <th scope="col">Deuda</th>
                                          <th scope="col">Días Atrasados</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($creditosVencidos as $creditoVencido)
                                          <tr>
                                              <td>{{ $creditoVencido->Cliente }}</td>
                                              <td>{{ $creditoVencido->Serie . '-' . $creditoVencido->Numero }}
                                                  @if ($creditoVencido->Nota == 1)
                                                      {{ 'N. Credito' }}
                                                  @elseif(!is_null($creditoVencido->MotivoAnulacion))
                                                      {{ 'Doc. Baja' }}
                                                  @else
                                                      {{ '' }}
                                                  @endif
                                              </td>
                                              <td>{{ $creditoVencido->FechaInicio }}</td>
                                              <td>{{ $creditoVencido->FechaUltimo }}</td>
                                              <td>{{ $creditoVencido->FechaPago }}</td>
                                              <td>{{ $creditoVencido->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                              <td>{{ $creditoVencido->Importe }}</td>
                                              <td>{{ $creditoVencido->Deuda }}</td>
                                              <td>{{ $creditoVencido->DiasPasados }}</td>
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
