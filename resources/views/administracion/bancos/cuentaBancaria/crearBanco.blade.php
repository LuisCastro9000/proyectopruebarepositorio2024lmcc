@extends('layouts.app')
@section('title', 'Crear Cuenta Corriente')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 id="titulo" class="page-title-heading mr-0 mr-r-5">Crear Cuenta Corriente</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
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
                                'url' => '/administracion/bancos/cuentas-bancarias',
                                'method' => 'POST',
                                'files' => true,
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="banco" id="selectBanco">
                                            @foreach ($listaBancos as $banco)
                                                <option value="{{ $banco->IdListaBanco }}">{{ $banco->Nombre }}</option>
                                            @endforeach
                                        </select>
                                        <label for="sucursal">Seleccionar Banco</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="tipoCuenta">
                                            @foreach ($tiposCuentasBancarias as $cuenta)
                                                <option value="{{ $cuenta->IdCuentaBancaria }}">{{ $cuenta->Nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                        <label for="sucursal">Seleccionar Tipo de cuenta</label>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="tipoMoneda">
                                            @foreach ($tipoMonedas as $tipoMoneda)
                                                @if ($tipoMoneda->IdTipoMoneda == 1 || $tipoMoneda->IdTipoMoneda == 2)
                                                    <option value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                        {{ $tipoMoneda->Nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="sucursal">Seleccionar Tipo Moneda</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Número de cuenta" name="cuenta"
                                            maxlength="20">
                                        <label for="dni">Número de cuenta</label>
                                        <span class="text-danger font-size">{{ $errors->first('cuenta') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6 col-cci">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="CCI" name="cci" maxlength="20">
                                        <label for="telefono">CCI</label>
                                        <span class="text-danger font-size">{{ $errors->first('cci') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-6 col-montoInicial">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Monto inicial" name="inicial"
                                            type="number" step="any">
                                        <label for="telefono">Monto Inicial</label>
                                        <span class="text-danger font-size">{{ $errors->first('inicial') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions btn-list mt-3 text-right">
                                <button class="btn btn-primary" type="submit">Crear</button>
                                <a href="../cuentas-bancarias"><button class="btn btn-outline-default"
                                        type="button">Cancelar</button></a>
                            </div>
                            {{-- el idCuentraDetracciones se obtiene desde la vista operaciones/crearVenta, el id se pasa al input para matener bloequados los elementos en caso se retorne errores de validacion --}}
                            <section>
                                <input type="hidden" id="idCuentraDetracciones" name="idCuentraDetracciones"
                                    value="{{ old('idCuentraDetracciones') ?? $idCuentraDetracciones }}">
                            </section>
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
        $(document).ready(function() {
            const idCuentraDetracciones = $('#idCuentraDetracciones').val();
            const planSuscripcionContratado = @json($planSuscripcionContratado);
            if (idCuentraDetracciones == '9' || (planSuscripcionContratado && planSuscripcionContratado.nombre ===
                    'Despegar')) {
                $('#selectBanco').val("9").addClass('disabled-elemento');
                $('select[name="tipoCuenta"]').val('1').addClass('disabled-elemento');
                $('select[name="tipoMoneda"]').val('1').addClass('disabled-elemento');
                $(".col-cci").hide();
                $('input[name="inicial"]').val(0);
                $(".col-montoInicial").hide();
                $('#titulo').text('Crear Cuenta de Detracciones');
            }
        })
    </script>
@stop
