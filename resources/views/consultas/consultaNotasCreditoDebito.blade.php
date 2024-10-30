 @extends('layouts.app')
 @section('title', 'Consulta Notas Créditos - Débitos')
 @section('content')
     <div class="container">
         <div class="row page-title clearfix">
             <div class="page-title-left">
                 <h6 class="page-title-heading mr-0 mr-r-5">Listado de Notas Créditos y Débitos</h6>
             </div>
             <!-- /.page-title-left -->
             <!--<div class="page-title-right">
                                                <div class="row mr-b-50 mt-2">
                                                    <div class="col-12 mr-b-20 d-sm-block d-none">
                                                        <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i>  Nuevo</button></a>
                                                    </div>
                                                    <div class="col-12 mr-b-20 d-sm-none d-block">
                                                        <a href="../../operaciones/ventas/realizar-venta/create"><button class="btn btn-block btn-primary ripple"><i class="list-icon material-icons fs-26">note</i></button></a>
                                                    </div>
                                                </div>
                                            </div>-->
             <!-- /.page-title-right -->
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

         {!! Form::open(['url' => '/consultas/notas-credito-debito', 'method' => 'POST', 'files' => true]) !!}
         {{ csrf_field() }}
         <div class="row clearfix">
             <div class="col-md-4 mt-4 order-md-1">
                 <div class="form-group form-material">
                     <label>Tipo</label>
                     <select id="tipoNota" class="form-control" name="tipoNota">
                         <option value="0">Todo</option>
                         <option value="1">Nota Crédito</option>
                         <option value="2">Nota Débito</option>
                     </select>
                 </div>
             </div>
             <div class="col-md-4 mt-4 order-md-2">
                 <x-selectorFiltrosFechas obtenerDatos="false" class="form-material" />
             </div>
             <div class="col-md-3 mt-4 order-md-3 order-last">
                 <div class="form-group">
                     <br>
                     <button type="submit" class="btn btn-primary">Buscar</button>
                 </div>
             </div>
         </div>
         <x-inputFechasPersonalizadas mostrarBoton="false" />
         {!! Form::close() !!}
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
                         <!--<div class="widget-heading clearfix">
                                                            <h5>TableSaw</h5>
                                                        </div>-->
                         <!-- /.widget-heading -->
                         <div class="widget-body clearfix">
                             <!--<p>Listado de ventas</p>-->
                             <table id="table" class="table table-responsive-sm" style="width:100%">
                                 <thead>
                                     <tr class="bg-primary">
                                         <th scope="col">Fecha</th>
                                         <th scope="col">Cliente</th>
                                         <th scope="col">Código</th>
                                         <th scope="col">Tipo de Nota</th>
                                         <th scope="col">Doc. Afectado</th>
                                         <th scope="col">Tipo Moneda</th>
                                         <th scope="col">Monto</th>
                                         <th scope="col">Tipo Oper.</th>
                                         <th scope="col">Motivo</th>
                                         <th scope="col">Estado</th>
                                         <th scope="col">Codigo Error</th>
                                         <th scope="col">Opciones</th>
                                     </tr>
                                 </thead>
                                 <tbody>
                                     @foreach ($notasAceptadas as $notaAceptada)
                                         <tr>
                                             <td>{{ $notaAceptada->FechaCreacion }}</td>
                                             <td>{{ $notaAceptada->Nombres }}</td>
                                             <td>{{ $notaAceptada->Serie }}-{{ $notaAceptada->Numero }}</td>
                                             <td>{{ $notaAceptada->TipoNota }}</td>
                                             <td>{{ $notaAceptada->DocModificado }}</td>
                                             <td>{{ $notaAceptada->IdTipoMoneda == 1 ? 'Soles' : 'Dólares' }}</td>
                                             <td>{{ $notaAceptada->Total }}</td>
                                             <td>{{ $notaAceptada->TipoVenta == 1 ? 'Gravada' : 'Exonerado' }}</td>
                                             <td>{{ $notaAceptada->Motivo }}</td>
                                             <td>{{ $notaAceptada->Estado }}</td>
                                             <td class="text-center">
                                                 {{ $notaAceptada->CodigoDoc == 0 ? '-' : $notaAceptada->CodigoDoc }}</td>
                                             <td class="text-center">
                                                 <a href="../consultas/notas-credito-debito/detalles/{{ $notaAceptada->IdCreditoDebito }}/2"
                                                     title="Detalles"><i class="list-icon material-icons">visibility</i></a>
                                                 <a href="../consultas/notas-credito-debito/descargar/{{ $notaAceptada->IdCreditoDebito }}"
                                                     title="Descargar PDF"><i
                                                         class="list-icon material-icons">picture_as_pdf</i></a>
                                                 <a href="../consultas/notas-credito-debito/xml/{{ $rucEmpresa }}/{{ $notaAceptada->IdCreditoDebito }}"
                                                     title="Descargar XML"><i class="list-icon material-icons">code</i></a>
                                                 @if ($notaAceptada->Estado == 'Aceptado')
                                                     <a href="../consultas/notas-credito-debito/cdr/{{ $rucEmpresa }}/{{ $notaAceptada->IdCreditoDebito }}"
                                                         title="Descargar CDR"><i
                                                             class="list-icon material-icons">attach_file</i></a>
                                                     @if ($notaAceptada->Nota == 0 && $notaAceptada->IdDocModificado == 2)
                                                         @if ($notaAceptada->FechaCreacion > $dateAtras)
                                                             <a href="#" data-toggle="modal"
                                                                 data-target=".bs-modal-sm-anular" title="Anular"
                                                                 onclick="anular({{ $notaAceptada->IdCreditoDebito }})"><i
                                                                     class="list-icon material-icons text-danger">do_not_disturb</i></a>
                                                         @endif
                                                     @endif
                                                 @endif
                                             </td>
                                         </tr>
                                     @endforeach
                                 </tbody>
                             </table>
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
         <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal"
             aria-hidden="true">
             <div class="modal-dialog">
                 <div class="modal-content">
                     <div class="modal-header">
                         <h6 class="text-success">Consultas de Notas de Crédito</h6>
                     </div>
                     <div class="modal-body form-material">
                         <div>
                             <label class="fs-14 negrita">Notas de Crédito del Mes</label>
                             <p class="fs-15negrita">Se mostraran solo las Notas de Créditos de este mes....... Si desea
                                 ver Notas de Créditos anteriores utilize los filtros</p>
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

         <div class="modal modal-primary fade bs-modal-sm-anular" tabindex="-1" role="dialog"
             aria-labelledby="mySmallModalLabel2" aria-hidden="true" style="display: none">
             <div class="modal-dialog modal-md">
                 {!! Form::open([
                     'url' => '/consultas/notas-credito-debito/anulando',
                     'method' => 'POST',
                     'files' => true,
                     'class' => 'form-material',
                 ]) !!}
                 <div class="modal-content">
                     <div class="modal-header text-inverse">
                         <!--<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>-->
                         <h6 class="modal-title" id="mySmallModalLabel2">Dar Baja Nota de Crédito</h6>
                     </div>
                     <div class="modal-body">
                         <div class="container">
                             <div class="form-group">
                                 <label>Descripción:</label>
                                 <input id="inpDescripcion" class="form-control" name="descripcion" />
                             </div>
                             <input id="inpNota" hidden class="form-control" name="idNota" />
                         </div>
                     </div>
                     <div class="modal-footer">
                         <button type="submit" class="btn btn-primary">Anular</button>
                         <button type="button" class="btn btn-primary" data-dismiss="modal">Cancelar</button>
                     </div>
                 </div>
                 {!! Form::close() !!}
             </div>
         </div>

     </div>
     <!-- /.container -->
 @stop

 <!-- Estas variables son usadas en el archivo assets/js/utilidades/scriptFechasPersonalizadas.js-->
 @section('variablesJs')
     <script>
         const variablesBlade = {
             fecha: @json($fecha),
             fechaInicial: @json($fechaInicial),
             fechaFinal: @json($fechaFinal),
         }
     </script>
 @endsection


 @section('scripts')
     <script>
         $(function() {
             var bandModal = <?php echo json_encode($IdTipoNota); ?>;

             if (bandModal === '') {
                 $("#mostrarmodal").modal("show");
             }
             var idTipoNota = <?php echo json_encode($IdTipoNota); ?>;
             $('#tipoNota option[value=' + idTipoNota + ']').prop('selected', true);
         });

         function redondeo(num) {
             /*var flotante = parseFloat(numero);
             var resultado = Math.round(flotante * Math.pow(10, decimales)) / Math.pow(10, decimales);
             return resultado;*/

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
             for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
                 num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
             return (((sign) ? '' : '-') + num + '.' + cents);
         }

         function anular(id) {
             $('#inpNota').val(id);
         }
     </script>
     <script type="text/javascript">
         $(function() {
             $(document).ready(function() {
                 $('#table').DataTable({
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
 @stop
