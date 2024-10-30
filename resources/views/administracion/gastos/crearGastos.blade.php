@extends('layouts.app')
@section('title', 'Crear Gastos')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Crear Gasto</h6>
            </div>
        </div>
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

    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            {!! Form::open([
                                'url' => '/administracion/gastos',
                                'method' => 'POST',
                                'files' => true,
                                'class' => 'form-material',
                                'id' => 'myform',
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <select id="tipoGasto" class="form-control" name="tipoGasto">
                                            <option value="0">-</option>
                                            <option value="1">Fijo</option>
                                            <option value="2">Variable</option>
                                        </select>
                                        <label for="sucursal">Seleccionar Tipo de Gasto</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        @if ($subniveles->contains('IdSubNivel', 46))
                                            <select id="tipoMoneda" class="form-control" name="tipoMoneda">
                                                @foreach ($tipoMonedas as $tipMon)
                                                    <option value="{{ $tipMon->IdTipoMoneda }}">
                                                        {{ $tipMon->Nombre }}</option>
                                                @endforeach
                                            </select>
                                        @else
                                            <select id="tipoMoneda" class="form-control" name="tipoMoneda" disabled>
                                                @foreach ($tipoMonedas as $tipoMoneda)
                                                    @if ($tipoMoneda->IdTipoMoneda == 1)
                                                        <option value="{{ $tipoMoneda->IdTipoMoneda }}">
                                                            {{ $tipoMoneda->Nombre }}</option>
                                                    @endif
                                                @endforeach
                                            </select>
                                        @endif
                                        <label>Tipo Moneda</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <div class="input-group-text"><label>Fecha</label></div>
                                            <input id="datepicker" type="date"
                                                data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}'
                                                data-date-end-date="0d" class="form-control" name="fecha"
                                                max="<?php echo date('Y-m-d'); ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <small class="text-muted"><strong>SELECCIONE ITEM</strong></small>
                                        <select id="listaGastos" class="m-b-10 form-control select2-hidden-accessible"
                                            name="listaGastos" data-placeholder="Seleccionar Opción" data-toggle="select2"
                                            tabindex="-1" aria-hidden="true">
                                        </select>
                                        <span class="text-danger font-size">{{ $errors->first('listaGastos') }}</span>
                                    </div>
                                </div>

                                <div class="col-md-5">
                                    <div class="form-group">
                                        <small class="text-muted"><strong>&nbsp;</strong></small>
                                        <input class="form-control" placeholder="Monto Gasto" type="number" step=".01"
                                            name="monto">
                                        <label for="direccion">Monto Gasto</label>
                                        <span class="text-danger font-size">{{ $errors->first('monto') }}</span>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <textarea id="observacion" class="form-control" rows="4" name="observacion"></textarea>
                                        <label>Observación</label>
                                        <span class="text-danger font-size">{{ $errors->first('observacion') }}</span>
                                    </div>
                                </div>
                            </div>
                            <input class="form-control" type="text" hidden name="IdOperadorUsuario"
                                value="{{ $usuarioSelect->IdOperador }}">
                            <div class="form-actions btn-list mt-3 text-right">
                                <button id="btnCrear" class="btn btn-primary" type="submit">Generar Gasto</button>
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
            var today = yyyy + '-' + mm + '-' + dd;
            $("#datepicker").val(today);
        });
    </script>
    <script>
        $(function() {
            $("#tipoGasto").on('change', function() {
                var tipo = $("#tipoGasto").val();
                $.ajax({
                    type: 'get',
                    url: 'listar-gastos',
                    data: {
                        'tipo': tipo
                    },
                    success: function(data) {
                        $('#listaGastos option').remove();
                        for (var i = 0; i < data.length; i++) {
                            $('#listaGastos').append('<option value="' + data[i][
                                    "IdListaGastos"
                                ] + '">' + data[i]["Descripcion"] +
                                '</option>');
                        }
                        // $('#listaGastos').append('<option value="0">Otros</option>');

                    }
                });
            });

            // $("#listaGastos").on('change', function(){
            //     var valTipo = $("#listaGastos").val();
            //     if(valTipo == 18 || valTipo == 19){
            //         $("#otros").attr('disabled',false);
            //     }else{
            //         $("#otros").attr('disabled',true);
            //     }
            // });

            $('#btnCrear').on('click', function() {
                var myForm = $("form#myform");
                if (myForm) {
                    $(this).attr('disabled', true);
                    $(myForm).submit();
                }
            });
        });
    </script>
@stop
