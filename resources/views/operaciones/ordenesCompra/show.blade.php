  @extends('layouts.app')
  @section('title', 'Consulta Orden Compra')
  @section('content')
      <div class="container">
          <section
              class="d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-sm-row my-4">
              <article>
                  <h6>Detalle Orden de Compra</h6>
              </article>
              <article>
                  @if ($mostrarBotonConvertirOrden == 'activo')
                      <a href="{{ route('verVistaConvertirOrden', $ordenCompraSelect->IdOrdenCompra) }}"><button
                              class="btn btn--naranja ripple"><i class="list-icon material-icons fs-20">add_to_photos</i>
                              Convertir a Compra</button></a>
                  @endif
                  <a href="#" data-toggle="modal" data-target=".bs-modal-sm-primary"><button
                          class="btn  btn-primary ripple"><i class="list-icon material-icons fs-20">mail</i></button></a>
                  <a href="{{ route('obtenerDocumentoPdf', [$ordenCompraSelect->IdOrdenCompra, 'imprimir']) }}"
                      target="_blank"><button class="btn btn-primary ripple"><i
                              class="list-icon material-icons fs-20">print</i></button></a>
                  <a target="_blank"
                      href="{{ route('obtenerDocumentoPdf', [$ordenCompraSelect->IdOrdenCompra, 'descargar']) }}"><button
                          class="btn  btn-primary ripple"><i
                              class="list-icon material-icons fs-20">picture_as_pdf</i></button></a>
              </article>
          </section>
          <div class="container">
              @if (session('error'))
                  <div class="alert alert-danger">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      {{ session('error') }}
                  </div>
              @endif
              @if (session('status'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      {{ session('status') }}
                  </div>
              @endif
              <div class="widget-list">
                  <div class="row">
                      <div class="col-md-12 widget-holder">
                          <div class="widget-bg">
                              <div class="widget-body clearfix">
                                  <div class="ecommerce-invoice">
                                      <div class="d-sm-flex align-items-center">
                                          <div class="col-md-6">
                                              <h5>Orden de Compra:
                                                  {{ $ordenCompraSelect->Serie }}-{{ $ordenCompraSelect->Numero }}</h5>
                                          </div>
                                          <div class="col-md-6 text-right d-none d-sm-block"><strong>PROVEEDOR:</strong>
                                              {{ $ordenCompraSelect->Nombres }}
                                              <br><strong>RAZ. SOCIAL:</strong> {{ $ordenCompraSelect->RazonSocial }}
                                              <br><strong>{{ $ordenCompraSelect->TipoDocumento }}:</strong>
                                              {{ $ordenCompraSelect->NumeroDocumento }}
                                              <br><strong>DIRECCIÓN:</strong> {{ $ordenCompraSelect->DireccionProveedor }}
                                          </div>
                                          <div class="col-md-6 d-block d-sm-none"><strong>PROVEEDOR:</strong>
                                              {{ $ordenCompraSelect->Nombres }}
                                              <br><strong>RAZ. SOCIAL:</strong> {{ $ordenCompraSelect->RazonSocial }}
                                              <br><strong>{{ $ordenCompraSelect->TipoDocumento }}:</strong>
                                              {{ $ordenCompraSelect->NumeroDocumento }}
                                              <br><strong>DIRECCIÓN:</strong> {{ $ordenCompraSelect->DireccionProveedor }}
                                          </div>
                                      </div>
                                      <hr>
                                      <div class="d-sm-flex align-items-center">
                                          <div class="col-md-6">
                                              <h6 class="mr-t-0">Usuario</h6>
                                              <strong>NOMBRES:</strong>
                                              {{ $ordenCompraSelect->Usuario }}
                                              <br><strong>DNI:</strong> {{ $ordenCompraSelect->DNI }}
                                              <br><strong>SUCURSAL:</strong> {{ $ordenCompraSelect->Sucursal }}
                                              <br><strong>DIRECCIÓN:</strong> {{ $ordenCompraSelect->Local }}
                                          </div>
                                          <hr class="d-block d-md-none">
                                          <div class="col-md-6 text-right d-none d-sm-block">
                                              <h6 class="mr-t-0">Detalle de la Orden:</h6>
                                              <strong>FECHA EMISIÓN:</strong> <span
                                                  class="text-muted">{{ $formatoFecha }}</span>
                                              <br><strong>HORA EMISIÓN:</strong> <span
                                                  class="text-muted">{{ $formatoHora }}</span><br>
                                              <strong>FECHA RECEPCIÓN:</strong> <span
                                                  class="text-muted">{{ $fechaRecepcion }}</span>
                                              <br><strong>MONTO A PAGAR:</strong> <span
                                                  class="text-muted">{{ $ordenCompraSelect->Total }}</span>
                                              <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                  @if ($ordenCompraSelect->IdTipoMoneda == 1)
                                                      Soles
                                                  @else
                                                      Dólares
                                                  @endif
                                              </span>
                                              <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                                  @if ($ordenCompraSelect->TipoCompra == 1)
                                                      Gravada
                                                  @else
                                                      Exonerada
                                                  @endif
                                              </span>
                                              <br><strong>TIPO DE COMPRA:</strong> <span
                                                  class="text-muted">{{ $ordenCompraSelect->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</span>
                                              <br>
                                              @if ($ordenCompraSelect->IdTipoPago == 2)
                                                  <strong class="negrita">Condición de Pago:</strong> <span
                                                      class="text-muted">{{ $ordenCompraSelect->DiasPlazoCredito }} Días -
                                                      (Después de la Fecha de
                                                      Recepción)</span>
                                              @endif
                                          </div>
                                          <div class="col-md-6 d-block d-sm-none">
                                              <h6 class="mr-t-0">Detalles de la Orden:</h6>
                                              <strong>FECHA:</strong> <span class="text-muted">{{ $formatoFecha }}</span>
                                              <br><strong>HORA:</strong> <span
                                                  class="text-muted">{{ $formatoHora }}</span>
                                              <br><strong>MONTO A PAGAR:</strong> <span
                                                  class="text-muted">{{ $ordenCompraSelect->Total }}</span>
                                              <br><strong>TIPO MONEDA:</strong> <span class="text-muted">
                                                  @if ($ordenCompraSelect->IdTipoMoneda == 1)
                                                      Soles
                                                  @else
                                                      Dólares
                                                  @endif
                                              </span>
                                              <br><strong>TIPO OPERACIÓN:</strong> <span class="text-muted">
                                                  @if ($ordenCompraSelect->TipoCompra == 1)
                                                      Gravada
                                                  @else
                                                      Exonerada
                                                  @endif
                                              </span>
                                              <br><strong>TIPO DE COMPRA:</strong> <span
                                                  class="text-muted">{{ $ordenCompraSelect->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</span>
                                              <br>
                                              @if ($ordenCompraSelect->IdTipoPago == 2)
                                                  <strong class="negrita">Condición de Pago:</strong> <span
                                                      class="text-muted">{{ $ordenCompraSelect->DiasPlazoCredito }} Días -
                                                      (Después de la Fecha de
                                                      Recepción)</span>
                                              @endif
                                          </div>
                                      </div>
                                      <!-- /.row -->
                                      <hr class="border-0">
                                      <table id="table" class="table table-bordered table-responsive-sm"
                                          style="width: 100%">
                                          <thead>
                                              <tr class="bg-primary-dark text-white">
                                                  <th class="text-center">Código</th>
                                                  <th>Descripción</th>
                                                  <th class="text-center">Uni/Medida</th>
                                                  <th class="text-center">Precio Costo</th>
                                                  <th class="text-center">Cantidad</th>
                                                  <th class="text-center">Importe</th>
                                              </tr>
                                          </thead>
                                          <tbody>
                                              @foreach ($itemsOrdenCompra as $item)
                                                  <tr class="text-muted">
                                                      <td scope="row">{{ $item->CodigoArticulo }}</td>
                                                      <td>{{ $item->Descripcion }}</td>
                                                      <td>{{ $item->UniMedida }}</td>
                                                      @if ($ordenCompraSelect->TipoCompra == 1)
                                                          <td>{{ $item->PrecioCosto }}</td>
                                                      @else
                                                          <td>{{ round($item->PrecioCosto / 1.18, 2) }}</td>
                                                      @endif
                                                      <td>{{ $item->Cantidad }}</td>
                                                      <td>{{ $item->Importe }}</td>
                                                  </tr>
                                              @endforeach
                                          </tbody>
                                      </table>
                                      <div class="row mt-4">
                                          <div class="col-12">
                                              <div class="form-group">
                                                  <label>Observación</label>
                                                  <textarea id="observacion" class="form-control" rows="4">{{ $ordenCompraSelect->Observacion }}</textarea>
                                              </div>
                                          </div>
                                          <div class="col-md-8">
                                          </div>
                                          <div class="col-md-4 invoice-sum">
                                              <ul class="list-unstyled">
                                                  <li>
                                                      @if ($ordenCompraSelect->TipoCompra == 1)
                                                          OP. GRAVADAS:
                                                      @else
                                                          OP. EXONERADAS:
                                                      @endif
                                                      {{ $ordenCompraSelect->SubTotal }}
                                                  </li>
                                                  <li>
                                                      @if ($ordenCompraSelect->TipoCompra == 1)
                                                          IGV(18%):
                                                      @else
                                                          IGV(0%):
                                                      @endif
                                                      {{ $ordenCompraSelect->Igv }}
                                                  </li>
                                                  <li><strong>TOTAL : {{ $ordenCompraSelect->Total }}</strong>
                                                  </li>
                                              </ul>
                                          </div>
                                      </div>
                                      <div class="mt-3 text-right">
                                          <a href="{{ route('ordenDeCompra.index') }}"><button class="btn btn-primary"
                                                  type="button">Volver</button></a>
                                      </div>
                                  </div>
                              </div>
                          </div>
                      </div>
                  </div>
              </div>

              {{-- Modal enviar correo --}}
              <div class="row">
                  <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog"
                      aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                      <div class="modal-dialog modal-sm">

                          {!! Form::open([
                              // 'url' => '/operaciones/compras/enviar-correo/' . $ordenCompraSelect->IdOrdenCompra,
                              'url' => 'operaciones/ordenes-compra/documento/' . $ordenCompraSelect->IdOrdenCompra . '/enviarCorreo',
                              'method' => 'GET',
                              'files' => true,
                              'class' => 'form-material',
                          ]) !!}
                          <div class="modal-content">
                              <div class="modal-header text-inverse">
                                  <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                              </div>
                              <div class="modal-body">
                                  <div class="container">
                                      <label>Correo cliente:</label>
                                      <input id="inpCorreo" class="form-control" name="correo" />
                                  </div>
                              </div>
                              <div class="modal-footer">
                                  <button type="submit" class="btn btn-primary">Enviar</button>
                                  <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                              </div>
                          </div>
                          {!! Form::close() !!}
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
                          "paging": false,
                          "ordering": false,
                          "info": false,
                          "searching": false
                      });
                  });

              });
          </script>
      @stop
