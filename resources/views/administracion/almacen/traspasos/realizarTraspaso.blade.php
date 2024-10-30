        @extends('layouts.app')
        @section('title', 'Realizar Traspasos')
        @section('content')
            <!-- Page Title Area -->
            <div class="container">
                <div class="row page-title clearfix">
                    <div class="page-title-left">
                        <h6 class="page-title-heading mr-0 mr-r-5">Realizar Traspaso</h6>
                    </div>
                    <!-- /.page-title-left -->
                </div>
                @if (Session::has('arrayProductos'))
                    @php $array = Session::get('arrayProductos') @endphp
                    @if (count($array) > 0)
                        <div class="alert alert-danger">
                            <button type="button" class="close" data-dismiss="alert">&times;</button>

                            <label class="text-danger">Estos productos fueron registrados, ya que no existia en el destino
                                seleccionado</label>
                            @foreach ($array as $datos)
                                <p class="text-danger">- {{ $datos }}</p>
                            @endforeach
                        </div>
                    @endif
                @endif
                <!-- /.page-title -->
            </div>
            <!-- /.container -->
            <!-- =================================== -->
            <!-- Different data widgets ============ -->
            <!-- =================================== -->
            <div class="container">
                <div class="widget-list">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            {!! Form::open(['url' => '/administracion/almacen/realizar-traspaso', 'method' => 'POST', 'files' => true]) !!}
                            {{ csrf_field() }}
                            <div class="row form-material">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="origen" class="form-control" name="almacenOrig">
                                            @if ($idAlmacen == $sucursalSelect->IdSucursal)
                                                <option selected value="s{{ $sucursalSelect->IdSucursal }}">
                                                    {{ $sucursalSelect->Nombre }}</option>
                                            @else
                                                <option value="s{{ $sucursalSelect->IdSucursal }}">
                                                    {{ $sucursalSelect->Nombre }}</option>
                                            @endif
                                            @foreach ($almacenes as $almacen)
                                                @if ($idAlmacen == $almacen->IdAlmacen)
                                                    <option selected value="a{{ $almacen->IdAlmacen }}">
                                                        {{ $almacen->Nombre }}</option>
                                                @else
                                                    <option value="a{{ $almacen->IdAlmacen }}">{{ $almacen->Nombre }}
                                                    </option>
                                                @endif
                                            @endforeach
                                        </select>
                                        <label for="almacenOrig">Almacén Origen</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <button type="submit" class="btn btn-primary">Ver Productos de Almacén
                                            Origen</button>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select id="destino" class="form-control" name="almacenDest">
                                            @if ($tipo == 's')
                                                @foreach ($sucursales as $sucursal)
                                                    <option value="s{{ $sucursal->IdSucursal }}">{{ $sucursal->Nombre }}
                                                    </option>
                                                @endforeach
                                                @foreach ($almacenes as $almacen)
                                                    <option value="a{{ $almacen->IdAlmacen }}">{{ $almacen->Nombre }}
                                                    </option>
                                                @endforeach
                                            @else
                                                <option value="s{{ $sucursalSelect->IdSucursal }}">
                                                    {{ $sucursalSelect->Nombre }}</option>
                                                @foreach ($sucursales as $sucursal)
                                                    <option value="s{{ $sucursal->IdSucursal }}">{{ $sucursal->Nombre }}
                                                    </option>
                                                @endforeach
                                                @foreach ($almacenes as $almacen)
                                                    @if ($idAlmacen != $almacen->IdAlmacen)
                                                        <option value="a{{ $almacen->IdAlmacen }}">{{ $almacen->Nombre }}
                                                        </option>
                                                    @endif
                                                @endforeach
                                            @endif
                                        </select>
                                        <label for="almacenDest">Almacén Destino</label>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <table id="tabla2" class="table table-responsive-lg" style="width:100%">
                                        <thead>
                                            <tr class="bg-primary">
                                                <th></th>
                                                <th scope="col"></th>
                                                <th scope="col">Descripción</th>
                                                <th scope="col">Código de Barra</th>
                                                <th scope="col">Tipo de Moneda</th>
                                                <th scope="col">Marca</th>
                                                <th scope="col">Unidad de Medidad</th>
                                                <th scope="col">Stock Disponible</th>
                                                <th scope="col">Cantidad a Traspasar</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $i=0; @endphp
                                            @foreach ($productos as $producto)
                                                <tr class="ajustar-texto">
                                                    <td></td>
                                                    <td class="chequear" id="{{ $producto->IdArticulo }}"><input
                                                            {{ $producto->Stock > 0 ? '' : 'disabled' }}
                                                            class="micheckbox{{ $producto->IdArticulo }}" type="checkbox"
                                                            value="{{ $producto->IdArticulo }}" /></td>
                                                    <td>{{ $producto->Descripcion }}</td>
                                                    <td>{{ $producto->Codigo }}</td>
                                                    <td>
                                                        @if ($producto->IdTipoMoneda == 1)
                                                            Soles
                                                        @else
                                                            Dólares
                                                        @endif
                                                    </td>
                                                    <td>{{ $producto->Marca }}</td>
                                                    <td>{{ $producto->UM }}</td>
                                                    <td>{{ $producto->Stock }}</td>
                                                    <td><input id="inpCantidad{{ $producto->IdArticulo }}" value="0"
                                                            type="number" disabled
                                                            class="input-cantidad{{ $producto->IdArticulo }}"
                                                            onchange="calcular(this, {{ $producto->IdArticulo }}, {{ $producto->Stock }});"
                                                            step="{{ $producto->IdUnidadMedida == 1 ? '1' : '0.05' }}"
                                                            min="0" max="{{ $producto->Stock }}"
                                                            style="width:100px" /></td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                <div id="armarArray">
                                </div>
                            </div>
                            <button class="btn btn-primary" id="guardarT" onclick="guardar();" type="button"
                                data-dismiss="modal">Guardar</button>
                        </div>
                        {!! Form::close() !!}
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                    <!-- /.widget-holder -->
                </div>
                <!-- /.row -->
            </div>
            <div class="modal modal-primary fade bs-modal-sm-primary" id="mostrarmodal" tabindex="-1" role="dialog"
                aria-labelledby="basicModal" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h6 id="idTitulo" class="text-white">Traspasar Stock</h6>
                        </div>
                        <div class="modal-body form-material">
                            <div class="container">
                                <label id="idAlerta" class="fs-12 negrita"></label>
                                <br>
                                <label id="nombreProducto" class="fs-14 negrita"></label>
                                <div class="form-group row">
                                    <div class="col-md-9">
                                        <select id="selectCoincidencias" class="custom-select">
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-9">
                                        <label>Ingrese cantidad a traspasar:</label>
                                        <input id="inpStock" class="form-control" type="number" />
                                    </div>
                                </div>
                                <div class="form-group row" hidden>
                                    <div class="col-md-9">
                                        <input id="inpIdArticulo" class="form-control" />
                                        <input id="inpDescripcion" class="form-control" />
                                        <input id="inpTipoMoneda" class="form-control" />
                                        <input id="inpMarca" class="form-control" />
                                        <input id="estado" class="form-control" />
                                        <input id="stockMax" class="form-control" />
                                        <input id="codInterno" class="form-control" />
                                        <input id="idUnidadMedida" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="form-actions btn-list mt-3">
                                <button class="btn btn-info" type="button" data-dismiss="modal"
                                    onclick="aceptado();">Aceptar</button>
                                <button class="btn btn-default" type="button" data-dismiss="modal">Cancelar</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @stop

        @section('scripts')
            <script>
                $(function() {
                    var table;
                    $(document).ready(function() {
                        table = $('#tabla2').DataTable({
                            columnDefs: [{
                                targets: 0,
                                checkboxes: {
                                    selectRow: true,
                                },
                            }, ],
                            select: {
                                style: "multi"
                            },
                            responsive: false,
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
                        })
                    });

                    $(".chequear").click(function() {
                        var table = $('#tabla2').DataTable();
                        var idCheck = $(this).attr("id");
                        if ($('.micheckbox' + idCheck).is(':checked')) {
                            $("#inpCantidad" + idCheck).prop('disabled', false);
                            $('#armarArray').append($("<input>").attr("id", "inp" + idCheck).attr("name",
                                "CantidadTraspasar[]").val(0));
                            $('#armarArray').append($("<input>").attr("id", "id" + idCheck).attr("name",
                                "IdArticulos[]").val(idCheck));
                        } else {
                            $("#inpCantidad" + idCheck).prop('disabled', true);
                            $("#inp" + idCheck).remove();
                            $("#id" + idCheck).remove();
                            $("#inpCantidad" + idCheck).val(0);
                        }
                    });
                });
            </script>
            <script>
                var arrayProductos = [];
                $(function() {
                    $('#selectCoincidencias').hide();
                    $("#origen").on('change', function() {
                        var almacen = $("#origen").val();
                        var tipo = almacen.substring(0, 1);
                        var idAlmacen = almacen.slice(1);
                        $('#destino option').remove();
                        var almacenes = <?php echo json_encode($almacenes); ?>;
                        if (tipo == 'a') {
                            var almacenSucursal = <?php echo json_encode($sucursalSelect); ?>;

                            $('#destino').append('<option value="s' + almacenSucursal["IdSucursal"] + '">' +
                                almacenSucursal["Nombre"] + '</option>');
                        }
                        var sucursales = <?php echo json_encode($sucursales); ?>;
                        for (var i = 0; i < sucursales.length; i++) {
                            $('#destino').append('<option value="s' + sucursales[i]["IdSucursal"] + '">' +
                                sucursales[i]["Nombre"] + '</option>');
                        }
                        if (tipo == 's') {
                            for (var i = 0; i < almacenes.length; i++) {
                                $('#destino').append('<option value="a' + almacenes[i]["IdAlmacen"] + '">' +
                                    almacenes[i]["Nombre"] + '</option>');
                            }
                        } else {
                            for (var i = 0; i < almacenes.length; i++) {
                                if (idAlmacen != almacenes[i]["IdAlmacen"]) {
                                    $('#destino').append('<option value="a' + almacenes[i]["IdAlmacen"] + '">' +
                                        almacenes[i]["Nombre"] + '</option>');
                                }
                            }
                        }
                        var table = $('#tabla2').DataTable();
                        table.rows().remove().draw();
                        $('#armarArray input').remove();
                    });

                    $("#destino").on('change', function() {
                        arrayProductos = [];
                        //var table = $('#tabla1').DataTable();
                        //table.rows().remove().draw();
                    });

                });

                function agregar(idArticulo, stock, codInterno) {
                    var almacen = $("#destino").val();
                    var tipo = almacen.substring(0, 1);
                    var idAlmacen = almacen.slice(1);

                    var almacenOrigen = $("#origen").val();
                    var tipoOrigen = almacenOrigen.substring(0, 1);
                    var idAlmacenOrigen = almacenOrigen.slice(1);

                    $.ajax({
                        type: 'get',
                        url: 'traspaso-producto',
                        data: {
                            'idAlmacen': idAlmacenOrigen,
                            'tipo': tipoOrigen,
                            'codInterno': codInterno
                        },
                        success: function(data) {
                            var unidadMed = data[0]["IdUnidadMedida"];
                            $("#idUnidadMedida").val(data[0]["IdUnidadMedida"]);
                            $("#inpDescripcion").val(escape(data[0]["Descripcion"]));
                            $("#inpMarca").val(data[0]["Marca"]);
                            $("#inpTipoMoneda").val(data[0]["IdTipoMoneda"]);
                            $("#inpIdArticulo").val(idArticulo);
                            $("#inpStock").attr('max', stock);
                            $("#inpStock").val(1);
                            $("#stockMax").val(stock);
                            $("#codInterno").val(codInterno);
                            if (unidadMed == 1) {
                                $("#inpStock").attr('min', 1);
                                $("#inpStock").attr('step', 1);
                            } else {
                                $("#inpStock").attr('min', 0.05);
                                $("#inpStock").attr('step', '0.05');
                            }
                            if (data.length > 0) {
                                $("#idAlerta").text("Se econtraron coincidencias");
                                $("#nombreProducto").text("");
                                $("#estado").val("1");
                                $('#selectCoincidencias').show();
                                $('#selectCoincidencias option').remove();
                                for (var i = 0; i < data.length; i++) {
                                    $('#selectCoincidencias').attr('size', data.length);
                                    $('#selectCoincidencias').append('<option value="' + data[i]["IdArticulo"] + '">' +
                                        data[i]["Descripcion"] + '</option>');
                                }
                            } else {
                                $('#selectCoincidencias').hide();
                                $("#estado").val("0");
                                $("#idAlerta").text("");
                                $("#nombreProducto").text(descripcion);
                            }
                        }
                    });
                }


                function calcular(row, id, stock) {
                    var cantidad = $("#inpCantidad" + id).val();
                    //var row = row.parentNode.parentNode;
                    //var cantidad = row.cells[7].getElementsByTagName('input')[0].value;
                    //alert(cantidad);
                    if (stock >= cantidad) {
                        $("#inp" + id).val(cantidad);
                    } else {
                        alert("la cantidad a traspasr excede al stock actual");
                        $("#inpCantidad" + id).val(stock);
                        $("#inp" + id).val(stock);
                    }
                }

                function remove(descripcion) {
                    var index = arrayProductos.indexOf(descripcion);
                    if (index > -1) {
                        arrayProductos.splice(index, 1);
                    }
                }

                function guardar() {
                    $('#guardarT').attr("disabled", true);
                    var table = $('#tabla2').DataTable();
                    var cantTrasp = $("input[name='CantidadTraspasar[]']").map(function() {
                        return $(this).val();
                    }).get();
                    var idsArticulo = $("input[name='IdArticulos[]']").map(function() {
                        return $(this).val();
                    }).get();
                    //var stocks = $("input[name='stock[]']").map(function(){return $(this).val();}).get();
                    //var descripciones = $("input[name='descripcion[]']").map(function(){return $(this).val();}).get();
                    //var codInterno = $("input[name='codigoInt[]']").map(function(){return $(this).val();}).get();
                    if (idsArticulo.length > 0) {
                        var origen = $("#origen").val();
                        var destino = $("#destino").val();
                        var tipoOrigen = origen.substring(0, 1);
                        var idAlmacenOrigen = origen.slice(1);
                        var tipoDestino = destino.substring(0, 1);
                        var idAlmacenDestino = destino.slice(1);


                        $.ajax({
                            type: 'post',
                            url: 'guardar-traspaso',
                            data: {
                                "_token": "{{ csrf_token() }}",
                                "idArticulo": idsArticulo,
                                "cantTrasp": cantTrasp,
                                "tipoOrigen": tipoOrigen,
                                "idAlmacenOrigen": idAlmacenOrigen,
                                "tipoDestino": tipoDestino,
                                "idAlmacenDestino": idAlmacenDestino,
                            },
                            success: function(data) {
                                if (data.respuesta === 'error') {
                                    return alert(data.mensaje);
                                }
                                $('#guardarT').attr("disabled", false);
                                alert(data);
                                window.location = 'realizar-traspaso';
                            }
                        });
                    } else {
                        alert("No se selecciono ningun producto a traspasar");
                    }
                }
            </script>

        @stop
