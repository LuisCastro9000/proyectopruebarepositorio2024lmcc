  @extends('layouts.app')
  @section('title', 'Reporte Consolidado Movimientos Inventario')
  @section('content')
      <div class="container">
          <div class="row my-4">
              <div class="col d-flex justify-content-sm-between flex-wrap">
                  <h6 class="page-title-heading ">Reporte Consolidado Movimientos Inventario</h6>
                  <a id="btnExcel" class="p-0" target="_blank" href="{{ route('exportarInventario', [$mes, $anio]) }}">
                      <span class="btn bg-excel ripple">
                          <i class="list-icon material-icons fs-20">explicit</i>XCEL
                      </span>
                  </a>
              </div>
          </div>
          {!! Form::open([
              'url' => 'reportes/almacen/consultar-inventario',
              'method' => 'POST',
              'files' => true,
              'id' => 'formularioConsultarInventario',
          ]) !!}
          @method('GET')
          {{ csrf_field() }}
          <div class="row d-flex align-items-end">
              <div class="col-md-5">
                  <div class="form-group">
                      <label>Seleccionar Año</label>
                      <select id="selectAnio" class="form-control" name="anio">
                          @for ($index = now()->format('Y'); $index >= 2018; $index--)
                              <option value="{{ $index }}">{{ $index }}</option>
                          @endfor
                      </select>
                  </div>
              </div>
              <div class="col-md-5">
                  <div class="form-group">
                      <label>Seleccionar Mes</label>
                      <select id="selectMes" class="form-control" name="mes">
                          <option value="1">Enero</option>
                          <option value="2">Febrero</option>
                          <option value="3">Marzo</option>
                          <option value="4">Abril</option>
                          <option value="5">Mayo</option>
                          <option value="6">Junio</option>
                          <option value="7">Julio</option>
                          <option value="8">Agosto</option>
                          <option value="9">Septiembre</option>
                          <option value="10">Octubre</option>
                          <option value="11">Noviembre</option>
                          <option value="12">Diciembre</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-2">
                  <div class="form-group">
                      <x-buttonLoader accion='Buscar'>
                          @slot('textoBoton', 'Buscar')
                          @slot('textoLoader', 'Cargando')
                      </x-buttonLoader>
                  </div>
              </div>
          </div>
          {!! Form::close() !!}

          <section class="jumbotron bg-jumbotron--white">
              <table id="table" class="table table-responsive-sm" style="width:100%">
                  <thead>
                      <tr class="bg-primary">
                          <th scope="col">Descripción</th>
                          <th scope="col">Código (SKU)</th>
                          <th scope="col">Unidad Medida</th>
                          <th scope="col">Marca</th>
                          <th scope="col">Inventario Inicial</th>
                          <th scope="col">Entradas</th>
                          <th scope="col">Salidas</th>
                          <th scope="col">Inventario Final</th>
                      </tr>
                  </thead>
                  <tbody>
                      @foreach ($inventario as $item)
                          <tr>
                              <td>{{ $item->Descripcion }}</td>
                              <td class="ajustar-texto">{{ $item->CodigoBarra }}</td>
                              <td>{{ $item->UnidadMedida }}</td>
                              <td>{{ $item->Marca }}</td>
                              <td>{{ $item->InventarioInicial }}</td>
                              <td>{{ $item->Entradas }}</td>
                              <td>{{ $item->Salidas }}</td>
                              <td>{{ $item->InventarioFinal }}</td>
                          </tr>
                      @endforeach
                  </tbody>
              </table>
          </section>

      </div>
  @stop

  @section('scripts')
      <script>
          $(document).on('click', function(e) {
              if (e.target.matches('.btnLoader') || e.target.matches('.btnLoader *')) {
                  showButtonLoader();
                  $('form').submit();
              }
          });
      </script>

      <script type="text/javascript">
          $(function() {
              const mes = @json($mes);
              const anio = @json($anio);
              $('#selectMes option[value=' + mes + ']').prop('selected', true);
              $('#selectAnio option[value=' + anio + ']').prop('selected', true);

              $(document).ready(function() {
                  $('#table').DataTable({
                      responsive: true,
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
