<html>

<head>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js"></script>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="assets/demo/favicon.png">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->

    <!-- CSS -->
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <!-- Head Libs -->


    <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
    <title>Login</title>
</head>

<body class="body-bg-full profile-page">
    <div id="wrapper" class="wrapper">
        <div class="row container-min-full-height">
            <div class="col-lg-8 p-3 login-left">
                <div class="w-50">
                    @if ($message = Session::get('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <li>{{ $message }}</li>
                        </div>
                    @endif
                    @if ($message = Session::get('out'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            <li>{{ $message }}</li>
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
                    <div class="row justify-content-center">
                        <div class="col-md-8 col-12">
                            <img src="{{ asset('assets/img/autocontrolLogo.png') }}">
                        </div>
                    </div>
                    <h2 class="mb-4 text-center">Bienvenido</h2>
                    {!! Form::open(['url' => 'iniciando', 'method' => 'POST']) !!}
                    @csrf
                    <div class="form-group">
                        <label class="text-muted" for="usuario">USUARIO</label>
                        <input type="text" placeholder="ejemplo@broadcast.pe" class="form-control form-control-line"
                            name="usuario" value="{{ old('usuario') }}">
                    </div>
                    <div class="form-group">
                        <label class="text-muted" for="contraseña">PASSWORD</label>
                        <input type="password" placeholder="******" class="form-control form-control-line"
                            name="contraseña">
                    </div>
                    <!--<div class="form-group no-gutters mb-5 text-center"><a href="page-forgot-pwd.html" id="to-recover" class="text-muted fw-700 text-uppercase heading-font-family fs-12">Forgot Password?</a>
                            </div>-->
                    <!-- /.form-group -->
                    <div class="form-group mr-b-20">
                        {{-- <button class="btn btn-block btn-rounded btn-md btn-primary text-uppercase fw-600 ripple"
                            type="submit">INGRESAR</button> --}}
                        <x-buttonLoader class="btn btn-block btn-rounded btn-md text-uppercase fw-600 ripple"
                            accion='Loguearse'>
                            @slot('textoBoton', 'INGRESAR')
                            @slot('textoLoader', 'cargando')
                        </x-buttonLoader>
                    </div>
                    <section>
                        <p class="text-center font-weight-bold">Si ya finalizó la suscripción, después de realizar el
                            depósito, registre su pago haciendo click aqui. <a
                                href="{{ route('registro-pago.create') }}">Registrar Pago</a></p>
                    </section>
                    {!! Form::close() !!}
                    <section>
                        <hr>
                        <div class="row">
                            <article class="col-12 text-center mb-2">
                                Central telefónica: (01)6429818
                            </article>
                            <article class="col-12 d-flex justify-content-around flex-wrap">
                                <div>Soporte Tec.: 913253636</div>
                                <div>Asesoria Com.: 930300534</div>
                                <div>Ventas.: 922483630</div>
                            </article>
                        </div>
                    </section>
                    <!-- /form -->
                    <!--<button type="button" class="btn btn-block btn-rounded btn-outline-facebook ripple" title="Login with Facebook">Connect using <span class="fw-700">facebook</span>
                        </button>-->
                </div>
                <!-- /.w-75 -->
            </div>
            <div class="col-lg-4 login-right d-lg-flex d-none pos-fixed pos-right text-inverse container-min-full-height"
                style="background-image: url(assets/img/logoizquierda.jpg)">
                <!--<div class="login-content px-3 w-75 text-center">
                        <img width="150" src="{{ asset('assets/img/FACILITO.png') }}">
                        <h6 class="heading-font-family fw-500 mt-4 letter-spacing-minus fs-15">Dale Megusta y entérate de todas nuestras novedades.</h6>
                        
                        <div id="fb-root"></div>
                        <script async defer crossorigin="anonymous"
                            src="https://connect.facebook.net/es_LA/sdk.js#xfbml=1&version=v4.0&appId=176990792974121&autoLogAppEvents=1">
                        </script>
                        <div style="background: rgba(255, 255, 255, 0.4)" class="fb-like p-1" data-href="https://www.facebook.com/mifacturita" data-width="300px" data-layout="standard" data-action="like" data-size="large" data-show-faces="true" data-share="true"></div>

                    </div>-->
                <!--<div class="mt-4 text-center">
                        <h6 class="heading-font-family fw-500 letter-spacing-minus fs-15">Conoce la nueva actualización de la semana <span class="text-black fw-800">2</span></h6>
                        <h6 class="heading-font-family fw-700 letter-spacing-minus fs-15">Apertura / Cierre de Caja con impresión y / o envío a correo</h6>
                    </div>
                    <div class="container text-center">
                       <iframe width="400" height="240" src="https://www.youtube.com/embed/lDxjFojfEJU" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                    </div>-->
                <!-- /.login-content -->
            </div>
            <!-- /.login-right -->
        </div>
        <!-- /.row -->
    </div>
    <!-- /.wrapper -->
    <!-- Scripts -->
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script src="{{ asset('assets/js/material-design.js') }}"></script>
    <script src="{{ asset('assets/js/scriptGlobal/buttonLoaderScript.js?v=' . time()) }}"></script>
    <script>
        $(document).on('click', function(e) {
            if (e.target.matches('.btnLoader') || e.target.matches('.btnLoader *')) {
                showButtonLoader();
                $('form').submit();
            }
        });
    </script>
</body>

</html>
