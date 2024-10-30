 @extends('admin.template.layout')
 @section('title', 'Reponer-stock')
 @section('contenido')
     <!-- start page title -->
     <div class="row my-3">
         <div class="col-12">
             <section class="d-flex justify-content-center justify-content-md-between flex-wrap">
                 <h4 class="page-title">Ventas</h4>
                 {{-- <button id="btnReponerStock" class="btn btn-primary" type="button">Reponer</button> --}}
             </section>
         </div>
     </div>
     <!-- end page title -->
     {{-- Inicio --}}
     <div class="card">
         <div class="card-body">
             <!-- Checkout Steps -->
             <ul class="nav nav-pills bg-nav-pills nav-justified mb-3">
                 <li class="nav-item">
                     <a href="#reponer-stock" data-bs-toggle="tab" aria-expanded="false"
                         class="nav-link rounded-0 active btnOperacion" data-tipo-operacion="reponer">
                         <i class="mdi mdi-truck-check font-22"></i>
                         <span class="d-none d-lg-block">Reponer Stock</span>
                     </a>
                 </li>
                 <li class="nav-item">
                     <a href="#completar-hash-qr" data-bs-toggle="tab" aria-expanded="true"
                         class="nav-link rounded-0 btnOperacion" data-tipo-operacion="completar">
                         <i class="mdi mdi-content-duplicate font-22"></i>
                         <span class="d-none d-lg-block">Actualizar Hash-Resumen</span>
                     </a>
                 </li>
             </ul>

             <!-- Steps Information -->
             <div class="tab-content">
                 <!-- Billing Content-->
                 <div class="tab-pane show active" id="reponer-stock">
                     <div class="row mt-4">
                         <div class="col-lg-12">
                             <form id="formularioReponerStock">
                                 @include('admin.administracion.ventas._form')
                             </form>
                             <br><br>
                             <section id="seccionTableReponer">
                                 @include('admin.administracion.ventas._tabla')
                             </section>
                         </div>
                         <div class="col-12 text-sm-end mt-4">
                             <button id="btnReponerStock" class="btn btn-danger" type="button">Reponer Stock</button>
                         </div>
                     </div> <!-- end row-->
                 </div>
                 <!-- End Billing Information Content-->

                 <!-- Shipping Content-->
                 <div class="tab-pane" id="completar-hash-qr">
                     <div class="row mt-4">
                         <div class="col-lg-12 mb-4">
                             <section class="d-flex justify-content-start flex-wrap">
                                 <div class="mt-2 d-flex align-items-end">
                                     <input type="checkbox" id="switchActualizarHash" data-switch="bool" />
                                     <label for="switchActualizarHash" data-on-label="Si" data-off-label="No"></label><span
                                         class="ms-1 me-2">Solo Hash</span>
                                 </div>
                                 <div class="mt-2 d-flex align-items-end">
                                     <input type="checkbox" id="switchActualizarResumen" data-switch="bool" />
                                     <label for="switchActualizarResumen" data-on-label="Si"
                                         data-off-label="No"></label><span class="ms-1 me-2">Solo Resumen</span>
                                 </div>
                                 <div class="mt-2 d-flex align-items-end">
                                     <input type="checkbox" id="switchActualizarHash-Resumen" data-switch="bool" />
                                     <label for="switchActualizarHash-Resumen" data-on-label="Si"
                                         data-off-label="No"></label><span class="ms-1 me-2">Ambos Hash-Resumen</span>
                                 </div>
                             </section>
                         </div>
                         <hr>
                         <div class="col-lg-12 mt-2">
                             <form id="formularioCompletarHash-Qr">
                                 @include('admin.administracion.ventas._form')
                             </form>
                             <br><br>
                             <section id="seccionTableCompletar">
                                 @include('admin.administracion.ventas._tablaDatosVenta')
                             </section>
                         </div>
                         <div class="col-12 text-sm-end mt-4">
                             <button id="btnCompletarDatos" class="btn btn-success" type="button">Completar Datos</button>
                         </div>
                     </div> <!-- end row-->
                 </div>
                 <!-- End Shipping Information Content-->
             </div> <!-- end tab content-->

         </div> <!-- end card-body-->
     </div>
     {{-- Fin --}}
     {{-- <div class="card">
         <div class="card-body">
             <form id="formulario">
                 <div class="row">
                     <div class="col-12 col-md-2">
                         <div>
                             <label for="inputSerie" class="form-label">Serie</label>
                             <input type="text" id="inputSerie" class="form-control inputLetrasMayusculas">
                             <span class="text-danger error d-none">Ingrese la serie</span>
                         </div>
                     </div>
                     <div class="col-12 col-md-2">
                         <div>
                             <label for="inputNumero" class="form-label">Número</label>
                             <input class="form-control inputNumeroEntero" id="inputNumero" type="number" name="number">
                             <span class="text-danger error d-none">Ingrese el número</span>
                         </div>
                     </div>
                     <div class="col-12 col-md-8">
                         <label for="selectSucursal" class="form-label">Seleccione Sucursal</label>
                         <select id="selectSucursal" class="form-control select2" data-toggle="select2">
                             <option value="0">-</option>
                             @foreach ($sucursales as $sucursal)
                                 <option value="{{ $sucursal->IdSucursal }}">
                                     {{ $sucursal->NombreEmpresa . '  ---  ' . $sucursal->RucEmpresa . '  ---  ' . $sucursal->NombreSucursal }}
                                 </option>
                             @endforeach
                         </select>
                         <span class="text-danger errorSucursal error d-none">Seleccione la sucursal</span>
                     </div>
                 </div>
             </form>
             <br><br>
             <section id="seccionTable">
                 @include('admin.administracion.stock._tabla')
             </section>
         </div>
     </div>
     <br> --}}
 @stop
 @section('scripts')

     <script>
         $(document).ready(function() {
             let tipoOperacion = 'reponer';
             let articulosVendidos = [];
             let datosVenta = [];
             $('.selectSucursal').on('change', function(e) {
                 const idFormulario = $(this).closest('form').attr('id');
                 ocultarMensajeError(this)
                 if (isFormularioValido(`#${idFormulario}`)) {
                     obtenerDatos(`#${idFormulario}`);
                 }
             })

             const obtenerDatos = (formulario) => {
                 showLoadingOverlay();
                 $.ajax({
                     type: 'GET',
                     url: "{{ route('admin.ventas.buscar') }}",
                     data: {
                         idSucursal: $(`${formulario} .selectSucursal option:selected`).val(),
                         serie: $(`${formulario} #inputSerie`).val(),
                         numero: $(`${formulario} #inputNumero`).val(),
                         tipoOperacion: tipoOperacion
                     },
                     success: function(data) {
                         if (tipoOperacion === 'reponer') {
                             if (data.articulosVendidos.length < 1) {
                                 Swal.fire({
                                     text: "No se encontro ninguna coincidencia",
                                     icon: "info"
                                 })
                                 articulosVendidos = [];
                                 $('#tablaArticulos').find('tbody').empty();
                             } else {
                                 articulosVendidos = data.articulosVendidos;
                                 $('#seccionTableReponer').html(data.vista);
                             }
                         }

                         if (tipoOperacion === 'completar') {
                             if (data.datosVenta.length < 1) {
                                 Swal.fire({
                                     text: "No se encontro ninguna coincidencia",
                                     icon: "info"
                                 })
                                 datosVenta = [];
                                 $('#tablaDatosVenta').find('tbody').empty();
                             } else {
                                 datosVenta = data.datosVenta;
                                 $('#seccionTableCompletar').html(data.vista);
                             }
                         }
                         hideLoadingOverlay();
                     }
                 })
             }

             $('#btnReponerStock').on('click', function(e) {
                 if (articulosVendidos.length < 1) {
                     Swal.fire({
                         text: "No existe articulos para Reponer el Stock",
                         icon: "error"
                     })
                 } else {
                     Swal.fire({
                         text: `Estas seguro de realizar la reposición de stock?`,
                         icon: "warning"
                     }).then((result) => {
                         if (result.isConfirmed) {
                             showLoadingOverlay(mensaje =
                                 "Reponiendo Stock <br> Espere un mmonento ...");
                             $.ajax({
                                 type: 'PUT',
                                 url: "{{ route('admin.ventas.reponer-stock') }}",
                                 data: {
                                     _token: '{{ csrf_token() }}',
                                     articulosVendidos: articulosVendidos,
                                 },
                                 success: function(data) {
                                     hideLoadingOverlay();
                                     if (data.respuesta === 'success') {
                                         data.articulos.forEach(articulo => {
                                             let span =
                                                 `<span class='badge badge-success-lighten font-14 spanExistencia-${articulo.IdArticulo}'>${articulo.existencia}</span>`;
                                             $(`.spanExistencia-${articulo.IdArticulo}`)
                                                 .replaceWith(span);
                                         });
                                         $.NotificationApp.send("Actualización Exitosa",
                                             "La reposicion se realizo correctamente",
                                             "bottom-right",
                                             "rgba(0,0,0,0.2)", "success")
                                     }
                                     if (data.respuesta === 'error') {
                                         Swal.fire({
                                             text: data.mensaje,
                                             icon: "error"
                                         })
                                     }
                                 },
                                 error: function(xhr, textStatus, errorThrown) {
                                     hideLoadingOverlay();
                                     if (xhr.status == 500) {
                                         // Error interno del servidor
                                         Swal.fire({
                                             text: 'Ocurrió un error interno del servidor. Por favor, comuníquese con el área de soporte.',
                                             icon: "error"
                                         });
                                     }
                                 }
                             })
                         }
                     });

                 }
             })

             $('#btnCompletarDatos').on('click', function(e) {
                 const switchMarcados = $(
                     '#switchActualizarHash-Resumen:checked, #switchActualizarHash:checked, #switchActualizarResumen:checked'
                 );
                 if (switchMarcados.length === 0) {
                     return Swal.fire({
                         text: "No ha chekeado ningún Switch",
                         icon: "error"
                     })
                 }
                 if (datosVenta.length < 1) {
                     return Swal.fire({
                         text: "No existe datos para actualizar",
                         icon: "error"
                     })
                 }
                 Swal.fire({
                     text: `Estas seguro de actualizar los datos?`,
                     icon: "warning"
                 }).then((result) => {
                     if (result.isConfirmed) {
                         showLoadingOverlay(mensaje =
                             "Actulizando datos <br> Espere un mmonento ...");
                         $.ajax({
                             type: 'PUT',
                             url: "{{ route('admin.ventas.actualizar-hash-qr') }}",
                             data: {
                                 _token: '{{ csrf_token() }}',
                                 datosVenta: datosVenta,
                                 switchActualizarHashResumen: $(
                                     '#switchActualizarHash-Resumen').prop(
                                     'checked'),
                                 switchActualizarHash: $('#switchActualizarHash').prop(
                                     'checked'),
                                 switchActualizarResumen: $('#switchActualizarResumen')
                                     .prop('checked')
                             },
                             success: function(data) {
                                 hideLoadingOverlay();
                                 if (data.respuesta === 'success') {
                                     const spanHash =
                                         `<span class='spanHash'>${data.nuevosDatos.hash}</span>`;
                                     const spanResumen =
                                         `<span class='spanResumen'>${data.nuevosDatos.resumen}</span>`;
                                     $(`.spanHash`).replaceWith(spanHash);
                                     $(`.spanResumen`).replaceWith(spanResumen);
                                     $.NotificationApp.send("Actualización Exitosa",
                                         "La reposicion se realizo correctamente",
                                         "bottom-right",
                                         "rgba(0,0,0,0.2)", "success")
                                 }
                                 if (data.respuesta === 'error') {
                                     Swal.fire({
                                         text: data.mensaje,
                                         icon: "error"
                                     })
                                 }
                             },
                             error: function(xhr, textStatus, errorThrown) {
                                 hideLoadingOverlay();
                                 if (xhr.status == 500) {
                                     // Error interno del servidor
                                     Swal.fire({
                                         text: 'Ocurrió un error interno del servidor. Por favor, comuníquese con el área de soporte.',
                                         icon: "error"
                                     });
                                 }
                             }
                         })
                     }
                 });
             })

             //  Delegar el evento keyup al formulario
             //  $('#formularioReponerStock').on('keyup', ':input', function(e) {
             //      console.log('escribiendo');
             //      console.log(e);
             //      ocultarMensajeError(this);

             //      if (e.which === 13) {
             //          if (isFormularioValido('#formularioReponerStock')) {
             //              obtenerDatos();
             //          }
             //      }
             //  });

             $(document).on('keyup', function(e) {
                 if ($(e.target).is('input')) {
                     const idFormulario = $(e.target).closest('form').attr('id');
                     ocultarMensajeError($(e.target));
                     if (e.which === 13) {
                         if (isFormularioValido(`#${idFormulario}`)) {
                             obtenerDatos(`#${idFormulario}`);
                         }
                     }
                 }
             });

             $('.btnOperacion').click(function(e) {
                 tipoOperacion = $(this).data('tipoOperacion');
                 console.log(tipoOperacion);
             })

             $('#switchActualizarHash').on('change', function(e) {
                 if ($(this).is(':checked')) {
                     $('#switchActualizarResumen').attr('disabled', true);
                     $('#switchActualizarHash-Resumen').attr('disabled', true);
                 } else {
                     $('#switchActualizarResumen').attr('disabled', false);
                     $('#switchActualizarHash-Resumen').attr('disabled', false);
                 }
             })

             $('#switchActualizarResumen').on('change', function(e) {
                 if ($(this).is(':checked')) {
                     $('#switchActualizarHash').attr('disabled', true);
                     $('#switchActualizarHash-Resumen').attr('disabled', true);
                 } else {
                     $('#switchActualizarHash').attr('disabled', false);
                     $('#switchActualizarHash-Resumen').attr('disabled', false);
                 }
             })

             $('#switchActualizarHash-Resumen').on('change', function(e) {
                 if ($(this).is(':checked')) {
                     $('#switchActualizarHash').attr('disabled', true);
                     $('#switchActualizarResumen').attr('disabled', true);
                 } else {
                     $('#switchActualizarHash').attr('disabled', false);
                     $('#switchActualizarResumen').attr('disabled', false);
                 }
             })
         });
     </script>
 @stop
