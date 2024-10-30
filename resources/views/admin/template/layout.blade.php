<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>Dashboard</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="ERP Especializado para Talleres mecánicos" name="description" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="assets/images/favicon.ico">

    <!-- third party css -->
    <link href="{{ asset('assetsAdmin/css/vendor/jquery-jvectormap-1.2.2.css') }}" rel="stylesheet" type="text/css">
    <!-- third party css end -->

    <!-- App css -->
    <link href="{{ asset('assetsAdmin/css/icons.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assetsAdmin/css/app.min.css') }}" rel="stylesheet" type="text/css" id="light-style">

    <!-- Datatables css -->
    <link href="{{ asset('assetsAdmin/css/vendor/dataTables.bootstrap5.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assetsAdmin/css/vendor/responsive.bootstrap5.css') }}" rel="stylesheet" type="text/css">
    <!-- Estilos personales css -->
    <link href="{{ asset('assetsAdmin/css/estilos-personalizados.css?v=' . time()) }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assetsAdmin/css/estilos-nuevos.css?v=' . time()) }}" rel="stylesheet" type="text/css">

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
    <!-- Begin page -->
    <div class="wrapper">
        <!-- ========== Left Sidebar Start ========== -->
        @include('admin.template.header-lateral')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start Page Content here -->
        <!-- ============================================================== -->

        <div class="content-page">
            <div class="content">
                <!-- Topbar Start -->
                @include('admin.template.header-superior')
                <!-- end Topbar -->

                <!-- Start Content-->
                <div class="container-fluid">

                    @yield('contenido')

                </div>
                <!-- container -->

            </div>
            <!-- content -->

            <!-- Footer Start -->
            <footer class="footer">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-6">
                            <script>
                                document.write(new Date().getFullYear())
                            </script> © Hyper - Coderthemes.com
                        </div>
                        <div class="col-md-6">
                            <div class="text-md-end footer-links d-none d-md-block">
                                <a href="javascript: void(0);">About</a>
                                <a href="javascript: void(0);">Support</a>
                                <a href="javascript: void(0);">Contact Us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
            <!-- end Footer -->

        </div>

        <!-- ============================================================== -->
        <!-- End Page content -->
        <!-- ============================================================== -->


    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <div class="end-bar">

        <div class="rightbar-title">
            <a href="javascript:void(0);" class="end-bar-toggle float-end">
                <i class="dripicons-cross noti-icon"></i>
            </a>
            <h5 class="m-0">Settings</h5>
        </div>

        <div class="rightbar-content h-100" data-simplebar="">

            <div class="p-3">
                <div class="alert alert-warning" role="alert">
                    <strong>Customize </strong> the overall color scheme, sidebar menu, etc.
                </div>

                <!-- Settings -->
                <h5 class="mt-3">Color Scheme</h5>
                <hr class="mt-1">

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="light"
                        id="light-mode-check" checked="">
                    <label class="form-check-label" for="light-mode-check">Light Mode</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="color-scheme-mode" value="dark"
                        id="dark-mode-check">
                    <label class="form-check-label" for="dark-mode-check">Dark Mode</label>
                </div>


                <!-- Width -->
                <h5 class="mt-4">Width</h5>
                <hr class="mt-1">
                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="width" value="fluid" id="fluid-check"
                        checked="">
                    <label class="form-check-label" for="fluid-check">Fluid</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="width" value="boxed" id="boxed-check">
                    <label class="form-check-label" for="boxed-check">Boxed</label>
                </div>


                <!-- Left Sidebar-->
                <h5 class="mt-4">Left Sidebar</h5>
                <hr class="mt-1">
                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="theme" value="default"
                        id="default-check">
                    <label class="form-check-label" for="default-check">Default</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="theme" value="light" id="light-check"
                        checked="">
                    <label class="form-check-label" for="light-check">Light</label>
                </div>

                <div class="form-check form-switch mb-3">
                    <input class="form-check-input" type="checkbox" name="theme" value="dark" id="dark-check">
                    <label class="form-check-label" for="dark-check">Dark</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="fixed" id="fixed-check"
                        checked="">
                    <label class="form-check-label" for="fixed-check">Fixed</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="condensed"
                        id="condensed-check">
                    <label class="form-check-label" for="condensed-check">Condensed</label>
                </div>

                <div class="form-check form-switch mb-1">
                    <input class="form-check-input" type="checkbox" name="compact" value="scrollable"
                        id="scrollable-check">
                    <label class="form-check-label" for="scrollable-check">Scrollable</label>
                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-primary" id="resetBtn">Reset to Default</button>

                    <a href="../../product/hyper-responsive-admin-dashboard-template/index.htm"
                        class="btn btn-danger mt-3" target="_blank"><i class="mdi mdi-basket me-1"></i> Purchase
                        Now</a>
                </div>
            </div> <!-- end padding-->

        </div>
    </div>

    <div class="rightbar-overlay"></div>
    <!-- /End-bar -->

    <!-- bundle -->
    <script src="{{ asset('assetsAdmin/js/vendor.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/app.min.js') }}"></script>
    <!-- third party js -->
    <script src="{{ asset('assetsAdmin/js/vendor/apexcharts.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/vendor/jquery-jvectormap-1.2.2.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/vendor/jquery-jvectormap-world-mill-en.js') }}"></script>
    <!-- third party js ends -->

    <!-- demo app -->
    <script src="{{ asset('assetsAdmin/js/pages/demo.dashboard.js') }}"></script>
    <!-- end demo js-->
    <!-- Datatables js -->
    <script src="{{ asset('assetsAdmin/js/vendor/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/vendor/dataTables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/vendor/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('assetsAdmin/js/vendor/responsive.bootstrap5.min.js') }}"></script>

    <!-- Datatable Init js -->
    {{-- <script src="{{ asset('assetsAdmin/js/pages/demo.datatable-init.js') }}"></script> --}}
    <!-- Plugins js -->
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <!-- Script Funciones -->
    <script src="{{ asset('assets/js/scriptGlobal/loadingOverlay.js') }}"></script>
    <script src="{{ asset('assets/js/scriptGlobal/validarFormulario.js?=v' . time()) }}"></script>
    <script>
        // ESTA FUNCION RECIBE UN OBJETO DE PARAMETROS
        const showLoaderMessage = ({
            color = "#f6851a",
            size = "20px",
            mensaje = ''
        }) => {
            $.LoadingOverlay("show", {
                image: '',
                custom: function() {
                    // Crea un div personalizado para el fondo
                    // var backgroundDiv = $("<div></div>");
                    // backgroundDiv.css({
                    //     position: "fixed",
                    //     top: 0,
                    //     left: 0,
                    //     width: "100%",
                    //     height: "100%",
                    //     backgroundColor: "#FBFDFE" // Cambia aquí el color de fondo
                    // });
                    // // Agrega el div personalizado al overlay
                    // $(this).append(backgroundDiv);
                    // Crea un div personalizado para mostrar el mensaje
                    var customOverlay = $("<div></div>");
                    customOverlay.css({
                        position: "absolute",
                        top: "50%",
                        left: "50%",
                        transform: "translate(-50%, -50%)",
                        textAlign: "center",
                        "font-size": size,
                        "color": color,
                    });
                    // Agrega el mensaje personalizado
                    customOverlay.append(
                        `<div>${mensaje}</div>`
                    );
                    // Agrega el div personalizado al overlay
                    $(this).append(customOverlay);
                }
            });
        }
    </script>
    @yield('scripts')
</body>

</html>
