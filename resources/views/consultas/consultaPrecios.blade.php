  @extends('layouts.app')
  @section('title', 'Consulta Precios')
  @section('content')
      <div class="container">
          <div class="widget-list">
              <div class="row">
                  <div class="col-md-12 widget-holder">
                      <div class="widget-bg zi-1 mt-3 mb-4" style="background-color: #eff2f7">
                          <div class="widget-body py-2">
                              <div class="pos-0 zi-n-1 d-none d-lg-block"
                                  style="background-repeat: no-repeat;                                                                                                           background-size: auto 100%;                                                                                                         
                                      background-position: 90% center;                                                                                                            background-image: url('./assets/img/MI FACTURITA.png')">
                              </div>
                              <div class="media d-sm-flex d-block">
                                  <div class="media-body">
                                      <span id="resultado"></span>
                                      <h6 class="fw-300 text-body">Almacenes:</h6>
                                      {!! Form::open(['url' => 'consultas/precios', 'method' => 'POST']) !!}
                                      {{ csrf_field() }}
                                      <div class="row">
                                          <div class="col-md-3 form-group">
                                              <select id="almacenes" class="form-control" name="almacenes">
                                                  <option class="fs-13" value="*{{ $sucursal->IdSucursal }}"
                                                      {{ $sucursal->IdSucursal == $idAlmacen ? 'selected' : '' }}>
                                                      {{ $sucursal->Nombre }}
                                                  </option>
                                                  @foreach ($almacenes as $almacen)
                                                      <option class="fs-13" value="{{ $almacen->IdAlmacen }}"
                                                          {{ $almacen->IdAlmacen == $idAlmacen ? 'selected' : '' }}>
                                                          {{ $almacen->Nombre }}</option>
                                                  @endforeach
                                              </select>
                                          </div>
                                          <div class="col-md-3 form-group">
                                              <select class="form-control" name="radioOption" id="tipoArticulo">
                                                  <option value="1" {{ $tipo == '1' ? 'selected' : '' }}>Productos
                                                  </option>
                                                  <option value="2" {{ $tipo == '2' ? 'selected' : '' }}>Servicios
                                                  </option>
                                              </select>
                                          </div>
                                          @if ($exonerado == 1 && $sucExonerado == 1)
                                              <div class="col-md-3 form-group">
                                                  <select class="form-control" name="radioOption2">
                                                      <option value="1" {{ $igv == '1' ? 'selected' : '' }}>Con IGV
                                                      </option>
                                                      <option value="2" {{ $igv == '2' ? 'selected' : '' }}>Sin IGV
                                                      </option>
                                                  </select>
                                              </div>
                                          @else
                                              <input hidden type="radio" name="radioOption2" value="1"
                                                  checked="checked">
                                          @endif
                                          <div class="col-md-2 form-group columna-md" style="width: 49px">
                                              <button id="btnMostrar" type="submit"
                                                  class="btn btn-primary">Consultar</button>
                                          </div>
                                      </div>
                                      {{-- AutoControlDesarrollo --}}
                                      {{-- <select id="almacenes" class="fs-16" name="almacenes">
                                          <option value="0">Seleccione Almacen</option>
                                          <option class="fs-13" value="*{{ $sucursal->IdSucursal }}">
                                              {{ $sucursal->Nombre }}</option>
                                          @foreach ($almacenes as $almacen)
                                              <option class="fs-13" value="{{ $almacen->IdAlmacen }}">
                                                  {{ $almacen->Nombre }}</option>
                                          @endforeach
                                      </select>
                                      <div class="form-group mt-3 mb-3">
                                          <div class="radiobox">
                                              <label>
                                                  @if ($tipo == 1)
                                                      <input id="radio1" type="radio" name="radioOption" value="1"
                                                          checked="checked"> <span class="label-text p-4">Productos</span>
                                                  @else
                                                      <input id="radio1" type="radio" name="radioOption"
                                                          value="1">
                                                      <span class="label-text p-4">Productos</span>
                                                  @endif
                                              </label>
                                              <label>
                                                  @if ($tipo == 2)
                                                      <input id="radio2" type="radio" name="radioOption" value="2"
                                                          checked="checked"> <span class="label-text p-4">Servicios</span>
                                                  @else
                                                      <input id="radio2" type="radio" name="radioOption"
                                                          value="2">
                                                      <span class="label-text p-4 btn-servicios">Servicios</span>
                                                  @endif
                                              </label>
                                          </div>
                                      </div>
                                      @if ($exonerado == 1 && $sucExonerado == 1)
                                          <div class="form-group mt-3 mb-3">
                                              <div class="radiobox">
                                                  <label>
                                                      @if ($igv == 1)
                                                          <input id="radio3" type="radio" name="radioOption2"
                                                              value="1" checked="checked"> <span
                                                              class="label-text p-4">Con
                                                              IGV</span>
                                                      @else
                                                          <input id="radio3" type="radio" name="radioOption2"
                                                              value="1">
                                                          <span class="label-text p-4">Con IGV</span>
                                                      @endif
                                                  </label>
                                                  <label>
                                                      @if ($igv == 2)
                                                          <input id="radio4" type="radio" name="radioOption2"
                                                              value="2" checked="checked"> <span
                                                              class="label-text p-4">Sin
                                                              IGV</span>
                                                      @else
                                                          <input id="radio4" type="radio" name="radioOption2"
                                                              value="2">
                                                          <span class="label-text p-4">Sin IGV</span>
                                                      @endif
                                                  </label>
                                              </div>
                                          </div>
                                      @else
                                          <input hidden type="radio" name="radioOption2" value="1" checked="checked">
                                      @endif
                                      <button id="btnMostrar" class="btn btn-default fs-11" name="submit"
                                          type="submit" name="boton" value="Enviar">Mostrar
                                      </button>
                                      @if ($idSucursal === $sucursal->IdSucursal)
                                          <a id="btnExcel" class="float-right " target="_blank"
                                              href='{{ url("consultas/precios/exportar-Excel/$tipo/$idAlmacen") }}'>
                                              <span class="btn bg-excel ripple">
                                                  <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                              </span>
                                          </a>
                                      @endif --}}
                                      {!! Form::close() !!}
                                  </div>
                              </div>
                          </div>
                          <!-- /.widget-body -->
                      </div>
                      <!-- /.widget-bg -->
                      <div class="row">
                          <div id="mensaje"></div>
                      </div>
                      <!-- /.widget-heading -->
                      <div class="widget-bg widget-body clearfix">
                          <!--<p>Listado de ventas</p>-->
                          <table id="table" class="table table-responsive-sm" style="width:100%">
                              @if (count($precios) >= 1)
                                  <div class="row">
                                      <div class="col-12 col-lg-6">
                                          <x-fieldset fieldsetClass="fieldset--bordeNaranja">
                                              <label>Buscar por descripción,
                                                  marca,codigo,ubicacion,moneda y precio</label>
                                              <div class="form-group">
                                                  <i
                                                      class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                  <input autocomplete="nope" type="search" id="inputBuscar" name="texto"
                                                      class="form-control  heading-font-family fs-16 pr-5 pl-3">
                                              </div>
                                          </x-fieldset>
                                      </div>
                                      <div class="col-12 col-lg-6">
                                          <x-fieldset fieldsetClass="fieldset--bordeNaranja">
                                              <label>Buscar solo por
                                                  detalle <br>
                                              </label>
                                              <div class="form-group">
                                                  <i
                                                      class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                  <input autocomplete="nope" type="search" id="inputBuscarPorDetalle"
                                                      name="texto"
                                                      class="form-control  heading-font-family fs-16 pr-5 pl-3">
                                              </div>
                                          </x-fieldset>
                                      </div>
                                  </div>
                              @endif
                              <thead>
                                  <tr class="bg-primary">
                                      <th scope="col">Descripción</th>
                                      @if ($tipo == 1)
                                          <th scope="col">Marca</th>
                                          <th scope="col">Código de Barra</th>
                                          <th scope="col">Ubicación</th>
                                      @endif
                                      <th scope="col">Tipo Moneda</th>
                                      <th scope="col">Precio</th>
                                      @if ($tipo == 1)
                                          <th scope="col">Precio x Mayor</th>
                                          <th scope="col">Stock</th>
                                          @if ($btn_ojo)
                                              <th scope="col">Almacenes</th>
                                          @endif
                                      @endif
                                      <th scope="col">Unidad Medida</th>
                                      <th scope="col">Detalle</th>
                                  </tr>
                              </thead>
                              <tbody>
                                  @foreach ($precios as $precio)
                                      <tr>
                                          <td scope="row">{{ $precio->Descripcion }}</td>
                                          @if ($tipo == 1)
                                              <td>{{ $precio->Marca }}</td>
                                              <td>{{ $precio->Codigo }}</td>
                                              <td>{{ $precio->Ubicacion }}</td>
                                          @endif
                                          @if ($precio->IdTipoMoneda == 1)
                                              <td>Soles</td>
                                          @else
                                              <td>Dólares</td>
                                          @endif
                                          @if ($igv == 1)
                                              <td>{{ $precio->Precio }}</td>
                                          @else
                                              <td>{{ round($precio->Precio / 1.18, 2) }}</td>
                                          @endif
                                          @if ($tipo == 1)
                                              @if ($precio->PrecioDescuento1 != null)
                                                  <td>{{ $precio->PrecioDescuento1 }} >=
                                                      {{ $precio->VentaMayor1 }}
                                                  </td>
                                              @else
                                                  <td>-</td>
                                              @endif
                                              <td>{{ $precio->Stock }}</td>
                                              @if ($btn_ojo)
                                                  <td class="text-center">
                                                      <a href="#" data-toggle="modal" class="mostrarAlmacenes"
                                                          onclick="mostrarAlmacen({{ $precio->CodigoInterno }});"
                                                          data-target=".bs-modal-lg-almacen"><i
                                                              class="list-icon material-icons">visibility</i></a>
                                                  </td>
                                              @endif
                                          @endif
                                          <td>{{ $precio->NombreUnidadMedida }}</td>
                                          <td>{{ $precio->Detalle }}</td>
                                      </tr>
                                  @endforeach
                              </tbody>
                          </table>
                      </div>
                      <!-- /.widget-body -->

                      <div class="modal fade bs-modal-lg-almacen" tabindex="-1" role="dialog"
                          aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                          <div class="modal-dialog modal-lg">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <h6>Almacenes</h6>
                                  </div>
                                  <div class="modal-body form-material" id="tablaAgregado">

                                      <table id="tablaAgregado" class="table table-responsive-lg" style="width:100%">
                                          <thead>
                                              <tr class="bg-primary-contrast">
                                                  <th scope="col" data-tablesaw-priority="persist">Código</th>
                                                  <th scope="col">Nombre</th>
                                                  <th scope="col">Precio</th>
                                                  <th scope="col">Stock</th>

                                              </tr>
                                          </thead>
                                          <tbody id="body">
                                              <tr>
                                                  <td>2</td>
                                                  <td>Apurimac</td>
                                                  <td>12.00</td>
                                                  <td>8</td>
                                              </tr>
                                              <tr>
                                                  <td>3</td>
                                                  <td>Don Pepito lima</td>
                                                  <td>11.00</td>
                                                  <td>4</td>
                                              </tr>
                                              <tr>
                                                  <td>7</td>
                                                  <td>Don Pepito chiclayo</td>
                                                  <td>12.00</td>
                                                  <td>0</td>
                                              </tr>
                                          </tbody>
                                      </table>
                                  </div>
                                  <div class="modal-footer">
                                      <div class="form-actions btn-list mt-3">
                                          <button class="btn btn-info" type="button"
                                              data-dismiss="modal">Aceptar</button>
                                      </div>
                                  </div>
                              </div>
                          </div>
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
              $('#table_filter').addClass('d-none');
              $(document).ready(function() {
                  let tabla = $('#table').DataTable({
                      responsive: true,
                      sDom: 'lrtip',
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

                  let nombresColumnas = tabla.columns().header().toArray().map(function(header) {
                      return $(header).text();
                  });

                  // Obtener el índice de la columna "detalle"
                  let indiceDetalle = nombresColumnas.indexOf('Detalle');

                  $('#inputBuscarPorDetalle').on('keyup', function() {
                      tabla.columns([indiceDetalle]).search(this.value).draw();
                  });
                  $('#inputBuscar').on('keyup', function() {
                      tabla.column([0, 1, 2, 3, 4, 5]).search(this.value).draw();
                  });
              });
          });
      </script>
      <script>
          function mostrarAlmacen(idPro) {
              //idPro=$(this).attr("id");
              $.ajax({
                  type: 'get',
                  url: 'porcentaje-descuento',
                  data: {
                      'idProducto': idPro
                  },
                  beforeSend: function() {
                      $("#tablaAgregado").html("Procesando, espere por favor...");
                  },
                  success: function(data) {

                      var cadena = '';
                      var inicio = `<table class="table table-responsive-lg" style="width:100%">
                                    <thead>
                                        <tr class="bg-primary-contrast">
                                            <th scope="col" data-tablesaw-priority="persist">Sucursal</th>
                                            <th scope="col">Nombre</th>
                                            <th scope="col">Precio</th>
                                            <th scope="col">Stock</th>
                                        </tr>
                                    </thead>
                                    <tbody id="body">`;
                      var body = '';
                      $.each(data.sucursal, function(i, object) {
                          body = '<tr><td>' + object.nombre + '</td><td>' + object.descripcion +
                              '</td><td>' + object.precio + '</td><td>' + object.stock + '</td></tr>';
                          $.each(data.alm_prod, function(property, value) {
                              if (value.IdSucursal === object.id_sucursal) {
                                  body += '<tr><td>' + value.Nombre + '</td><td>' + value
                                      .Descripcion + '</td><td> - </td><td>' + value.Stock +
                                      '</td></tr>';
                              }
                          });

                          cadena += inicio + '' + body + '</tbody></table>';
                          body = '';
                      });

                      $("#tablaAgregado").html(cadena + '</tbody></table>');

                  }
              });
          }

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


      <script>
          $("input[name=customRadio]").change(function() {

              if ($(this).is(':checked')) {
                  $('#producto').prop('disabled', true);
              } else {
                  $('#verCodigo').hide();
                  $('#producto').prop('disabled', false);
              }
          });
      </script>
  @stop
