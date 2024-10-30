  @extends('layouts.app')
  @section('title', 'Listar')
  @section('content')

      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Vehiculos</h6>
              </div>
              <!-- /.page-title-left -->
              <div class="page-title-right">
                  <div class="row mr-b-50 mt-2">
                      <div class="col-12 mr-b-20 d-sm-block d-none">
                          <a href="../administracion/crear"><button class="btn btn-block btn-info ripple"><i
                                      class="list-icon material-icons fs-26">person_add</i> Crear</button></a>
                      </div>
                      <div class="col-12 mr-b-20 d-sm-none d-block">
                          <a href="../administracion/crear"><button class="btn btn-block btn-info ripple"><i
                                      class="list-icon material-icons fs-26">person_add</i></button></a>
                      </div>
                  </div>
              </div>
              <!-- /.page-title-right -->
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
                                      <tr class="bg-info">
                                          <th scope="col">Cliente</th>
                                          @if ($modulosSelect->contains('IdModulo', 5))
                                              <th scope="col">Seguro</th>
                                          @endif
                                          <th scope="col">Placa</th>
                                          <th scope="col">Marca</th>
                                          <th scope="col">Modelo</th>
                                          <th scope="col">Estado</th>
                                          <th scope="col">Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($vehiculos as $vehiculo)
                                          <tr>
                                              <td>{{ $vehiculo->RazonSocial }}</td>
                                              @if ($modulosSelect->contains('IdModulo', 5))
                                                  <td>{{ $vehiculo->Seguro }}</td>
                                              @endif
                                              <td>{{ $vehiculo->PlacaVehiculo }}</td>
                                              <td>{{ $vehiculo->NombreMarca }}</td>
                                              <td>{{ $vehiculo->NombreModelo }}</td>
                                              @if ($vehiculo->Estado == 1)
                                                  <td><span class="badge bg-success color-white fs-12 p-1">HABILITADO</span>
                                                  </td>
                                              @elseif($vehiculo->Estado == 0)
                                                  <td><span
                                                          class="badge bg-danger color-white fs-12 p-1">DESHABILITADO</span>
                                                  </td>
                                              @endif
                                              <td class="text-center">
                                                  <a href="../administracion/{{ $vehiculo->IdVehiculo }}/edit"><button
                                                          class="btn btn-info"><i
                                                              class="list-icon material-icons">edit</i></button></a>
                                                  <a href="javascript:void(0);"><button class="btn btn-info"
                                                          data-toggle="modal" data-target="#exampleModal"
                                                          onclick="modalEliminar({{ $vehiculo->IdVehiculo }})"><i
                                                              class="list-icon material-icons">clear</i></button></a>
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

      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-body">
                      <h6 class="modal-title">Desea Eliminar vehículo?</h6>
                      <input id="idUsuario" hidden />
                  </div>
                  <div class="modal-footer">
                      <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
          </div>
      </div>
      <!-- /.container -->

      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="text-success">Listado de Vehículos</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <p class="fs-15negrita">Se mostraran solo los vehículos registrados de este mes... Si desea ver
                              vehículos registrados angituos, utilize los filtros</p>
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
  @stop

  @section('scripts')
      <script type="text/javascript">
          $(function() {
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
      <script>
          function modalEliminar(id) {
              $("#idUsuario").val(id);
          }
          $(function() {
              $("#exampleModal button.btnEliminar").on("click", function(e) {
                  var id = $("#idUsuario").val();
                  window.location = +id + '/delete';
              });
          });
      </script>
  @stop
