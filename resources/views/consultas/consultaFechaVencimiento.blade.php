		@extends('layouts.app')
		@section('title', 'Comprobante Generado')
		@section('content')		
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Editar Fecha de Vencimiento</h6>
                    </div>
                    <!-- /.page-title-left -->
                <!--    <div class="page-title-right">
                        <div class="row mr-b-50 mt-2">
                            <div class="col-12 mr-b-20 d-flex">
                                <a class="p-1" href="../../compras/imprimir/{{$compraSelect->IdCompras}}" target="_blank"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">print</i></button></a>
                                <a class="p-1" href="../../compras/descargar/{{$compraSelect->IdCompras}}"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-28">picture_as_pdf</i></button></a>
                            </div>
                        </div>
                    </div> -->
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
                                                <h5>Documento: {{$compraSelect->Serie}}-{{$compraSelect->Numero}}</h5>
                                            </div>
                                            <div class="col-md-6 text-right d-none d-sm-block"><strong>PROVEEDOR:</strong> {{$compraSelect->Nombres}}
                                                
                                            </div>
                                            <div class="col-md-6 d-block d-sm-none"><strong>PROVEEDOR:</strong> {{$compraSelect->Nombres}}
                                                
                                            </div>
                                        </div>
                                        <!-- /.row -->
                                        <hr>
                                        <div class="d-sm-flex">
                                            <div class="col-md-6">
                                                <h6 class="mr-t-0">Usuario</h6><strong>NOMBRES:</strong> {{$compraSelect->Usuario}}
                                                <br><strong>SUCURSAL:</strong> {{$compraSelect->Sucursal}}
											</div>
                                            <div class="col-md-6 text-right d-none d-sm-block">
                                              
                                            </div>
                                            <div class="col-md-6 d-block d-sm-none">
                                              
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
                                                    <th class="text-center">Precio Venta</th>
                                                    <th class="text-center">Precio Costo</th>
                                                    <th class="text-center">Cantidad</th>
                                                    <th class="text-center">F. Vencimiento</th>
                                                    <th class="text-center">Importe</th>
                                                    <th class="text-center">Opciones</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($items as $item)
                                                <tr>
                                                    <td scope="row">{{$item->Cod}}</td>
                                                    <td>{{$item->Descripcion}}</td>
                                                    <td>{{$item->UniMedida}}</td>
                                                    <td>{{$item->Precio}}</td>
                                                    <td>{{$item->PrecioCosto}}</td>
                                                    <td>{{$item->Cantidad}}</td>
                                                    <td>{{ isset( $item->FechaVencimiento) ? date('d-m-Y', strtotime($item->FechaVencimiento)) : '' }}</td>
                                                    <td>{{$item->Importe}}</td>
													<td class="text-center">
                                                        <a href="#" class="edit-fecha" id="{{ $item->IdComprasArticulo}}"><i class="list-icon material-icons" data-toggle="modal" data-target="#signup-modal">create</i></a>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="row mt-4">
                                            <div class="col-md-8">
                                                <!--<p>Thanks for your business</p>
                                                <ul class="text-muted small">
                                                    <li>Aeserunt tenetur cum nihil repudiandae perferendis fuga vitae corporis!</li>
                                                    <li>Laborum, necessitatibus recusandae ullam at iusto dolore.</li>
                                                    <li>Voluptatum aperiam voluptates quasi!</li>
                                                    <li>Assumenda, iusto, consequuntur corporis atque culpa saepe magnam recusandae</li>
                                                    <li>Possimus odio ipsam magni sint reiciendis unde amet</li>
                                                </ul>-->
                                            </div>
                                            <div class="col-md-4 invoice-sum">
                                            <!--    <ul class="list-unstyled">
                                                    <li>SUBTOTAL: {{$compraSelect->Subtotal}}</li>
                                                    <li>IGV(18%): {{$compraSelect->IGV}}</li>
                                                    <li><strong>TOTAL : {{$compraSelect->Total}}</strong>
                                                    </li>
                                                </ul> -->
                                            </div>
                                        </div>
                                        <div class="form-actions btn-list mt-3">
                                            <a href="../../consultas/compras-boletas-facturas"><button class="btn btn-primary" type="button">Volver</button></a>
                                        </div>

									<!-- Signup Modal -->
                                    <div id="signup-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true" style="display: none">
                                        <div class="modal-dialog">
                                            <div class="modal-content">
                                                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                <div class="modal-body">
                                                    <div class="text-center my-3"><a href="#"><span><img src="assets/demo/logo-expand-dark.png" alt=""></span></a>
                                                    </div>
                                                   
                                                    	<div class="form-group mr-b-30">
                                                                <label for="fecha">Seleccione la fecha de Vencimiento</label>
																<div class="input-group">
                                                                    <input id="datepicker" type="date" data-plugin-options="{&quot;autoclose&quot;: true, &quot;format&quot;: &quot;dd/mm/yyyy&quot;}" class="form-control datepicker" name="fechaVencimiento">
                                                                    <div class="input-group-append">
                                                                        <div class="input-group-text"><i class="list-icon material-icons">date_range</i></div>
                                                                    </div>
                                                                </div>
																<input type="hidden" name="id_artCompra" id="laravel" value=""/>
                                                        </div>
                                                        <div class="text-center mr-b-30">
                                                            <button class="btn btn-rounded btn-success ripple" id='btn-ingresar' type="submit">Registrar Fecha</button>
                                                        </div>
                                                    
                                                </div>
                                            </div>
                                            <!-- /.modal-content -->
                                        </div>
                                        <!-- /.modal-dialog -->
                                    </div>
                                    <!-- /.modal -->

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
        @stop			
			
		@section('scripts')    
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
	
	<script>
		 $(".edit-fecha").click(function() {
 			$('#laravel').val($(this).attr("id"));
			//alert($(this).attr("id"))
 		 });
		
		$('#btn-ingresar').click(function(e){
			e.preventDefault();
			
			var id= $('#laravel').val();
			var fecha = $('#datepicker').val()
			
			//alert(id+' - '+fecha);
		   	$.ajax({                        
		      type: "POST",                 
		      url: 'http://www.mifacturita.pe/test5/registro/fecha_action',                    
		      data: {"_token": "{{csrf_token()}}", "idem": id, "fechaVenc":fecha},
		      success: function(data)            
		      {
		   		//alert('llorare');
				$('#signup-modal').modal('hide')
				//$('#resp').html(data);
				if(data[0] == 'alert1'){
                    alert(data[1]);
                }
				else if(data[0] == 'alert2')
				{	
					alert(data[1]);
				}
				else
				{
					location.reload();
 				}				
		      }
		    });
		});		
	</script>
 	@stop

