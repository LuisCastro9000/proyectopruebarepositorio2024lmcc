@extends('layouts.app')
@section('title', 'Editar Vehiculo')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Vehiculo</h6>
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
                                'url' => '/vehicular/administracion/' . $vehiculo->IdVehiculo,
                                'method' => 'PUT',
                                'class' => 'form-material',
                                'id' => 'formEditarVehiculo',
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Cliente</label>
                                        <!--<select class="form-control" id="clientes" name="cliente">-->
                                        <select class="m-b-10 form-control select2-hidden-accessible" id="clientes"
                                            name="cliente" data-placeholder="Cliente" data-toggle="select2" tabindex="-1"
                                            aria-hidden="true">
                                            <option value="">-</option>
                                            @foreach ($clientes as $cliente)
                                                @if ($cliente->IdCliente == $vehiculo->IdCliente)
                                                    <option selected value="{{ $cliente->IdCliente }}">
                                                        {{ $cliente->Nombre }}</option>
                                                @else
                                                    <option value="{{ $cliente->IdCliente }}">{{ $cliente->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted"><strong>Seleccione el Cliente</strong></small>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <select class="m-b-10 form-control select2-hidden-accessible" disabled
                                            id="tipoVehiculo" name="tipoVehiculo" data-placeholder="Tipo"
                                            data-toggle="select2" tabindex="-1" aria-hidden="true">
                                            @if ($vehiculo->TipoVehicular == 1)
                                                <option value="1" selected>Vehículo de 4 ruedas</option>
                                                <option value="2">Vehículo de 2 ruedas</option>
                                            @else
                                                <option value="1">Vehículo de 4 ruedas</option>
                                                <option value="2" selected>Vehículo de 2 ruedas</option>
                                            @endif
                                        </select>
                                        <small class="text-muted"><strong>Tipo Vehiculo</strong></small>
                                        <span class="text-danger font-size">{{ $errors->first('tipoVehiculo') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Placa" type="text"
                                            value="{{ $vehiculo->PlacaVehiculo }}" name="placa" maxlength="7">
                                        <label for="nombre">Placa</label>
                                        <span class="text-danger font-size">{{ $errors->first('placa') }}</span>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Chasis / VIN" type="text"
                                            value="{{ $vehiculo->ChasisVehiculo }}" name="chasis">
                                        <label for="nombre">Chasis / VIN</label>
                                        <span class="text-danger font-size">{{ $errors->first('chasis') }}</span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                @if ($modulosSelect->contains('IdModulo', 5))
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select class="m-b-10 form-control select2-hidden-accessible" id="seguro"
                                                name="seguro" data-placeholder="Seguros" data-toggle="select2"
                                                tabindex="-1" aria-hidden="true">
                                                <option value="2">Sin Seguro</option>
                                                @foreach ($seguros as $seguro)
                                                    @if ($vehiculo->IdSeguro == $seguro->IdSeguro)
                                                        <option value="{{ $seguro->IdSeguro }}" selected>
                                                            {{ $seguro->Descripcion }}</option>
                                                    @else
                                                        <option value="{{ $seguro->IdSeguro }}">{{ $seguro->Descripcion }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <small class="text-muted"><strong>Seleccione Seguro Vehicular</strong></small>
                                            <span class="text-danger font-size">{{ $errors->first('seguro') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <!--<select class="form-control" id="clientes" name="cliente">-->
                                            <select class="m-b-10 form-control select2-hidden-accessible" id="anio"
                                                name="anio" data-placeholder="Año" data-toggle="select2" tabindex="-1"
                                                aria-hidden="true">
                                                @foreach ($arrayAnio as $anio)
                                                    @if ($anio == $vehiculo->Anio)
                                                        <option selected value="{{ $anio }}">{{ $anio }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $anio }}">{{ $anio }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <small class="text-muted"><strong>Seleccione año</strong></small>
                                            <span class="text-danger font-size">{{ $errors->first('anio') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Color" type="text"
                                                value="{{ $vehiculo->Color }}" name="color">
                                            <label for="nombre">Color</label>
                                            <span class="text-danger font-size">{{ $errors->first('color') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Nro Motor" type="text"
                                                value="{{ $vehiculo->Motor }}" name="motor">
                                            <label for="email">Nro Motor</label>
                                        </div>
                                    </div>
                                @else
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <!--<select class="form-control" id="clientes" name="cliente">-->
                                            <select class="m-b-10 form-control select2-hidden-accessible" id="anio"
                                                name="anio" data-placeholder="Año" data-toggle="select2"
                                                tabindex="-1" aria-hidden="true">
                                                @foreach ($arrayAnio as $anio)
                                                    @if ($anio == $vehiculo->Anio)
                                                        <option selected value="{{ $anio }}">{{ $anio }}
                                                        </option>
                                                    @else
                                                        <option value="{{ $anio }}">{{ $anio }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                            <small class="text-muted"><strong>Seleccione año</strong></small>
                                            <span class="text-danger font-size">{{ $errors->first('anio') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Color" type="text"
                                                value="{{ $vehiculo->Color }}" name="color">
                                            <label for="nombre">Color</label>
                                            <span class="text-danger font-size">{{ $errors->first('color') }}</span>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input class="form-control" placeholder="Nro Motor" type="text"
                                                value="{{ $vehiculo->Motor }}" name="motor">
                                            <label for="email">Nro Motor</label>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="row" hidden>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Kilometros" type="text"
                                            value="{{ $vehiculo->KilometroInicial }}" name="kilometraje">
                                        <label for="dni">Kilometro. Inicial</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Horometro" type="text"
                                            value="{{ $vehiculo->HorometroInicial }}" name="horometro">
                                        <label for="email">Horometro. Inicial</label>
                                    </div>
                                </div>
                                <div class="col-md-4">

                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Marca</label>
                                        <select class="m-b-10 form-control select2-hidden-accessible" id="marca"
                                            name="marca" data-placeholder="Marca" data-toggle="select2" tabindex="-1"
                                            aria-hidden="true">
                                            <option value="">-</option>
                                            @foreach ($marcas as $marca)
                                                @if ($marca->IdMarcaGeneral == $vehiculo->IdMarcaVehiculo)
                                                    <option selected value="{{ $marca->IdMarcaGeneral }}">
                                                        {{ $marca->NombreMarca }}</option>
                                                @else
                                                    <option value="{{ $marca->IdMarcaGeneral }}">
                                                        {{ $marca->NombreMarca }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted"><strong>Seleccione La Marca</strong></small>
                                    </div>
                                </div>

                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Modelo</label>
                                        <select class="m-b-10 form-control select2-hidden-accessible" id="modelo"
                                            name="modelo" data-placeholder="Modelo" data-toggle="select2"
                                            tabindex="-1" aria-hidden="true">
                                            <option value="">-</option>
                                            @foreach ($modelos as $modelo)
                                                @if ($modelo->IdModeloGeneral == $vehiculo->IdModeloVehiculo)
                                                    <option selected value="{{ $modelo->IdModeloGeneral }}">
                                                        {{ $modelo->NombreModelo }}</option>
                                                @else
                                                    <option value="{{ $modelo->IdModeloGeneral }}">
                                                        {{ $modelo->NombreModelo }}</option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted"><strong>Seleccione El Modelo</strong></small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tipo</label>
                                        <select class="m-b-10 form-control select2-hidden-accessible" id="tipo"
                                            name="tipo" data-placeholder="Tipo" data-toggle="select2" tabindex="-1"
                                            aria-hidden="true">
                                            <option value=""></option>
                                            @foreach ($tipos as $tipo)
                                                @if ($tipo->IdTipoGeneral == $vehiculo->IdTipoVehiculo)
                                                    <option selected value="{{ $tipo->IdTipoGeneral }}">
                                                        {{ $tipo->NombreTipo }}</option>
                                                @else
                                                    <option value="{{ $tipo->IdTipoGeneral }}">{{ $tipo->NombreTipo }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <small class="text-muted"><strong>Seleccione El Tipo</strong></small>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <small class="text-muted"><strong>Fecha Venc. Soat</strong></small>
                                        <div class="input-group">
                                            <input id="fechaSoat" type="date"
                                                data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                class="form-control" name="fechaSoat"
                                                value="{{ $vehiculo->FechaSoat }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="form-group">
                                            <small class="text-muted"><strong>Fecha Revis. Técnica</strong></small>
                                            <div class="input-group">
                                                <input id="fechaRevTecnica" type="date"
                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                    class="form-control" name="fechaRevTecnica"
                                                    value="{{ $vehiculo->FechaRevTecnica }}">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group mt-4">
                                        <input class="form-control" placeholder="Nro Flota" type="text"
                                            name="flota" value="{{ $vehiculo->NumeroFlota }}">
                                        <label>Nro Flota</label>
                                    </div>
                                </div>
                            </div>
                            @if ($modulosSelect->contains('IdModulo', 6))
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <small class="text-muted"><strong>Certificación Anual</strong></small>
                                            <div class="input-group">
                                                <input id="fechaCertAnual" type="date"
                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                    class="form-control" name="fechaCertAnual">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <small class="text-muted"><strong>Prueba Quinquenal</strong></small>
                                            <div class="input-group">
                                                <input id="fechaPrueQuin" type="date"
                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                    class="form-control" name="fechaPrueQuin">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="row">
                                <div class="col-12 col-md-4">
                                    <div class="form-group">
                                        <div class="radiobox radio-success mr-10">
                                            <h5 class="box-title mr-b-0">Estado</h5>
                                            <label>
                                                <input type="radio" name="radioOpcion" value="1"
                                                    {{ $vehiculo->Estado == 1 ? 'checked' : '' }}> <span
                                                    class="label-text">Activo</span>
                                            </label>
                                        </div>
                                        <!-- /.radiobox -->
                                        <div class="radiobox mr-10">
                                            <label>
                                                <input type="radio" name="radioOpcion" value="0"
                                                    {{ $vehiculo->Estado == 0 ? 'checked' : '' }}> <span
                                                    class="label-text">Desactivado</span>
                                            </label>
                                        </div>
                                        <!-- /.radiobox -->
                                    </div>
                                    <!-- /.form-group -->
                                </div>
                                <div class="col-12 col-md-8">
                                    <div class="form-group">
                                        <input class="form-control" placeholder="Observacion"
                                            value="{{ $vehiculo->NotaVehiculo }}" type="text" name="nota">
                                        <label for="direccion">Observacion</label>
                                    </div>
                                </div>
                            </div>
                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-info" type="submit">Editar</button>
                                <a href="../lista-vehiculos"><button class="btn btn-outline-default"
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
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert1/libreriaSweetalert1.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#formEditarVehiculo').submit(function(event) {
                event.preventDefault();
                let formulario = this;
                swal({
                    title: "Importante!",
                    text: "Cualquier actualización de datos estará disponible para las próximas cotizaciones; por lo tanto, las cotizaciones anteriores seguirán asociadas a los datos anteriores.",
                    icon: "info",
                    button: "Entendido",
                }).then((value) => {
                    if (value) {
                        showLoadingOverlay();
                        formulario.submit();
                    }
                });
            })
        });
    </script>
    <script>
        $(function() {
            var fechaSoat = <?php echo json_encode($fechaSoat); ?>;
            var fechaRevTecnica = <?php echo json_encode($fechaRevTecnica); ?>;
            var fechaCertAnual = <?php echo json_encode($fechaCertAnual); ?>;
            var fechaPrueQuin = <?php echo json_encode($fechaPrueQuin); ?>;
            var _fechaSoat = getFecha(fechaSoat.replaceAll('-', '/'));
            var _fechaRevTecnica = getFecha(fechaRevTecnica.replaceAll('-', '/'));
            $("#fechaSoat").val(_fechaSoat);
            $("#fechaRevTecnica").val(_fechaRevTecnica);
            if (fechaCertAnual != null && fechaPrueQuin != null) {
                var _fechaCertAnual = getFecha(fechaCertAnual.replaceAll('-', '/'));
                var _fechaPrueQuin = getFecha(fechaPrueQuin.replaceAll('-', '/'));
                $("#fechaCertAnual").val(_fechaCertAnual);
                $("#fechaPrueQuin").val(_fechaPrueQuin);
            }
        });

        function getFecha(date) {
            var today = new Date(date);
            var dd = today.getDate();
            var mm = today.getMonth() + 1;

            var yyyy = today.getFullYear();
            if (dd < 10) {
                dd = '0' + dd;
            }
            if (mm < 10) {
                mm = '0' + mm;
            }
            var today = yyyy + '-' + mm + '-' + dd;
            return today;
        }
    </script>
@stop
