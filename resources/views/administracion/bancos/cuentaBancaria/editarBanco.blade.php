@extends('layouts.app')
@section('title', 'Crear Cuenta Corriente')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Actualizar Cuenta Corriente</h6>
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
                                'url' => '/administracion/bancos/cuentas-bancarias/' . $cuentaCorriente->IdBanco,
                                'method' => 'PUT',
                                'files' => true,
                                'class' => 'form-material',
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="banco">
                                            @foreach ($listaBancos as $banco)
                                                @if ($banco->IdListaBanco == $cuentaCorriente->IdListaBanco)
                                                    <option selected value="{{ $banco->IdListaBanco }}">{{ $banco->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $banco->IdListaBanco }}">{{ $banco->Nombre }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="sucursal">Seleccionar Banco</label>
                                    </div>
                                </div>
                                {{-- Nuevo codigo --}}
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select class="form-control" name="tipoCuenta">
                                            @foreach ($tiposCuentasBancarias as $cuenta)
                                                @if ($cuenta->IdCuentaBancaria == $cuentaCorriente->IdCuentaBancaria)
                                                    <option value="{{ $cuenta->IdCuentaBancaria }}" selected>
                                                        {{ $cuenta->Nombre }}
                                                    </option>
                                                @else
                                                    <option value="{{ $cuenta->IdCuentaBancaria }}">{{ $cuenta->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="sucursal">Seleccionar Tipo de cuenta</label>
                                    </div>
                                </div>
                                {{-- fin --}}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Número de cuenta" name="cuenta"
                                            maxlength="20" value="{{ $cuentaCorriente->NumeroCuenta }}">
                                        <label for="dni">Número de cuenta</label>
                                        <span class="text-danger font-size">{{ $errors->first('cuenta') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="CCI" name="cci" maxlength="20"
                                            value="{{ $cuentaCorriente->CCI }}">
                                        <label for="telefono">CCI</label>
                                        <span class="text-danger font-size">{{ $errors->first('cci') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select class="form-control" name="tipoMoneda">
                                            @foreach ($tipoMonedas as $tipoMoneda)
                                                @if ($tipoMoneda->IdTipoMoneda == 1 || $tipoMoneda->IdTipoMoneda == 2)
                                                    @if ($tipoMoneda->IdTipoMoneda == $cuentaCorriente->IdTipoMoneda)
                                                        <option selected value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                            {{ $tipoMoneda->Nombre }}</option>
                                                    @else
                                                        <option value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                            {{ $tipoMoneda->Nombre }}</option>
                                                    @endif
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="sucursal">Seleccionar Tipo Moneda</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Monto inicial" name="inicial"
                                            type="number" value="{{ $cuentaCorriente->MontoInicial }}" readonly>
                                        <label for="telefono">Monto Inicial</label>
                                        <span class="text-danger font-size">{{ $errors->first('inicial') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-primary" type="submit">Actualizar</button>
                                <a href="../../cuentas-bancarias"><button class="btn btn-outline-default"
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
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
@stop
