@extends('layouts.app')
@section('title', 'Detalle Grupo')
@section('content')
    <style>
        .jumbotron-fluid {
            background-color: #FFF !important;
            border-radius: 10px;
        }
    </style>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">
        <h6 class="mt-4">Detalle Paquete</h6>
        <div class="jumbotron jumbotron-fluid my-4">
            <div class="container">
                <section class="row">
                    <div class="col">
                        @if (count($itemsEliminado) >= 1)
                            <section class="text-center">
                                <span class="badge badge-danger fs-14">Los items tachados se encuentran eliminados, no se
                                    incluyen dentro del Paquete</span>
                            </section>
                            <br><br>
                        @endif
                        <section class="d-flex justify-content-sm-between justify-content-center flex-wrap mb-3">
                            <article class="text-center">
                                <span class="fs-15 text-uppercase mr-2">Paquete:</span>
                                <span class="font-weight-bold fs-20">{{ $nombreGrupo }}</span>
                            </article>
                            <article>
                                <a href=" {{ url('/administracion/almacen/grupos') }}">
                                    <button type="button" class="btn btn-secondary btn-sm"><i
                                            class='bx bx-share fs-20 icono-vehicular mr-1'></i> Volver</button>
                                </a>
                            </article>
                        </section>
                        <table id="table" class="table table-bordered table-responsive-sm" style="width: 100%">
                            <thead>
                                <tr class="bg-success">
                                    <th scope="col" class="text-center">Codigo</th>
                                    <th scope="col" class="text-center">Descripcion</th>
                                    <th scope="col" class="text-center">Marca</th>
                                    <th scope="col" class="text-center">Categoria</th>
                                    <th scope="col" class="text-center">Precio</th>
                                    <th scope="col" class="text-center">Stock</th>
                                    <th scope="col" class="text-center">CodigoBarra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemsGrupo as $item)
                                    <tr>
                                        @if ($item->Estado == 'D')
                                            <td class="text-center text-tachado">{{ $item->Codigo }}</td>
                                            <td class="text-center text-tachado">{{ $item->Descripcion }}</td>
                                            <td class="text-center text-tachado">{{ $item->nombreMarca }}</td>
                                            <td class="text-center text-tachado">{{ $item->nombreCategoria }}</td>
                                            <td class="text-center text-tachado">{{ $item->Precio }}</td>
                                            <td class="text-center text-tachado">{{ $item->Stock }}</td>
                                            <td class="text-center text-tachado">{{ $item->codigoBarra }}</td>
                                        @else
                                            <td class="text-center">{{ $item->Codigo }}</td>
                                            <td class="text-center">{{ $item->Descripcion }}</td>
                                            <td class="text-center">{{ $item->nombreMarca }}</td>
                                            <td class="text-center">{{ $item->nombreCategoria }}</td>
                                            <td class="text-center">{{ $item->Precio }}</td>
                                            <td class="text-center">{{ $item->Stock }}</td>
                                            <td class="text-center">{{ $item->codigoBarra }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop
@section('scripts')

    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false
                });
            });

        });
    </script>
@stop
