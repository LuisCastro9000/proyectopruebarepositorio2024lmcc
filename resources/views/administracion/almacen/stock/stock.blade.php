@extends('layouts.app')
@section('title', 'EmparejarStock')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">

        @if (session::has('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <div class="row mt-4 align-items-center">
            <div class="col-12 col-md-2">
                <a href="{!! url('/reportes/almacen/regularizacion-stock') !!}">
                    <img width="100px" src="{{ asset('/assets/img/graficoReporte.png') }}" alt=""><br>
                    <button class="btn  btn--verde ripple">Ver
                        Reporte</button></a>
            </div>
            <div class="col-12 col-md-10">
                <div class="jumbotron jumbotron-fluid py-4" style="background-color: #009EF7; color:#fff">
                    <div class="container">
                        <h6 class="page-title-heading mr-0 mr-r-5 font-weight-bold" style="color: #181c32">Regularizar stock
                        </h6>
                        <br>
                        <span class="fs-16 font-weight-normal">
                            Nuestra plataforma detectó interminencia de su línea de Internet originando una inconsistencia
                            en los
                            Productos mostrados líneas abajo.
                            Es necesario que realize un conteo físico de estos e ingrese la cantidad correcta en la columna
                            STOCK EN
                            ALMACEN.
                            <br><br>
                            En el caso de que el conteo físico coincida con la columna STOCK EN SISTEMA, de igual manera
                            deberá
                            ingresarlo para su validación.
                        </span>
                    </div>
                </div>

            </div>
        </div>
        <div class="row mt-4">
            <div class="col-12">
                {{-- {!! Form::open(['url' => '/administracion/almacen/actualizar-stock', 'method' => 'POST', 'files' => true]) !!} --}}
                {{-- Contenedor productos --}}
                <table id="table" class="table table-responsive-xl" style="width:100%">
                    <thead>
                        <tr class="bg-primary">
                            <th scope="col">Id Articulo</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Marca</th>
                            <th scope="col">Tipo de Moneda</th>
                            <th scope="col">Unidad de Medida</th>
                            <th scope="col">Stock en sistema</th>
                            <th scope="col">Stock en Almacén</th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($cantidadArticulos as $datos)
                            <tr>
                                <td class="text-center"> {{ $datos->IdArticulo }}</td>
                                <td scope="row">
                                    {{ $datos->Descripcion }}
                                </td>
                                <td class="text-center"> {{ $datos->NombreMarca }}</td>
                                <td class="text-center">{{ $datos->IdTipoMoneda == 1 ? 'Soles' : 'Dolares' }}</td>
                                <td class="text-center">
                                    {{ $datos->UM }}
                                </td>
                                <td class="text-center">
                                    {{ $datos->Stock }}
                                </td>
                                <td>
                                    @if ($datos->IdUnidadMedida == 1)
                                        <div class="form-group">
                                            <input type="number" class="form-control w-100" value="" min="0"
                                                id="inputStockActualizado-{{ $datos->IdArticulo }}"
                                                onchange="validarStock({{ $datos->IdArticulo }})"
                                                onkeydown="soloNumeroEntero()" disabled>
                                        </div>
                                    @else
                                        <div class="form-group">
                                            <input type="number" class="form-control w-100" value="" step="0.05"
                                                min="0" id="inputStockActualizado-{{ $datos->IdArticulo }}"
                                                onchange="validarStock({{ $datos->IdArticulo }})" disabled>
                                        </div>
                                    @endif

                                    <input type="number" value="{{ $datos->Stock }}"
                                        id="inputStockReal-{{ $datos->IdArticulo }}" hidden />
                                    <input type="text" value="{{ $datos->IdArticulo }}"
                                        id="inputId-{{ $datos->IdArticulo }}" hidden>
                                    <input type="text" value="{{ $datos->CodigoInterno }}"
                                        id="inputCodigoInterno-{{ $datos->IdArticulo }}" hidden>
                                    <input type="text" value="{{ $datos->Precio }}"
                                        id="inputPrecio-{{ $datos->IdArticulo }}" hidden>
                                    <input type="text" value="{{ $datos->Costo }}"
                                        id="inputCosto-{{ $datos->IdArticulo }}" hidden>
                                    <input type="text" value="{{ $datos->SumaTotal }}"
                                        id="inputSumaTotalStock-{{ $datos->IdArticulo }}" hidden>
                                </td>
                                <td>
                                    <input id="check-{{ $datos->IdArticulo }}" class="check-{{ $datos->IdArticulo }} mt-2"
                                        type="checkbox" onclick="comprobarChekeo({{ $datos->IdArticulo }})" />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 mt-4">
                <section class="d-flex justify-content-end">
                    <button id="btnActualizarStock" class="btn btn--verde font-weight-bolder" type="submit"
                        value="regularizacionStock">Actualizar
                        Stock</button>
                </section>
            </div>
            {{-- {!! Form::close() !!} --}}
        </div>

        <div class="modal fade modalcomprobarPermiso" id="exampleModalCentered" tabindex="-1" role="dialog"
            aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="form-group text-center mt-3">
                        <input id="id" class="d-none" type="text" value="">
                        <label for="formGroupExampleInput" class="fs-14">Ingrese clave de Supervisor para Regularizar
                            stock</label>
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
    <script src="{{ asset('assets/js/libreriasExternas/libreriaSweetAlert2-11.7.3/sweetalert2.all.min.js') }}"></script>
    <script src="{{ asset('assets/js/libreriasExternas/loadingOverlay/loadingoverlay.min.js') }}"></script>
    <script src="{{ asset('assets/js/scriptGlobal/loadingOverlay.js') }}"></script>
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
                console.log('indice ' + indice);
                if (indice > -1) {
                    articulos[indice].stockActualizado = $('#inputStockActualizado-' + idArticulo).val();
                } else {
                    capturarDatosArticulo(idArticulo);
                    console.log(articulos);
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
                    text: 'Falta ingregar cantidad!',
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
                        showLoadingOverlay('Regularizando Stock. <br> espere un momento, por favor ....')
                        $.ajax({
                            type: 'post',
                            url: 'actualizar-stock',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                'articulos': articulos,
                                'btnRegularizar': $(this).val()
                            },
                            success: function(data) {
                                hideLoadingOverlay();
                                if (data[0] == 'Success') {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Excelente!',
                                        text: 'La regularización fue un éxito',
                                        allowOutsideClick: false,
                                        allowEscapeKey: false,
                                        allowEnterKey: false,
                                        stopkeydownPropagation: true
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location = '../../panel';
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
                    "order": [
                        [4, "asc"]
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
