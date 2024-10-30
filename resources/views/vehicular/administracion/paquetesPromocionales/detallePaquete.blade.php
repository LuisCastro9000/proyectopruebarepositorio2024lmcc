@extends('layouts.app')
@section('title', 'Detalle Paquete Promocional')
@section('content')
    <style>
        .jumbotron-fluid {
            background-color: #FFF !important;
            border-radius: 10px;
        }
    </style>
    <link href='https://unpkg.com/boxicons@2.1.2/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">
        <h6 class="mt-4">Detalle Paquete Promocional</h6>
        <div class="jumbotron jumbotron-fluid my-4 px-2">
            <div class="container">
                <section class="row">
                    <div class="col">
                        @if (count($itemsEliminado) >= 1)
                            <section class="text-center">
                                <span class="badge badge-danger fs-14">Los items tachados se encuentran eliminados, no se
                                    incluyen dentro del Paquete Miscelaneo</span>
                            </section>
                            <br><br>
                        @endif
                        <section class="d-flex justify-content-sm-between justify-content-center flex-wrap mb-3">
                            <article class="text-center">
                                <span class="fs-15 text-uppercase mr-2">Paquete:</span>
                                <span class="font-weight-bold fs-20">{{ $nombrePaquete }}</span>
                            </article>
                            <article>
                                <a href=" {{ url('vehicular/administracion/paquetes-promocionales') }}">
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
                                    <th scope="col" class="text-center">Costo</th>
                                    <th scope="col" class="text-center">Cantidad</th>
                                    <th scope="col" class="text-center">Stock</th>
                                    <th scope="col" class="text-center">CodigoBarra</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($itemsPaquete as $item)
                                    <tr>
                                        @if ($item->Estado == 'D')
                                            <td class="text-center text-tachado">{{ $item->Codigo }}</td>
                                            <td class="text-center text-tachado">{{ $item->Descripcion }}</td>
                                            <td class="text-center text-tachado">{{ $item->nombreMarca }}</td>
                                            <td class="text-center text-tachado">{{ $item->nombreCategoria }}</td>
                                            <td class="text-center text-tachado">{{ $item->Precio }}</td>
                                            <td class="text-center text-tachado">{{ $item->Costo }}</td>
                                            <td class="text-center text-tachado">{{ $item->Cantidad }}</td>
                                            <td class="text-center text-tachado">{{ $item->Stock }}</td>
                                            <td class="text-center text-tachado">{{ $item->codigoBarra }}</td>
                                        @else
                                            <td class="text-center">{{ $item->CodigoArticulo }}</td>
                                            <td class="text-center">{{ $item->Descripcion }}</td>
                                            <td class="text-center">{{ $item->nombreMarca }}</td>
                                            <td class="text-center">{{ $item->nombreCategoria }}</td>
                                            <td class="text-center">{{ $item->Precio }}</td>
                                            <td class="text-center">{{ $item->Costo }}</td>
                                            <td class="text-center">{{ $item->Cantidad }}</td>
                                            <td class="text-center">{{ $item->Stock }}</td>
                                            <td class="text-center">{{ $item->codigoBarra }}</td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="col-12 mt-3">
                        <section class="d-flex justify-content-end align-items-center">
                            <div>
                                <label class="mr-2">Costo:</label>
                                <input id="costoPaquete" class="input-transparent" type="text" name="costoPaquete"
                                    value="" readonly>
                            </div>
                        </section>
                        <section class="d-flex justify-content-end align-items-center">
                            <div>
                                <label class="mr-2">Total:</label>
                                <input id="totalPaquete" class="input-transparent" type="text" name="totalPaquete"
                                    value="" readonly>
                            </div>
                        </section>
                    </div>
                </section>
            </div>
        </div>
    </div>
@stop
@section('scripts')
    <script>
        $(function() {
            let $items = @json($itemsPaquete);
            let $costo = 0;
            let $total = 0;
            $items.forEach(element => {
                $importe = parseFloat(element.Precio) * parseFloat(element.Cantidad);
                $costoTotal = parseFloat(element.Costo) * parseFloat(element.Cantidad);
                $total += parseFloat($importe);
                $costo += parseFloat($costoTotal);
            });
            document.querySelector('#totalPaquete').value = $total.toFixed(2);
            document.querySelector('#costoPaquete').value = $costo.toFixed(2);
        });
    </script>

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
