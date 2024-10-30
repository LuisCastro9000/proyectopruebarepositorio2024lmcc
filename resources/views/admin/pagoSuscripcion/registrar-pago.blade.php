<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title>Registro-Pago</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="ERP Especializado para Talleres mecánicos" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- App css -->
    <link href="{{ asset('assetsAdmin/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assetsAdmin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">
    <link rel="stylesheet" href="{{ asset('assetsAdmin/css/estilos-personalizados.css?v=' . time()) }}">

</head>

<body class="loading"
    data-layout-config='{"leftSideBarTheme":"dark","layoutBoxed":false, "leftSidebarCondensed":false, "leftSidebarScrollable":false,"darkMode":false, "showRightSidebarOnStart": true}'>
    <!-- Pre-loader -->
    <div id="preloader">
        <div id="status">
            <div class="bouncing-loader">
                <div></div>
                <div></div>
                <div></div>
            </div>
        </div>
    </div>
    <!-- End Preloader-->
    <div class="account-pages pt-2 pt-sm-5 pb-4 pb-sm-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-xxl-5 col-lg-6">
                    <div class="card">
                        <!-- Logo-->
                        <div class="card-header  text-center">
                            <a href="index.html">
                                <span><img src="{{ asset('assets/img/autocontrolLogo.png') }}" alt=""
                                        height="100"></span>
                            </a>
                        </div>

                        <div class="card-body p-4">
                            <div class="text-center w-75 m-auto mb-4">
                                <h4 class="text-dark-50 text-center mt-0 fw-bold">Registre Pago de la Plataforma</h4>
                            </div>
                            <form action="{{ route('registro-pago.store') }}" method="POST"
                                enctype="multipart/form-data" id="formularioRegistrarPago">
                                @csrf
                                <div class="mb-3">
                                    <strong class="text-danger">*</strong><label for="inputNumeroRuc"
                                        class="form-label">Ruc</label>
                                    <input type="text" class="form-control" data-toggle="input-mask"
                                        id="inputNumeroRuc" data-mask-format="00000000000" data-reverse="true"
                                        placeholder="Ingrese el número de Ruc válido" required name="inputNumeroRuc">
                                </div>

                                <div id="seccionRazonSocial" class="mb-3 d-none">
                                    <strong class="text-danger">*</strong><label for="inputNombreEmpresa"
                                        class="form-label">Razón Social</label>
                                    <input type="text" class="form-control" readonly id="inputNombreEmpresa"
                                        name="inputNombreEmpresa" placeholder="Ingrese el número de Ruc" required>
                                    <input hidden type="text" class="form-control" id="inputIdEmpresa"
                                        name="inputIdEmpresa">
                                    <input hidden type="text" class="form-control" id="inputCodigoCliente"
                                        name="inputCodigoCliente">
                                </div>

                                <section class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong class="text-danger">*</strong><label for="inputNumeroOperacion"
                                                class="form-label">Número de
                                                Operación</label>
                                            <input class="form-control disable-elemento" type="text"
                                                id="inputNumeroOperacion" placeholder="Ingrese el núm. de operación"
                                                required disabled name="inputNumeroOperacion">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <strong class="text-danger">*</strong><label for="inputMontoPago"
                                                class="form-label">Monto</label>
                                            <input type="text" id="inputMontoPago"
                                                class="form-control disable-elemento"
                                                placeholder="Ingrese el monto de Pago" name="inputMontoPago" required
                                                disabled>
                                        </div>
                                    </div>
                                </section>
                                <div class="mb-3 position-relative" id="inputFechaDeposito">
                                    <strong class="text-danger">*</strong><label class="form-label">Fecha del
                                        Depósito</label>
                                    <input type="text" class="form-control disable-elemento"
                                        data-provide="datepicker" data-date-autoclose="true" name="inputFechaDeposito"
                                        onkeydown="return false" data-date-end-date="0d" data-date-format="dd/mm/yyyy"
                                        placeholder="Ingrese la fecha en la que realizó el depósito." required
                                        disabled>
                                </div>
                                <div class="mb-3">
                                    <strong class="text-danger">*</strong><label for="inputNumeroCelular"
                                        class="form-label">Número Contacto</label>
                                    <input type="text" class="form-control disable-elemento"
                                        data-toggle="input-mask" id="inputNumeroCelular"
                                        data-mask-format="00000000000" data-reverse="true"
                                        placeholder="Ingrese un número de contacto" required disabled
                                        name="inputNumeroCelular">
                                </div>
                                <div class="mb-4">
                                    <label class="form-label">Cargar Imagen <strong>(Opcional)</strong></label>
                                    <input class="form-control disable-elemento" type="file" id="inputImagen"
                                        name="inputImagen" accept=".png, .jpg, .jpeg" disabled>
                                    <p class="text-muted">Formatos Aceptables: .png, .jpg, .jpeg</p>
                                </div>
                                <div class="text-center">
                                    <button id="btnSave" class="btn btn-primary disable-elemento" type="submit"
                                        disabled> Registrar Pago
                                    </button>
                                </div>
                            </form>
                        </div>
                        <!-- end card-body -->
                    </div>
                    <!-- end card -->
                </div> <!-- end col -->
            </div>
            <!-- end row -->
        </div>
        <!-- end container -->
    </div>
    <!-- end page -->

    <footer class="footer footer-alt">
        @php
            $fecha = new DateTime();
            $anioActual = $fecha->format('Y');
        @endphp
        Copyright @ 2019-{{ $anioActual }}. Todos los derechos reservados EASYFACTPERU SAC
    </footer>
    <!-- bundle -->
    <script src="{{ asset('assetsAdmin/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/app.min.js') }}"></script>

    <!-- plugin js -->
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>

    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>

    <!-- Para mostrar la sesiones con SweetAlert, jQuery debe estar cargado antes -->
    @if (session('success'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'success',
                    text: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 6000,
                    // scrollbarPadding: false,
                })
            });
        </script>
    @endif
    @if (session('error'))
        <script>
            $(document).ready(function() {
                Swal.fire({
                    icon: 'error',
                    text: '{{ session('error') }}',
                    showConfirmButton: false,
                    timer: 6000,
                    // scrollbarPadding: false,
                })
            });
        </script>
    @endif

    <script>
        $("#inputNumeroRuc").on("input", function() {
            $('#spanMensaje').text('');
            restaurarElementodDom();
            const inputRuc = $(this).val();
            console.log(inputRuc.length);
            if (inputRuc.length >= 11) {
                this.value = this.value.slice(0, 11);
                $.LoadingOverlay("show", {
                    image: '',
                    text: 'Espere un momento por favor...',
                    imageAnimation: '1.5s fadein',
                    textColor: "#f6851a",
                    textResizeFactor: '0.3',
                    textAutoResize: true
                });
                $.ajax({
                    type: 'get',
                    url: "{{ route('registro-pago.consulta-ruc') }}",
                    data: {
                        'numDoc': inputRuc
                    },
                    success: function(data) {
                        $.LoadingOverlay("hide");
                        if (data.respuesta == 'success') {
                            $('.disable-elemento').prop('disabled', false);
                            $('#seccionRazonSocial').removeClass('d-none');
                            $('#inputNombreEmpresa').val(data.empresa.NombreEmpresa);
                            $('#inputIdEmpresa').val(data.empresa.IdEmpresa);
                            $('#inputCodigoCliente').val(data.empresa.CodigoCliente);
                        } else {
                            restaurarElementodDom();
                            respuestaInfoAjax("Consulta no Disponible!", data.mensaje)
                        }
                    },

                });
            }
        });

        const restaurarElementodDom = () => {
            $('.disable-elemento').prop('disabled', true);
            $('#seccionRazonSocial').addClass('d-none');
            $('#inputNombreEmpresa').val('');
            $('#inputIdEmpresa').val('');
            $('#inputCodigoCliente').val('');
        }

        $('form').submit(function(e) {
            e.preventDefault(); // Prevenir el envío automático del formulario
            Swal.fire({
                text: "¿Están correctos los datos ingresados?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Sí, enviar',
                cancelButtonText: 'Cancelar',
                reverseButtons: true,
                scrollbarPadding: false,
                heightAuto: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $.LoadingOverlay("show", {
                        image: '',
                        text: 'Procesando el pago...',
                        imageAnimation: '1.5s fadein',
                        textColor: "#f6851a",
                        textResizeFactor: '0.3',
                        textAutoResize: true,
                    });
                    // Cuando las operaciones se completen, puedes enviar el formulario manualmente
                    $('#formularioRegistrarPago').off(
                        'submit'); // Desvincula el evento submit para evitar bucles infinitos
                    $('#formularioRegistrarPago').submit(); // Envía el formulario
                }
            });
        });

        // VALIDAR IMAGEN QUE ACEPTE FORMATOS PNG, JPG, JPNG
        $('#inputImagen').change(function() {
            const imagen = $(this).val();
            const extensionImagen = imagen.split('.').pop().toLowerCase();
            const extensionesPermitidas = ['png', 'jpg', 'jpeg']; // Lista de extensiones permitidas
            if (!extensionesPermitidas.includes(extensionImagen)) {
                $(this).val('');
                Swal.fire({
                    icon: "error",
                    title: "Formato Invalido",
                    text: "La imagen no cumple con el formato esperado (.png, .jpg, .jpeg)",
                });
            }
        })
    </script>
</body>

</html>
