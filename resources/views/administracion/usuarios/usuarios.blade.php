@extends('layouts.app')
@section('title', 'Usuarios')
@section('content')
    <div class="container">
        <section class="d-flex justify-content-center justify-content-md-between flex-wrap mt-4">
            <article>
                <h6 class="">Listado de Usuario</h6>
            </article>
            <article>
                @if ($usuarioSelect->IdUsuario == 1)
                    <a class="p-1" href="usuarios-suscripciones"><button class="btn btn-primary ripple"><i
                                class="list-icon material-icons fs-20">featured_play_list</i></button></a>
                    <a class="p-1" href="javascript:void(0);"><button class="btn btn-primary ripple" data-toggle="modal"
                            data-target="#mensaje"><i
                                class="list-icon material-icons fs-20">supervisor_account</i></button></a>
                    <a class="p-1 agregarIdSucursal" href=""><button class="btn btn-primary ripple">Agregar sucursal
                            a Suscripción</button></a>
                @endif
                <a class="p-1" href="../administracion/usuarios/create"><button class="btn btn-primary ripple"><i
                            class="list-icon material-icons fs-20">person_add</i>
                        Crear</button></a>
            </article>
        </section>
        {{-- </div> --}}
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
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        @if ($usuarioSelect->IdUsuario == 1)
                                            <th scope="col">Empresa</th>
                                            <th scope="col">Ruc Empresa</th>
                                        @endif
                                        <th scope="col">Nombre</th>
                                        <th scope="col">Login</th>
                                        <th scope="col">Email</th>
                                        @if ($usuarioSelect->IdUsuario == 1)
                                            <th scope="col">Fecha CDT</th>
                                            <th scope="col">Plan Suscripción</th>
                                        @endif
                                        <th scope="col">Operador</th>
                                        <th scope="col">Sucursal</th>
                                        <th scope="col">Estado</th>
                                        <th scope="col">Opciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($usuarios as $usuario)
                                        <tr>
                                            @if ($usuarioSelect->IdUsuario == 1)
                                                <td>{{ $usuario->Empresa }}</td>
                                                <td>{{ $usuario->RucEmpresa }}</td>
                                            @endif
                                            <td>{{ $usuario->Nombre }}</td>
                                            <td>{{ $usuario->Login }}</td>
                                            <td>{{ $usuario->Email }}</td>
                                            @if ($usuarioSelect->IdUsuario == 1)
                                                <td>{{ $usuario->FechaFinalCDT }}</td>
                                                <td>{{ $usuario->NombrePlanSucripcion }}</td>
                                            @endif
                                            <td>{{ $usuario->Rol }}</td>
                                            <td>{{ $usuario->Sucursal }}</td>
                                            @if ($usuario->Estado == 'E')
                                                <td><span class="badge bg-success color-white fs-12 p-1">HABILITADO</span>
                                                </td>
                                            @elseif($usuario->Estado == 'D' || $usuario->Estado == 'Suscripcion Caducada')
                                                <td><span class="badge bg-danger color-white fs-12 p-1">DESACTIVADO</span>
                                                </td>
                                            @endif
                                            <td class="text-center">
                                                <a href="usuarios/{{ $usuario->IdUsuario }}/edit"><button
                                                        class="btn btn-primary"><i
                                                            class="list-icon material-icons">edit</i></button></a>
                                                <a href="javascript:void(0);"><button class="btn btn-primary"
                                                        data-toggle="modal" data-target="#exampleModal"
                                                        onclick="modalEliminar({{ $usuario->IdUsuario }})"><i
                                                            class="list-icon material-icons">clear</i></button></a>
                                                @if ($usuarioSelect->IdUsuario == 1)
                                                    <a href="../consultas/articulos-stock/{{ $usuario->IdUsuario }}"><button
                                                            class="btn btn-primary"><i
                                                                class="list-icon material-icons">visibility</i></button></a>
                                                    <a href="../consultas/articulos-kardex/{{ $usuario->IdUsuario }}"><button
                                                            class="btn btn-primary"><i
                                                                class="list-icon material-icons">swap_horiz</i></button></a>
                                                    <a href="usuarios/config-suscripcion/{{ $usuario->IdUsuario }}"><button
                                                            class="btn btn-primary"><i
                                                                class="list-icon material-icons">account_box</i></button></a>
                                                    <a href="usuarios/lista-xml/{{ $usuario->IdUsuario }}"><button
                                                            class="btn btn-primary"><i
                                                                class="list-icon material-icons">code</i></button></a>
                                                    {{-- Nuevo boton enviar archivos a usuarios retirados --}}
                                                    <a href="{{ route('lista-documentos', [$usuario->IdUsuario]) }}"><button
                                                            class="btn btn-primary">Enviar
                                                            Documentos</button></a>
                                                    {{-- Fin --}}
                                                    <a class="p-1 subirFactura" href=""><button
                                                            class="btn btn-primary ripple">Subir
                                                            Factura</button></a>
                                                    <a class="p-1"
                                                        href="{{ route('enviarDocumentosUsuarios', [$usuario->IdUsuario]) }}"><button
                                                            class="btn btn-primary ripple">enviar Xml Y Cdr</button></a>
                                                @endif
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

    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <h6 class="modal-title">Desea Eliminar Usuario?</h6>
                    <input id="idUsuario" hidden />
                </div>
                <div class="modal-footer">
                    <button id="btnEliminar" type="button" class="btn btn-primary btnEliminar">Eliminar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="mensaje" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Configurar Mensaje</h5>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => '/administracion/usuarios/actualizar-mensaje', 'method' => 'POST', 'files' => true]) !!}
                    <div class="row">
                        <div class="col-12">
                            {{-- Nuevo codigo --}}
                            <section class="d-flex justify-content-between align-items-center">
                                <label class='fs-14'>Mensaje Actualización de Módulos</label>
                                @if ($mensajeActualizacion->Estado == 1)
                                    <label class="switch ">
                                        <input id="checkActivarEnvio" type="checkbox" name="checkMensaje" checked>
                                        <span class="slider round"></span>
                                    </label>
                                @else
                                    <label class="switch ">
                                        <input id="checkActivarEnvio" type="checkbox" name="checkMensaje">
                                        <span class="slider round"></span>
                                    </label>
                                @endif
                            </section>
                            {{-- Fin --}}
                            <div class="form-group">
                                <textarea id="mensaje" class="form-control" name="mensaje" rows="5">{{ $mensajeActualizacion->Descripcion }}</textarea>
                                <input hidden name="idMensaje" value="1">
                                <input type="text" class="form-control mt-2" id="formGroupExampleInput"
                                    placeholder="Escribir Url del video" name="urlVideo"
                                    value="{{ $mensajeActualizacion->UrlVideo }}">
                            </div>

                            <div class="form-group">
                                <select class="form-control" name="rubro">
                                    @if ($mensajeActualizacion->IdRubro == 0)
                                        <option value="0" selected>Todos</option>
                                    @else
                                        <option value="0">Todos</option>
                                    @endif
                                    @foreach ($rubros as $rubro)
                                        @if ($mensajeActualizacion->IdRubro == $rubro->IdRubro)
                                            <option value="{{ $rubro->IdRubro }}" selected>
                                                {{ $rubro->Descripcion }}</option>
                                        @else
                                            <option value="{{ $rubro->IdRubro }}">{{ $rubro->Descripcion }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <section class="text-center">
                                <button class="btn btn--celeste btnEliminar mt-2" type="submit"
                                    name="btnMensajeSunat">Guardar Mensaje Actualizacion</button>
                            </section>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    <hr>
                    {{-- Nuevo Codigo --}}
                    {!! Form::open(['url' => '/administracion/usuarios/actualizar-mensaje', 'method' => 'POST', 'files' => true]) !!}
                    <div class="row mt-4">
                        <div class="col-12">
                            <section class="d-flex justify-content-between align-items-center">
                                <label class='fs-14'>Mensaje Sunat</label>
                                @if ($mensajeSunat->Estado == 1)
                                    <label class="switch ">
                                        <input id="checkActivarEnvio" type="checkbox" name="checkMensaje" checked>
                                        <span class="slider round"></span>
                                    </label>
                                @else
                                    <label class="switch ">
                                        <input id="checkActivarEnvio" type="checkbox" name="checkMensaje">
                                        <span class="slider round"></span>
                                    </label>
                                @endif
                            </section>
                            <div class="form-group">
                                <textarea class="form-control" name="mensaje" rows="5">{{ $mensajeSunat->Descripcion }}</textarea>
                                <input hidden name="idMensaje" value="2">
                            </div>
                            <section class="text-center">
                                <button class="btn btn--verde btnEliminar mt-2" type="submit"
                                    name="btnMensajeSunat">Guardar Mensaje sunat</button>
                            </section>
                        </div>
                    </div>
                    {!! Form::close() !!}
                    {{-- Fin --}}
                </div>
                {{-- <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                </div> --}}
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalSubirFactura" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                {!! Form::open([
                    'url' => '',
                    'method' => 'POST',
                    'files' => true,
                ]) !!}
                <div class="modal-body">
                    <section class="text-center">
                        <label class="fs-18">Subir Factura</label>
                        <hr>
                    </section>
                    <section>
                        <label>Seleccion Año</label>
                        <select id="anio" class="form-control" name="anio">
                        </select>
                    </section>
                    <section class="my-4">
                        <label>Seleccion Mes</label>
                        <select id="anio" class="form-control" name="anio">
                            <option value="1">Enero</option>
                            <option value="2">Febrero</option>
                            <option value="3">Marzo</option>
                            <option value="4">Abril</option>
                            <option value="5">Mayo</option>
                            <option value="6">Junio</option>
                            <option value="7">Julio</option>
                            <option value="8">Agosto</option>
                            <option value="9">Setiembre</option>
                            <option value="10">Octubre</option>
                            <option value="11">Noviembre</option>
                            <option value="12">Dicembre</option>
                            v
                        </select>
                    </section>
                    <section class="formImport" id="formImport">
                        <span><i class='bx bxs-cloud-upload fs-60 color-celeste'></i></span>
                        <span id="subTitleFile" class="text-center">Click Aquí <br> Seleccionar Archivo</span>
                        <input type="file" class="input-file" name="datosExcelServicios" id="input-file"
                            accept=".pdf, .png, .jpg" hidden>
                        <span id="nameFile"></span>
                    </section>
                    <section>
                        <input type="hidden" name="rucEmpresa">
                    </section>
                </div>
                <div class="modal-footer mt-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary" id="btnImportar">Cargar en el AWS</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="modalAgregarIdSucursal" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
            <div class="modal-content">
                {!! Form::open([
                    'route' => ['AgregarIdSucursal-Suscripcion'],
                    'method' => 'POST',
                    'files' => true,
                ]) !!}
                <div class="modal-body">
                    <table id="table" class="table table-responsive-sm" style="width:100%">
                        <thead>
                            <tr class="bg-primary">
                                <th scope="col">IdSucursalSucursal</th>
                                <th scope="col">IdSucursalSuscripcion</th>
                                <th scope="col">IdUsuario</th>
                                <th scope="col">FechaFinalContrato</th>
                                <th scope="col">FechaFinalCDT</th>
                                <th scope="col">IdSucursal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($suscripcionesSinIdSucursal as $suscripcion)
                                <tr>
                                    <td>{{ $suscripcion->IdSucursal }}</td>
                                    <td>{{ $suscripcion->IdSucursalSuscripcion }}</td>
                                    <td>{{ $suscripcion->NombreUsuario }}</td>
                                    <td>{{ $suscripcion->FechaFinalContrato }}</td>
                                    <td>{{ $suscripcion->FechaFinalCDT }}</td>
                                    <td>{{ $suscripcion->IdSucursal ?? 'No asignado' }}</td>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer mt-4">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script>
        document.addEventListener("click", function(event) {
            if (event.target.matches('.agregarIdSucursal *')) {
                event.preventDefault();
                $('#modalAgregarIdSucursal').modal('show');
            }
        });
    </script>
    <script>
        document.addEventListener("click", function(event) {
            if (event.target.matches('.subirFactura *')) {
                event.preventDefault();
                $('#modalSubirFactura').modal('show');
                // alert('Evento click sobre un input text con id="nombre2"');
                // const dataId = event.target.dataset.idcontrol;
                // const inputMedida = document.getElementById('inputMedida' + dataId);
                // if (inputMedida.hasAttribute('disabled')) {
                //     document.getElementById('inputMedida' + dataId).disabled = false;
                // }
            }
        });

        const form = document.querySelector('#formImport');
        const inputFile = form.querySelector('.input-file');
        form.addEventListener('click', () => {
            inputFile.click();
        })

        inputFile.onchange = ({
            target
        }) => {
            if (target.files[0].size <= 153600) {
                $('#nameFile').text(target.files[0].name);
                $('#subTitleFile').text('');
                $('#subTitleFile').removeClass('text-crimson');
            } else {
                $('#subTitleFile').text('');
                $('#nameFile').text('');
                $('#subTitleFile').addClass('text-crimson');
                $('#subTitleFile').text('El Archivo es muy Grande');
            }
        }

        $(() => {
            const date = new Date();
            const anioActual = date.getFullYear();
            for (let index = anioActual; index >= 2018; index--) {
                $('#anio').append(`<option>${index}</option>`);
            }
        })
    </script>


    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });

        });
    </script>
    <script>
        function modalEliminar(id) {
            $("#idUsuario").val(id);
        }
        $(function() {
            $("#exampleModal button.btnEliminar").on("click", function(e) {
                var id = $("#idUsuario").val();
                window.location = 'usuarios/' + id + '/delete';
            });
        });
    </script>
@stop
