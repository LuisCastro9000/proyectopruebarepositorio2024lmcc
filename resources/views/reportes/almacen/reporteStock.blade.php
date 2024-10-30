  @extends('layouts.app')
  @section('title', 'Reporte Stock')
  @section('content')
      <style>
          .card-bg-color--celeste {
              background-color: #0095E8;
              color: #ffff;
              font-weight: 800;
              padding: 10px;
          }

          .card_datos {
              font-size: 25px;
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

          <div class="row my-4">
              <div class="col d-flex justify-content-sm-between justify-content-center flex-wrap">
                  <section>
                      <h6 class="page-title-heading ">Detalles de ventas</h6>
                  </section>
                  <section class="d-flex">
                      @if ($band)
                          <a class="p-1" target="_blank"
                              href='{{ url("reportes/almacen/imprimir/{$tipo}/{$alojado}") }}'>
                              <button class="btn btn-block btn-primary ripple">
                                  <i class="list-icon material-icons fs-20">print</i>
                              </button>
                          </a>
                          <a class="p-1" target="_blank"
                              href='{{ url("reportes/almacen/excel-stock/{$tipo}/{$alojado}") }}'>
                              <span class="btn bg-excel ripple">
                                  <i class="list-icon material-icons fs-20">explicit</i>XCEL
                              </span>
                          </a>
                      @endif
                  </section>
              </div>
          </div>

          <div class="widget-bg zi-1" style="background-color: #eff2f7">
              <div class="widget-body py-2">
                  <div class="media d-sm-flex d-block">
                      <div class="media-body">
                          <span id="resultado"></span>
                          <div class="row py-4">
                              <div class="col-12 col-md-4 mb-3 mb-md-0">
                                  <span class="fs-15 text-uppercase mr-2">Almacenes:</span>
                                  <span class="font-weight-bold fs-16">{{ $nombre }}</span>
                                  {!! Form::open(['url' => 'reportes/almacen/stock/', 'method' => 'POST', 'class' => 'form-material']) !!}
                                  {{ csrf_field() }}
                                  <section class="d-flex">
                                      <div class="flex-grow-0 pr-2">
                                          <select class="fs-16 custom-select" name="almacenes">
                                              <option selected value="">Seleccione Almacen</option>
                                              <option class="fs-13" value="*{{ $sucursal->IdSucursal }}">
                                                  {{ $sucursal->Nombre }}
                                              </option>
                                              @foreach ($almacenes as $almacen)
                                                  <option class="fs-13" value="{{ $almacen->IdAlmacen }}">
                                                      {{ $almacen->Nombre }}
                                                  </option>
                                              @endforeach
                                          </select>
                                      </div>
                                      <button class="btn btn-default fs-11" name="submit" type="submit">Mostrar </button>
                                  </section>
                                  {!! Form::close() !!}
                              </div>
                              <div class="col-12 col-sm-6 col-md-4  text-center">
                                  <section class="">
                                      <span class="font-weight-bold fs-16">Cant. Productos Creados</span>
                                  </section>
                                  <section class="font-weight-bold">
                                      <span class="badge badge-success fs-28">{{ $totalArticulos }}</span>
                                  </section>
                              </div>
                              <div class="col-12 col-sm-6 col-md-4 text-center">
                                  <section class="">
                                      <span class="font-weight-bold fs-16">Stock Total de Productos</span>
                                  </section>
                                  <section class="font-weight-bold">
                                      <span class="badge badge-success fs-28">{{ $totalStock }}</span>
                                  </section>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>
              <!-- /.widget-body -->
          </div>
          <!-- /.widget-bg -->

          {{-- Nuevo Codigo Montos --}}
          <div class="row justify-content-md-center my-4">
              <div class="col-12 col-lg-4">
                  <div class="card card-bg-color--celeste text-center">
                      <span class="card_datos">S/. {{ number_format($totalProductosConStockSoles, 2, '.', ',') }}</span>
                      <span class="mb-2">Valorización de Almacén en Soles</span>
                      <span class="badge badge-warning fs-14 m-auto">Precio Venta</span>
                  </div>
              </div>
              <div class="col-12 col-lg-4">
                  <div class="card card-bg-color--celeste text-center">
                      <span class="card_datos">$ {{ number_format($totalProductosConStockDolares, 2, '.', ',') }}</span>
                      <span class="mb-2">Valorización de Almacén en Dólares</span>
                      <span class="badge badge-warning fs-14 m-auto">Precio Venta</span>
                  </div>
              </div>
          </div>
          {{-- Fin --}}


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
                          <div class="widget-body clearfix">
                              <!--<p>Listado de ventas</p>-->
                              <table id="table" class="table table-responsive-sm" style="width:100%">
                                  <thead>
                                      <tr class="bg-primary">
                                          <th scope="col">Descripción</th>
                                          <th scope="col">Marca</th>
                                          <th scope="col">Categoria</th>
                                          <th scope="col">Cód. Barra</th>
                                          <th scope="col">Ubicación</th>
                                          <th scope="col">Stock</th>
                                          <th scope="col">Tipo Moneda</th>
                                          <th scope="col">Costo</th>
                                          <th scope="col">Precio</th>
                                          <th scope="col">Precio x Mayor</th>
                                          <th scope="col">Precio x Tipo</th>
                                          <th scope="col">UM</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($reporteStock as $stock)
                                          <tr>
                                              <td>{{ $stock->Descripcion }}</td>
                                              <td>{{ $stock->NombreMar }}</td>
                                              <td>{{ $stock->NombreCat }}</td>
                                              <td>{{ $stock->Codigo }}</td>
                                              <td>{{ $stock->Ubicacion }}</td>
                                              <td>{{ $stock->Stock }}</td>
                                              <td>{{ $stock->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                              <td>{{ $stock->Costo }}</td>
                                              <td>{{ $stock->Precio }}</td>
                                              @if ($stock->PrecioDescuento1 != null)
                                                  <td>{{ $stock->PrecioDescuento1 }} >= {{ $stock->VentaMayor1 }}</td>
                                              @else
                                                  <td>-</td>
                                              @endif
                                              @if ($stock->PrecioTipo != null)
                                                  <td>{{ $stock->NombreTipo }} x {{ $stock->CantidadTipo }} =
                                                      {{ $stock->PrecioTipo }}</td>
                                              @else
                                                  <td>-</td>
                                              @endif
                                              <td>{{ $stock->Nombre }}</td>
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

          <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog"
              aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
              <div class="modal-dialog modal-sm">
                  {!! Form::open([
                      'url' => "reportes/almacen/enviar-correo/{$tipo}/{$alojado}",
                      'method' => 'POST',
                      'files' => true,
                      'class' => 'form-material',
                  ]) !!}
                  <div class="modal-content">
                      <div class="modal-header text-inverse">
                          <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                          <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                      </div>
                      <div class="modal-body">
                          <div class="container">
                              <label>Ingrese correo:</label>
                              <input id="inpCorreo" class="form-control" name="correo" />
                          </div>
                      </div>
                      <div class="modal-footer">
                          <button type="submit" class="btn btn-primary">Enviar</button>
                          <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                      </div>
                  </div>
                  {!! Form::close() !!}
                  <!-- /.modal-content -->
              </div>
              <!-- /.modal-dialog -->
          </div>
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
