		@extends('layouts.app')
		@section('title', 'Detalles Guías Remisión')
		@section('content')
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Detalles de Guías de Remisión</h6>
                    </div>
                    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-flex">
                                <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-print"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-20">print</i></button></a>
                                <a class="p-1" href="#" data-toggle="modal" data-target=".bs-modal-sm-primary" onclick="cargarCorreo()"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-20">mail</i></button></a>
                            </div>
                        </div>
                    </div>
                </div>
                @if (session('status'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        {{ session('status') }}
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
                                    <div class="ecommerce-invoice">
                                        <div class="d-sm-flex">
                                            <div class="col-md-6">
                                                <h6>GUÍA REMISIÓN: {{$guiaSelect->Serie}}-{{$guiaSelect->Numero}}</h5>
                                            </div>
                                            <div class="col-md-6 text-right d-none d-sm-block"><strong>CAJERO:</strong> {{$guiaSelect->Usuario}}
                                                <br><strong>SUCURSAL:</strong> {{$guiaSelect->Sucursal}}
                                                <br><strong>DIRECCIÓN:</strong> {{$guiaSelect->Local}}
                                                <br><strong>CIUDAD:</strong> {{$guiaSelect->Ciudad}}
                                            </div>
                                            <div class="col-md-6 d-block d-sm-none"><strong>CAJERO:</strong> {{$guiaSelect->Usuario}}
                                                <br><strong>SUCURSAL:</strong> {{$guiaSelect->Sucursal}}
                                                <br><strong>DIRECCIÓN:</strong> {{$guiaSelect->Local}}
                                                <br><strong>CIUDAD:</strong> {{$guiaSelect->Ciudad}}
                                            </div>
                                        </div>
                                        <!-- /.row -->
                                        <hr>
                                        @if($guiaSelect->IdMotivo != 16)
                                        <div class="d-sm-flex">
                                            <div class="col-md-6 mt-2">
                                                <h6>Cliente:</h6>
                                                <strong>NOMBRES:</strong> {{$guiaSelect->Nombres}}
                                                <br><strong>RAZ. SOCIAL:</strong> {{$guiaSelect->RazonSocial}}
                                                <br><strong>{{$guiaSelect->TipoDocumento}}:</strong> {{$guiaSelect->NumDocumento}}
                                                <br><strong>DIRECCIÓN:</strong> {{$guiaSelect->DirCliente}}</div>
                                            <div class="col-md-6 text-right mt-2 d-none d-sm-block">
                                                <h6>Detalles:</h6>
                                                <strong>FECHA:</strong>  <span class="text-muted">{{$formatoFecha}}</span>
                                                <br><strong>HORA:</strong>  <span class="text-muted">{{$formatoHora}}</span>
                                            </div>
                                            <div class="col-md-6 mt-2 d-block d-sm-none">
                                                <h6>Detalles:</h6>
                                                <strong>FECHA:</strong>  <span class="text-muted">{{$formatoFecha}}</span>
                                                <br><strong>HORA:</strong>  <span class="text-muted">{{$formatoHora}}</span>
                                            </div>
                                        </div>
                                        <hr>
                                        @endif
                                        <div class="d-sm-flex">
                                            <div class="col-md-6 mt-2">
                                                <h6>Datos de Envío</h6>
                                                <strong>FECHA TRASLADO:</strong> {{$formatoFecha2}}
                                                <br><strong>MOTIVO DE TRANSLADO:</strong> {{$guiaSelect->Motivo}}
                                                <br><strong>PESO (Kg):</strong> {{$guiaSelect->Peso}}
                                                <br><strong>Nº BULTOS:</strong> {{$guiaSelect->Bultos}}
                                                <br><strong>ORIGEN:</strong> {{$guiaSelect->Origen}}
                                                <br><strong>DESTINO:</strong> {{$guiaSelect->Destino}}
											</div>
                                        
                                            <div class="col-md-6 mt-2 text-right">
                                                <h6>Transporte</h6>
                                                <strong>TRANSPORTISTA:</strong> {{$guiaSelect->Transportista}}
                                                <br><strong>DNI/RUC:</strong> {{$guiaSelect->NumeroDocumento}}
                                                <br><strong>RAZON SOCIAL:</strong> {{$guiaSelect->RazonSocialTransp}}
                                                <br><strong>RUC:</strong> {{$guiaSelect->RucTransp}}
                                                <br><strong>PLACA:</strong> {{$guiaSelect->PlacaVehicular}}
                                                <br><strong>ORIGEN:</strong> {{$guiaSelect->Origen}}
                                                <br><strong>DESTINO:</strong> {{$guiaSelect->Destino}}
											</div>
                                        </div>
                                        <!-- /.row -->
                                        <hr class="border-0">
                                        <table id="table" class="table table-bordered table-responsive-sm" style="width: 100%">
                                            <thead>
                                                <tr class="bg-primary-dark text-white">
                                                    <th class="text-center">Código</th>
                                                    <th>Descripción</th>
                                                    <th class="text-center">Uni/Medida</th>
                                                    <th class="text-center">Cantidad</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $item)
                                                <tr>
                                                    <td scope="row">{{$item->Cod}}</td>
                                                    <td>{{$item->Descripcion}}</td>
                                                    <td>{{$item->UniMedida}}</td>
                                                    <td>{{$item->Cantidad}}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="form-actions btn-list mt-3">
                                            <a href="../../guias-remision"><button class="btn btn-primary" type="button">Volver</button></a>
                                        </div>
                                        <!-- /.row -->
                                    </div>
                                    <!-- /.ecommerce-invoice -->
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
            <div class="modal modal-primary fade bs-modal-sm-primary" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                <div class="modal-dialog modal-sm">
                    {!!Form::open(array('url'=>'/consultas/guias-remision/enviar-correo/'.$guiaSelect->IdGuiaRemision,'method'=>'POST','files'=>true, 'class' => 'form-material'))!!}
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                <h6 class="modal-title" id="mySmallModalLabel2">Enviar por correo</h6>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <label>Correo cliente:</label>
                                    <input id="inpCorreo" class="form-control" name="correo"/>
                                    <input id="inpCliente" hidden class="form-control" name="cliente"/>
                                    <input id="inpComprobante" hidden class="form-control" name="comprobante"/>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Enviar</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    {!!Form::close()!!}
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
            
            <div class="modal modal-primary fade bs-modal-sm-print" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
                <div class="modal-dialog modal-sm">
                    {!!Form::open(array('url'=>'/consultas/guias-remision/imprimir/'.$guiaSelect->IdGuiaRemision,'method'=>'POST','files'=>true, 'class' => 'form-material', 'target' => '_blank'))!!}
                        <div class="modal-content">
                            <div class="modal-header text-inverse">
                                <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                                <h6 class="modal-title" id="mySmallModalLabel2">Imprimir comprobante</h6>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <label>Seleccionar tipo de impresión:</label>
                                    <select id="selectImpre" class="form-control" name="selectImpre">
                                        <option value="1">A4</option>
                                        <option value="2">A5</option>
                                    </select>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Imprimir</button>
                                <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    {!!Form::close()!!}
                    <!-- /.modal-content -->
                </div>
                <!-- /.modal-dialog -->
            </div>
		@stop			
			
		@section('scripts')		
    <script>
        function redondeo(num) {
            if (!num || num == 'NaN') return '-';
            if (num == 'Infinity') return '&#x221e;';
            num = num.toString().replace(/\$|\,/g, '');
            if (isNaN(num))
                num = "0";
            sign = (num == (num = Math.abs(num)));
            num = Math.floor(num * 100 + 0.50000000001);
            cents = num % 100;
            num = Math.floor(num / 100).toString();
            if (cents < 10)
                cents = "0" + cents;
            for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3) ; i++)
                num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
            return (((sign) ? '' : '-') + num + '.' + cents);
        }
        
        function cargarCorreo(){
            var venta = <?php echo json_encode($guiaSelect);?>;
            $('#inpCorreo').val(venta['Email']);
            $('#inpCliente').val(venta['Nombres']);
            $('#inpComprobante').val(venta['Serie']+'-'+venta['Numero']);
        }
    </script>
	<script type="text/javascript">
        $(function() {
            $(document).ready(function () {
                $('#table').DataTable({
                    responsive: true,
                    "paging":   false,
                    "ordering": false,
                    "info":     false,
                    "searching":false
                });
            });
            
        });
        
    </script>
	@stop







