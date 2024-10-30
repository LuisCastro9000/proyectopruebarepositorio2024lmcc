@extends('layouts.app')
@section('title', 'Compras')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">

        <div class="row mt-4">
            <div class="col-12">
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
            </div>
            <div class="col-12">
                <div
                    class="d-flex justify-content-center flex-wrap align-items-center justify-content-sm-between flex-column flex-sm-row">

                    <section>
                        <div class="mb-3 mb-sm-0">
                            <h6 class="page-title-heading mr-0 mr-r-5">Listado de Compras</h6>
                        </div>
                    </section>
                    <section class="d-flex align-items-center flex-wrap justify-content-center">
                        <div class="d-md-block m-0  mr-md-2 mb-2 mb-md-0">
                            <a href="crear-compra" id="btnCrearCompra"><button class="btn btn-block btn-primary ripple"><i
                                        class="list-icon material-icons fs-24" value="1" name='btnSoles'
                                        id="btnSoles">add</i>Generar Compra</button></a>
                        </div>
                        {{-- <a href="orden-compra" class="py-1 py-md-5 mx-2"><button class="btn btn--verde ripple"><i
                                    class="list-icon material-icons fs-24">add</i>Generar Orden de Compra</button></a> --}}
                        <a class="d-md-block " href="https://www.youtube.com/watch?v=jyOGpjhkZPk&ab_channel=AutocontrolPeru"
                            target="_blank">
                            <span class="btn btn-autocontrol-naranja ripple text-white">
                                Video Instructivo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                            </span>
                        </a>

                    </section>
                </div>
            </div>
        </div>

        <section class="jumbotron jumbotron-fluid mt-2 py-4">
            <div class="container">
                <div class="row justify-content-md-center">
                    <div class="col-12 col-md-5 col-lg-3 text-center">
                        <a href="{!! url('/reportes/compras/proveedores') !!}">
                            <img width="100px" src="{{ asset('/assets/img/reporteProveedores.png') }}" alt=""
                                style="width: 50px; heigth:50px"><br>
                            <button class="btn bg-green ripple">Ver reporte de compras <br> por proveedor</button></a>
                    </div>
                    <div class="col-12 col-md-5 col-lg-3 text-center mt-sm-0 mt-4">
                        <a href="{!! url('/reportes/compras/productos') !!}">
                            <img width="100px" src="{{ asset('/assets/img/reporteProducto.png') }}" alt=""
                                style="width: 50px; heigth:50px"><br>
                            <button class="btn bg-green ripple">Ver reporte de compras <br>por producto</button></a>
                    </div>
                    {{-- <div class="col-12 col-md-5 col-lg-3 text-center mt-sm-0 mt-4">
                        <a href="{!! url('/reportes/compras/ordenes-compra') !!}">
                            <img width="100px" src="{{ asset('/assets/img/reporteOrdenCompra.png') }}" alt=""><br>
                            <button class="btn bg-green ripple">Ver reporte <br>Orden de Compra</button></a>
                    </div> --}}
                </div>
            </div>
        </section>
        {{-- Nuevo codigo --}}
        <section>
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="nav-home-tab" data-toggle="tab" href="#comprasPendientes"
                        role="tab" aria-controls="nav-home" aria-selected="true">Compras Pendientes</a>
                    <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#comprasFinalizada"
                        role="tab" aria-controls="nav-profile" aria-selected="false">Compras Finalizadas</a>
                    {{-- <a class="nav-item nav-link" id="nav-profile-tab" data-toggle="tab" href="#ordenesDeCompra"
                        role="tab" aria-controls="nav-profile" aria-selected="false">Ordenes de compra</a> --}}
                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="comprasPendientes" role="tabpanel"
                    aria-labelledby="nav-home-tab">
                    <table id="tablePendientes" class="table table-responsive-sm" style="width:100%">
                        <thead>
                            <tr class="bg-primary">
                                <th scope="col">Fecha Creacion</th>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Ruc</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Tipo de Moneda</th>
                                <th scope="col">Tipo de Pago</th>
                                <th scope="col">Total</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Tipo de Compra</th>
                                <th scope="col">Editar</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reporteComprasPendientes as $compras)
                                <tr>
                                    <td>{{ $compras->FechaCreacion }}</td>
                                    <td>{{ $compras->Nombres }}</td>
                                    <td>{{ $compras->NumeroDocumento }}</td>
                                    <td>{{ $compras->Serie }}-{{ $compras->Numero }}</td>
                                    <td>{{ $compras->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                                    <td>{{ $compras->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                    <td>{{ $compras->Total }}</td>
                                    <td>{{ $compras->Estado }}</td>
                                    <td>{{ $compras->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                    <td>
                                        <a href="editar-compra/{{ $compras->IdCompras }}" class="btnEditarCompraPendiente"
                                            data-id="{{ $compras->IdCompras }}" title="Editar Compra"><i
                                                class="list-icon material-icons">edit</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="tab-pane fade" id="comprasFinalizada" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <table id="tableRegistradas" class="table table-responsive-sm" style="width:100%">
                        <thead>
                            <tr class="bg-primary">
                                <th scope="col">Fecha Creacion</th>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Ruc</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Tipo de Moneda</th>
                                <th scope="col">Tipo de Pago</th>
                                <th scope="col">Total</th>
                                <th scope="col">Estado</th>
                                <th scope="col">Tipo de Compra</th>
                                <th scope="col">Opciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reporteComprasRegistradas as $compras)
                                <tr>
                                    <td>{{ $compras->FechaCreacion }}</td>
                                    <td>{{ $compras->Nombres }}</td>
                                    <td>{{ $compras->NumeroDocumento }}</td>
                                    <td>{{ $compras->Serie }}-{{ $compras->Numero }}</td>
                                    <td>{{ $compras->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                                    <td>{{ $compras->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                    <td>{{ $compras->Total }}</td>
                                    <td>{{ $compras->Estado }}</td>
                                    <td>{{ $compras->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                    <td>
                                        @if ($compras->Estado == 'Registrado')
                                            <a href="#" data-toggle="modal" data-target=".bs-modal-sm-anular"
                                                title="Anular"
                                                onclick="anular({{ $compras->IdCompras }}, '{{ $compras->Serie }}', '{{ $compras->Numero }}', '{{ $compras->FechaCreacion }}')"><i
                                                    class="list-icon material-icons text-danger">do_not_disturb</i></a>
                                            <a href="javascript:void(0);" title="Editar"
                                                onclick="abrirModal({{ $compras->IdCompras }}, {{ $compras->IdProveedor }}, '{{ $compras->Serie }}', '{{ $compras->Numero }}', '{{ $compras->FechaCreacion }}', {{ $compras->IdCreacion }}, {{ $compras->IdTipoComprobante }})"><i
                                                    class="list-icon material-icons">edit</i></a>
                                        @endif
                                        <a href="../compras/comprobante-generado/{{ $compras->IdCompras }}"><i
                                                class="list-icon material-icons">visibility</i></a>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                {{-- <div class="tab-pane fade" id="ordenesDeCompra" role="tabpanel" aria-labelledby="nav-profile-tab">
                    <table id="tableOrdenesDeCompra" class="table table-responsive-sm" style="width:100%">
                        <thead>
                            <tr class="bg-primary">
                                <th scope="col">Fecha Emision</th>
                                <th scope="col">Proveedor</th>
                                <th scope="col">Ruc</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Tipo de Moneda</th>
                                <th scope="col">Tipo de Pago</th>
                                <th scope="col">Total</th>
                                <th scope="col">Tipo de Compra</th>
                                <th scope="col">Ver Detalle</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($reporteOrdenesCompra as $compras)
                                <tr>
                                    <td>{{ $compras->FechaEmision }}</td>
                                    <td>{{ $compras->Nombres }}</td>
                                    <td>{{ $compras->NumeroDocumento }}</td>
                                    <td>{{ $compras->Serie }}-{{ $compras->Numero }}</td>
                                    <td>{{ $compras->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                                    <td>{{ $compras->IdTipoPago == 1 ? 'Contado' : 'Crédito' }}</td>
                                    <td>{{ $compras->Total }}</td>
                                    <td>{{ $compras->TipoCompra == 1 ? 'Gravada' : 'Exonerada' }}</td>
                                    <td> <a
                                            href="../../operaciones/compras/comprobante-orden-compra/{{ $compras->IdOrdenCompra }}"><i
                                                class="list-icon material-icons">visibility</i></a>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div> --}}
            </div>
        </section>
        {{-- Fin --}}

        {{-- Modal --}}
        <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin --}}
    {{-- Modal Mensaje --}}
    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header d-flex justify-content-center">
                    <h6 class="text-success">Listado de Compras</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <p class="fs-15negrita">En la Tabla se mostraran solo las compras de los ULTIMOS TREINTA DÍAS</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions btn-list mt-3">
                        <button class="btn btn-info" type="button" data-dismiss="modal">Aceptar</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin --}}

    <div class="modal modal-primary fade bs-modal-sm-anular" tabindex="-1" role="dialog"
        aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
        <div class="modal-dialog modal-md">
            {!! Form::open([
                'url' => '/operaciones/compras/anular-compra',
                'method' => 'POST',
                'files' => true,
                'class' => 'form-material',
                'id' => 'formBaja',
            ]) !!}
            <div class="modal-content">
                <div class="modal-header text-inverse">
                    <h6 class="modal-title" id="mySmallModalLabel2">Dar Baja Compras</h6>
                </div>
                <div class="modal-body">
                    <text>¿Deseas dar de baja esta compra?</text>
                    <div class="container">
                        <input id="inpCompras" hidden class="form-control" name="idCompras" />
                        <input id="inpBanco" hidden class="form-control" name="idBanco" value='0' />
                    </div>
                </div>
                <div class="form-group" id="checkReponer">
                    <input type="checkbox" id="reponer" name="reponer"><span id="textoDescontar"
                        class="pd-l-10 pd-l-0-rtl pd-r-50-rtl text-warning">Reponer gasto de compra en Cuenta
                        Corriente</span>
                </div>
                <div class="modal-footer">
                    <button id="btnEnvio" type="submit" class="btn btn-primary">Anular</button>
                    <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>

    {{-- Modal comprobar Permiso --}}
    <div class="modal fade modalcomprobarPermiso" id="exampleModalCentered" tabindex="-1" role="dialog"
        aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="form-group text-center mt-3">
                    <input id="id" class="d-none" type="text" value="">
                    <label for="formGroupExampleInput">Ingrese clave de Supervisor para Proceder Editar el código</label>
                    <input id="password" value="" type="text" class="form-control text-center"
                        placeholder="********" style="-webkit-text-security: disc;" autocomplete="off">
                    <div id="mensaje" class="py-1">
                    </div>
                    <button id="btnComprobar" type="button" class="btn btn-primary">Comprobar Permiso</button>
                </div>
            </div>
        </div>
    </div>
    {{-- Fin --}}
    <div class="modal fade bs-modal-lg-cliente" id="modalEditarDocumento" tabindex="-1" role="dialog"
        aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h6>Editar Documento</h6>
                </div>
                <div class="modal-body">
                    <div class="row form-material contenedorInput">
                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="tipoDoc" class="form-control" name="tipoDocumento">
                                </select>
                                <label for="tipoDoc">Tipo Documento</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <select id="proveedores" class="form-control" name="proveedores">
                                </select>
                                <label for="tipoDoc">Proveedor</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Serie</label></div>
                                    </div>
                                    <input id="serie" class="form-control" placeholder="Serie" type="text"
                                        name="serie">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <div class="input-group">
                                    <div class="input-group-append">
                                        <div class="input-group-text"><label>Número</label></div>
                                    </div>
                                    <input id="numero" class="form-control" placeholder="Número" name="numero"
                                        type="number" onkeypress='return event.charCode >= 48 && event.charCode <= 57'>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <input type="text" id="idCompra" hidden>
                        <input type="text" id="fechaCreacion" hidden>
                        <input type="text" id="idUsuarioCompra" hidden>
                        <input type="text" id="serieAnterior" hidden>
                        <input type="text" id="numeroAnterior" hidden>
                    </div>
                    <div class="form-actions btn-list mt-3 d-flex justify-content-end">
                        <button class="btn btn-primary" onclick="actualizarDocumento();" type="button">Actualizar
                            Documento</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/respuestaAjax/respuestaSweetAlert2.js?v=' . time()) }}"></script>
    <script src="{{ asset('assets/js/scriptCompras.js') }}"></script>

    <script>
        // Codigo para cargar poner el focus en input del modal
        $('body').on('shown.bs.modal', '.modalcomprobarPermiso', function() {
            $('input:visible:enabled:first', this).focus();
        });
        // Fin
        function CerrarModal() {
            $("#exampleModalCentered").modal('hide');
        }

        function abrirModal(idCompra, idProveedor, serie, numero, fechaCreacion, idUsuarioCompra, IdTipoComprobante) {
            $(".modalcomprobarPermiso").modal('show');
            $('#password').removeClass('border-danger');
            $("p").remove();
            $('#password').val("");
            $('#btnComprobar').click(function() {
                var password = $('#password').val();
                if (password !== "") {
                    $.ajax({
                        type: "get",
                        url: "validar-password-supervisor",
                        data: {
                            'password': password
                        },
                        success: function(data) {
                            $('p').remove();
                            if (data[0] == 'Success') {
                                CerrarModal();
                                $('#password').val("");
                                $('#btnActualizar').removeClass('d-none');
                                setTimeout(() => {
                                    abrirModalEditarDocumento(idCompra, idProveedor, serie,
                                        numero, fechaCreacion, idUsuarioCompra,
                                        IdTipoComprobante);
                                }, 200);
                            } else {
                                $('#mensaje').append(
                                    '<p class="text-center text-danger font-weight-bold">Error la clave no coincide</p>'
                                )
                                $('#password').val("");
                                $('#password').focus();
                                $('#password').addClass('border-danger');
                            }
                        }
                    })
                } else {
                    $('p').remove();
                    $('#mensaje').append(
                        '<p class="text-center text-danger font-weight-bold">Por favor ingrese la clave</p>');
                    $('#password').addClass('border-danger');
                    $('#password').focus();
                }
            })
        }

        function abrirModalEditarDocumento(idCompra, idProveedor, serie, numero, fechaCreacion, idUsuarioCompra,
            IdTipoComprobante) {
            $('#idCompra').val(idCompra);
            $('#serie').val(serie);
            $('#numero').val(numero);
            $('#serieAnterior').val(serie);
            $('#numeroAnterior').val(numero);
            $('#fechaCreacion').val(fechaCreacion);
            $('#idUsuarioCompra').val(idUsuarioCompra);
            $('#proveedores option').remove();
            $('#tipoDoc option').remove();
            const listaProveedores = @json($listaProveedores);
            const listaTipoComprobante = @json($tipoComprobante);
            listaTipoComprobante.map(function(comprobante) {
                if (comprobante.IdTipoComprobante == IdTipoComprobante) {
                    $('#tipoDoc').append('<option selected value="' + comprobante.IdTipoComprobante + '">' +
                        comprobante.Descripcion + '</option>');
                } else {
                    $('#tipoDoc').append('<option value="' + comprobante.IdTipoComprobante + '">' +
                        comprobante.Descripcion + '</option>');
                }
            });

            listaProveedores.map(function(proveedor) {
                if (proveedor.IdProveedor == idProveedor) {
                    $('#proveedores').append('<option selected value="' + proveedor.IdProveedor + '">' +
                        proveedor.Nombre + '</option>');
                } else {
                    $('#proveedores').append('<option value="' + proveedor.IdProveedor + '">' +
                        proveedor.Nombre + '</option>');
                }
            });

            $("#modalEditarDocumento").modal('show');
        }

        function actualizarDocumento() {
            var idTipoDocumento = $('#tipoDoc').val();
            var serie = $('#serie').val();
            var numero = $('#numero').val();
            var serieAnterior = $('#serieAnterior').val();
            var numeroAnterior = $('#numeroAnterior').val();
            var idCompra = $('#idCompra').val();
            var idProveedor = $('#proveedores').val();
            var fechaCreacion = $('#fechaCreacion').val();
            var idUsuarioCompra = $('#idUsuarioCompra').val();
            $.ajax({
                type: 'post',
                url: 'lista-compras/actualizar-documento',
                data: {
                    "_token": "{{ csrf_token() }}",
                    'idTipoDocumento': idTipoDocumento,
                    'serie': serie,
                    'numero': numero,
                    'idCompra': idCompra,
                    'idProveedor': idProveedor,
                    'fechaCreacion': fechaCreacion,
                    'idUsuarioCompra': idUsuarioCompra,
                    'serieAnterior': serieAnterior,
                    'numeroAnterior': numeroAnterior

                },
                success: function(data) {

                    if (data[0] == 'alert01') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error...',
                            text: data[1],
                        })
                    } else {
                        if (data[0] == 'alert02') {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error...',
                                text: data[1],
                            })
                        } else {
                            if (data[0] == 'documentoDuplicado') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error...',
                                    text: data[1],
                                })
                            } else {
                                $("#modalEditarDocumento").modal('hide');
                                Swal.fire({
                                    position: 'center',
                                    icon: 'success',
                                    text: data[1],
                                    showConfirmButton: false,
                                    timer: 500
                                }).then(() => {
                                    window.location = 'lista-compras';
                                });
                            }
                        }
                    }
                }
            })

        }
    </script>


    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tablePendientes').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
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

        $(function() {
            $(document).ready(function() {
                $('#tableRegistradas').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
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

        $(function() {
            $(document).ready(function() {
                $('#tableOrdenesDeCompra').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
                    ],
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
        // Nuevo codigo
        $('#btnCrearCompra').click((e) => {
            e.preventDefault();
            verificarCajaAndRedirigir('crear-compra',
                'Antes de generar una compra, tiene que estar la caja abierta');
        })

        $(document).on('click', (e) => {
            if (e.target.closest('.btnEditarCompraPendiente')) {
                e.preventDefault();
                const idCompra = $(e.target).closest('.btnEditarCompraPendiente').data('id');
                verificarCajaAndRedirigir(`editar-compra/${idCompra}`,
                    'Antes de editar la compra, tiene que estar la caja abierta');
            }
        })

        const verificarCajaAndRedirigir = (url, mensaje) => {
            $caja = @json($caja);
            if ($caja == null) {
                respuestaInfoValidacion('Caja Cerrada',
                    mensaje, 'Abrir Caja',
                    '../../caja/cierre-caja')
            } else {
                window.location.href = url;
            }
        }

        $(function() {
            respuestaInfoMensaje(null, 'En la Tabla se mostraran solo las compras de los ULTIMOS TREINTA DÍAS')
        });
        // Fin


        function anular(id, serie, numero, fecha) {
            $('#inpCompras').val(id);
            $.ajax({
                type: 'get',
                url: 'verificar-compra',
                data: {
                    'serie': serie,
                    'numero': numero,
                    'fechaCompra': fecha
                },
                success: function(data) {
                    if (data.length > 0) {
                        $('#checkReponer').show();
                        $('#inpBanco').val(data[0]['IdBanco']);
                        //alert(data[0]['IdBanco']);
                    } else {
                        $('#checkReponer').hide();
                    }

                }
            });
        }
    </script>
@stop
