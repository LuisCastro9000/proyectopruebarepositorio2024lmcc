@extends('layouts.app')
@section('title', 'Reguralizar Inventario')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">

        @if (session::has('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif

        <div>
            <section class="d-flex justify-content-between align-items-end flex-wrap mt-3 mb-4">
                <article>
                    <a href="{!! url('/reportes/almacen/regularizacion-inventario') !!}">
                        <img width="100px" class="ml-2" src="{{ asset('/assets/img/reporteInventario.png') }}"
                            alt=""><br>
                        <button class="btn bg-green ripple">Ver Reporte</button></a>
                </article>
                <article>
                    <button id="btnActualizarStock" class="btn btn--verde font-weight-bolder" type="submit"
                        value="inventario">Actualizar
                        Stock</button>
                </article>
            </section>
            <section><input type="text" id="regularizar" value="inventario" hidden></section>
            <div class="widget-bg">
                <div class="widget-body clearfix">
                    <table id="table" class="table table-responsive-xl" style="width:100%">
                        <thead>
                            <tr class="bg-primary">
                                <th scope="col">Nombre Producto</th>
                                <th scope="col">Marca</th>
                                <th scope="col">Codigo</th>
                                <th scope="col">Tipo Moneda</th>
                                <th scope="col">Precio</th>
                                <th scope="col">Stock Actual</th>
                                <th scope="col">Nuevo Stock</th>
                                <th scope="col"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($stockArticulos as $articulo)
                                <tr class="chequear" id="{{ $articulo->IdArticulo }}">

                                    <td>{{ $articulo->Descripcion }} </td>
                                    <td>{{ $articulo->Marca }}</td>
                                    <td>{{ $articulo->CodigoBarra }}</td>
                                    <td>
                                        @if ($articulo->IdTipoMoneda == 1)
                                            Soles
                                        @else
                                            Dólares
                                        @endif
                                    </td>
                                    <td>{{ $articulo->Precio }}</td>
                                    <td>{{ $articulo->Stock }}</td>
                                    <td>
                                        @if ($articulo->IdUnidadMedida == 1)
                                            <input style="width: 80px" type="number"
                                                id="inputStockActualizado-{{ $articulo->IdArticulo }}"
                                                name="stockActualizado[]" disabled value="" min="0"
                                                onchange="validarStock({{ $articulo->IdArticulo }})"
                                                onkeydown="soloNumeroEntero()" />
                                        @else
                                            <input style="width: 80px" type="number"
                                                id="inputStockActualizado-{{ $articulo->IdArticulo }}"
                                                name="stockActualizado[]" disabled value="" step="0.05"
                                                min="0" onchange="validarStock({{ $articulo->IdArticulo }})" />
                                        @endif
                                        <input type="number" id="inputStockReal-{{ $articulo->IdArticulo }}"
                                            name="stockReal[]" value="{{ $articulo->Stock }}" hidden disabled />
                                        <input type="text" id="inputId-{{ $articulo->IdArticulo }}" name="idArticulo[]"
                                            value="{{ $articulo->IdArticulo }}" hidden disabled>
                                        <input type="text" id="inputCodigoInterno-{{ $articulo->IdArticulo }}"
                                            name="codigoInterno[]" value="{{ $articulo->CodigoInterno }}" hidden disabled>
                                        <input type="text" id="inputPrecio-{{ $articulo->IdArticulo }}" name="precio[]"
                                            value="{{ $articulo->Precio }}" hidden disabled>
                                        <input type="text" id="inputCosto-{{ $articulo->IdArticulo }}" name="costo[]"
                                            value="{{ $articulo->Precio }}" hidden disabled>
                                        <input type="text" id="inputSumaTotalStock-{{ $articulo->IdArticulo }}"
                                            name="sumaTotalStock[]" value="{{ $articulo->SumaTotal }}" hidden disabled>
                                    </td>
                                    <td>
                                        <input id="check-{{ $articulo->IdArticulo }}" class="mt-2" name="artSucursal[]"
                                            type="checkbox" value="{{ $articulo->CodigoInterno }}"
                                            onclick="comprobarChekeo({{ $articulo->IdArticulo }})" />
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>


        <div class="modal fade modalcomprobarPermiso" id="exampleModalCentered" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="form-group text-center mt-3">
                        <input id="id" class="d-none" type="text" value="">
                        <label for="formGroupExampleInput" class="fs-14">Ingrese clave de Supervisor para Regularizar
                            Inventario</label>
                        <input id="password" value="" type="text" class="form-control text-center"
                            placeholder="********" style="-webkit-text-security: disc;" autocomplete="off">
                        <div id="mensaje" class="py-1">
                        </div>
                        <button id="btnComprobar" type="button" class="btn btn-primary">Comprobar Permiso</button>
                        <div class="row mt-3">
                            <div class="col  m-auto">
                                <a href="../../panel"><button class="btn btn--verde ripple">Cancelar</button></a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>



@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        var articulos = [];

        function comprobarChekeo(id) {
            if ($('#check-' + id).is(':checked')) {
                $('#inputStockActualizado-' + id).removeAttr('disabled', 'false');
            } else {
                $('#inputStockActualizado-' + id).attr('disabled', 'true');
                $('#inputStockActualizado-' + id).val('');
                eliminarArticulo(id);
            }
        }

        function validarStock(idArticulo) {
            const stockActualizado = $('#inputStockActualizado-' + idArticulo).val();
            if (stockActualizado < 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stock Negativo',
                    text: 'El stock no debe ser menor que cero',
                    confirmButtonText: 'Entendido',
                }).then((result) => {
                    if (result.isConfirmed) {
                        $('#inputStockActualizado-' + idArticulo).val(1);
                    }
                })
            } else {

                indice = articulos.findIndex((item => item.idArticulo === idArticulo));
                if (indice > -1) {
                    articulos[indice].stockActualizado = $('#inputStockActualizado-' + idArticulo).val();
                } else {
                    capturarDatosArticulo(idArticulo);
                }
            }
        }

        function capturarDatosArticulo(idArticulo) {
            articulos.push({
                idArticulo: idArticulo,
                stockReal: $('#inputStockReal-' + idArticulo).val(),
                stockActualizado: $('#inputStockActualizado-' + idArticulo).val(),
                codigoInterno: $('#inputCodigoInterno-' + idArticulo).val(),
                precio: $('#inputPrecio-' + idArticulo).val(),
                costo: $('#inputCosto-' + idArticulo).val(),
                sumaTotalStock: $('#inputSumaTotalStock-' + idArticulo).val(),

            });
        }

        function eliminarArticulo(id) {
            articulos = articulos.filter((item) => item.idArticulo != id);
        }

        function soloNumeroEntero() {
            var tecla = event.key;
            if (['.'].includes(tecla))
                event.preventDefault()
        }
    </script>

    <script>
        function CerrarModal() {
            $("#exampleModalCentered").modal('hide');
        }

        $(function() {
            $('.modalcomprobarPermiso').modal({
                backdrop: 'static',
                keyboard: false
            }, 'show');
        })

        $('#btnComprobar').click(function() {
            var password = $('#password').val();
            if (password !== "") {
                $.ajax({
                    type: "get",
                    url: "validar-clave",
                    data: {
                        'password': password
                    },
                    success: function(data) {
                        $('p').remove();
                        if (data[0] == 'Success') {
                            CerrarModal()
                            Swal.fire({
                                icon: 'success',
                                text: "Permiso Concedido",
                                showConfirmButton: false,
                                timer: 1000
                            })
                            $('#password').val("");
                            $('.editarGasto').removeClass('disabled-elemento');
                            $('#btnActualizar').removeClass('d-none');

                        } else {
                            $('#mensaje').append(
                                '<p class="text-center text-danger font-weight-bold">Error la clave no coincide</p>'
                            )
                            $('#password').val("");
                            $('#password').focus();
                            $('#password').addClass('border-danger');
                        }
                    }
                })
            } else {
                $('p').remove();
                $('#mensaje').append(
                    '<p class="text-center text-danger font-weight-bold">Por favor ingrese la clave</p>');
                $('#password').addClass('border-danger');
                $('#password').focus();
            }
        })
    </script>

    <script>
        $('#btnActualizarStock').click(function() {
            if (articulos.length < 1) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error..',
                    text: 'No se ha seleccionado ningún artículo',
                })
            } else {
                Swal.fire({
                    text: 'Las cantidades son Correctas?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si (Actualizar)',
                    cancelButtonText: 'No (Desistir)',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: 'post',
                            url: 'actualizar-stock',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'articulos': articulos,
                                'btnRegularizar': $(this).val()
                            },
                            success: function(data) {
                                if (data[0] == 'Success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Excelente!',
                                        text: data[1],
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false,
                                        stopkeydownPropagation: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location =
                                                'stock-articulos';
                                        }
                                    })
                                }
                            }
                        });
                    }
                })
            }

        })
    </script>

    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    // responsive: true,
                    // "pageLength": 50,
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
