<nav class="navbar">
    <div class="container px-0 align-items-stretch">
        <!-- Left Menu & Sidebar Toggle -->
        <ul class="nav navbar-nav">
            <li class="sidebar-toggle dropdown"><a href="javascript:void(0)" class="ripple"><i
                        class="material-icons list-icon md-24">menu</i></a>
            </li>
        </ul>
        <!-- /.navbar-left -->
        <!-- Logo Area -->
        <div class="navbar-header">
            <a href="{!! url('/panel') !!}" class="navbar-brand">
                <img class="logo-expand" alt="" width="190"
                    src="{{ asset('assets/img/logoeasyfactperu.png') }}">
                <img class="logo-collapse" alt="" width="200"
                    src="{{ asset('assets/img/logoeasyfactperu.png') }}">
            </a>
        </div>
        <!-- /.navbar-header -->

        <div class="spacer"></div>

        <ul class="nav navbar-nav">
            @if ($permisos->contains('IdPermiso', 7))
                <li class="menu-item-has-children d-md-block d-none"><a href="{!! url('/soporte') !!}"><i
                            class="list-icon material-icons pr-md-1">settings</i> <span
                            class="hide-menu">Soporte</span></a>
                </li>
                <li class="menu-item-has-children d-md-none d-block m-auto"><a href="{!! url('/soporte') !!}"><span
                            class="hide-menu"><i class="list-icon material-icons">settings</i></span></a>
                </li>
            @endif
            <li class="dropdown"><a href="javascript:void(0);" class="dropdown-toggle dropdown-toggle-user ripple"
                    data-toggle="dropdown"><span class="avatar thumb-xs2"><img src="{{ Session::get('foto') }}"
                            class="rounded-circle" alt=""> <i
                            class="material-icons list-icon">expand_more</i></span></a>
                <div class="dropdown-menu dropdown-left dropdown-card dropdown-card-profile animated flipInY">
                    <div class="card">
                        <ul class="list-unstyled card-body">
                            @if ($permisos->contains('IdPermiso', 1))
                                {{-- @if ($usuarioSelect->IdOperador == 1 || $usuarioSelect->IdOperador == 2) --}}
                                <li><a href="{!! url('/configurar-empresa') !!}"><span><span class="align-middle">Administrar
                                                Empresa</span></span></a>
                                </li>
                                {{-- @endif --}}
                            @endif
                            <li><a href="{!! url('/cambiar-contrasena') !!}"><span><span class="align-middle">Cambiar
                                            Contraseña</span></span></a>
                            </li>
                            <li><a href="{!! url('/crear-firma-digital') !!}"><span><span class="align-middle">Firma
                                            Digital</span></span></a>
                            </li>
                            {{-- <li><a  href="{!! url('/cambiar-contrasena-comprobacion-de-permiso') !!}"><span><span class="align-middle">Cambiar Contraseña de comprobar Permiso</span></span></a>
                            </li> --}}
                            <li><a href="{!! url('/cerrarSesion') !!}"><span><span class="align-middle">Cerrar
                                            Sesión</span></span></a>
                            </li>
                        </ul>
                        <!-- /.card-body -->
                    </div>
                    <!-- /.card -->
                </div>
                <!-- /.dropdown-card-profile -->
            </li>
            <!-- /.dropdown -->
        </ul>
        <!-- /.navbar-nav -->
    </div>
    <!-- /.container -->
</nav>
