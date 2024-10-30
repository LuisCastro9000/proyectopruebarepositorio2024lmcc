<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <title>Cambiar contraseña</title>
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
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">
    <!-- Head Libs -->

    <script src="{{ asset('assets/js/modernizr.min.js') }}"></script>
    <script data-pace-options='{ "ajax": false, "selectors": [ "img" ]}' src="{{ asset('assets/js/pace.min.js') }}">
    </script>
</head>

<body class="sidebar-horizontal">
    <div id="wrapper" class="wrapper">
        @if ($contrasenaActualizada != 'true')
            @include('schemas.schemaHeader')
        @endif
        <div class="content-wrapper">
            <aside class="site-sidebar clearfix">
                <div class="container">
                    @if ($contrasenaActualizada != 'true')
                        @include('schemas.schemaSideNav')
                    @endif
                </div>
            </aside>
            <main class="main-wrapper clearfix">
                <div class="container">
                    @if (session('error'))
                        <div class="alert alert-danger mt-4">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
                <div class="container">
                    <div class="widget-list">
                        <div class="row">
                            <div class="col-md-12 widget-holder">
                                <div class="widget-bg-transparent">
                                    <div class="widget-body clearfix">
                                        {!! Form::open(['url' => 'actualizando-contrasena', 'method' => 'POST']) !!}
                                        {{ csrf_field() }}
                                        <section class="text-center mb-4">
                                            <h6 class="text-uppercase font-weight-bold">Cambiar contraseña</h6>
                                        </section>
                                        <br>
                                        <div class="row justify-content-center form-material">
                                            <div class="col-lg-5 col-md-6 col-sm-8 col-10">
                                                @if ($usuarioSelect->Cliente == 1)
                                                    <div class="form-group">
                                                        <div class="radiobox">
                                                            <section
                                                                class="d-flex justify-content-center justify-content-lg-around  flex-column flex-lg-row">
                                                                <label>
                                                                    <input id="radio1" type="radio"
                                                                        name="radioOption" value="Password"
                                                                        checked="checked" class="radioActivarClave">
                                                                    <span
                                                                        class="label-text p-4 font-weight-bold">Contraseña
                                                                        de Panel</span>
                                                                </label>
                                                                <label>
                                                                    <input id="radio2" type="radio"
                                                                        name="radioOption" value="ClaveDeComprobacion"
                                                                        class="radioActivarClave">
                                                                    <span
                                                                        class="label-text p-4 font-weight-bold">Contraseña
                                                                        de Supervisor<span>
                                                                </label>
                                                            </section>
                                                        </div>
                                                    </div>
                                                @else
                                                    <div class="form-group d-none">
                                                        <div class="radiobox">
                                                            <section class="d-flex justify-content-around">
                                                                <label>
                                                                    <input id="radio1" type="radio"
                                                                        name="radioOption" value="Password"
                                                                        checked="checked">
                                                                    <span
                                                                        class="label-text p-4 font-weight-bold">Password
                                                                        de
                                                                        sesion</span>
                                                                </label>
                                                            </section>
                                                        </div>
                                                    </div>
                                                @endif
                                                <section id="seccionclaveAdministrador">
                                                    {{-- <p>Administrador</p> --}}
                                                    <div class="form-group">
                                                        <label class="text-muted" for="ncontrasena">Nueva
                                                            contraseña</label>
                                                        <input type="password" placeholder="******"
                                                            class="form-control form-control-line inputClaveAdministrador"
                                                            name="ncontrasena">
                                                        <span
                                                            class="text-danger font-size">{{ $errors->first('ncontrasena') }}</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-muted" for="rncontrasena">Repetir nueva
                                                            contraseña</label>
                                                        <input type="password" placeholder="******"
                                                            class="form-control form-control-line inputClaveAdministrador"
                                                            name="rncontrasena">
                                                        <span
                                                            class="text-danger font-size">{{ $errors->first('rncontrasena') }}</span>
                                                    </div>
                                                </section>
                                                <section id="seccionClaveSupervisor">
                                                    {{-- <p>Supervisor</p> --}}
                                                    <div class="form-group">
                                                        <label class="text-muted" for="claveSupervisor">Nueva
                                                            contraseña</label>
                                                        <input type="text"
                                                            class="form-control form-control-line inputClaveSupervisor"
                                                            placeholder="******" style="-webkit-text-security: disc;"
                                                            autocomplete="off" name="claveSupervisor">
                                                        <span
                                                            class="text-danger font-size">{{ $errors->first('claveSupervisor') }}</span>
                                                    </div>
                                                    <div class="form-group">
                                                        <label class="text-muted"
                                                            for="claveSupervisorDuplicado">Repetir nueva
                                                            contraseña</label>
                                                        <input type="text"
                                                            class="form-control form-control-line inputClaveSupervisor"
                                                            placeholder="******" style="-webkit-text-security: disc;"
                                                            autocomplete="off" name="claveSupervisorDuplicado">
                                                        <span
                                                            class="text-danger font-size">{{ $errors->first('claveSupervisorDuplicado') }}</span>
                                                    </div>
                                                </section>
                                                <button
                                                    class="btn btn-block btn-rounded btn-md btn-primary text-uppercase fw-600 ripple mb-2"
                                                    type="submit">Actualizar</button>
                                                <a href="panel"><button
                                                        class="btn btn-block btn-rounded btn-md btn-default text-uppercase fw-600 ripple"
                                                        type="button">Cancelar</button></a>
                                            </div>
                                        </div>
                                        {!! Form::close() !!}

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>
        @if ($contrasenaActualizada != 'true')
            @include('schemas.schemaFooter')
        @endif

    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        $(() => {
            $('#seccionClaveSupervisor').hide();
        })

        $(".radioActivarClave").click(function() {
            if ($(".radioActivarClave").is(':checked')) {

                if ($(this).val() == 'Password') {
                    // $(".inputClaveAdministrador").attr("type", 'password');
                    // $(".claveUno").attr("name", 'ncontrasena');
                    // $(".claveDos").attr("name", 'rncontrasena');
                    $('#seccionClaveSupervisor').hide();
                    $('#seccionclaveAdministrador').show();
                    $('.inputClaveSupervisor').val('');
                }

                if ($(this).val() == 'ClaveDeComprobacion') {
                    // $(".inputClaveAdministrador").attr("type", 'text');
                    // $(".claveUno").attr("name", 'claveSupervisor');
                    // $(".claveDos").attr("name", 'claveSupervisor');
                    $('#seccionClaveSupervisor').show();
                    $('#seccionclaveAdministrador').hide();
                    $('.inputClaveAdministrador').val('');
                }
            }
        });
    </script>
</body>
