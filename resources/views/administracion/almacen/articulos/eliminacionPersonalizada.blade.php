@extends('layouts.app')
<link type="text/css" href="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/css/dataTables.checkboxes.css"
    rel="stylesheet" />
@section('title', 'Eliminacion Personalizada')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Eliminación Personalizada</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
        @if (session('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('status') }}
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-warning">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
        <div id="modal">

        </div>
    </div>

    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">

                        <div class="widget-body clearfix">
                            <div class="pb-2">
                                <button class="btn btn-success" onclick="modalEliminarProductos()" type="button"><i
                                        class="material-icons list-icon">delete</i>&nbsp;Eliminar Seleccionados</button>
                            </div>
                            <!--<p>Listado de ventas</p>-->
                            <table id="table" class="table table-responsive-sm" style="width:100%">
                                <thead>
                                    <tr class="bg-primary">
                                        <th scope="col"></th>
                                        <th scope="col">Nombre Producto</th>
                                        <th scope="col">Tipo Moneda</th>
                                        <th scope="col">Precio</th>
                                        <th scope="col">Stock</th>
                                        <th scope="col">Codigo</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $k=0; @endphp
                                    @foreach ($articulos as $articulo)
                                        <tr class="chequear" id="{{ $k }}">
                                            <td>
                                                {{ $articulo->IdArticulo }}-{{ $articulo->Stock }}
                                            </td>
                                            <td>{{ $articulo->Descripcion }} </td>
                                            <td>
                                                @if ($articulo->IdTipoMoneda == 1)
                                                    Soles
                                                @else
                                                    Dólares
                                                @endif
                                            </td>
                                            <td>{{ $articulo->Precio }}</td>
                                            <td>{{ $articulo->Stock }}</td>
                                            <td>{{ $articulo->Codigo }}</td>
                                        </tr>
                                        @php $k++; @endphp
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
    </div>

    <div class="modal fade" id="mostrarmodal" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
        <div class="modal-dialog">
            {!! Form::open([
                'url' => '/administracion/almacen/eliminacion-personalizada/eliminar',
                'method' => 'POST',
                'class' => 'form-material',
                'id' => 'form-eliminar',
            ]) !!}
            {{ csrf_field() }}
            <div class="modal-content">
                <div class="modal-header">
                    <label class="fs-14">Desea eliminar los siguientes productos?</h6>
                </div>
                <div class="modal-body form-material">
                    <div>
                        <label class="fs-12">Se eliminaran <strong id="total" class="fs-14 text-black"></strong>
                            producto(s), de los cuales <strong id="masCero" class="fs-14 text-danger"></strong> aún tienen
                            stock</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="form-actions btn-list mt-3">
                        <button class="btn btn-info">Aceptar</button>
                        <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                    </div>
                </div>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
    <!-- /.container -->
@stop

@section('scripts')

    <script type="text/javascript"
        src="//gyrocode.github.io/jquery-datatables-checkboxes/1.2.12/js/dataTables.checkboxes.min.js"></script>
    <script src="{{ asset('assets/js/administracion/productos.js') }}"></script>


@stop
