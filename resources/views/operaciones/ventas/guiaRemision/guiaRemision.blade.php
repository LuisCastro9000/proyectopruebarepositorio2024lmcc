<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1,shrink-to-fit=no">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/img/favicon.png') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/pace.css') }}">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Generar Guía de Remisión</title>
    <!-- CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/css/select2.min.css" rel="stylesheet"
        type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Poppins:200,300,400,500,600" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/material-icons/material-icons.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/vendors/mono-social-icons/monosocialiconsfont.css') }}" rel="stylesheet"
        type="text/css">
    <link href="{{ asset('assets/vendors/feather-icons/feather.css') }}" rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/css/bootstrap-datepicker.min.css"
        rel="stylesheet" type="text/css">

    <link href="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/css/perfect-scrollbar.min.css"
        rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/responsive.dataTables.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/main.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('assets/css/newStyles.css?v=' . rand(1, 99)) }}" rel="stylesheet" type='text/css'>
    <link href="{{ asset('assets/css/loading.min.css') }}" rel="stylesheet" type="text/css">
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
                            <h6 class="page-title-heading mr-0 mr-r-5">Generar Guía de Remitente</h6>
                        </div>
                        <!-- /.page-title-left -->
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger" role="alert">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('status'))
                        <div class="alert alert-success">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('status') }}
                        </div>
                    @endif
                    @if (session('caja'))
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>
                            {{ session('caja') }}
                            <a href="../../caja/cierre-caja"><button class="btn btn-info">Ir a Caja</button></a>
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
                                        {!! Form::open(['url' => '/operaciones/ventas/guia-remision', 'method' => 'POST', 'files' => true, 'id'=>'myform']) !!}
                                        {{ csrf_field() }}
                                        <div class="row form-material">
                                            <div class="col-12">
                                                <div class="form-group">
                                                    <div class="radiobox radio-success">
                                                        <label>
                                                            <input id="radio1" type="radio" class="ik"
                                                                name="option" value="1" checked="checked"> <span
                                                                class="label-text">Guía por Venta
                                                                &ensp;&ensp;&ensp;&ensp;</span>
                                                        </label>
                                                        <label>
                                                            <input id="radio2" type="radio" class="ik"
                                                                name="option" value="2"><span
                                                                class="label-text">Guía de Traslado</span>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-material">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="selectMotivo" class="form-control" name="motivo">
                                                        <option value="0">-</option>
                                                        @foreach ($motivosGuias as $motivo)
                                                            @if(old('motivo') == $motivo->IdMotivo)
                                                            <option value="{{ $motivo->IdMotivo }}" selected>
                                                                {{ $motivo->Descripcion }} </option>
                                                            @else
                                                            <option value="{{ $motivo->IdMotivo }}">
                                                                {{ $motivo->Descripcion }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <label>Motivo</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Serie</label></div>
                                                        </div>
                                                        <input id="serie" class="form-control" value="{{ old('serie') }}"
                                                            placeholder="Serie" maxlength="4" type="text"
                                                            name="serie" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><label>Número</label></div>
                                                        </div>
                                                        <input id="numero" class="form-control" value="{{ old('numero') }}"
                                                            placeholder="Número" maxlength="8" type="text"
                                                            name="numero" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="datosCliente">
                                            <div id="documentos" class="col-md-4">
                                                <select id="_documentos" class="m-b-10 form-control docSelect" name="documentos"
                                                    data-placeholder="Seleccione Documento" data-toggle="select2">
                                                    <option value="0">Seleccione Comprobante</option>
                                                    @foreach ($reportesVentasAceptados as $reporteAceptado)
                                                        @if(old('documentos') == $reporteAceptado->IdVentas)
                                                        <option value="{{ $reporteAceptado->IdVentas }}" selected>
                                                            {{ $reporteAceptado->Serie }}-{{ $reporteAceptado->Numero }}
                                                        </option>
                                                        @else
                                                        <option value="{{ $reporteAceptado->IdVentas }}">
                                                            {{ $reporteAceptado->Serie }}-{{ $reporteAceptado->Numero }}
                                                        </option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div id="clientes" class="col-md-4">
                                                <select id="_clientes" class="m-b-10 form-control docSelect" name="clientes"
                                                    data-placeholder="Seleccione Cliente" data-toggle="select2">
                                                    <option value="0">Seleccione Cliente</option>
                                                    @foreach ($clientes as $_cliente)
                                                        @if(old('clientes') == $_cliente->IdCliente)
                                                        <option value="{{ $_cliente->IdCliente }}" selected>
                                                            {{ $_cliente->RazonSocial }}</option>
                                                        @else
                                                        <option value="{{ $_cliente->IdCliente }}">
                                                            {{ $_cliente->RazonSocial }}</option>
                                                        @endif
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div class="col-md-1">
                                                <a href="javascript:void(0);" id="btnEnvio" title="Detalles"
                                                    class="btn btn-block btn-primary ripple"><i
                                                        class="list-icon material-icons fs-20">add</i></a>
                                                <input class="form-control" id="idTipoComprobante"
                                                    name="idTipoComprobante" hidden value="{{ $idTipoComprobante }}">
                                                <input class="form-control" id="idVentas" name="idVentas" hidden
                                                    value="{{ $idVentas }}">
                                                <input class="form-control" id="codComprobante" name="codComprobante"
                                                    hidden value="{{ $codComprobante }}">
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input id="cliente" type="text" class="form-control"
                                                            name="cliente" placeholder="Cliente" readonly
                                                            value="{{ old('cliente') }}">
                                                    </div>
                                                </div>
                                                <input type="text" id="idCliente" class="form-control"
                                                    name="idCliente" hidden value="{{ $idCliente }}">
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <div class="input-group">
                                                        <input id="documento" type="text" class="form-control" value="{{ old('documento') }}"
                                                            name="documento" placeholder="Nro. Documento" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="sucursales">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>Sucursales</label>
                                                    <select id="sucursal" class="form-control" name="sucursales">
                                                        <option value="0">-</option>
                                                        @foreach ($sucursales as $_sucursal)
                                                            @if(old('sucursales') == $_sucursal->IdSucursal)
                                                            <option value="{{ $_sucursal->IdSucursal }}" selected>
                                                                {{ $_sucursal->Nombre }}</option>
                                                            @else
                                                            <option value="{{ $_sucursal->IdSucursal }}">
                                                                {{ $_sucursal->Nombre }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <input class="form-control" id="codigoSucDestino" name="codigoSucDestino" hidden
                                                 value="0">
                                            </div>
                                        </div>
                                        <hr style="border: 0.5px solid #66afe9">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="departamento">Departamento</label>
                                                    <select id="departamento" class="form-control"
                                                        name="departamento">
                                                        <option value="0">-</option>
                                                        @foreach ($departamentos as $departamento)
                                                            @if ($departamento->IdDepartamento == $sucursal->IdDepartamento)
                                                                <option selected
                                                                    value="{{ $departamento->IdDepartamento }}">
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
                                                        @foreach ($provincias as $provincia)
                                                            @if($provincia->IdProvincia == $sucursal->IdProvincia)
                                                                <option selected
                                                                    value="{{ $provincia->IdProvincia }}">
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
                                                        @foreach ($distritos as $distrito)
                                                            @if($distrito->IdDistrito == $sucursal->IdDistrito)
                                                                <option selected value="{{ $distrito->IdDistrito }}">
                                                                    {{ $distrito->Nombre }}</option>
                                                            @else
                                                                <option value="{{ $distrito->IdDistrito }}">
                                                                    {{ $distrito->Nombre }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Dirección Origen</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="origen" value="{{$sucursal->DirPrin}}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="border: 0.5px solid #66afe9">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label for="departamento2">Departamento</label>
                                                    <select id="departamento2" class="form-control"
                                                        name="departamento2">
                                                        <option value="0">-</option>
                                                        @foreach ($departamentos as $departamento)
                                                            @if (old('departamento2') == $departamento->IdDepartamento)
                                                            <option value="{{ $departamento->IdDepartamento }}" selected>
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
                                                    <label for="provincia2">Provincia</label>
                                                    <select id="provincia2" class="form-control" name="provincia2">
                                                        <option value="0">-</option>
                                                        @foreach ($provincias2 as $provincia)
                                                        @if (old('provincia2') == $provincia->IdProvincia)
                                                            <option value="{{ $provincia->IdProvincia }}" selected>
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
                                                    <label for="distrito2">Distrito</label>
                                                    <select id="distrito2" class="form-control" name="distrito2">
                                                        <option value="0">-</option>
                                                        @foreach ($distritos2 as $distrito)
                                                            @if (old('distrito2') == $distrito->IdDistrito)
                                                            <option value="{{ $distrito->IdDistrito }}" selected>
                                                                {{ $distrito->Nombre }}</option>
                                                            @else
                                                            <option value="{{ $distrito->IdDistrito }}">
                                                                {{ $distrito->Nombre }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Dirección Destino</label>
                                                    <div class="input-group">
                                                        <input id="dirDestino" type="text" class="form-control" value="{{ old('destino') }}"
                                                            name="destino">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <hr style="border: 0.5px solid #66afe9">
                                        <div class="row form-material">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="modalidad" class="form-control" name="modoTraslado">
                                                        <option value="01">Público</option>
                                                        <option value="02">Privado</option>
                                                    </select>
                                                    <label>Modo Traslado</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Número Bultos </label>
                                                    <div class="input-group">
                                                        <input id="cantidadItem" type="number" class="form-control"
                                                            name="bultos" value="{{ old('bultos') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Peso (Kg) <span class="text-danger">(*)</span></label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" class="form-control"
                                                            name="peso" value="{{ old('peso') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div id="trasPublico">
                                        <div class="row form-material">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Razón Social Empresa <span class="text-danger">(*)</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            name="razonSocialEmpresa" value="{{ old('razonSocialEmpresa') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="tipDocEmpresa" class="form-control"
                                                        name="tipDocEmpresa">
                                                            <option value="6">RUC</option>
                                                    </select>
                                                    <label>Tipo Doc. Empresa</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Nro Doc. Empresa <span class="text-danger">(*)</span></label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="rucEmpresa" value="{{ old('rucEmpresa') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-material">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Transportista</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control"
                                                            name="transportista" value="{{ old('transportista') }}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select id="tipDocTrans" class="form-control"
                                                        name="tipDocTransp">
                                                        @foreach ($tiposDoc as $tipodoc)
                                                            @if (old('tipDocTransp') == $tipodoc->CodigoSunat)
                                                            <option value="{{ $tipodoc->CodigoSunat }}" selected>
                                                                {{ $tipodoc->Descripcion }}</option>
                                                            @else
                                                            <option value="{{ $tipodoc->CodigoSunat }}">
                                                                {{ $tipodoc->Descripcion }}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <label>Tipo Doc. Transp.</label>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Documento Transp.</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="docTransp" value="{{ old('docTransp') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-material">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Placa Vehicular</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="placa" value="{{ old('placa') }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        </div>
                                        <div class="row form-material">
                                            <div class="col-md-4">
                                                <div class="form-control-label"><label>Fecha Emisión <span class="text-danger">(*)</span></label></div>
                                                <div class="form-group">
                                                    <div class="input-group justify-content-center">
                                                        <input id="datepicker" type="text" readonly
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                            class="form-control" name="fechaEmitida" value="{{ old('fechaEmitida') }}">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i
                                                                    class="list-icon material-icons">date_range</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-control-label"><label>Fecha Traslado <span class="text-danger">(*)</span></label></div>
                                                <div class="form-group">
                                                    <div class="input-group justify-content-center">
                                                        <input id="datepicker2" type="text"
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' data-date-start-date="0d"
                                                            class="form-control datepicker" name="fechaTraslado" value="{{ old('fechaTraslado') }}">
                                                        <div class="input-group-append">
                                                            <div class="input-group-text"><i
                                                                    class="list-icon material-icons">date_range</i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row form-material">
                                            <div class="col-lg-8">
                                                <div class="form-group">
                                                    <textarea class="form-control" rows="5" name="observacion" value="{{ old('observacion') }}"></textarea>
                                                    <label>Observación</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row" id="btnAgregar">
                                            <div class="col-lg-2 col-md-3 col-sm-4 col-5">
                                                <div class="mt-2">
                                                    <a href="#" data-toggle="modal"
                                                        data-target=".bs-modal-lg-productos"><button
                                                            id="agregarArticulo" class="btn btn-info"><i
                                                                class="list-icon material-icons">add_circle</i> Agregar
                                                            <span class="caret"></span></button></a>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row mt-2">
                                            <div class="col-md-12">
                                                <table id="tablaAgregado" class="table table-responsive-lg"
                                                    style="width:100%">
                                                    <thead>
                                                        <tr class="bg-primary-contrast">
                                                            <th class="text-center">Código</th>
                                                            <th class="text-left">Descripción</th>
                                                            <th class="text-center">Uni/Medida</th>
                                                            <th class="text-center">Cantidad</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @foreach ($items as $item)
                                                            <tr>
                                                                <td class="text-center">{{ $item->Cod }}</td>
                                                                <td class="text-left">{{ $item->Descripcion }}
                                                                    {{ $item->Detalle }}</td>
                                                                <td class="text-center">{{ $item->UniMedida }}</td>
                                                                <td class="text-right">{{ $item->Cantidad }}</td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        </div>
                                        <div class="form-actions btn-list mt-3">
                                            <button id="btnGenerar" class="btn btn-primary" type="submit">Generar</button>
                                        </div>
                                        {!! Form::close() !!}
                                    </div>
                                </div>
                                <!-- /.widget-bg -->
                            </div>
                            <!--<div class="title m-b-md">
                            {!! QrCode::size(300)->generate('B001|1|0001') !!}
                         </div>-->
                            <!-- /.widget-holder -->
                        </div>
                        <!-- /.row -->
                    </div>
                    <!-- /.widget-list -->
                </div>

                <div class="modal fade bs-modal-lg-comprobantes" tabindex="-1" role="dialog"
                    aria-labelledby="myLargeModalLabel" aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6>Listado de Comprobantes</h6>
                            </div>
                            <div class="modal-body">
                                <table id="tabla" class="table table-responsive-sm" style="width:100%">
                                    <thead>
                                        <tr class="bg-primary">
                                            <th>Agregar</th>
                                            <th scope="col">Fecha</th>
                                            <th scope="col">Cliente</th>
                                            <th scope="col">Código</th>
                                            <th scope="col">Total</th>
                                            <th scope="col">Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($reportesVentasAceptados as $reporteAceptado)
                                            <tr>
                                                <td><a href="guia-remision-{{ $reporteAceptado->IdVentas }}"
                                                        title="Detalles"><i
                                                            class="list-icon material-icons">check</i></a></td>
                                                <td>{{ $reporteAceptado->FechaCreacion }}</td>
                                                <td>{{ $reporteAceptado->Nombres }}</td>
                                                <td>{{ $reporteAceptado->Serie }}-{{ $reporteAceptado->Numero }}</td>
                                                <td>{{ $reporteAceptado->Total }}</td>
                                                <td>{{ $reporteAceptado->Estado }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success btn-rounded ripple text-left"
                                    data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog"
                    aria-labelledby="basicModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h6>AVISO</h6>
                            </div>
                            <div class="modal-body form-material">
                                <div>
                                    <label class="fs-12 negrita">Si es la primera que trabaja con comprobantes
                                        electrónicos deje la serie y número correlativo de las guías de remisión como
                                        están por defecto, caso contrario edite la serie y número correlativo de cada
                                        uno según SUNAT.</label>
                                </div>
                                <div class="mt-2">
                                    <text class="fs-11">NOTA: La serie y número correlativo de las Guías de Remisión
                                        solamente se ingresara una vez. Luego se manejara automáticamente.</text>
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

                <div class="modal fade bs-modal-lg-productos" role="dialog" aria-labelledby="myLargeModalLabel"
                    aria-hidden="true" style="display: none">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-body">
                                <div class="tabs tabs-bordered">
                                    <div class="tab-content">
                                        <div class="tab-pane active" id="tab-productos">
                                            <div class="clearfix">
                                                <div class="form-group">
                                                    <i
                                                        class="feather feather-search pos-absolute pos-right vertical-center pr-4"></i>
                                                    <input type="search" id="inputBuscarProductos"
                                                        name="textoBuscar" placeholder="Buscar producto..."
                                                        class="form-control form-control-rounded heading-font-family fs-16 pr-5 pl-3">
                                                </div>

                                                <!-- Products List -->
                                                <div id="listaProductos" class="ecommerce-products list-unstyled row">
                                                    @foreach ($productos as $producto)
                                                        <div class="product col-12 col-md-6">
                                                            <div class="card-body">
                                                                <div class="row">
                                                                    <div class="col-6 d-flex">
                                                                        <span class="fs-16" style="line-height: 1;">
                                                                            @if ($producto->IdTipoMoneda == 1)
                                                                                S/
                                                                            @else
                                                                                $
                                                                            @endif
                                                                        </span>
                                                                        <span id="p2-{{ $producto->IdArticulo }}"
                                                                            class="product-price fs-16">{{ $producto->Precio }}</span>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <span id="p1-{{ $producto->IdArticulo }}"
                                                                            class="product-title font-weight-bold fs-16">{{ $producto->Descripcion }}</span>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <span
                                                                            class="text-success fs-12">{{ $producto->Codigo }}</span>
                                                                    </div>
                                                                    <div class="col-12">
                                                                        <span
                                                                            class="text-muted fs-13">{{ $producto->Marca }}
                                                                            / {{ $producto->Categoria }} / </span>
                                                                        <span class="text-danger fs-13">Stock :
                                                                            {{ $producto->Stock }} </span>
                                                                    </div>

                                                                </div>

                                                                <input hidden id="p3-{{ $producto->IdArticulo }}"
                                                                    value="{{ $producto->UM }}" />
                                                                <!-- esto puse 1 -->
                                                                <input hidden
                                                                    id="IdUnidadMedida-{{ $producto->IdArticulo }}"
                                                                    value="{{ $producto->IdUnidadMedida }}" />
                                                                <!-- esto puse 1 -->


                                                                <div class="form-group mt-2" hidden>
                                                                    <label class="col-form-label-sm">Cantidad </label>
                                                                    @if ($producto->Stock < 1)
                                                                        <input id="p4-{{ $producto->IdArticulo }}"
                                                                            type="number" min="0"
                                                                            value="0" class=" text-center" />
                                                                    @else
                                                                        <input id="p4-{{ $producto->IdArticulo }}"
                                                                            type="number" min="1"
                                                                            value="1"
                                                                            max="{{ $producto->Stock }}"
                                                                            class=" text-center" />
                                                                    @endif
                                                                </div>

                                                                <div hidden>
                                                                    <div class="form-group col-12">
                                                                        <label class="col-form-label-sm">Costo</label>
                                                                        <input id="p6-{{ $producto->IdArticulo }}"
                                                                            value="{{ $producto->Costo }}"
                                                                            class="form-control text-center" />
                                                                    </div>
                                                                </div>
                                                                <div hidden>
                                                                    <div class="form-group col-12">
                                                                        <label class="col-form-label-sm">Stock </label>
                                                                        <input id="p7-{{ $producto->IdArticulo }}"
                                                                            value="{{ $producto->Stock }}"
                                                                            class="form-control text-center" />
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="card-footer">
                                                                <div class="product-info col-12">
                                                                    @if ($producto->Stock < 1)
                                                                        <a class="bg-info color-white fs-12 disabled"
                                                                            href="javascript:void(0);">
                                                                            Agotado
                                                                        </a>
                                                                    @else
                                                                        <a class="bg-info color-white fs-12"
                                                                            onclick="agregarProducto({{ $producto->IdArticulo }})"
                                                                            href="javascript:void(0);">
                                                                            <i
                                                                                class="list-icon material-icons">add</i>Agregar
                                                                        </a>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                                <!-- /.ecommerce-products -->
                                                <!-- Product Navigation -->
                                                <div class="col-md-12">
                                                    <nav aria-label="Page navigation">
                                                        <ul id="paginasProductos"
                                                            class="pagination pagination-md d-flex justify-content-center pagProd">
                                                            @if ($productos->onFirstPage())
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Previous"><span
                                                                            aria-hidden="true"><i
                                                                                class="feather feather-chevrons-left"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Previous"><span
                                                                            aria-hidden="true"><i
                                                                                class="feather feather-chevron-left"></i></span></a>
                                                                </li>
                                                            @else
                                                                <li class="page-item"><a class="page-link"
                                                                        href="productos?page=1"
                                                                        aria-label="Previous"><span
                                                                            aria-hidden="true"><i
                                                                                class="feather feather-chevrons-left"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link"
                                                                        href="{{ $productos->previousPageUrl() }}"
                                                                        aria-label="Previous"><span
                                                                            aria-hidden="true"><i
                                                                                class="feather feather-chevron-left"></i></span></a>
                                                                </li>
                                                            @endif

                                                            @if ($productos->currentPage() < 3)
                                                                @for ($i = 1; $i <= 5; $i++)
                                                                    @if ($i > 0 && $i <= $productos->lastPage())
                                                                        @if ($i == $productos->currentPage())
                                                                            <li class="page-item active"><a
                                                                                    class="page-link"
                                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                                            </li>
                                                                        @else
                                                                            <li class="page-item"><a class="page-link"
                                                                                    href="productos?page={{ $i }}">{{ $i }}</a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                @endfor
                                                            @elseif($productos->lastPage() > 2)
                                                                @if ($productos->currentPage() > $productos->lastPage() - 2)
                                                                    @for ($i = $productos->currentPage() - 4; $i <= $productos->lastPage(); $i++)
                                                                        @if ($i > 0 && $i <= $productos->lastPage())
                                                                            @if ($i == $productos->currentPage())
                                                                                <li class="page-item active"><a
                                                                                        class="page-link"
                                                                                        href="javascript:void(0);">{{ $i }}</a>
                                                                                </li>
                                                                            @else
                                                                                <li class="page-item"><a
                                                                                        class="page-link"
                                                                                        href="productos?page={{ $i }}">{{ $i }}</a>
                                                                                </li>
                                                                            @endif
                                                                        @endif
                                                                    @endfor
                                                                @endif
                                                            @endif
                                                            @if ($productos->currentPage() >= 3 && $productos->currentPage() <= $productos->lastPage() - 2)
                                                                @for ($i = $productos->currentPage() - 2; $i <= $productos->currentPage() + 2; $i++)
                                                                    @if ($i > 0 && $i <= $productos->lastPage())
                                                                        @if ($i == $productos->currentPage())
                                                                            <li class="page-item active"><a
                                                                                    class="page-link"
                                                                                    href="javascript:void(0);">{{ $i }}</a>
                                                                            </li>
                                                                        @else
                                                                            <li class="page-item"><a class="page-link"
                                                                                    href="productos?page={{ $i }}">{{ $i }}</a>
                                                                            </li>
                                                                        @endif
                                                                    @endif
                                                                @endfor
                                                            @endif
                                                            @if ($productos->hasMorePages())
                                                                <li class="page-item"><a class="page-link"
                                                                        href="{{ $productos->nextPageUrl() }}"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevron-right"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link"
                                                                        href="productos?page={{ $productos->lastPage() }}"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevrons-right"></i></span></a>
                                                                </li>
                                                            @else
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevron-right"></i></span></a>
                                                                </li>
                                                                <li class="page-item"><a class="page-link disabled"
                                                                        aria-label="Next"><span aria-hidden="true"><i
                                                                                class="feather feather-chevrons-right"></i></span></a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </nav>
                                                </div>
                                                <!-- /.col-md-12 -->
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success btn-rounded ripple text-left"
                                    data-dismiss="modal">Cerrar</button>
                            </div>
                        </div>
                    </div>
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.5/js/select2.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/metisMenu/2.7.9/metisMenu.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.perfect-scrollbar/1.4.0/perfect-scrollbar.min.js"></script>
    <script src="{{ asset('assets/js/jquery.loading.min.js') }}"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.8.0/js/bootstrap-datepicker.min.js"></script>

    <script src="{{ asset('assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/template.js') }}"></script>
    <script src="{{ asset('assets/js/custom.js') }}"></script>
    <script type="text/javascript">
        var bandOpc = 0;
        var textUrl = '';
        var arrayIds = [];
        var j = 0;
        $('#clientes').hide();
        $('#btnAgregar').hide();
        $('#sucursales').hide();
        $('[data-toggle="select2"]').select2();

        $('.docSelect').change(function() {
            ever = $(this).val();
            bandOpc = ever;
            //textUrl='guia-remision/'+ever+'/'+opc;
            //$('#btnEnvio').prop('href', textUrl);
        });

        $('#btnEnvio').click(function(e) {
            if (bandOpc == 0) {
                e.preventDefault();
                alert("Seleccione el Comprobante..... ");
            } else {
                $.showLoading({
                    name: 'circle-fade',
                });
                //$('#btnGenerar').attr("disabled", true);
                var option = $("#radio1").is(':checked');
                if (option) {
                    opc = 1;
                } else {
                    opc = 2;
                }
                $.ajax({
                    type: 'get',
                    url: 'obtener-datos',
                    data: {
                        'id': bandOpc,
                        'option': opc
                    },
                    success: function(data) {
                        if (data != null) {
                            for (var i = 0; i < data["provincias2"].length; i++) {
                                $('#provincia2').append('<option value="' + data["provincias2"][i][
                                    "IdProvincia"
                                ] + '">' + data["provincias2"][i]["Nombre"] + '</option>');
                            }

                            for (var i = 0; i < data["distritos2"].length; i++) {
                                $('#distrito2').append('<option value="' + data["distritos2"][i][
                                    "IdDistrito"
                                ] + '">' + data["distritos2"][i]["Nombre"] + '</option>');
                            }
                            $('#tablaAgregado tr:gt(0)').remove();
                            $('#cliente').val(data["cliente"]);
                            $('#idCliente').val(data["idCliente"]);
                            $('#idVentas').val(data["idVentas"]);
                            $('#idTipoComprobante').val(data["idTipoComprobante"]);
                            $('#codComprobante').val(data["codComprobante"]);
                            $('#documento').val(data["nroDocumento"]);
                            $('#departamento2 option[value=' + data["idDepartamento"] + ']').prop(
                                'selected', true);
                            $('#provincia2 option[value=' + data["idProvincia"] + ']').prop('selected',
                                true);
                            $('#distrito2 option[value=' + data["idDistrito"] + ']').prop('selected',
                                true);
                            $('#dirDestino').val(data["dirCliente"]);
                            //$('#cantidadItem').val(data["cantidadItems"]);
                            if (opc == 1) {
                                for (var i = 0; i < data["items"].length; i++) {
                                    if(data["items"][i]["VerificaTipo"] > 0){
                                        var fila = '<tr id="row' + i + '"><td id="cod' + i +
                                            '" class="text-center">' + data["items"][i]["Cod"] +
                                            '</td><td id="descrip' + i + '" class="text-left">' + data[
                                                "items"][i]["Descripcion"] +
                                            '</td><td id="detalle' + i + '" class="text-center">' + data[
                                                "items"][i]["UniMedida"] +
                                            '</td><td id="um' + i + '" class="text-center">' + data["items"]
                                            [i]["Cantidad"] +
                                            '</td>' +
                                            '</tr>';
                                        $('#tablaAgregado tr:last').after(fila);
                                    }
                                }
                            }
                        } else {
                            alert("no se encontraron datos");
                        }
                        $.hideLoading();
                    }
                });
            }
        });

        $('#modalidad').change(function() {
            var tipoMotivo = $("#modalidad").val();
            if(tipoMotivo == 01){
                $("#trasPublico").show();
            }else{
                $("#trasPublico").hide();
            }
        });

        $('#selectMotivo').change(function() {
            $('#serie').val('');
            $('#numero').val('');
            var tipoMotivo = $("#selectMotivo").val();
            if (tipoMotivo == 16) {
                $("#datosCliente").hide();
                $('#sucursales').show();
            } else {
                $("#datosCliente").show();
                $('#sucursales').hide();
            }
            $.ajax({
                type: 'get',
                url: 'obtener-informacion',
                data: {
                    'tipoDoc': tipoMotivo
                },
                success: function(result) {
                    if (result.error) {
                        $('#serie').val('');
                        $('#numero').val('');

                        alert('Seleccione el Motivo');
                    } else {
                        $('#serie').val(result.serie);
                        $('#numero').val(result.numero);
                    }
                }
            });
        });

        $('#sucursal').change(function() {
            var idSucursal = $("#sucursal").val();
            if (idSucursal > 0) {
                $.ajax({
                    type: 'get',
                    url: 'mostrar-sucursales',
                    data: {
                        'idSucursal': idSucursal
                    },
                    success: function(result) {
                        if (result.error) {
                            alert('Seleccione Sucursal');
                        } else {
                            $('#provincia2 option').remove();
                            $('#provincia2').append('<option value="0">-</option>');
                            $('#distrito2 option').remove();
                            $('#distrito2').append('<option value="0">-</option>');
                            for (var i = 0; i < result.provincias.length; i++) {
                                $('#provincia2').append('<option value="' + result.provincias[i][
                                    "IdProvincia"
                                ] + '">' + result.provincias[i]["Nombre"] + '</option>');
                            }

                            for (var i = 0; i < result.distritos.length; i++) {
                                $('#distrito2').append('<option value="' + result.distritos[i][
                                    "IdDistrito"
                                ] + '">' + result.distritos[i]["Nombre"] + '</option>');
                            }
                            $('#departamento2 option[value=' + result.sucursal["IdDepartamento"] + ']')
                                .prop('selected', true);
                            $('#provincia2 option[value=' + result.sucursal["IdProvincia"] + ']').prop(
                                'selected', true);
                            $('#distrito2 option[value=' + result.sucursal["IdDistrito"] + ']').prop(
                                'selected', true);
                            $('#dirDestino').val(result.sucursal["Direccion"]);

                            $('#codigoSucDestino').val(result.sucursal["CodFiscal"]);
                        }
                    }
                });
            }
        });

        $(".ik").change(function() {
            var option = $(this).val();
            if (option == "1") {
                $.showLoading({
                    name: 'circle-fade',
                });
                $('#documentos').show();
                $('#clientes').hide();
                $.ajax({
                    type: 'get',
                    url: 'mostrar-documentos',
                    data: {
                        'option': option
                    },
                    success: function(data) {
                        $('#btnAgregar').hide();
                        $("#datosCliente").show();
                        $('#sucursales').hide();
                        //$('#_documentos option').remove();
                        $('#selectMotivo option').remove();
                        //$('#_documentos').append('<option value="0">Seleccione Comprobante</option>');
                        $('#selectMotivo').append('<option value="0">-</option>');
                        $('#cliente').val('-');
                        $('#codComprobante').val('');
                        $('#documento').val('');
                        $('#dirDestino').val('');
                        $('#provincia2 option').remove();
                        $('#provincia2').append('<option value="0">-</option>');
                        $('#distrito2 option').remove();
                        $('#distrito2').append('<option value="0">-</option>');
                        $('#tablaAgregado tr:gt(0)').remove();
                        //if(data.array.length > 0){
                        /*for(var i=0; i<data.array.length; i++){ 
                            $('#_documentos').append('<option value="'+data.array[i]["IdVentas"]+'">'+data.array[i]["Serie"]+'-'+data.array[i]["Numero"]+'</option>');    
                        }*/

                        for (var i = 0; i < data.motivos.length; i++) {
                            $('#selectMotivo').append('<option value="' + data.motivos[i]["IdMotivo"] +
                                '">' + data.motivos[i]["Descripcion"] + '</option>');
                        }
                        /*}else{
                            alert("No se encontraron datos");
                        }*/
                        $.hideLoading();
                    }
                });
            } else {
                $.showLoading({
                    name: 'circle-fade',
                });
                $('#documentos').hide();
                $('#clientes').show();
                $.ajax({
                    type: 'get',
                    url: 'mostrar-documentos',
                    data: {
                        'option': option
                    },
                    success: function(data) {
                        $('#btnAgregar').show();
                        //$('#_clientes option').remove();
                        $('#selectMotivo option').remove();
                        //$('#_clientes').append('<option value="0">Seleccione Cliente</option>');
                        $('#selectMotivo').append('<option value="0">-</option>');
                        $('#cliente').val('-');
                        $('#codComprobante').val('');
                        $('#documento').val('');
                        $('#dirDestino').val('');
                        $('#provincia2 option').remove();
                        $('#provincia2').append('<option value="0">-</option>');
                        $('#distrito2 option').remove();
                        $('#distrito2').append('<option value="0">-</option>');
                        $('#tablaAgregado tr:gt(0)').remove();
                        //if(data.array.length > 0){
                        /*for(var i=0; i<data.array.length; i++){ 
                            $('#_clientes').append('<option value="'+data.array[i]["IdCliente"]+'">'+data.array[i]["RazonSocial"]+'</option>');    
                        }*/

                        for (var i = 0; i < data.motivos.length; i++) {
                            $('#selectMotivo').append('<option value="' + data.motivos[i]["IdMotivo"] +
                                '">' + data.motivos[i]["Descripcion"] + '</option>');
                        }
                        /*}else{
                            alert("No se encontraron datos");
                        }*/
                        $.hideLoading();
                    }
                });
            }
        });

        function agregarProducto(id) {
            if (arrayIds.includes(id) == true) {
                alert("Producto ya agregado, por favor modificar la cantidad si desea agregar más");
                return 0;
            } else {
                var descripcion = $('#p1-' + id).text();
                var unidadMedida = $('#p3-' + id).val();
                var precio = $('#p2-' + id).text();
                var cantidad = $('#p4-' + id).val();
                var stock = $('#p7-' + id).val();
                var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
                j = j + 1;
                var fila = '<tr id="row' + id + '"><td class="text-center"><input id="cod' + id +
                    '" name="Codigo[]" readonly type="text" value="PRO-' + id + '" style="width:100px">' +
                    '</td><td id="descrip' + id + '" class="text-left">' + descripcion +
                    '</td><td id="detalle' + id + '" class="text-center">' + unidadMedida +
                    '</td><td class="text-center"><input id="cantidad' + id + '" name="Cantidad[]" min="1" max="' + stock +
                    '" type="number" step=".5" value="' + cantidad + '" style="width:100px">' +
                    '</td><td style="width:80px"><button id="btn' + id + '" onclick="quitar(' + id +
                    ')" class="btn btn-primary" style="width:40px"><i class="list-icon material-icons fs-16">clear</i></button>' +
                    '</td>' +
                    '</tr>';
                $('#tablaAgregado tr:last').after(fila);

                arrayIds.push(parseInt(id));
            }
        }

        $("#inputBuscarProductos").keyup(function() {
            var textoBusqueda = $("#inputBuscarProductos").val();
            var stock = '';
            $.ajax({
                type: 'get',
                url: 'buscar-productos-guias',
                data: {
                    'textoBuscar': textoBusqueda
                },
                success: function(data) {
                    $('#listaProductos').empty();
                    var moneda = 'S/';
                    for (var i = 0; i < data["data"].length; i++) {

                        if (data["data"][i]["Stock"] < 1) {
                            stock =
                                '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' +
                                data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }

                        if (data["data"][i]["IdTipoMoneda"] == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }


                        $('#listaProductos').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-6 d-flex">' +
                            '<span class="fs-16" style="line-height: 1;">' + moneda + '</span>' +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] +
                            '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + data["data"][i]["Codigo"] +
                            '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted fs-13">' + data["data"][i]["Marca"] + ' / ' +
                            data["data"][i]["Categoria"] +
                            ' / </span><span class="text-danger fs-13">Stock : ' + data["data"][i][
                                "Stock"
                            ] + '</span>' +
                            '</div>' +
                            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' +
                            data["data"][i]["UM"] + '"/>' +
                            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            '<input id="p4-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] +
                            '" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["Costo"] + '" class="form-control text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Stock </label>' +
                            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["Stock"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');

                    }

                    $('#paginasProductos').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data[
                                "first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data[
                                "prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        primero =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    }


                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' +
                                        i + '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' +
                                        i + '">' + i + '</a></li>';
                                }
                            }
                        }
                    }


                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' +
                            (data["current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' +
                            data["last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('#paginasProductos').append(concatenacion);
                }

            });
        });

        $(document).on('click', '.pagProd a', function(e) {
            e.preventDefault();
            var page = $(this).attr('href').split('page=')[1];
            getProductos(page);
        });

        function getProductos(page) {
            var textoBusqueda = $('#inputBuscarProductos').val();
            var stock = '';

            $.ajax({
                type: 'get',
                url: 'productos-guias?page=' + page,
                data: {
                    'textoBuscar': textoBusqueda
                },
                success: function(data) {
                    var moneda = 'S/';
                    $('#listaProductos').empty();
                    for (var i = 0; i < data["data"].length; i++) {

                        if (data["data"][i]["Stock"] < 1) {
                            stock =
                                '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
                        } else {
                            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data[
                                    "data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                                '<i class="list-icon material-icons">add</i>Agregar' +
                                '</a>';
                        }

                        if (data["data"][i]["IdTipoMoneda"] == 1) {
                            moneda = 'S/';
                        } else {
                            moneda = '$';
                        }

                        $('#listaProductos').append('<div class="product col-12 col-md-6">' +
                            '<div class="card-body">' +
                            '<div class="row">' +
                            '<div class="col-6 d-flex">' +
                            '<span class="fs-16" style="line-height: 1;">' + moneda + '</span>' +
                            '<span id="p2-' + data["data"][i]["IdArticulo"] +
                            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span id="p1-' + data["data"][i]["IdArticulo"] +
                            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
                                "Descripcion"
                            ] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-success fs-12">' + data["data"][i]["Codigo"] + '</span>' +
                            '</div>' +
                            '<div class="col-12">' +
                            '<span class="text-muted fs-13">' + data["data"][i]["Marca"] + ' / ' + data[
                                "data"][i]["Categoria"] +
                            ' / </span><span class="text-danger fs-13">Stock : ' + data["data"][i][
                            "Stock"] + '</span>' +
                            '</div>' +
                            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data[
                                "data"][i]["UM"] + '"/>' +
                            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
                            '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
                            '<div class="form-group mt-2" hidden>' +
                            '<label class="col-form-label-sm">Cantidad </label>' +
                            '<input id="p4-' + data["data"][i]["IdArticulo"] +
                            '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] +
                            '" class="text-center" />' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Costo</label>' +
                            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Costo"] + '" class="form-control text-center" />' +
                            '</div>' +
                            '</div>' +
                            '<div hidden>' +
                            '<div class="form-group col-12">' +
                            '<label class="col-form-label-sm">Stock </label>' +
                            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                            ["Stock"] + '" class="form-control text-center"/>' +
                            '</div>' +
                            '</div>' +
                            '</div>' +
                            '<div class="card-footer">' +
                            '<div class="product-info col-12">' + stock +
                            '</div>' +
                            '</div>' +
                            '</div>');
                    }


                    $('#paginasProductos').empty();
                    var primero = '';
                    var ultimo = '';
                    var anterior = '';
                    var paginas = '';
                    var siguiente = '';
                    if (data["prev_page_url"] !== null) {
                        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
                            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    } else {
                        primero =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
                        anterior =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
                    }


                    if (data["current_page"] < 3) {
                        for (var i = 1; i <= 5 + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    } else {
                        if (data["last_page"] > 2) {
                            if (data["current_page"] > data["last_page"] - 2) {
                                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                                    if (i > 0 && i <= data["last_page"]) {
                                        if (i == data["current_page"]) {
                                            paginas +=
                                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                                i + '</a></li>';
                                        } else {
                                            paginas +=
                                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                                i + '">' + i + '</a></li>';
                                        }
                                    }
                                }
                            }
                        }
                    }

                    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
                        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
                            if (i > 0 && i <= data["last_page"]) {
                                if (i == data["current_page"]) {
                                    paginas +=
                                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                        i + '</a></li>';
                                } else {
                                    paginas +=
                                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                                        '">' + i + '</a></li>';
                                }
                            }
                        }
                    }


                    if (data["next_page_url"] !== null) {
                        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (data[
                                "current_page"] + 1) +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' + data[
                                "last_page"] +
                            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    } else {
                        siguiente =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
                        ultimo =
                            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
                    }

                    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
                    $('#paginasProductos').append(concatenacion);
                }
            });
        }

        function quitar(id) { //agregue una parametro mas el tipo
            $('#row' + id).remove();
        }
    </script>
    <script>
        $(function() {
            var today = new Date();
            var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            //var today = yyyy + '-' + mm + '-' + dd;
            var today = dd + '/' + mm + '/' + yyyy;
            $("#datepicker").val(today);
            $("#datepicker2").val(today);
        });
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#tabla').DataTable({
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
    <script></script>

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
                                        "IdProvincia"] + '">' + data[i]["Nombre"] +
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
                                    "IdDistrito"] + '">' + data[i]["Nombre"] + '</option>');
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
            $("#departamento2").on('change', function() {
                var departamento = $("#departamento2").val();
                $.ajax({
                    type: 'get',
                    url: 'consultar-provincias',
                    data: {
                        'departamento': departamento
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#distrito2 option').remove();
                            $('#provincia2 option').remove();
                            $('#distrito2').append('<option value="0">-</option>');
                            $('#provincia2').append('<option value="0">-</option>');
                            for (var i = 0; i < data.length; i++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                                $('#provincia2').append('<option value="' + data[i][
                                    "IdProvincia"
                                ] + '">' + data[i]["Nombre"] + '</option>');
                            }
                        } else {
                            $('#provincia2 option').remove();
                            $('#distrito2 option').remove();
                        }
                    }
                });
            });

            $("#provincia2").on('change', function() {
                var provincia = $("#provincia2").val();
                $.ajax({
                    type: 'get',
                    url: 'consultar-distritos',
                    data: {
                        'provincia': provincia
                    },
                    success: function(data) {
                        if (data.length > 0) {
                            $('#distrito2 option').remove();
                            $('#distrito2').append('<option value="0">-</option>');
                            for (var i = 0; i < data.length; i++) {
                                //$('#contenido').append('<option id="'+galeria[i]["IdAlbum"]+'" value="'+galeria[i]["Titulo"]+'"></option>'); 
                                $('#distrito2').append('<option value="' + data[i][
                                    "IdDistrito"] + '">' + data[i]["Nombre"] + '</option>');
                            }
                        } else {
                            $('#distrito2 option').remove();
                        }
                    }
                });
            });

            $('#btnGenerar').on('click', function () {   
                var myForm = $("form#myform");   
                if(myForm) {   
                    $(this).attr('disabled', true);   
                    $(myForm).submit();   
                }   
            });  
        });
    </script>
</body>

</html>
