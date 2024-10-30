  @extends('layouts.app')
  @section('title', 'Consulta Clientes')
  @section('content')
      <div class="container">
          <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Clientes</h6>
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
                                          <th scope="col">Cliente</th>
                                          <th scope="col">R. Social</th>
                                          <th scope="col">Documento</th>
                                          <th scope="col">Dirección</th>
                                          <th scope="col">Linea Credito</th>
                                          <th scope="col">Teléfono</th>
                                          <th scope="col">Email</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($clientes as $cliente)
                                          <tr>
                                              <td scope="row">{{ $cliente->Nombre }}</td>
                                              <td>{{ $cliente->RazonSocial }}</td>
                                              <td>{{ $cliente->Descripcion }}: {{ $cliente->NumeroDocumento }}</td>
                                              <td>{{ $cliente->Direccion }}</td>
                                              <td>{{ $cliente->SaldoCredito }}</td>
                                              <td>{{ $cliente->Telefono }}</td>
                                              <td>{{ $cliente->Email }}</td>
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
  @stop
