@extends('layouts.app')
@section('title', 'Reguralizacion Stock')
@section('content')
    <style>
        .card-bg {
            background-color: #d6e9f3;
            color: #009EF7;
            border-radius: 10px;
        }
    </style>

    <div class="container">
        {!! Form::open(['url' => '/reportes/almacen/filtrar-articulo', 'method' => 'POST', 'files' => true]) !!}
        {{ csrf_field() }}
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="form-group form-material">
                    <label>Producto</label>
                    <select class="m-b-10 form-control select2-hidden-accessible" id="producto" name="producto"
                        data-placeholder="Seleccione producto" data-toggle="select2" tabindex="-1" aria-hidden="true">
                        <option value="0">Seleccione Producto</option>
                        @foreach ($nombresArticulos as $producto)
                            @if ($idArticulo == $producto->IdArticulo)
                                <option value="{{ $producto->IdArticulo }}" selected>{{ $producto->Descripcion }}</option>
                            @else
                                <option value="{{ $producto->IdArticulo }}">{{ $producto->Descripcion }}</option>
                            @endif
                            {{-- <option value="{{ $producto->IdArticulo }}">{{ $producto->Descripcion }}</option> --}}
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-12 col-md-4">
                <x-selectorFiltrosFechas obtenerDatos='false' />
            </div>
            <div class="col-3 col-md-1 form-group align-self-end">
                <button type="submit" class="btn btn-primary">Buscar</button>
            </div>
            <div class="col-4 col-md-1 form-group align-self-end">
                <a class="p-0" target="_blank"
                    href='{{ url("reportes/almacen/excel-articulo/$idArticulo/$fecha/$ini/$fin") }}'>
                    <span class="btn bg-excel ripple">
                        <i class="list-icon material-icons fs-20">explicit</i>XCEL
                    </span>
                </a>
            </div>
        </div>
        <x-inputFechasPersonalizadas mostrarBoton='false' />
        {!! Form::close() !!}
        <div class="row my-3 justify-content-center">
            <section class="col-4">
                <div class="card card-bg">
                    <div class="card-body text-center">
                        <span class="fs-16">Cantidad de Productos <br> Regularizados</span><br>
                        <span class="font-weight-bold fs-30">{{ $motivoInventario }}</span><br>
                        <span class="badge badge-danger">Motivo Inventario</span>
                    </div>
                </div>
            </section>
            <section class="col-4">
                <div class="card card-bg">
                    <div class="card-body text-center">
                        <span class="fs-16">Cantidad de Productos <br> Regularizados</span><br>
                        <span class="font-weight-bold fs-30">{{ $motivoLatenciaInternet }}</span><br>
                        <span class="badge badge-danger">Motivo Latencia de Internet</span>
                    </div>
                </div>
            </section>
        </div>
        <div class="row mt-4">
            <div class=" col-12 widget-holder">
                <div class="widget-bg">
                    <div class="widget-body clearfix">
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary">
                                    <th scope="col">Fecha de Regularización</th>
                                    <th scope="col">Usuario Responsable</th>
                                    <th scope="col">Descripcion</th>
                                    <th scope="col">Motivo</th>
                                    <th scope="col">Stock de Sistema</th>
                                    <th scope="col">Stock de Almacén</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listaArticulos as $item)
                                    <tr>
                                        <td>{{ $item->FechaCreacion }}</td>
                                        <td>{{ $item->NombreUsuario }}</td>
                                        <td>{{ $item->Descripcion }}</td>
                                        <td>{{ $item->Motivo }}</td>
                                        <td>{{ $item->StockSistema }}</td>
                                        <td>{{ $item->StockAlmacen }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

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
@endsection
