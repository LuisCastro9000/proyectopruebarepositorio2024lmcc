@extends('layouts.app')
@section('title', 'Editar Cliente')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Cliente</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
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
                            {!! Form::open([
                                'url' => 'administracion/staff/clientes/' . $cliente->IdCliente,
                                'method' => 'PUT',
                                'files' => true,
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row form-material">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Razón Social" type="text"
                                            name="razonSocial" value="{{ $cliente->RazonSocial }}">
                                        <label for="razonSocial">Nombre o Razón Social</label>
                                        <span class="text-danger font-size">{{ $errors->first('razonSocial') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Nombre" type="text"
                                            name="nombreComercial" value="{{ $cliente->Nombre }}">
                                        <label for="nombreComercial">Nombre Comercial</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row form-material">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="tipoDocumento">
                                            @foreach ($tipoDoc as $doc)
                                                @if ($cliente->IdTipoDocumento == $doc->IdTipoDocumento)
                                                    <option selected value="{{ $doc->IdTipoDocumento }}">
                                                        {{ $doc->Descripcion }}</option>
                                                @else
                                                    <option value="{{ $doc->IdTipoDocumento }}">{{ $doc->Descripcion }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="tipoDoc">Tipo Documento</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="nroDocumento"
                                            value="{{ $cliente->NumeroDocumento }}" maxlength="12">
                                        <label for="nroDocumento">Número de Documento</label>
                                        <span class="text-danger font-size">{{ $errors->first('nroDocumento') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="departamento">Departamento</label>
                                        <select id="departamento" class="form-control" name="departamento">
                                            <option value="0">-</option>
                                            @foreach ($departamentos as $departamento)
                                                @if ($cliente->IdDepartamento == $departamento->IdDepartamento)
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
                                                @if ($cliente->IdProvincia == $provincia->IdProvincia)
                                                    <option selected value="{{ $provincia->IdProvincia }}">
                                                        {{ $provincia->Nombre }}</option>
                                                @else
                                                    <option value="{{ $provincia->IdProvincia }}">{{ $provincia->Nombre }}
                                                    </option>
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
                                                @if ($cliente->Ubigeo == $distrito->IdDistrito)
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
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="direccion" maxlength="100"
                                            value="{{ $cliente->Direccion }}">
                                        <label for="direccion">Dirección</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="telefono"
                                            value="{{ $cliente->Telefono }}">
                                        <label for="telefono">Teléfono</label>
                                        <span class="text-danger font-size">{{ $errors->first('telefono') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <input class="form-control" type="email" name="email"
                                            value="{{ $cliente->Email }}">
                                        <label for="email">Email</label>
                                        <span class="text-danger font-size">{{ $errors->first('email') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <h5 class="box-title mr-b-0">Saldo (Credito)</h5>
                                    <div class="form-group">
                                        <div class="radiobox">
                                            <label id="con">
                                                <input type="radio" name="radioOpcion"
                                                    {{ $cliente->BandSaldo == 0 ? 'checked' : '' }} value="0"><span
                                                    class="label-text">Ilimitado</span>
                                            </label>
                                        </div>
                                        <!-- /.radiobox -->
                                        <div class="radiobox radio-success">
                                            <label id="sin">
                                                <input type="radio" name="radioOpcion"
                                                    {{ $cliente->BandSaldo == 1 ? 'checked' : '' }} value="1">
                                                <span class="label-text">Con Monto</span>
                                                <input id="saldo" hidden class="form-control" type="number"
                                                    step="any" name="saldoCredito"
                                                    value="{{ $cliente->SaldoCredito }}"><small hidden
                                                    class="text-muted"><strong>Escriba el monto del
                                                        Cliente(obligatorio)</strong></small>
                                            </label>
                                        </div>
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" type="text" name="personaContacto"
                                            value="{{ $cliente->PersonaContacto }}">
                                        <label for="personaContacto">Persona de Contacto</label>
                                        <span class="text-danger font-size">{{ $errors->first('personaContacto') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                                <a href="../../clientes"><button class="btn btn-outline-default"
                                        type="button">Cancelar</button></a>
                                <a href="https://e-consultaruc.sunat.gob.pe/cl-ti-itmrconsruc/jcrS00Alias"
                                    target="_blank"><button class="btn btn-outline-danger" type="button">Consultar
                                        RUC</button></a>
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
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script>
        $(function() {

            if ($("input:radio:checked").val() == 1) {
                $("#saldo").removeAttr("hidden");
                $(".text-muted").removeAttr("hidden");
            }

            $("#departamento").on('change', function() {
                var departamento = $("#departamento").val();
                $.ajax({
                    type: 'get',
                    url: 'edit/consultar-provincias',
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
                    url: 'edit/consultar-distritos',
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
        $("#sin").click(function() {
            $("#saldo").removeAttr("hidden");
            $(".text-muted").removeAttr("hidden");

        });

        $("#con").click(function() {
            $("#saldo").prop("hidden", "true");
            $(".text-muted").prop("hidden", "true");
        })
    </script>
@stop
