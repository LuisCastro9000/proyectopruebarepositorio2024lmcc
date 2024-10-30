@extends('layouts.app')
@section('title', 'Suscripcion Usuario')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Suscripción de Usuario</h6>
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
                                'url' => '/administracion/usuarios/suscripcion-finalizada',
                                'method' => 'POST',
                                'files' => true,
                                'class' => 'form-material',
                            ]) !!}
                            {{ csrf_field() }}
                            <input hidden class="form-control" name="idenUsuario" value={{ $idenUsuario }}>
                            @foreach ($suscripcionesSucursales as $suscripcion)
                                <div id="accordion" role="tablist">
                                    <div class="card">
                                        <div class="card-header" role="tab" id="heading-{{ $suscripcion->IdSucursal }}">
                                            <b class="mb-0">
                                                <div class="form-group" type="checkbox">
                                                    <input type="checkbox" name="checkSuscripcion[]" class="fs-28 mr-2"
                                                        data-target="#collapse-{{ $suscripcion->IdSucursal }}"
                                                        data-toggle="collapse" value="{{ $suscripcion->IdSucursal }}">Editar
                                                    Suscripción: <b class="fs-16">
                                                        {{ $suscripcion->NombreSucursal }}
                                                    </b>
                                                </div>
                                            </b>
                                        </div>
                                        <div id="collapse-{{ $suscripcion->IdSucursal }}" class="collapse" role="tabpanel"
                                            aria-labelledby="heading-{{ $suscripcion->IdSucursal }}">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <select id="plan-{{ $suscripcion->IdSucursal }}"
                                                                class="form-control"
                                                                name="plan-{{ $suscripcion->IdSucursal }}">
                                                                <option value="1">Mensual</option>
                                                                <option value="2">Semestral</option>
                                                                <option value="3">Anual</option>
                                                            </select>
                                                            <label for="sucursal">Plan</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input id="datepicker2" type="text"
                                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                    class="form-control datepicker"
                                                                    name="fechaContrato-{{ $suscripcion->IdSucursal }}"
                                                                    value="{{ empty($suscripcion->FechaFinalContrato) ? '' : date('d/m/Y', strtotime($suscripcion->FechaFinalContrato)) }}">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><i
                                                                            class="list-icon material-icons">date_range</i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted"><strong>Fecha Fin
                                                                    Contrato</strong></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <div class="input-group">
                                                                <input id="datepicker2" type="text"
                                                                    data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                                    class="form-control datepicker"
                                                                    name="fechaCDT-{{ $suscripcion->IdSucursal }}"
                                                                    value="{{ empty($suscripcion->FechaFinalCDT) ? '' : date('d/m/Y', strtotime($suscripcion->FechaFinalCDT)) }}">
                                                                <div class="input-group-append">
                                                                    <div class="input-group-text"><i
                                                                            class="list-icon material-icons">date_range</i>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <small class="text-muted"><strong>Fecha Fin
                                                                    CDT</strong></small>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input class="form-control" type="number" min="1"
                                                                step="any"
                                                                name="montoPago-{{ $suscripcion->IdSucursal }}"
                                                                value="{{ $suscripcion->MontoPago ?? '' }}">
                                                            <label for="montoPago">Monto de Pago</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group">
                                                            <input class="form-control" type="number" min="1"
                                                                name="bloqueo-{{ $suscripcion->IdSucursal }}"
                                                                value="{{ $suscripcion->Bloqueo ?? '' }}">
                                                            <label for="bloqueo">Días Bloqueo</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-primary" type="submit">Guardar</button>
                                <a href="../../usuarios"><button class="btn btn-outline-default"
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
    <script>
        $(function() {
            const suscripcionesSucursales = @json($suscripcionesSucursales);
            suscripcionesSucursales.forEach(element => {
                $('#plan-' + element.IdSucursal + ' option[value=' + element.Plan + ']').prop('selected',
                    true);
            });
        })
    </script>
    <script>
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
