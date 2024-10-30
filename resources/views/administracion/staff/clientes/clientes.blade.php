  @extends('layouts.app')
  @section('title', 'Clientes')
  @section('content')
      <style>
          /* Safari Chrome */
          progress::-webkit-progress-bar {
              background-color: #0D6EFD
          }

          progress::-webkit-progress-value {
              background: #0D6EFD
          }

          /* firefox */
          progress::-moz-progress-bar {
              background: #0D6EFD
          }

          .progress {
              height: 20px;
              border-radius: 10px;
          }

          .progress-bar {
              font-size: 13px;
          }
      </style>
      <div class="container">
          <div class="row mt-3">
              <div class="col">
                  <div
                      class="d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-sm-row">
                      <section>
                          <div>
                              <h6 class="page-title-heading mr-0 mr-r-5">Listado de Clientes</h6>
                          </div>
                      </section>
                      <section class="d-flex align-items-center flex-wrap justify-content-center">
                          @if ($usuarioSelect->OpcionImportarExcel == 1)
                              <div class="d-md-block d-none  mr-2">
                                  <a href="#" data-toggle="modal" data-target="#modalImportarExcelClientes"><button
                                          class="btn btn-block btn-primary ripple"><i
                                              class="list-icon material-icons fs-20">vertical_align_top</i>
                                          Importar</button></a>
                              </div>
                              <div class=" d-md-none d-block  mr-2">
                                  <a href="#" data-toggle="modal" data-target=".bs-modal-sm-importar"><button
                                          class="btn btn-block btn-primary ripple"><i
                                              class="list-icon material-icons fs-20">vertical_align_top</i></button></a>
                              </div>
                          @endif
                          <div class="d-md-block d-none mr-2">
                              <a href="../staff/clientes/create"><button class="btn btn-block btn-primary ripple"><i
                                          class="list-icon material-icons fs-20">person_add</i> Crear</button></a>
                          </div>
                          <div class="d-md-none d-block mr-2">
                              <a href="../staff/clientes/create"><button class="btn btn-block btn-primary ripple"><i
                                          class="list-icon material-icons fs-20">person_add</i></button></a>
                          </div>
                          <div class="d-md-block d-none">
                              <a target="_blank" href="excel-clientes">
                                  <span class="btn bg-excel ripple">
                                      <i class="list-icon material-icons fs-20">explicit</i>XCEL
                                  </span>
                              </a>
                          </div>
                          <div class="d-md-none d-block">
                              <a target="_blank" href="excel-clientes">
                                  <span class="btn bg-excel ripple">
                                      <i class="list-icon material-icons fs-20">explicit</i>
                                  </span>
                              </a>
                          </div>
                      </section>
                  </div>
              </div>

          </div>

          {{-- <div class="row page-title clearfix">
              <div class="page-title-left">
                  <h6 class="page-title-heading mr-0 mr-r-5">Listado de Clientes</h6>
              </div>
              <div class="page-title-right">
                  <div class="row">
                      <div class="col-6 mr-b-20 d-sm-block d-none pt-2">
                          <a href="../staff/clientes/create"><button class="btn btn-block btn-primary ripple"><i
                                      class="list-icon material-icons fs-26">person_add</i> Crear</button></a>
                      </div>
                      <div class="col-6 mr-b-20 d-sm-block d-none">
                          <a target="_blank" href="excel-clientes">
                              <span class="btn bg-excel ripple">
                                  <i class="list-icon material-icons fs-20">explicit</i>XCEL
                              </span>
                          </a>
                      </div>
                      <div class="col-6 mr-b-20 d-sm-none d-block pt-2">
                          <a href="../staff/clientes/create"><button class="btn btn-block btn-primary ripple"><i
                                      class="list-icon material-icons fs-26">person_add</i></button></a>
                      </div>
                      <div class="col-6 mr-b-20 d-sm-none d-block">
                          <a target="_blank" href="excel-clientes">
                              <span class="btn bg-excel ripple">
                                  <i class="list-icon material-icons fs-20">explicit</i>XCEL
                              </span>
                          </a>
                      </div>
                  </div>
                  <div class="row mr-b-50 mt-2">
                      <div class="col-6 mr-b-20 d-sm-block d-none">

                      </div>
                  </div>
              </div>
          </div> --}}
          <section class="mt-3">
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

              @if (session('arrayClientesNoGuardados'))
                  @php $array = Session::get('arrayClientesNoGuardados') @endphp
                  @if (count($array) >= 1)
                      <div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          @foreach ($array as $datos)
                              <span class="text-danger">- {{ $datos }}</span><br>
                          @endforeach
                      </div>
                  @endif
              @endif

              @if (session('errorDatosIncompletos'))
                  <div class="alert alert-success">
                      <button type="button" class="close" data-dismiss="alert">&times;</button>
                      {{ session('errorDatosIncompletos') }}
                  </div>
              @endif
              @if (session('arrayDatosIncompletos'))
                  @php $array = Session::get('arrayDatosIncompletos') @endphp
                  @if (count($array) >= 1)
                      <div class="alert alert-success">
                          <button type="button" class="close" data-dismiss="alert">&times;</button>
                          @foreach ($array as $item)
                              <span class="text-danger">- {{ $item }}</span><br>
                          @endforeach
                      </div>
                  @endif
              @endif
          </section>

          {!! Form::open([
              'url' => 'administracion/staff/clientes',
              'method' => 'POST',
              'files' => true,
              'id' => 'form',
          ]) !!}
          {{ csrf_field() }}
          <div class="row clearfix">
              <div class="col-md-6 mt-4 order-md-2">
                  <div class="form-group form-material">
                      <label>Fecha</label>
                      <select id="idFecha" class="form-control" name="fecha">
                          <option value="0">Todo</option>
                          <option value="1">Hoy</option>
                          <option value="2">Ayer</option>
                          <option value="3">Esta semana</option>
                          <option value="4">Última semana</option>
                          <option value="5">Este mes</option>
                          <option value="6">Último mes</option>
                          <option value="7">Este año</option>
                          <option value="8">Último año</option>
                          <option value="9">Personalizar</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-4 mt-4 col-6 order-md-2">
                  <div class="form-group container ">
                      <br>
                      <button id="boton" type="submit" class="btn btn-primary">Buscar</button>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-3 order-last">
                  <div id="Inicio" class="form-group">
                      <label class="form-control-label">Desde</label>
                      <div class="input-group">
                          <input id="datepickerIni" type="text" class="form-control datepicker" name="fechaIni"
                              data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                      </div>
                  </div>
              </div>
              <div class="col-md-3 mt-4 order-md-4">
                  <div id="Final" class="form-group">
                      <label class="form-control-label">Hasta</label>
                      <div class="input-group">
                          <input id="datepickerFin" type="text" class="form-control datepicker" name="fechaFin"
                              data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'>
                      </div>
                  </div>
              </div>
          </div>
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
                                          <th scope="col">Nombre</th>
                                          <th scope="col">R. Social</th>
                                          <th scope="col">Dirección</th>
                                          <th scope="col">Documento</th>
                                          <th scope="col">Teléfono</th>
                                          <th scope="col">Email</th>
                                          <th scope="col">Contacto</th>
                                          <th scope="col">Opciones</th>
                                      </tr>
                                  </thead>
                                  <tbody>
                                      @foreach ($clientes as $cliente)
                                          <tr>
                                              <td>{{ $cliente->Nombre }}</td>
                                              <td>{{ $cliente->RazonSocial }}</td>
                                              <td>{{ $cliente->Direccion }}</td>
                                              <td>{{ $cliente->Descripcion }}: {{ $cliente->NumeroDocumento }}</td>
                                              <td>{{ $cliente->Telefono }}</td>
                                              <td>{{ $cliente->Email }}</td>
                                              <td>{{ $cliente->PersonaContacto }}</td>
                                              <td class="text-center">
                                                  <a href="clientes/{{ $cliente->IdCliente }}/edit"><button
                                                          class="btn btn-primary"><i
                                                              class="list-icon material-icons">edit</i></button></a>
                                                  <a href="javascript:void(0);"><button class="btn btn-primary"
                                                          data-toggle="modal" data-target="#exampleModal"
                                                          onclick="modalEliminar({{ $cliente->IdCliente }})"><i
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

      {{-- Nuevo Modal Importar excel --}}
      <div class="modal" id="modalImportarExcelClientes" tabindex="-1" role="dialog"
          aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
          <div class="modal-dialog modal-dialog-centered" role="document">
              <div class="modal-content">
                  <div class="modal-body">
                      <section class="text-center">
                          <label class="fs-18">Importar excel Clientes</label>
                          <hr>
                      </section>
                      <section class="mt-2 mb-4">
                          <button type="button" class="btn btn-success d-block w-100"
                              onclick="descargarFormatoExcel();">Descargar Formato Excel</button>
                      </section>
                      {!! Form::open([
                          'url' => 'administracion/staff/importar-excel-clientes',
                          'method' => 'POST',
                          'files' => true,
                      ]) !!}
                      <section class="formImport" id="formImport">
                          <span><i class='bx bxs-cloud-upload fs-60 color-celeste'></i></span>
                          <span id="subTitleFile" class="text-center">Click Aquí <br> Seleccionar Archivo</span>
                          <input type="file" class="input-file" name="datosExcelClientes" id="input-file"
                              accept=".xlsx, .xls" hidden>
                          <span id="nameFile"></span>
                      </section>
                      <section class="text-center mt-4">
                          <b>TAMAÑO DE ARCHIVO : 150 KB (1500 CLIENTES APROX.)</b>
                      </section>
                      <section class="progress d-none" id="progress">
                          <div class="progress-bar " role="progressbar" aria-valuenow="0" aria-valuemin="0"
                              aria-valuemax="100" style="width: 0%">
                              <span id="porcentajeBarra"></span>
                          </div>
                      </section>
                      <div class="modal-footer mt-4">
                          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                          <button type="submit" class="btn btn-primary" id="btnImportar">Importar</button>
                      </div>
                      {!! Form::close() !!}
                  </div>
              </div>
          </div>
      </div>
      {{-- Fin --}}

      <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
          aria-hidden="true">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <!--<div class="modal-header">
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    </div>-->
                  <div class="modal-body">
                      <h6 class="modal-title">Desea Eliminar Cliente?</h6>
                      <input id="idCliente" hidden />
                  </div>
                  <div class="modal-footer">
                      <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                      <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                  </div>
              </div>
          </div>
      </div>

      <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
          aria-hidden="true">
          <div class="modal-dialog">
              <div class="modal-content">
                  <div class="modal-header">
                      <h6 class="text-success">Listado de Clientes</h6>
                  </div>
                  <div class="modal-body form-material">
                      <div>
                          <p class="fs-15negrita">Se mostraran solo los clientes registrados de este mes... Si desea ver
                              clientes registrados angituos, utilize los filtros</p>
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

  @section('scripts')
      <script>
          respuestaError('hola');

          function descargarFormatoExcel() {
              alert("descargar");
              window.open("../staff/descargar/formato-excel-clientes", "_blank");
          }
      </script>
      <script>
          const form = document.querySelector('#formImport');
          const inputFile = form.querySelector('.input-file');
          form.addEventListener('click', () => {
              inputFile.click();
          })

          inputFile.onchange = ({
              target
          }) => {
              if (target.files[0].size <= 153600) {
                  $('#nameFile').text(target.files[0].name);
                  $('#subTitleFile').text('');
                  $('#subTitleFile').removeClass('text-crimson');
              } else {
                  $('#subTitleFile').text('');
                  $('#nameFile').text('');
                  $('#subTitleFile').addClass('text-crimson');
                  $('#subTitleFile').text('El Archivo es muy Grande');
              }
          }
      </script>

      <script type="text/javascript">
          $("#btnImportar").click(function() {
              if ($("#input-file").val() != "") {
                  const progressBar = document.getElementById('progress');
                  const porcentajeBarra = document.getElementById('porcentajeBarra');
                  progressBar.classList.remove("d-none")
                  var formData = new FormData();
                  formData.append("file", $("#input-file")[0].files[0]);
                  $.ajax({
                      url: "/upload",
                      type: "post",
                      data: formData,
                      contentType: false, // Debe ser falso para agregar automáticamente el tipo de contenido correcto
                      processData: false,
                      xhr: function() {
                          myXhr = $.ajaxSettings.xhr();
                          if (myXhr.upload) { // Comprueba si el atributo de carga existe
                              // La función de devolución de llamada vinculada al evento de progreso
                              myXhr.upload.addEventListener('progress', function(e) {
                                  var curr = e.loaded;
                                  var total = e.total;
                                  process = Math.round((curr / total) * 100);
                                  $(".progress-bar").css("width", process + "%");
                                  $("#porcentajeBarra").text(`${process}%`);

                              }, false);
                          }
                          return myXhr;
                      }
                  });
              }
          });
      </script>


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
              $("#idCliente").val(id);
          }
          $(function() {
              $("#exampleModal button.btnEliminar").on("click", function(e) {
                  var id = $("#idCliente").val();
                  window.location = 'clientes/' + id + '/delete';
              });

              var bandModal = <?php echo json_encode($band); ?>;

              if (bandModal == '') {
                  $("#mostrarmodal").modal("show");
              }
              $('#Inicio').hide();
              $('#Final').hide();

              var fecha = <?php echo json_encode($fecha); ?>;

              if (fecha == '9') {
                  var fechaIni = <?php echo json_encode($fechaInicial); ?>;
                  var fechaFin = <?php echo json_encode($fechaFinal); ?>;
                  $('#Inicio').show();
                  $('#Final').show();
                  $('#datepickerIni').val(fechaIni);
                  $('#datepickerFin').val(fechaFin);
              }
              $('#idFecha option[value=' + fecha + ']').prop('selected', true);
          });
      </script>
      <script>
          $(function() {
              $("#idFecha").on('change', function() {
                  var valor = $("#idFecha").val();
                  if (valor == "9") {
                      $('#Inicio').show();
                      $('#Final').show();
                  } else {
                      $('#Inicio').hide();
                      $('#Final').hide();
                      $('#datepickerIni').val('');
                      $('#datepickerFin').val('');
                  }
              });
          });
      </script>
  @stop
