 @extends('admin.template.layout')
 @section('title', 'Cotizaciones')
 @section('contenido')
     <!-- start page title -->
     <div class="row my-3">
         <div class="col-12">
             <section class="d-flex justify-content-center justify-content-md-between flex-wrap">
                 <h4 class="page-title">Cambiar Estado-Cotizaciones</h4>
                 <button id="btnActualizarEstado" class="btn btn-primary" type="button">Actualizar Estado</button>
             </section>
             <div class="mt-2 d-flex align-items-end">
                 <input type="checkbox" id="switchReponerStock" data-switch="bool" />
                 <label for="switchReponerStock" data-on-label="Si" data-off-label="No"></label><span class="ms-2">Reponer
                     Stock</span>
             </div>
         </div>
     </div>
     <!-- end page title -->
     <div class="card">
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
                             <input class="form-control inputNumeroEntero" id="inputNumero" type="text" name="number">
                             <span class="text-danger error d-none">Ingrese el número</span>
                         </div>
                     </div>
                     <div class="col-12 col-md-8">
                         <label for="inputNumero" class="form-label">Seleccione Sucursal</label>
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
                 <br><br>
                 <section id="seccionTable">
                     @include('admin.administracion.cotizaciones._tabla')
                 </section>
             </form>
         </div>
     </div>
     <br>

 @stop
 @section('scripts')
     <script>
         let estadoCotizacion = 0;
         $('#selectSucursal').change(function(e) {
             ocultarMensajeError(this)
             if (isFormularioValido('#formulario')) {
                 realizarPeticionAjax();
             }
         })

         const realizarPeticionAjax = () => {
             showLoadingOverlay();
             $.ajax({
                 type: 'GET',
                 url: "{{ route('admin.cotizaciones.obtener') }}",
                 data: {
                     idSucursal: $('#selectSucursal option:selected').val(),
                     serie: $('#inputSerie').val(),
                     numero: $('#inputNumero').val()
                 },
                 success: function(data) {
                     hideLoadingOverlay();
                     if (data.cotizaciones.length > 0) {
                         estadoCotizacion = data.cotizaciones[0].IdEstadoCotizacion;
                         $('#seccionTable').html(data.vista);
                     } else {
                         estadoCotizacion = 0;
                         Swal.fire({
                             text: "No se encontro ninguna coincidencia",
                             icon: "info"
                         })
                         $('#tablaCotizaciones').find('tbody').empty();
                     }
                 }
             })
         }

         $('#switchReponerStock').change(function() {
             const valorSwitch = $(this).prop('checked');
             if (valorSwitch === true) {
                 if (estadoCotizacion != 2 && estadoCotizacion != 3 && estadoCotizacion != 4) {
                     $(this).prop('checked', false);
                     Swal.fire({
                         text: "Solo puedes reponer el stock cuando la cotización está en 'proceso' o ha sido 'finalizada'.",
                         icon: "error"
                     })
                 } else {
                     const nuevoEstado = $('#selectEstados  option:selected').val();
                     if (nuevoEstado !== '1' && nuevoEstado !== '6') {
                         $(this).prop('checked', false);
                         Swal.fire({
                             text: "Solo puedes reponer el stock si el nuevo estado es 'abierto' o 'baja'.",
                             icon: "error"
                         })
                     }
                 }
             }
         })

         $(document).on('change', '#selectEstados', function() {
             const valueSelect = $(this).val();
             const valueSwitchReponerStock = $("#switchReponerStock").prop('checked');
             if (valueSwitchReponerStock === true) {
                 if (valueSelect !== '1' && valueSelect !== '6') {
                     $("#switchReponerStock").prop('checked', false);
                     Swal.fire({
                         text: "La opción de reponer stock está activa; solo puedes reponer el stock si el nuevo estado es 'abierto' o 'baja'.",
                         icon: "error"
                     })
                 }
             }
         })

         $('#btnActualizarEstado').click(function(e) {
             if ($('.table').find('tbody tr').length == 0) {
                 Swal.fire({
                     text: "No existe ninguna cotización para actualizar el estado",
                     icon: "error"
                 })
             } else {
                 Swal.fire({
                     text: `Estas seguro de actualizar a estado ${$('#selectEstados  option:selected').text()}?`,
                     icon: "warning"
                 }).then((result) => {
                     if (result.isConfirmed) {
                         showLoadingOverlay();
                         $.ajax({
                             type: 'PUT',
                             url: "{{ route('admin.cotizaciones.update-estado') }}",
                             data: {
                                 "_token": "{{ csrf_token() }}",
                                 nuevoEstado: $('#selectEstados  option:selected').val(),
                                 idCotizacion: $('#selectEstados').data('id'),
                                 idSucursal: $('#selectEstados').data('sucursal'),
                                 correlativo: $('#selectEstados').data('correlativo'),
                                 estadoAnterior: $('#selectEstados').data('estadoCotizacion'),
                                 switchReponerStock: $("#switchReponerStock").prop('checked')
                             },
                             success: function(data) {
                                 hideLoadingOverlay();
                                 if (data.respuesta === 'success') {
                                     estadoCotizacion = $('#selectEstados  option:selected')
                                         .val();
                                     $('.spanEstado').replaceWith(data.html);
                                     $.NotificationApp.send("Actualización Exitosa",
                                         "Se realizo correctamente el cambio de estado",
                                         "bottom-right", "rgba(0,0,0,0.2)", "success")
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

         //  Delegar el evento keyup al formulario
         $('#formulario').on('keyup', ':input', function(e) {
             ocultarMensajeError(this);

             if (e.which === 13) {
                 if (isFormularioValido('#formulario')) {
                     realizarPeticionAjax();
                 }
             }
         });

         //  const inicializarTabla = (tabla) => {
         //      tabla.DataTable({
         //          keys: !0,
         //          language: {
         //              paginate: {
         //                  previous: "<i class='mdi mdi-chevron-left'>",
         //                  next: "<i class='mdi mdi-chevron-right'>"
         //              },
         //              search: "Buscar:",
         //              lengthMenu: "Mostrar _MENU_ registros",
         //              info: "Registros del _START_ al _END_ de un total de _TOTAL_",
         //              infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
         //              infoFiltered: "",
         //              infoPostFix: "",
         //              loadingRecords: "Cargando...",
         //              zeroRecords: "No se encontraron resultados",
         //              emptyTable: "Ningún dato disponible en esta tabla",
         //          },
         //          drawCallback: function() {
         //              $(".dataTables_paginate > .pagination").addClass("pagination-rounded")
         //          }
         //      });
         //  }
     </script>
 @stop
