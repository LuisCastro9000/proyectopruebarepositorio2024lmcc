<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Permisos</title>
    <!-- CSS -->
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/jquery-confirm/3.3.2/jquery-confirm.min.css">

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
                    <div
                        class="my-3 d-flex justify-content-center justify-content-md-between align-items-center flex-wrap">
                        <section>
                            <h6 class="page-title-heading mr-0 mr-r-5">Listado de usuarios con permisos</h6>
                        </section>
                        @if ($usuarioSelect->IdOperador == 1)
                            <section class="mt-2 mt-md-0">
                                <a href="permiso/lista-permisos-sistema"><button
                                        class="btn btn-block btn-primary ripple">Asignación
                                        Masiva</button></a>
                            </section>
                        @endif
                    </div>
                    {{-- <div class="row page-title clearfix">
                        <div class="page-title-left">
                            <h6 class="page-title-heading mr-0 mr-r-5">Listado de usuarios con permisos</h6>
                        </div>
                        <div class="page-title-right">
                            <div class="row mr-b-50 mt-2">
                                <div class="col-12 mr-b-20 d-sm-block d-none">
                                    <a href="../administracion/permisos/create"><button
                                            class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-26">add</i> Agregar</button></a>
                                </div>
                                <div class="col-12 mr-b-20 d-sm-none d-block">
                                    <a href="../administracion/permisos/create"><button
                                            class="btn btn-block btn-primary ripple"><i
                                                class="list-icon material-icons fs-26">add</i></button></a>
                                </div>
                            </div>
                        </div>
                    </div> --}}
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('status') }}
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
                                    <!--<div class="widget-heading clearfix">
                                    <h5>TableSaw</h5>
                                </div>-->
                                    <!-- /.widget-heading -->
                                    <div class="widget-body clearfix">
                                        <!--<p>Listado de ventas</p>-->
                                        <table id="tabla1" class="table table-responsive-sm" style="width:100%">
                                            <thead>
                                                <tr class="bg-primary">
                                                    <th scope="col">Usuario</th>
                                                    <th scope="col">Operador</th>
                                                    <th scope="col">Login</th>
                                                    @if ($idUsuario == 1)
                                                        <th scope="col">RUC</th>
                                                        <th scope="col">Plan Suscripción</th>
                                                    @endif
                                                    <th scope="col" class="text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($usuariosPermisos as $usuarioPermisos)
                                                    <tr>
                                                        <td>{{ $usuarioPermisos->Nombre }}</td>
                                                        <td>{{ $usuarioPermisos->Rol }}</td>
                                                        <td>{{ $usuarioPermisos->Login }}</td>
                                                        @if ($idUsuario == 1)
                                                            <td>{{ $usuarioPermisos->Ruc }}</td>
                                                            <td>{{ $usuarioPermisos->NombrePlan }}</td>
                                                        @endif
                                                        {{-- <td>
                                                            @foreach ($usuarioPermisos->Permisos as $permiso)
                                                                <a
                                                                    href="{!! url('/permisos/ver/' . $permiso->IdPermiso . '/' . $usuarioPermisos->IdUsuario) !!}">{{ $permiso->Descripcion }}</a>
                                                                <br>
                                                            @endforeach
                                                        </td> --}}
                                                        <td class="text-center">
                                                            {{-- <a href="permisos/{{ $usuarioPermisos->IdUsuario }}/edit"><button
                                                                    title="Editar" class="btn btn-primary"><i
                                                                        class="list-icon material-icons">edit</i></button></a> --}}
                                                            @if ($usuarioSelect->IdOperador == 1)
                                                                <a href="modulos/{{ $usuarioPermisos->IdUsuario }}"><button
                                                                        title="Asignar módulos"
                                                                        class="btn btn-primary p-1"><i
                                                                            class="list-icon material-icons">dehaze</i></button></a>
                                                                <a
                                                                    href="completar-modulos/{{ $usuarioPermisos->IdUsuario }}"><button
                                                                        title="Completar módulos"
                                                                        class="btn btn-primary p-1"><i
                                                                            class="list-icon material-icons">add</i></button></a>
                                                            @endif
                                                            <a
                                                                href="permisos/lista-permisos/{{ $usuarioPermisos->IdUsuario }}"><button
                                                                    title="Editar Permiso"
                                                                    class="btn btn-primary p-1"><i
                                                                        class="list-icon material-icons">edit</i></button></a>
                                                            {{-- <a
                                                                href="{{ route('permisos.botones.administrativos.show', $usuarioPermisos->IdUsuario) }}"><button
                                                                    title="Editar Permiso Botones"
                                                                    class="btn btn-success p-1"><i
                                                                        class="list-icon material-icons">edit</i></button></a> --}}
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
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

                <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog"
                    aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <!--<div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    </div>-->
                            <div class="modal-body">
                                <h6 class="modal-title">Desea Eliminar Usuario?</h6>
                                <input id="idUsuario" hidden />
                            </div>
                            <div class="modal-footer">
                                <button id="btnEliminar" type="button"
                                    class="btn btn-primary btnEliminar">Eliminar</button>
                                <button type="button" class="btn btn-secondary"
                                    data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
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
    <script src="{{ asset('assets/js/datatables.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script src="{{ asset('assets/js/general.js') }}"></script>
    <script src="{{ asset('assets/js/administracion/permisos.js') }}"></script>
</body>

</html>
