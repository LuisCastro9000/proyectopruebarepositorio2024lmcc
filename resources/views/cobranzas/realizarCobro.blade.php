
		@extends('layouts.app')
		@section('title', 'Realizar Cobro')
		@section('content')		
            <div class="container">
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
                                    {!!Form::open(array('url'=>'/detalle-cobranza/realizar-cobro','method'=>'POST','files'=>true, 'class' => 'form-material', 'id'=>'myform'))!!}
                                        {{csrf_field()}}
                                        <div class="row mt-4">
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="modoPago" name="modoPago">
                                                        <option value="1">Totalidad</option>
                                                        <option value="2">Parcial</option>
                                                    </select>
                                                    <label>Modo de Pago</label>
                                                    <input class="form-control text-black" name="idFechaPago" value="{{$selectCuota->IdFechaPago}}" hidden>
                                                    <input class="form-control text-black" name="idVenta" value="{{$selectCuota->IdVenta}}" hidden>
                                                    <input class="form-control text-black" name="importePagado" value="{{$selectCuota->ImportePagado}}" hidden>
                                                    <input class="form-control text-black" name="importe" value="{{$selectCuota->Importe}}" hidden>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Deuda Total</label>
                                                    <div class="input-group">
                                                        <input min="0.0" class="form-control text-black" name="deudaTotal" value="{{$selectCuota->TotalDeuda}}" readonly>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div id="pagoParcial" class="form-group">
                                                    <label class="form-control-label">Monto a Pagar</label>
                                                    <div class="input-group">
                                                        <input id="number" type="number" step="any" min="0.0" class="form-control" name="pagoParcial" value="{{old('pagoParcial')}}">
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="form-control-label">Monto Pagado(Efectivo)</label>
                                                    <div class="input-group">
                                                        <input id="number" type="number" step="any" min="0.0" class="form-control" name="pagoEfectivo">
                                                    </div>
                                                    <input class="form-control text-black" name="totalEfectivo" value="{{$selectCuota->MontoEfectivo}}" hidden>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" name="tipoTarjeta">
                                                        <option value="1">Visa</option>
                                                        <option value="2">MasterCard</option>
                                                    </select>
                                                    <label class="form-control-label">Tarjeta Crédito/Débito</label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">4 últimos dígitos</label>
                                                    <div class="input-group">
                                                        <input type="text" class="form-control" name="numTarjeta" minlength="4" maxlength="4" value="{{old('numTarjeta')}}">
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Monto Pagado(Con tarjeta)</label>
                                                    <div class="input-group">
                                                        <input type="number" step="any" min="0.0" class="form-control" name="pagoTarjeta">
                                                    </div>
                                                    <input class="form-control text-black" name="totalTarjeta" value="{{$selectCuota->MontoTarjeta}}" hidden>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <select class="form-control" id="cuentaBancaria" name="cuentaBancaria">
                                                        <option value="0">Seleccione cuenta bancaria</option>
                                                        @foreach($bancos as $banco)
                                                            <option value="{{$banco->IdBanco}}">{{$banco->Banco}} - {{$banco->NumeroCuenta}}</option>
                                                        @endforeach
                                                    </select>
                                                    <label>Cuenta Bancaria</label>
                                                </div>
                                                <div class="form-group">
                                                    <text style="font-size: .75em;top: 0;opacity: 1;font-weight: 700;">FECHA ABONO DEPÓSITO</text>
                                                    <div class="input-group">
                                                        <input id="date" type="text" class="form-control datepicker" name="fechaCobroCuenta"
                                                            data-plugin-options='{"autoclose": true, "format": "dd/mm/yyyy"}' data-date-end-date="0d"
                                                            autocomplete="off" onkeydown="return false" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Numero Operación</label>
                                                    <div class="input-group">
                                                        <input id="nroOperacion" type="text" class="form-control" name="nroOperacion" value="{{old('nroOperacion')}}" disabled>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="form-control-label">Monto (Cuenta Bancaria)</label>
                                                    <div class="input-group">
                                                        <input id="pagoCuenta" type="number" step="any" class="form-control" name="montoCuenta" value="{{old('montoCuenta')}}" disabled>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-md-12 form-actions btn-list mt-2">
                                                <button id="btnCobrar" class="btn btn-primary" type="submit">Cobrar</button>
                                                <a href="../../{{$selectCuota->IdVenta}}"><button class="btn btn-default" type="button">Regresar</button></a>
                                            </div>
                                        </div>
                                    {!!Form::close()!!}
                                </div>
                            </div>
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

		$(function () {
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
            var dateBanco = dd + '/' + mm + '/' + yyyy;
            $("#date").val(dateBanco);
          });
    </script>
    <script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
                    "order": [[ 0, "desc" ]],
                    language: {
                        processing:     "Procesando...",
                        search:         "Buscar:",
                        lengthMenu:     "Mostrar _MENU_ registros",
                        info:           "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty:      "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered:   "",
                        infoPostFix:    "",
                        loadingRecords: "Cargando...",
                        zeroRecords:    "No se encontraron resultados",
                        emptyTable:     "Ningún dato disponible en esta tabla",
                        paginate: {
                            first:      "Primero",
                            previous:   "Anterior",
                            next:       "Siguiente",
                            last:       "Último"
                        },
                        aria: {
                            sortAscending:  ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });
    </script>
    <script>
        $(function() {
            $('#pagoParcial').hide();
            $("#modoPago").on('change', function () {
                var tipo = $("#modoPago").val();
                if(tipo == "1"){
                    $('#pagoParcial').hide();
                }else{
                    $('#pagoParcial').show();
                }
            });

            $("#cuentaBancaria").on('change', function () {
                var tipoBan = $("#cuentaBancaria").val();
                if(tipoBan == "0"){
                    $('#pagoCuenta').attr("disabled", true);
                    $('#nroOperacion').attr("disabled", true);
                    $('#date').attr("disabled", true);
                }else{
                    $('#pagoCuenta').attr("disabled", false);
                    $('#nroOperacion').attr("disabled", false);
                    $('#date').attr("disabled", false);
                }
            });

            $('#btnCobrar').on('click', function () {   
                var myForm = $("form#myform");   
                if(myForm) {   
                    $(this).attr('disabled', true);   
                    $(myForm).submit();   
                }   
            });  
        });
    </script>
	@stop






