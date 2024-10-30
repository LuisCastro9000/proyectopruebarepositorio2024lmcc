<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Cotizacion Generada</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css">

    <link rel="stylesheet" href="{{ asset('assets/css/newStyles.css?v=' . time()) }}">
    {{-- iconos --}}
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">

    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/datatables.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <!-- Head Libs -->
    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>
</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">
        <!-- HEADER & TOP NAVIGATION -->
        @include('schemas.schemaHeader')
        <!-- /.navbar -->
        <div class="content-wrapper">
            <!-- SIDEBAR -->
            <aside class="site-sidebar clearfix">
                <div class="container">
                    @include('schemas.schemaSideNav')
                </div>
                <!-- /.container -->
            </aside>
            <!-- /.site-sidebar -->
            <main class="main-wrapper clearfix">
                <!-- Page Title Area -->
                <div class="container">
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Detalle de Orden de Trabajo</h6>
                        </div>
                        <!-- /.page-title-left -->
                        <div class="page-title-right">
                            <div class="row mr-b-50 mt-2">
                                <div class="col-6 mr-b-20 d-flex">
                                    <a class="p-1"
                                        href="../../cotizacion/descargar-orden/{{ $cotizacionSelect->IdCotizacion }}"
                                        target="_blank"><button class="btn btn-block btn-success ripple"><i
                                                class="list-icon material-icons fs-20">picture_as_pdf</i>Orden de
                                            Servicio</button></a>
                                </div>
                            </div>
                        </div>
                    </div>
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
                                    <div class="widget-body clearfix">
                                        <div class="ecommerce-invoice">
                                            <x-fieldset :legend="$cotizacionSelect->Serie . '-' . $numeroCeroIzq" :legendClass="'px-2'">
                                                <section class="row">
                                                    <article class="col-12 col-sm-6">
                                                        <strong>NOMBRE/RAZ. SOCIAL:</strong>
                                                        {{ $cotizacionSelect->RazonSocial }}
                                                        <br>
                                                        <strong>Operario:</strong> {{ $operario }} <br>
                                                        <strong>Placa: </strong> {{ $vehiculo->PlacaVehiculo }}
                                                        <br>
                                                        <strong>Marca: </strong> {{ $vehiculo->NombreMarca }} <br>
                                                    </article>
                                                    <article class="col-12 col-sm-6">
                                                        <strong>Modelo: </strong> {{ $vehiculo->NombreModelo }}
                                                        <br>
                                                        <strong>Kilometraje: </strong>
                                                        {{ $cotizacionSelect->Campo1 }} <br>
                                                        <strong>Año: </strong> {{ $vehiculo->Anio }} <br>
                                                        <strong>Tipo Atención: </strong>
                                                        {{ $cotizacionSelect->Atencion }}
                                                    </article>
                                                </section>
                                            </x-fieldset>
                                            <!-- /.row -->
                                            <hr class="border-0">
                                            <!-- /.row -->
                                        </div>
                                        <!-- /.ecommerce-invoice -->
                                    </div>
                                    <!-- /.widget-body -->
                                </div>
                                <!-- /.widget-bg -->
                            </div>
                            <!-- /.widget-holder -->
                        </div>
                        <div class="widget-bg">
                            <div id="alertaEstado" class="pt-2">
                                <span class="text-danger">NOTA: Antes de pasar al siguiente estado, debe reponer el
                                    stock de los productos de la cotización</span>
                            </div>

                            <div class="widget-body clearfix">
                                <h6>Estados</h6>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div
                                            class="{{ $cotizacionSelect->IdEstadoCotizacion == 1 ? 'flecha-abierto' : 'flecha-disabled' }} text-center">
                                            <span class="text-black">Abierto</span>
                                            <p class="text-black fs-12">
                                                {{ $usuarioAbierto == null ? '' : $usuarioAbierto->Nombre }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="{{ $cotizacionSelect->IdEstadoCotizacion == 2 ? 'flecha-enproceso' : 'flecha-disabled' }} text-center">
                                            <span class="text-black">En Proceso</span>
                                            <p class="text-black fs-12">
                                                {{ $usuarioEnProceso == null ? '' : $usuarioEnProceso->Nombre }}</p>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="{{ $cotizacionSelect->IdEstadoCotizacion == 3 ? 'flecha-finalizado' : 'flecha-disabled' }} text-center">
                                            <span class="text-black">Finalizado</span>
                                            <p class="text-black fs-12">
                                                {{ $usuarioFinalizado == null ? '' : $usuarioFinalizado->Nombre }}</p>

                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="{{ $cotizacionSelect->IdEstadoCotizacion == 4 ? 'flecha-cerrado' : 'flecha-disabled' }} text-center">
                                            <span class="text-black">Cerrado</span>
                                            <p class="text-black fs-12">
                                                {{ $usuarioCerrado == null ? '' : $usuarioCerrado->Nombre }}</p>
                                        </div>
                                    </div>
                                </div>
                                <input hidden name="estadoCotizacion"
                                    value="{{ $cotizacionSelect->IdEstadoCotizacion }}">

                                {{-- Nuevo codigo --}}
                                @if ($cotizacionSelect->TipoCotizacion == 2 && $cotizacionSelect->IdEstadoCotizacion == 1)
                                    <br><br>
                                    <x-fieldset>
                                        <span class="text-danger">Para llevar a cabo el
                                            cambio de estado, es fundamental seleccionar la fecha de finalización de la
                                            atención. Esta acción se realiza con el propósito de monitorear el tiempo de
                                            entrega del vehículo y la productividad de las atenciones.
                                        </span>
                                        <br>
                                        <h5 class="mt-2 fs-15 fw-400 m-0 blockquote-footer">Tipo atención actual: <span
                                                class="fw-600 text-uppercase">{{ $cotizacionSelect->Atencion }}</span>
                                        </h5>
                                        <div class="form-group mt-3">
                                            <div class="input-group">
                                                <div class="input-group-append">
                                                    <div class="input-group-text"><i
                                                            class="list-icon material-icons">date_range</i></div>
                                                </div>
                                                <input id="inputFechaFinAtencion" type="text"
                                                    class="form-control datepicker" name="inputFechaFinAtencion"
                                                    data-plugin-options="{&quot;autoclose&quot;: true, &quot;format&quot;: &quot;dd/mm/yyyy&quot;}"
                                                    autocomplete="off" onkeydown="return false"
                                                    data-date-start-date="0d" data-date-end-date="+6m"
                                                    required="">
                                            </div>
                                        </div>
                                    </x-fieldset>
                                @endif
                                {{-- Fin --}}
                                @if ($cotizacionSelect->IdEstadoCotizacion == 1 || $cotizacionSelect->IdEstadoCotizacion == 2)
                                    @if ($ventasRealizadas > 0)
                                        <div><span class="text-danger">La cotización ya tiene comprobantes generados.
                                                Completar las ventas para cambiar de Estado</span></div>
                                    @else
                                        <div class="form-actions btn-list mt-4">
                                            {{-- <a href="#" data-toggle="modal"
                                                data-target=".bs-modal-lg-estados"><button id="btnGenerar"
                                                    class="btn btn-primary">Siguiente Estado</button></a> --}}
                                            <button id="btnGenerar" class="btn btn-primary">Siguiente Estado</button>
                                        </div>
                                    @endif
                                @endif
                            </div>

                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.widget-list -->
                </div>
                <!-- /.container -->
                <div id="modalCofirmarActualizacionEstado" class="modal fade bs-modal-lg-estados" tabindex="-1"
                    role="dialog" aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        {!! Form::open(['url' => '/operaciones/cotizacion/actualizar-estados', 'method' => 'POST', 'id' => 'myform']) !!}
                        {{ csrf_field() }}
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6>Pasar a siguiente estado</h6>
                            </div>
                            <div class="modal-body form-material">
                                <div>
                                    <label class="fs-12 negrita">¿Desea continuar al siguiente estado?</label>
                                </div>
                                @if ($cotizacionSelect->IdEstadoCotizacion == 2)
                                    <div>
                                        Por favor, ten en cuenta que una vez que el estado se haya cambiado a
                                        "finalizado", no será posible revertirlo. Te solicitamos que aceptes solo si el
                                        vehículo está listo para ser retirado, ya que no se podrán agregar más productos
                                        o servicios a las cotizaciones.
                                    </div>
                                @endif
                            </div>
                            <input value="{{ $cotizacionSelect->IdCotizacion }}" name="idCotizacion" hidden>
                            <input value="{{ $cotizacionSelect->IdEstadoCotizacion }}" name="estadoCotizacion"
                                hidden>
                            <input id="inputFechaFinAtencionModal" type="hidden" name="inputFechaFinAtencion">
                            <div class="modal-footer">
                                <div class="form-actions btn-list mt-3">
                                    <button id="btnAceptar" class="btn btn-info" type="submit">Aceptar</button>
                                    <button class="btn btn-default" type="button"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </main>
            <!-- /.main-wrappper -->

        </div>
        <!-- /.content-wrapper -->
        <!-- FOOTER -->
        @include('schemas.schemaFooter')
    </div>
    <!--/ #wrapper -->
    <!-- Scripts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <script>
        $(function() {
            $("#alertaEstado").hide();
            var items = <?php echo json_encode($items); ?>;
            var idEstadoCotizacion = <?php echo json_encode($IdEstadoCotizacion); ?>;
            if (idEstadoCotizacion == 1) {
                for (var i = 0; i < items.length; i++) {
                    if (parseInt(items[i]["IdTipo"], 10) == 1) {
                        //productoEnTabla(items[i]["IdArticulo"], items[i]["Descripcion"], items[i]["TextUnidad"], precio, items[i]["Cantidad"], parseFloat(items[i]["Descuento"]), parseFloat(items[i]["Costo"]), parseFloat(items[i]["Stock"]), items[i]["IdUnidadMedida"], tipoVenta);
                        if (parseFloat(items[i]["Cantidad"]) > parseFloat(items[i]["Stock"])) {
                            alert("Sobrepaso el límite de stock del producto : " + items[i]["Descripcion"]);
                            $("#btnGenerar").prop("disabled", true);
                            $("#alertaEstado").show();
                        } else {}
                    }
                }
            }


            $('#btnGenerar').on('click', () => {
                const fechaFinAtencion = $('#inputFechaFinAtencion').val();
                if (fechaFinAtencion === '') {
                    swal("Error", "Debe ingresar la fecha de finalización de la atención");
                } else {
                    $('#inputFechaFinAtencionModal').val(fechaFinAtencion);
                    $('#modalCofirmarActualizacionEstado').modal('show');
                }
            })

            $('#btnAceptar').on('click', function() {
                var myForm = $("form#myform");
                if (myForm) {
                    $(this).attr('disabled', true);
                    $(myForm).submit();
                    $(".bs-modal-lg-estados").modal('hide');
                    $.LoadingOverlay("show", {
                        image: '../../../assets/img/logo1.png',
                        text: 'Se están procesando los datos, esperar un momento...',
                        imageAnimation: '1.5s fadein',
                        textResizeFactor: '0.3',
                        textAutoResize: true
                    });
                }
            });
        });
    </script>
</body>

</html>
