<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Configurar Empresa</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

    <link rel="stylesheet" href="{{ asset('assets/css/newStyles.css?v=' . time()) }}">
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
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Configurar Datos de Empresa</h6>
                        </div>
                        <!-- /.page-title-left -->
                        @if ($modulosSelect->contains('IdModulo', 4))
                            <div class="page-title-right">
                                <div class="row mr-b-50 mt-2">
                                    <div class="col-12 mr-b-20 d-sm-block d-none">
                                        <!--<a href="../almacen/descargar-formato"><button class="btn btn-info" type="button"><i class="list-icon material-icons fs-22">file_download</i>Descargar Formato</button></a>-->
                                        <a href="#" data-toggle="modal" data-target=".bs-modal-sm-importar"
                                            onclick="abrirModal()"><button class="btn btn-block btn-primary ripple"><i
                                                    class="list-icon material-icons fs-28">file_upload</i> Subir Cert.
                                                Dig.</button></a>
                                    </div>
                                    <div class="col-12 mr-b-20 d-sm-none d-block">
                                        <a href="#" data-toggle="modal" data-target=".bs-modal-sm-importar"
                                            onclick="abrirModal()"><button class="btn btn-block btn-primary ripple"><i
                                                    class="list-icon material-icons fs-28">file_upload</i></button></a>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </div>
                    <!-- /.page-title -->

                </div>
                <!-- /.container -->
                <!-- =================================== -->
                <!-- Different data widgets ============ -->
                <!-- =================================== -->
                <div class="container">
                    <div class="widget-list">
                        {!! Form::open(['url' => 'actualizando-empresa', 'method' => 'POST', 'files' => true]) !!}
                        {{ csrf_field() }}
                        <div class="row form-material">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="nombreEmpresa"
                                        value="{{ $datosEmpresa->Nombre }}">
                                    <label for="nombreEmpresa">Razón Social</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="rucEmpresa"
                                        value="{{ $datosEmpresa->Ruc }}">
                                    <label for="rucEmpresa">RUC</label>
                                </div>
                            </div>
                            <div class="col-md-4" hidden>
                                <div class="form-group">
                                    <input class="form-control" type="text" name="ciudadEmpresa"
                                        value="{{ $datosEmpresa->Ciudad }}">
                                    <label for="ciudadEmpresa">CIUDAD</label>
                                </div>
                            </div>
                        </div>

                        <div class="row form-material">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="comercialEmpresa"
                                        value="{{ $datosEmpresa->NombreComercial }}">
                                    <label for="comercialEmpresa">Nombre Comercial</label>
                                </div>
                            </div>
                            @if ($modulosSelect->contains('IdModulo', 4))
                                @if ($usuarioSelect->IdOperador == 1 || $usuarioSelect->IdOperador == 2)
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" type="text" name="usuarioSol"
                                                data-toggle="tooltip" data-placement="top"
                                                title="No modificar Usuario Sol"
                                                value="{{ $datosEmpresa->UsuarioSol }}">
                                            <label for="usuarioSol">USUARIO SOL</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" data-toggle="tooltip" data-placement="top"
                                                title="No modificar Clave Sol"
                                                {{ $datosEmpresa->ClaveSol != null || $datosEmpresa->ClaveSol != '' ? 'type=password' : 'type=text' }}
                                                name="claveSol" value="{{ $datosEmpresa->ClaveSol }}">
                                            <label for="claveSol">CLAVE SOL</label>
                                        </div>
                                    </div>
                                    @if ($subniveles->contains('IdSubNivel', 11))
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" type="text" name="clientId"
                                                    {{ $datosEmpresa->Client_Id != null || $datosEmpresa->Client_Id != '' ? 'readonly' : '' }}
                                                    data-toggle="tooltip" data-placement="top"
                                                    title="No modificar Client ID"
                                                    value="{{ $datosEmpresa->Client_Id }}">
                                                <label for="clientId">Client ID</label>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <input class="form-control" name="clientSecret" data-toggle="tooltip"
                                                    data-placement="top"
                                                    {{ $datosEmpresa->Client_Secret != null || $datosEmpresa->Client_Secret != '' ? 'type=password readonly' : 'type=text' }}
                                                    title="No modificar Client Secret"
                                                    value="{{ $datosEmpresa->Client_Secret }}">
                                                <label for="clientSecret">Client Secret</label>
                                            </div>
                                        </div>
                                    @endif
                                @else
                                    <input class="form-control" type="hidden" name="usuarioSol"
                                        data-toggle="tooltip" data-placement="top" title="No modificar Usuario Sol"
                                        value="{{ $datosEmpresa->UsuarioSol }}">
                                    <input class="form-control" type="hidden" data-toggle="tooltip"
                                        data-placement="top" title="No modificar Clave Sol"
                                        {{ $datosEmpresa->ClaveSol != null || $datosEmpresa->ClaveSol != '' ? 'type=password' : 'type=text' }}
                                        name="claveSol" value="{{ $datosEmpresa->ClaveSol }}">
                                    <input class="form-control" type="hidden" name="clientId"
                                        {{ $datosEmpresa->Client_Id != null || $datosEmpresa->Client_Id != '' ? 'readonly' : '' }}
                                        data-toggle="tooltip" data-placement="top" title="No modificar Client ID"
                                        value="{{ $datosEmpresa->Client_Id }}">
                                    <input class="form-control" type="hidden" name="clientSecret"
                                        data-toggle="tooltip" data-placement="top"
                                        {{ $datosEmpresa->Client_Secret != null || $datosEmpresa->Client_Secret != '' ? 'type=password readonly' : 'type=text' }}
                                        title="No modificar Client Secret"
                                        value="{{ $datosEmpresa->Client_Secret }}">
                                @endif
                            @endif
                        </div>

                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="departamento">Departamento</label>
                                    <select id="departamento" class="form-control" name="departamento">
                                        <option value="0">-</option>
                                        @foreach ($departamentos as $departamento)
                                            @if ($datosEmpresa->IdDepartamento == $departamento->IdDepartamento)
                                                <option selected value="{{ $departamento->IdDepartamento }}">
                                                    {{ $departamento->Nombre }}</option>
                                            @else
                                                <option value="{{ $departamento->IdDepartamento }}">
                                                    {{ $departamento->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="provincia">Provincia</label>
                                    <select id="provincia" class="form-control" name="provincia">
                                        <option value="0">-</option>
                                        @foreach ($provincias as $provincia)
                                            @if ($datosEmpresa->IdProvincia == $provincia->IdProvincia)
                                                <option selected value="{{ $provincia->IdProvincia }}">
                                                    {{ $provincia->Nombre }}</option>
                                            @else
                                                <option value="{{ $provincia->IdProvincia }}">
                                                    {{ $provincia->Nombre }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="distrito">Distrito</label>
                                    <select id="distrito" class="form-control" name="distrito">
                                        <option value="0">-</option>
                                        @foreach ($distritos as $distrito)
                                            @if ($datosEmpresa->Ubigeo == $distrito->IdDistrito)
                                                <option selected value="{{ $distrito->IdDistrito }}">
                                                    {{ $distrito->Nombre }}</option>
                                            @else
                                                <option value="{{ $distrito->IdDistrito }}">{{ $distrito->Nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row form-material mt-4">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <input class="form-control" type="text" name="descripcion"
                                        value="{{ $datosEmpresa->Descripcion }}" maxlength="100">
                                    <label for="telefonoEmpresa">DESCRIPCIÓN</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" type="text" maxlength="50" name="telefonoEmpresa"
                                        value="{{ $datosEmpresa->Telefono }}">
                                    <label for="telefonoEmpresa">TELÉFONO</label>
                                </div>
                            </div>

                            <div class="col-md-8">
                                <div class="form-group">
                                    <input class="form-control" type="text" maxlength="150" name="paginaWeb"
                                        value="{{ $datosEmpresa->PaginaWeb }}">
                                    <label for="telefonoEmpresa">Página Web</label>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="form-group">
                                    <input class="form-control" type="text" maxlength="50" name="correoEmpresa"
                                        value="{{ $datosEmpresa->CorreoEmpresa }}">
                                    <label for="correoEmpresa">Correo Empresa</label>
                                </div>
                            </div>
                        </div>
                        <fieldset class="fieldset fieldset--bordeCeleste">
                            <legend class="legend legend--colorNegro">Opciones Empresa:
                            </legend>
                            <div class="row mt-2">
                                {{-- <div class="col-md-3">
                                    <div class="form-group text-center p-2" style="background-color: #EFF2F5">
                                        <label for="selectVentaRapida">CONFIGURAR VENTA RÁPIDA</label><br>
                                        @if ($datosEmpresa->VentaRapida > 0)
                                            <input type="checkbox" id="selectVentaRapida" checked
                                                name="selectVentaRapida"><span
                                                class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                        @else
                                            <input type="checkbox" id="selectVentaRapida"
                                                name="selectVentaRapida"><span
                                                class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                        @endif
                                    </div>
                                </div> --}}
                                <div class="col-md-6">
                                    <div class="form-group text-center p-2" style="background-color: #EFF2F5">
                                        <label for="selectVentaRapida">CONFIGURAR VENTA RÁPIDA</label><br>
                                        <div class="radiobox p-0 d-flex justify-content-around flex-wrap">
                                            @if ($datosEmpresa->VentaRapida > 0)
                                                <div>
                                                    <input type="checkbox" id="selectVentaRapida" checked
                                                        name="selectVentaRapida"><span
                                                        class="pl-1">Activar/Desactivar</span>
                                                </div>
                                            @else
                                                <div><input type="checkbox" id="selectVentaRapida"
                                                        name="selectVentaRapida"><span
                                                        class="pl-1">Activar/Desactivar</span></div>
                                            @endif
                                            @if ($modulosSelect->contains('IdModulo', 1))
                                                <label>
                                                    @if ($datosEmpresa->VentaRapida == 1)
                                                        <input id="radio1" type="radio" name="radioOption"
                                                            value="1" checked="checked"> <span
                                                            class="label-text">Ticket</span>
                                                    @elseif($datosEmpresa->VentaRapida == 2)
                                                        <input id="radio1" type="radio" name="radioOption"
                                                            value="1"> <span class="label-text">Ticket</span>
                                                    @else
                                                        <input id="radio1" type="radio" name="radioOption"
                                                            value="1" disabled> <span
                                                            class="label-text">Ticket</span>
                                                    @endif
                                                </label>
                                            @endif
                                            @if ($modulosSelect->contains('IdModulo', 4))
                                                <label>
                                                    @if ($datosEmpresa->VentaRapida == 1)
                                                        <input id="radio2" type="radio" name="radioOption"
                                                            value="2"> <span class="label-text">Boleta</span>
                                                    @elseif($datosEmpresa->VentaRapida == 2)
                                                        <input id="radio2" type="radio" name="radioOption"
                                                            value="2" checked="checked"> <span
                                                            class="label-text">Boleta</span>
                                                    @else
                                                        <input id="radio2" type="radio" name="radioOption"
                                                            value="2" disabled> <span
                                                            class="label-text">Boleta</span>
                                                    @endif
                                                </label>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group text-center p-2" style="background-color: #EFF2F5">
                                        <label for="selectVentaRapida">TIPO DE IMPRESIÓN</label><br>
                                        <div class="radiobox p-0 d-flex justify-content-between">
                                            <label>
                                                @if ($datosEmpresa->TipoImpresion == 'A4')
                                                    <input id="radioTipoImpresion" type="radio"
                                                        name="radioTipoImpresion" value="A4" checked>
                                                    <span class="label-text">A4</span>
                                                @else
                                                    <input id="radioTipoImpresion" type="radio"
                                                        name="radioTipoImpresion" value="A4">
                                                    <span class="label-text">A4</span>
                                                @endif
                                            </label>
                                            <label>
                                                @if ($datosEmpresa->TipoImpresion == 'Ticket')
                                                    <input id="radioTipoImpresion" type="radio"
                                                        name="radioTipoImpresion" value="Ticket" checked>
                                                    <span class="label-text">Ticket</span>
                                                @else
                                                    <input id="radioTipoImpresion" type="radio"
                                                        name="radioTipoImpresion" value="Ticket">
                                                    <span class="label-text">Ticket</span>
                                                @endif
                                            </label>
                                            <label>
                                                @if ($datosEmpresa->TipoImpresion == 'Todo')
                                                    <input id="radioTipoImpresion" type="radio"
                                                        name="radioTipoImpresion" value="Ticket" checked>
                                                    <span class="label-text">Todo</span>
                                                @else
                                                    <input id="radioTipoImpresion" type="radio"
                                                        name="radioTipoImpresion" value="Todo">
                                                    <span class="label-text">Todo</span>
                                                @endif
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                @if ($datosEmpresa->Exonerado > 0)
                                    <div class="col-md-3">
                                        <div class="form-group text-center p-2" style="background-color: #EFF2F5">
                                            <label for="selectExonerar">Exonerar Facturación</label><br>
                                            @if ($datosEmpresa->Exonerado == 1)
                                                <input type="checkbox" checked name="selectExonerar"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                            @else
                                                <input type="checkbox" name="selectExonerar"><span
                                                    class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Activar/Desactivar</span>
                                            @endif
                                        </div>
                                    </div>
                                @else
                                    <input hidden type="text" name="selectExonerar" value="0">
                                @endif
                            </div>
                        </fieldset>
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <div class="fileUpload btnCambiarFotoPerfil">
                                        <i class="list-icon material-icons">image</i><span class="hide-menu">Cargar
                                            Imagen</span>
                                        <input id="archivo" class="upload btn" type="file" name="imagen"
                                            accept=".png, .jpg, .jpeg, .gif" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group text-center">
                                    @if (
                                        !empty($datosEmpresa->Imagen) &&
                                            !str_contains($datosEmpresa->Imagen, config('variablesGlobales.urlDominioAmazonS3Anterior')))
                                        <label for="archivo">
                                            <img id="imgPrevia" class="cursor-pointer"
                                                src="{{ str_contains($datosEmpresa->Imagen, config('variablesGlobales.urlDominioAmazonS3'))
                                                    ? $datosEmpresa->Imagen
                                                    : config('variablesGlobales.urlDominioAmazonS3') . $datosEmpresa->Imagen }}"
                                                alt="Vista de Imagen" width="100%" />
                                        </label>
                                    @else
                                        <label for="archivo">
                                            {{-- <img src="ruta-a-tu-imagen.jpg" alt="Seleccionar archivo" width="100" height="100" style="cursor: pointer;"> --}}
                                            <img id="imgPrevia" class="cursor-pointer"
                                                src="{{ config('variablesGlobales.urlImagenNotFound') }}"
                                                alt="Vista de Imagen" width="50%" height="25%" />
                                        </label>
                                    @endif
                                    <input type="hidden" name="inputLogoAnterior"
                                        value="{{ $datosEmpresa->Imagen }}">
                                </div>
                                <div class="form-group text-center">
                                    <label class="fs-12">Dim Aprox.: (500 x 220)px , Tamaño Máx: 400 kb <br>Formatos
                                        Aceptables: .png, .jpg, .jpeg, .gif</label>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="selectImagen">Quitar imágen</label><br>
                                    @if ($datosEmpresa->Imagen)
                                        <input type="checkbox" id="selectImagen" name="selectImagen"><span
                                            class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Quitar</span>
                                    @else
                                        <input type="checkbox" id="selectImagen" name="selectImagen" disabled><span
                                            class="pd-l-10 pd-l-0-rtl pd-r-50-rtl">Quitar</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <input hidden class="form-control" type="text" name="exonerado"
                            value="{{ $datosEmpresa->Exonerado }}">
                        <div class="form-actions btn-list mt-3">
                            <button class="btn btn-primary" type="submit">Guardar</button>
                            <a href="panel"><button class="btn btn-outline-default"
                                    type="button">Cancelar</button></a>
                        </div>
                        {!! Form::close() !!}
                    </div>
                    <!-- /.widget-list -->

                    <div class="modal modal-primary fade bs-modal-sm-importar" tabindex="-1" role="dialog"
                        aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                        <div class="modal-dialog modal-sm">
                            {!! Form::open([
                                'url' => 'importando-certificado',
                                'method' => 'POST',
                                'files' => true,
                                'class' => 'form-material',
                                'enctype' => 'multipart/form-data',
                            ]) !!}
                            <div class="modal-content">
                                <div class="modal-header text-inverse">
                                    <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                    <h6 class="modal-title" id="mySmallModalLabel2">Subir Certificado Digital</h6>
                                </div>
                                <div class="modal-body">
                                    <div class="container">
                                        <label><small class="text-danger fs-14">ADVERTENCIA:</small> Antes de subir
                                            Certificado Digital, verificar si el número de Ruc de Empresa le
                                            corresponde.</label>
                                        <div class="form-group">
                                            <div class="col-12">
                                                <div class="fileUpload btnCambiarFotoPerfil">
                                                    <i class="list-icon material-icons">description</i><span
                                                        class="hide-menu">Seleccionar C.D. (.pfx)</span>
                                                    <input id="file" class="upload btn" type="file"
                                                        name="file" accept=".pfx" />
                                                </div>
                                                <div class="mt-2">
                                                    <label id="nombreArchivo"></label>
                                                </div>
                                                <div class="form-group mt-2">
                                                    <input class="form-control" type="text" name="password">
                                                    <label for="password">Password</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary">Guardar</button>
                                    <button type="button" class="btn btn-primary"
                                        data-dismiss="modal">Cancelar</button>
                                </div>
                            </div>
                            {!! Form::close() !!}
                            <!-- /.modal-content -->
                        </div>
                        <!-- /.modal-dialog -->
                    </div>
                </div>
                <!-- /.container -->
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
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        $(function() {
            $('#archivo').change(function(e) {
                addImage(e);
            });

            function addImage(e) {
                var file = e.target.files[0],
                    imageType = /image.*/;

                if (!file.type.match(imageType))
                    return;

                var reader = new FileReader();
                reader.onload = fileOnload;
                reader.readAsDataURL(file);
            }

            function fileOnload(e) {
                var result = e.target.result;
                $('#imgPrevia').attr("src", result);
            }


        });
    </script>
    <script>
        $(function() {
            $("#departamento").on('change', function() {
                var departamento = $("#departamento").val();
                $.ajax({
                    type: 'get',
                    url: 'consultar-provincias',
                    data: {
                        'departamento': departamento
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#distrito option').remove();
                            $('#provincia option').remove();
                            $('#distrito').append('<option value="0">-</option>');
                            $('#provincia').append('<option value="0">-</option>');
                            for (var i = 0; i < data.length; i++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                                $('#provincia').append('<option value="' + data[i][
                                        "IdProvincia"
                                    ] + '">' + data[i]["Nombre"] +
                                    '</option>');
                            }
                        } else {
                            $('#provincia option').remove();
                            $('#distrito option').remove();
                        }
                    }
                });
            });

            $("#provincia").on('change', function() {
                var provincia = $("#provincia").val();
                $.ajax({
                    type: 'get',
                    url: 'consultar-distritos',
                    data: {
                        'provincia': provincia
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#distrito option').remove();
                            $('#distrito').append('<option value="0">-</option>');
                            for (var i = 0; i < data.length; i++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                                $('#distrito').append('<option value="' + data[i][
                                    "IdDistrito"
                                ] + '">' + data[i]["Nombre"] + '</option>');
                            }
                        } else {
                            $('#distrito option').remove();
                        }
                    }
                });
            });
        });
    </script>

    <script>
        $(function() {
            $('#file').change(function(e) {
                addImage(e);
            });

            function addImage(e) {
                var file = e.target.files[0];

                $('#nombreArchivo').text(file.name);
            }

            $('#selectVentaRapida').change(function(e) {
                var selectVenta = $("#selectVentaRapida:checked").val();
                if (selectVenta == 'on') {
                    $("#radio1").prop('disabled', false);
                    $("#radio2").prop('disabled', false);
                } else {
                    $("#radio1").prop('disabled', true);
                    $("#radio2").prop('disabled', true);
                }
            });
        });
    </script>
</body>
