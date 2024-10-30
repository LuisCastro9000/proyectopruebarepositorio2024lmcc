<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Editar Permisos</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.min.css"
        rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/css/multi-select.min.css" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Editar Permisos</h6>
                        </div>
                        <!-- /.page-title-left -->
                    </div>
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
                                        {!! Form::open([
                                            'url' => '/permisos/agregar_update/' . $usuario->IdUsuario . '/' . $IdPermiso,
                                            'method' => 'POST',
                                            'files' => true,
                                        ]) !!}
                                        {{ csrf_field() }}
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group form-material">
                                                    <label for="usuario">Usuario</label>
                                                    <input class="form-control" disabled
                                                        value="{{ $usuario->Nombre }}">
                                                </div>
                                            </div>
                                            <div class="col-md-8">
                                                <div class="form-group">
                                                    <label for="permisos">Permisos</label>
                                                    <select class="selectpicker form-control" name="sub_permisos[]"
                                                        multiple="multiple" data-style="btn btn-primary">
                                                        @foreach ($permisos as $permiso)
                                                            @if (in_array($permiso->IdSubPermiso, $usuarioPermisos))
                                                                <option selected value="{{ $permiso->IdSubPermiso }}">
                                                                    {{ $permiso->Descripcion }}</option>
                                                            @else
                                                                {{-- Nuevo Codigo solo la condicion If --}}
                                                                {{-- <option value="{{ $permiso->IdSubPermiso }}">
                                                                    {{ $permiso->Descripcion }}</option> --}}
                                                                {{-- @if ($permiso->IdSubPermiso != 34 && $permiso->IdUsuario != 1) --}}
                                                                @if ($permiso->IdSubPermiso != 34)
                                                                    <option value="{{ $permiso->IdSubPermiso }}">
                                                                        {{ $permiso->Descripcion }}</option>
                                                                @else
                                                                    <option value="{{ $permiso->IdSubPermiso }}">
                                                                        {{ $permiso->Descripcion }}</option>
                                                                @endif
                                                                {{-- Fin --}}
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="form-actions btn-list mt-3">
                                            <button class="btn btn-primary" type="submit">Actualizar</button>
                                            <a href="{!! url('/administracion/permisos') !!}"><button class="btn btn-outline-default"
                                                    type="button">Cancelar</button></a>
                                        </div>
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
                    @if ($usuarioSelect->IdOperador == 1)
                        {!! Form::open([
                            'url' => '/permisos/agregar_update_completar/' . $IdPermiso,
                            'method' => 'POST',
                            'files' => true,
                        ]) !!}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="permisos">Seleccionar Permiso</label>
                                    <select id="subPermisos" class="form-control" name="subPermisos"
                                        data-style="btn btn-primary">
                                        <option value="0">-</option>
                                        @foreach ($permisos as $permiso)
                                            <option value="{{ $permiso->IdSubPermiso }}">{{ $permiso->Descripcion }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label for="permisos">Usuarios sin Permiso</label>
                                    <select id="usuariosSinSubPermisos" class="selectpicker form-control"
                                        name="usuariosSinSubPermisos[]" multiple="multiple"
                                        data-style="btn btn-primary">
                                    </select>
                                </div>
                            </div> --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="permisos">Usuarios sin Permiso</label>
                                    <select id="usuariosSinSubPermisos" class="selectpicker form-control"
                                        name="usuariosSinSubPermisos[]" multiple="multiple" data-style="btn btn-primary"
                                        data-toggle="select2">
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions btn-list mt-3">
                            <button class="btn btn-primary" type="submit">Completar</button>
                        </div>
                        {!! Form::close() !!}
                    @endif
                </div>
                <!-- /.container -->
            </main>
            <!-- /.main-wrapper -->
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/multi-select/0.9.12/js/jquery.multi-select.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script>
        $(function() {
            $("#subPermisos").on('change', function() {
                var subPermiso = $("#subPermisos").val();
                let arrayUsuario = [];
                $.ajax({
                    type: 'get',
                    url: '../../buscar-subPermisos',
                    data: {
                        'subPermiso': subPermiso
                    },
                    success: function(data) {
                        $('#usuariosSinSubPermisos option').remove();
                        for (var i = 0; i < data.length; i++) {
                            var newOption = new Option(data[i]["Nombre"], data[i]["IdUsuario"],
                                false, false);
                            $('#usuariosSinSubPermisos').append(newOption);
                            //$('#usuariosSinSubPermisos').append('<option value="'+data[i]["IdUsuario"]+'">'+data[i]["Nombre"]+'</option>');
                        }
                        $("#usuariosSinSubPermisos").selectpicker("refresh");
                    }
                });
            });
        });
    </script>


</body>

</html>
