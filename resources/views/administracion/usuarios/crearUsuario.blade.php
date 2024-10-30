@extends('layouts.app')
@section('title', 'Crear Usuario')
@section('content')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <!-- Head Libs -->
    <style>
        input::placeholder {
            color: black;
            font-size: 0.9em;
            font-style: italic;
        }
    </style>
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Crear Usuario</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
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
                            {!! Form::open([
                                'url' => '/administracion/usuarios',
                                'method' => 'POST',
                                'files' => true,
                                'class' => 'formularioConFirma',
                                'id' => 'myForm',
                            ]) !!}
                            {{ csrf_field() }}
                            {{-- SECCION DATOS DE USUARIO --}}
                            <fieldset class="fieldset fieldset--bordeCeleste">
                                <legend class="legend legend--colorNegro">Datos de Usuario:
                                </legend>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Nombres" type="text" name="nombre">
                                            <label for="nombre">Nombres</label>
                                            <span class="text-danger font-size">{{ $errors->first('nombre') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="form-control" name="sucursal">
                                                @if ($usuarioSelect->IdOperador == 1)
                                                    @foreach ($sucursales as $sucursal)
                                                        @if ($sucursal->CodigoCliente == null)
                                                            <option value="{{ $sucursal->IdSucursal }}">
                                                                {{ $sucursal->Nombre }}</option>
                                                        @endif
                                                    @endforeach
                                                @else
                                                    @foreach ($sucursales as $sucursal)
                                                        <option value="{{ $sucursal->IdSucursal }}">{{ $sucursal->Nombre }}
                                                        </option>
                                                    @endforeach
                                                @endif

                                            </select>
                                            <label for="sucursal">Sucursal</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            @if ($usuarioSelect->IdOperador == 1)
                                                <select class="form-control" name="operador">
                                                    <option value="2">Administrador</option>
                                                </select>
                                            @else
                                                <select class="form-control" name="operador">
                                                    @foreach ($operadores as $operador)
                                                        <option value="{{ $operador->IdOperador }}">{{ $operador->Rol }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            <label for="operador">Operador</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="DNI" type="text" name="dni">
                                            <label for="dni">DNI</label>
                                            <span class="text-danger font-size">{{ $errors->first('dni') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="email" type="text" name="email">
                                            <label for="email">Email</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="telefono" type="text"
                                                name="telefono">
                                            <label for="telefono">Teléfono</label>
                                            <span class="text-danger font-size">{{ $errors->first('telefono') }}</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Direcciòn" type="text"
                                                name="direccion">
                                            <label for="direccion">Dirección</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            @if ($usuarioSelect->IdOperador == 1)
                                                <input id="inputLogin" class="form-control" type="text" name="login">
                                            @else
                                                {{-- <input id="inputLogin" class="form-control" type="text" name="login"
                                                    oninput="validarLogin(this)" value="{{ $loginUser }}"
                                                    onclick="posicionarFocus(this)" data-toggle="tooltip"
                                                    data-placement="top" title="Ingrese solo nombre de Usuario">
                                                <input hidden class="form-control" type="text" name="formatoLogin"
                                                    value="{{ $loginUser }}"> --}}
                                                <input id="inputLogin" class="form-control" type="text" name="login"
                                                    oninput="validarLogin(this)" placeholder="{{ $loginUser }}"
                                                    onclick="posicionarFocus(this)" data-toggle="tooltip"
                                                    data-placement="top" title="Ingrese solo nombre de Usuario">
                                                <input hidden class="form-control" type="text" name="formatoLogin"
                                                    value="{{ $loginUser }}">
                                            @endif
                                            <label for="login">Login</label>
                                            <span class="text-danger font-size">{{ $errors->first('login') }}</span>
                                        </div>
                                    </div>
                                </div>

                                @if ($usuarioSelect->IdOperador == 1)
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input class="form-control" placeholder="Total Usuarios" type="number"
                                                    min="1" value="1" name="totalUsuarios">
                                                <label for="totalUsuarios">Total Usuarios</label>
                                            </div>
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <input class="form-control" placeholder="Total Sucursales" type="number"
                                                    min="1" value="1" name="totalSucursales">
                                                <label for="totalSucursales">Total Sucursales</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" name="rubro">
                                                    @foreach ($rubros as $rubro)
                                                        <option value="{{ $rubro->IdRubro }}">{{ $rubro->Descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="rubro">Rubro</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select class="form-control" name="selectPlanSuscripcion">
                                                    @foreach ($planesSuscripcion as $plan)
                                                        <option value="{{ $plan->IdPlanSuscripcion }}">
                                                            {{ $plan->Nombre }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                                <label for="selectPlanSuscripcion">PLANES DE SUSCRIPCIÓN</label>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </fieldset>
                            {{-- FIN --}}

                            {{-- SECCION HABILITAR PERMISOS --}}
                            <fieldset class="fieldset fieldset--bordeCeleste">
                                <legend class="legend legend--colorNegro">Habilitar Permisos:
                                </legend>
                                <div class="row">
                                    @if ($usuarioSelect->IdOperador == 1 || $usuarioSelect->EditarPrecio == 1)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editPrecio">Editar Precio</label><br>
                                                <input type="checkbox" name="editPrecio"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar / Desactivar</span>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Codigo para mostrar el boton de paquetes promocionales --}}
                                    @if ($usuarioSelect->IdOperador == 1)
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="exonerar">Exoneración</label><br>
                                                <input type="checkbox" name="exonerar"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Facturación Exonerado</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="codigoProducto">Codigo Producto</label><br>
                                                <input type="checkbox" name="codigoProducto"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Enlazar Codigo de Barra</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editPrecio">Activar Paquete Promocional</label><br>
                                                <input type="checkbox" name="activarPaquetePromo"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar / Desactivar</span>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editPrecio">Activar Importar Excel Clientes</label><br>
                                                <input type="checkbox" name="activarImportacionExcelClientes"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar / Desactivar</span>
                                            </div>
                                        </div>
                                    @endif
                                    {{-- Fin --}}
                                </div>
                                @if ($usuarioSelect->IdOperador == 1)
                                    <div class="row">
                                        <div class="col-sm-4 mb-3">
                                            <h5 class="box-title mr-b-0">Facturación</h5>
                                            <div class="form-group">
                                                <div class="radiobox">
                                                    <label>
                                                        <input type="radio" name="radioOpcion" value="0"
                                                            {{ $usuarioSelect->OpcionFactura == 0 ? 'checked' : '' }}>
                                                        <span class="label-text">Desactivado</span>
                                                    </label>
                                                </div>
                                                <!-- /.radiobox -->
                                                <div class="radiobox radio-success">
                                                    <label>
                                                        <input type="radio" name="radioOpcion" value="1"
                                                            {{ $usuarioSelect->OpcionFactura == 1 ? 'checked' : '' }}>
                                                        <span class="label-text">Con Sunat</span>
                                                    </label>
                                                </div>
                                                <!-- /.radiobox -->
                                                <div class="radiobox radio-info">
                                                    <label>
                                                        <input type="radio" name="radioOpcion" value="2"
                                                            {{ $usuarioSelect->OpcionFactura == 2 ? 'checked' : '' }}>
                                                        <span class="label-text">Con OSE</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        {{-- Nuevo codigo asignar modulos --}}
                                        <div class="col-md-8">
                                            <div class="form-group">
                                                <label for="modulos">Módulos</label>
                                                <select class="selectpicker form-control" name="modulos[]"
                                                    multiple="multiple" data-style="btn btn-primary">
                                                    @foreach ($modulosDelSistema as $modulo)
                                                        <option value="{{ $modulo->IdModulo }}">{{ $modulo->IdModulo }} -
                                                            {{ $modulo->Descripcion }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        {{-- fin --}}
                                    </div>
                                @endif
                            </fieldset>
                            {{-- FIN --}}
                            {{-- SECCION RESTRICCIONES --}}
                            @if ($usuarioSelect->IdOperador != 1)
                                <x-fieldset :legend="'Restricciones'" :legendClass="'px-2'">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="descuentoMaximoSoles"
                                                    data-toggle="validarOnInput" data-numero-entero="true"
                                                    data-maximo-digitos="4">
                                                <label for="descuentoMaximoSoles">Descuento máximo X items (S/)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control validacionOnInput" type="text"
                                                    name="descuentoMaximoDolares" data-toggle="validarOnInput"
                                                    data-numero-entero="true" data-maximo-digitos="4">
                                                <label for="descuentoMaximoDolares">Descuento máximo X items ($)</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="editPrecio">Operacion
                                                    gratuita</label><br>
                                                <input type="checkbox" name="operacionGratuita"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar /
                                                    Desactivar</span>

                                            </div>
                                        </div>
                                    </div>
                                </x-fieldset>
                            @endif
                            {{-- FIN --}}
                            {{-- SECCION CARGAR FOTO Y FIRMA --}}
                            @if ($usuarioSelect->IdOperador != 1)
                                <fieldset class="fieldset fieldset--bordeCeleste">
                                    <legend class="legend legend--colorNegro">Cargar Foto:
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="fileUpload btnCambiarFotoPerfil">
                                                    <i class="list-icon material-icons">image</i><span
                                                        class="hide-menu">Cargar
                                                        Foto</span>
                                                    <input id="archivo" class="upload btn" type="file"
                                                        accept=".png, .jpg, .jpeg, .gif" name="foto">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <img id="imgPrevia" src="" alt="Vista de Foto" width="100%" />
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>
                                <section class="col-12 mt-4">
                                    <hr>
                                    @include('lienzoFirma.lienzoFirma')
                                </section>
                            @endif
                            {{-- FIN --}}

                            <input class="form-control" type="text" hidden name="IdOperadorUsuario"
                                value="{{ $usuarioSelect->IdOperador }}">

                            {{-- SECCION SUSCRIPCION --}}
                            @if ($usuarioSelect->IdOperador == 1)
                                <fieldset class="fieldset fieldset--bordeCeleste">
                                    <legend class="legend legend--colorNegro">Datos suscripcion:
                                    </legend>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <select id="plan" class="form-control" name="plan">
                                                    <option value="1">Mensual</option>
                                                    <option value="2">Semestral</option>
                                                    <option value="3">Anual</option>
                                                </select>
                                                <label for="sucursal">Plan</label>
                                            </div>
                                        </div>

                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input id="datepicker2" type="text"
                                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                        class="form-control datepicker" name="fechaContrato">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text"><i
                                                                class="list-icon material-icons">date_range</i></div>
                                                    </div>
                                                </div>
                                                <small class="text-muted"><strong>Fecha Fin Contrato</strong></small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <div class="input-group">
                                                    <input id="datepicker2" type="text"
                                                        data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                        class="form-control datepicker" name="fechaCDT">
                                                    <div class="input-group-append">
                                                        <div class="input-group-text"><i
                                                                class="list-icon material-icons">date_range</i></div>
                                                    </div>
                                                </div>
                                                <small class="text-muted"><strong>Fecha Fin CDT</strong></small>
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input class="form-control" type="number" min="1" step="any"
                                                    name="montoPago" value="">
                                                <label for="montoPago">Monto de Pago</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input class="form-control" type="number" min="1" name="bloqueo"
                                                    value="">
                                                <label for="bloqueo">Días Bloqueo</label>
                                            </div>
                                        </div>
                                    </div>
                                </fieldset>

                                <fieldset class="fieldset fieldset--bordeCeleste">
                                    <legend class="legend legend--colorNegro">Inicio Comprobantes:</legend>
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="fs-15">Nota de Crédito Factura</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="serie[]" value="FC01"
                                                    readonly>
                                                <label for="serieNC">Serie</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="correlativo[]"
                                                    placeholder="ejem.: 00000001" value="">
                                                <label for="correlativoNC">Correlativo</label>
                                            </div>
                                        </div>
                                        <input class="form-control" type="text" name="tipoComprobante[]"
                                            value="1" hidden>
                                    </div>
                                    <div class="row align-items-center">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label class="fs-15">Nota de Crédito Boleta</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">

                                                <input class="form-control" type="text" name="serie[]" value="BC01"
                                                    readonly>
                                                <label for="serieNC">Serie</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="correlativo[]"
                                                    placeholder="ejem.: 00000001" value="">
                                                <label for="correlativoNC">Correlativo</label>
                                            </div>
                                        </div>
                                        <input class="form-control" type="text" name="tipoComprobante[]"
                                            value="2" hidden>
                                    </div>
                                </fieldset>
                            @endif
                            {{-- FIN --}}
                            <br><br>
                            <x-notaInformativa color='info' icono='bxs-info-circle'>
                                <h5 class="mt-2 fs-15 fw-400 m-0 blockquote-footer">La contraseña asignado para un
                                    nuevo Usuario es: <span class="fw-600">*easyfactperu* (La contraseña es con asteriscos
                                        en los extremos)</span>.
                                </h5>
                                <h5 class="mt-2 fs-15 fw-400 m-0 blockquote-footer">Al ingresar al Panel Principal se le
                                    pedirá que haga el cambio de contraseña obligatorio para mayor seguridad.
                                </h5>
                            </x-notaInformativa>
                            {{-- SECCION BOTONES --}}
                            <div class="form-actions btn-list mt-3 d-flex justify-content-end">
                                {{-- <x-buttonLoader>
                                    @slot('textoBoton', 'Crear usaurio')
                                    @slot('textoLoader', 'Registrando')
                                </x-buttonLoader> --}}
                                <button class="btn btn-primary" type="submit">Crear</button>
                                <a href="../usuarios"><button class="btn btn-outline-default"
                                        type="button">Cancelar</button></a>
                            </div>
                            {{-- FIN --}}
                            {!! Form::close() !!}
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
    <!-- /.container -->
@stop

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/lienzoFirma/lienzoFirma.js?v=' . time()) }}"></script>
    <!--<script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>-->
    <script>
        // document.addEventListener('DOMContentLoaded', function(e) {
        //     const form = document.querySelector('#myForm'); // Cambia esto al ID de tu formulario
        //     form.addEventListener('submit', function() {
        //         $('.btnCrearUsuario').attr('disabled', true);
        //         $('#btnTexto').addClass('d-none')
        //         $('#seccionLoader').removeClass('d-none');
        //     });
        // });
        // $(document).on('click', function(e) {
        //     if (e.target.matches('.btnLoader') || e.target.matches('.btnLoader *')) {
        //         const elementoPadre = $(e.target).closest('.btnCrearUsuario');
        //         showButtonLoader(e);
        //         // $('form').submit();
        //     }
        // });


        $(document).on('click', function(e) {
            if (e.target.matches('.btnLoader') || e.target.matches('.btnLoader *')) {
                showButtonLoader(e);
                if ($('#inputLogin').val() == '') {
                    hideButtonLoader(e);
                    $('#inputLogin').addClass('border border-danger');
                    Swal.fire({
                        icon: 'error',
                        text: 'Por favor, debe ingresar el Login!',
                    });
                    return false;
                }
                $('form').submit();
            }
        });
    </script>
    <script>
        const login = @json($loginUser);
        const regExp = @json($expreReg);

        function validarLogin(e) {
            if (!e.value.match(regExp)) {
                e.value = login;
                e.setSelectionRange(0, 0);
            }
        }

        function posicionarFocus(e) {
            const inputLogin = e.value;
            const tamañoTexto = login.length;
            if (inputLogin.length <= tamañoTexto) {
                e.setSelectionRange(0, 0);
            }
        }
    </script>
    <script>
        $("#archivo").change(function(e) {
            addImage(e);
        });

        function addImage(e) {
            var file = e.target.files[0],
                imageType = /image.*/;

            if (!file.type.match(imageType)) return;

            var reader = new FileReader();
            reader.onload = fileOnload;
            reader.readAsDataURL(file);
        }

        function fileOnload(e) {
            var result = e.target.result;
            $("#imgPrevia").attr("src", result);
        }
    </script>

    {{-- <script>
        const inputLogin = document.getElementById("inputLogin");
        const login = @json($loginUser);
        const patron = /\.[a-z.@]+$/;
        const resul = patron.exec(login);

        $(function() {
            inputLogin.value = resul[0];
        })


        function validarLogin(e) {
            let patro = new RegExp('.' + resul + "$")
            if (!e.value.match(patro)) {
                e.value = resul;
                e.setSelectionRange(0, 0);
            }
        }
    </script> --}}

@stop
