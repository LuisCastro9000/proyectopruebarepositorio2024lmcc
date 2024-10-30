@extends('admin.template.layout')
@section('title', 'Pagos-Suscripcion')
@section('contenido')
    <div class="main">
        <section class="row my-3">
            <div class="col-12">
                <div class="page-title-box">
                    <div class="page-title-right">
                        <form class="d-flex">
                            <div class="input-group">
                                <input type="text" class="form-control form-control-light" id="dash-daterange">
                                <span class="input-group-text bg-primary border-primary text-white">
                                    <i class="mdi mdi-calendar-range font-13"></i>
                                </span>
                            </div>
                            <a href="javascript: void(0);" class="btn btn-primary ms-2">
                                <i class="mdi mdi-autorenew"></i>
                            </a>
                            <a href="javascript: void(0);" class="btn btn-primary ms-1">
                                <i class="mdi mdi-filter-variant"></i>
                            </a>
                        </form>
                    </div>
                    <h4 class="page-title">Pagos Suscripción</h4>
                </div>
            </div>
        </section>
        <section class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <table id="basic-datatable" class="table dt-responsive nowrap w-100">
                            <thead class="table-light">
                                <tr>
                                    <th>Fecha</th>
                                    <th class="text-center">Razon Social</th>
                                    <th class="text-center">Contacto</th>
                                    <th class="text-center">Num. Operación</th>
                                    <th class="text-center">Monto</th>
                                    <th class="text-center">Estado</th>
                                    <th class="text-center">Fecha Depósito</th>
                                    <th class="text-center">Acción</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($pagosClientes as $pago)
                                    <tr>
                                        <td>{{ $pago->FechaRegistro }}</td>
                                        <td class="text-center">{{ $pago->NombreEmpresa }}</td>
                                        <td class="text-center">{{ $pago->Celular }}</td>
                                        <td class="text-center">{{ $pago->NumeroOperacion }}</td>
                                        <td class="text-center">{{ $pago->MontoPago }}</td>
                                        <td class="text-center">
                                            @if ($pago->Estado == 'Verificado')
                                                <span class="badge badge-success-lighten">{{ $pago->Estado }}</span>
                                            @elseif ($pago->Estado == 'Renovado')
                                                <span
                                                    class="badge badge-warning-lighten  estadoPago{{ $pago->Id }}">{{ $pago->Estado }}</span>
                                            @else
                                                <span
                                                    class="badge badge-danger-lighten estadoPago{{ $pago->Id }}">{{ $pago->Estado }}</span>
                                            @endif
                                        </td>
                                        <td class="text-center">{{ $pago->FechaDeposito }}</td>
                                        <td class="text-center">
                                            @if ($pago->Estado == 'Verificado' && $pago->Imagen == null)
                                                <span><i class="mdi mdi-lock"></i></span>
                                            @else
                                                <div class="dropdown">
                                                    <a href="#"
                                                        class="dropdown-toggle text-muted arrow-none bg-light rounded p-1"
                                                        data-bs-toggle="dropdown" aria-expanded="false">
                                                        <i class="mdi mdi-dots-horizontal"></i>
                                                    </a>
                                                    <div class="dropdown-menu dropdown-menu-end">
                                                        @if ($pago->Estado != 'Verificado')
                                                            <a class="dropdown-item renovarSuscripcion" href="#"
                                                                data-codigo-cliente="{{ $pago->CodigoCliente }}"
                                                                data-id-pago="{{ $pago->Id }}"><i
                                                                    class="mdi mdi-update me-2 text-muted vertical-middle"></i>Renovar
                                                                Suscripción</a>
                                                            <a class="dropdown-item editarEstado" href="#"
                                                                data-id-pago="{{ $pago->Id }}"><i
                                                                    class="mdi mdi-pencil me-2 text-muted vertical-middle"></i>Editar
                                                                Estado</a>
                                                        @endif
                                                        @if ($pago->Imagen != null)
                                                            <a class="dropdown-item verDocumento" href="#"
                                                                data-url-imagen={{ config('variablesGlobales.urlDominioAmazonS3') . $pago->Imagen }}><i
                                                                    class="mdi mdi-eye me-2 text-muted vertical-middle"></i>Ver
                                                                Comprobante</a>
                                                        @endif
                                                    </div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="modal fade" id="centermodal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12">
                            <img id="imgComprobantePago" src="{{ asset('assetsAdmin/images/PagoPlim.png') }}"
                                alt="" class="w-100">
                        </div>
                    </div>
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

    <!-- Renovar Suscripcion modal -->
    <div id="modalSucursales" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="text-center mt-2 mb-4">
                        <h3>Renovar suscripción</h3>
                    </div>

                    <form action="#" class="ps-3 pe-3">

                        <div class="accordion custom-accordion" id="custom-accordion-one">
                            <div class="card mb-0">
                                <div class="card-header" id="headingFour">
                                    <h5 class="m-0">
                                        <a class="custom-accordion-title d-block" data-bs-toggle="collapse"
                                            href="#collapseFour" aria-expanded="true" aria-controls="collapseFour">
                                            Q. Can I use this template for my client? <i
                                                class="mdi mdi-chevron-down accordion-arrow"></i>
                                        </a>
                                    </h5>
                                </div>

                                <div id="collapseFour" class="collapse show" aria-labelledby="headingFour"
                                    data-bs-parent="#custom-accordion-one">
                                    <div class="card-body">
                                        ...
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-0">
                                <div class="card-header" id="headingFive">
                                    <h5 class="m-0">
                                        <a class="custom-accordion-title collapsed d-block" data-bs-toggle="collapse"
                                            href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
                                            Q. Can this theme work with WordPress? <i
                                                class="mdi mdi-chevron-down accordion-arrow"></i>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseFive" class="collapse" aria-labelledby="headingFive"
                                    data-bs-parent="#custom-accordion-one">
                                    <div class="card-body">
                                        ...
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-0">
                                <div class="card-header" id="headingSix">
                                    <h5 class="m-0">
                                        <a class="custom-accordion-title collapsed d-block" data-bs-toggle="collapse"
                                            href="#collapseSix" aria-expanded="false" aria-controls="collapseSix">
                                            Q. How do I get help with the theme? <i
                                                class="mdi mdi-chevron-down accordion-arrow"></i>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseSix" class="collapse" aria-labelledby="headingSix"
                                    data-bs-parent="#custom-accordion-one">
                                    <div class="card-body">
                                        ...
                                    </div>
                                </div>
                            </div>
                            <div class="card mb-0">
                                <div class="card-header" id="headingSeven">
                                    <h5 class="m-0">
                                        <a class="custom-accordion-title collapsed d-block" data-bs-toggle="collapse"
                                            href="#collapseSeven" aria-expanded="false" aria-controls="collapseSeven">
                                            Q. Will you regularly give updates of Hyper ? <i
                                                class="mdi mdi-chevron-down accordion-arrow"></i>
                                        </a>
                                    </h5>
                                </div>
                                <div id="collapseSeven" class="collapse" aria-labelledby="headingSeven"
                                    data-bs-parent="#custom-accordion-one">
                                    <div class="card-body">
                                        ...
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-3 text-center">
                            <button class="btn rounded-pill btn-primary" type="submit">Sign In</button>
                        </div>

                    </form>

                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->

@endsection
@section('scripts')
    <script>
        $(document).on('click', '.verDocumento', function(e) {
            e.preventDefault();
            let urlImagen = $(this).data('urlImagen');
            $('#imgComprobantePago').attr('src', urlImagen);
            setTimeout(() => {
                $('#centermodal').modal('show');
            }, 500);
        });

        $(document).on('click', '.editarEstado', function() {
            let idPago = $(this).data('idPago');
            let url = "{{ route('pagos-plan-sucripcion.update', [':id']) }}".replace(':id', idPago);
            Swal.fire({
                title: 'Estas seguro?',
                text: "El estado se actualizara a pago verificado",
                icon: 'warning',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Entendido'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.LoadingOverlay("show", {
                        image: '',
                        text: 'Espere un momento por favor...',
                        imageAnimation: '1.5s fadein',
                        textColor: "#f6851a",
                        textResizeFactor: '0.3',
                        textAutoResize: true
                    });
                    $.ajax({
                        type: 'PUT',
                        url: url,
                        data: {
                            "_token": "{{ csrf_token() }}",
                            'idPago': idPago
                        },
                        success: function(data) {
                            if (data.respuesta == 'success') {
                                $(`.estadoPago${idPago}`).text('Verificado');
                                $(`.estadoPago${idPago}`).removeClass('badge-danger-lighten')
                                $(`.estadoPago${idPago}`).addClass('badge-success-lighten')
                            } else {
                                mostrarMensaje('error',
                                    `Hubo un error en la solicitud. Por favor, póngase en contacto con el área de soporte técnico`
                                );
                            }
                            $.LoadingOverlay("hide");
                        },
                        error: function(data) {
                            $.LoadingOverlay("hide");
                            mostrarMensaje('error',
                                `Hubo un error en la solicitud. Detalles: ${data.responseJSON.detalleError}`
                            );
                        }

                    });
                }
            })

        });

        $(document).on('click', '.renovarSuscripcion', function(e) {
            e.preventDefault();
            const idPago = $(this).data('idPago');
            $.LoadingOverlay("show", {
                image: '',
                text: 'Espere un momento por favor...',
                imageAnimation: '1.5s fadein',
                textColor: "#f6851a",
                textResizeFactor: '0.3',
                textAutoResize: true
            });
            $.ajax({
                type: 'GET',
                url: "{{ route('pago-sucripcion.obtener-suscripcion') }}",
                data: {
                    'codigoCliente': $(this).data('codigoCliente'),
                },
                success: function(data) {
                    $('.main').html(data);
                    $('#inputIdPagoSucripcion').val(idPago);
                    $.LoadingOverlay("hide");
                },

            });
        })
    </script>
@endsection
