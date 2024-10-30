 @extends('admin.template.layout')
 @section('title', 'Cotizaciones')
 @section('contenido')
     <!-- start page title -->
     <div class="row my-3">
         <div class="col-12">
             <section class="d-flex justify-content-center justify-content-md-between flex-wrap">
                 <h4 class="page-title">Tipo de Cambio</h4>
                 <button id="btnActualizar" class="btn btn-primary" type="button">Actualizar</button>
             </section>
         </div>
     </div>
     <!-- end page title -->
     <div class="card">
         <div class="card-body">
             <form id="formulario">
                 <div class="col-12">
                     <label for="selectSucursal" class="form-label">Seleccione Sucursal</label>
                     <select class="form-control select2 selectSucursal" data-toggle="select2">
                         <option value="0">-</option>
                         @foreach ($sucursales as $sucursal)
                             <option value="{{ $sucursal->IdSucursal }}">
                                 {{ $sucursal->NombreEmpresa . '  ---  ' . $sucursal->RucEmpresa . '  ---  ' . $sucursal->NombreSucursal }}
                             </option>
                         @endforeach
                     </select>
                     <span class="text-danger errorSucursal error d-none">Seleccione la sucursal</span>
                 </div>
             </form>
             <br><br>
             <section id="seccionTable">
                 @include('admin.administracion.tipoCambio._tablaTipoCambio')
             </section>
         </div>
     </div>
     <br>
 @stop
 @section('scripts')
     <script>
         $(document).ready(function() {
             $('.selectSucursal').on('change', function(e) {
                 const valueSelect = $(this).val();
                 showLoadingOverlay();
                 $.ajax({
                     type: 'GET',
                     url: "{{ route('admin.tipo-cambio.obtener') }}",
                     data: {
                         idSucursal: valueSelect,
                     },
                     success: function(data) {
                         console.log(data);
                         if (data.tipoCambio == null) {
                             $('#tablaDatos').find('tbody').empty();
                         } else {
                             $('#seccionTable').html(data.view);
                         }
                         hideLoadingOverlay();
                     }
                 })
             })

             $('#btnActualizar').click(function(e) {
                 if ($('.table').find('tbody tr').length == 0) {
                     Swal.fire({
                         text: "No existe datos para actualizar",
                         icon: "error"
                     })
                 } else {
                     const valueTipoCambioCompras = $('#inputTipoCambioCompras').val();
                     const valueTipoCambioVentas = $('#inputTipoCambioVentas').val();
                     Swal.fire({
                         text: `Estas seguro de actualizar?`,
                         icon: "warning"
                     }).then((result) => {
                         if (result.isConfirmed) {
                             showLoadingOverlay();
                             $.ajax({
                                 type: 'PUT',
                                 url: "{{ route('admin.tipo-cambio.actualizar') }}",
                                 data: {
                                     "_token": "{{ csrf_token() }}",
                                     idSucursal: $('.selectSucursal  option:selected').val(),
                                     valueTipoCambioCompras: valueTipoCambioCompras,
                                     valueTipoCambioVentas: valueTipoCambioVentas,
                                 },
                                 success: function(data) {
                                     hideLoadingOverlay();
                                     if (data.respuesta === 'success') {
                                         if (valueTipoCambioCompras != '') {
                                             let spanCompras =
                                                 `<span class='badge badge-success-lighten font-14'>${valueTipoCambioCompras}</span>`;
                                             $('#tipoCompra')
                                                 .replaceWith(spanCompras);
                                         }
                                         if (valueTipoCambioVentas != '') {
                                             let spanVentas =
                                                 `<span class='badge badge-success-lighten font-14'>${valueTipoCambioVentas}</span>`;
                                             $('#tipoVenta')
                                                 .replaceWith(spanVentas);
                                         }
                                         $.NotificationApp.send("Actualización Exitosa",
                                             "Se realizo correctamente el tipo de cambio",
                                             "bottom-right", "rgba(0,0,0,0.2)",
                                             "success")
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

             //  $(document).on('keypress', function(event) {
             //      const inputObjetivo = $(event.target).closest('#inputTipoCambioCompras');
             //      if (inputObjetivo.length > 0) {
             //          setTimeout(() => {
             //              let value = inputObjetivo.val();
             //              console.log(`valor antiguo ${value}`);

             //              if (event.key === "Delete" || event.key === "Backspace") {
             //                  console.log("Tecla Eliminar presionada");
             //                  // Tu código aquí
             //              } else {
             //                  let value = inputObjetivo.val();

             //                  console.log(`value.length ${value.length}`);
             //                  if (value.length == 1) {
             //                      console.log(`soy menor que uno`);
             //                      // Establecer el nuevo valor correctamente
             //                      inputObjetivo.val(`${value}.`);
             //                      console.log(`nuevo valor ${inputObjetivo.val()}`);
             //                  }
             //              }
             //          }, 0);
             //      }
             //  });
             //  $(document).on('keypress', '#inputTipoCambioCompras', function(event) {
             //      // Espera un breve período antes de obtener el valor actualizado
             //      setTimeout(() => {
             //          let value = $(this).val();
             //          console.log(`Valor actualizado: ${value}`);
             //      }, 0);
             //  });






         });
     </script>

 @stop
