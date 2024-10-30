function agregarPaquetePromocional(id) {
    $.ajax({
        type: 'GET',
        url: 'obtener-items-paquete-promocional',
        data: {
            'idPaquete': id,
        },
        success: function (data) {
            let arrayItemsDuplicados = data.filter(function (item) {
                return arrayIds.includes(item.IdArticulo);
            });

            if (arrayItemsDuplicados.length >= 1) {
                alert("Los siguientes articulos agregados ya estan dentro del paquete, retirelos para agregar el paquete promocional: \n" +
                    arrayItemsDuplicados.map(element => element.NombreArticulo).toString()
                        .replace(
                            ",",
                            "\n"));
            } else {
                if (arrayIds.includes(id) == true) {
                    alert("Paquete promocional ya agregado");
                } else {

                    // agregar los item del paquete al array
                    data.forEach(element => {
                        arrayIdsItems.push({
                            IdArticulo: element.IdArticulo,
                            IdPaquetePromocional: id
                        })
                    });
                    // fin
                    var valorCambioVentas = $("#valorCambioVentas").val();
                    var valorCambioCompras = $("#valorCambioCompras").val();
                    var banderaVentaSolesDolares = $("#banderaVentaSolesDolares").val();
                    if ((banderaVentaSolesDolares == 1 || tipoMoneda == 2) && (parseFloat(
                        valorCambioVentas) == 0 ||
                        parseFloat(
                            valorCambioCompras) == 0)) {
                        $(".bs-modal-lg-productos-soles").modal("hide");
                        $(".bs-modal-lg-productos-dolares").modal("hide");
                        $("#tipoCambio").modal("show");
                    } else {
                        var nombre = $('#p1-' + id).text();
                        var precio = $('#p2-' + id).text();
                        var costo = $('#p3-' + id).val();
                        // var etiqueta = $('#p4-' + id).val();
                        var etiqueta = 'PaquetePromocional';
                        var idTipoMoneda = $('#p5-' + id).val();
                        var tipoVenta = $('#tipoVenta').val();
                        var unidadMedida = 'ZZ';
                        var descuento = 0;
                        var cantidad = 1;
                        // productoEnTabla($id, nombre, 'ZZ', precio, 1, 0, costo, 2, 1,
                        //     tipoVenta, idTipoMoneda, etiqueta)
                        // servicioEnTabla(id, descripcion, unidadMedida, precio, cantidad, descuento, costo, tipoVenta,
                        //     banderaVentaSolesDolares, valorCambioVentas, valorCambioCompras, idTipoMoneda, 'No');
                        servicioEnTabla(id, nombre, unidadMedida, precio, cantidad, descuento,
                            costo,
                            tipoVenta,
                            banderaVentaSolesDolares, valorCambioVentas, valorCambioCompras,
                            idTipoMoneda, etiqueta)
                    }
                }
            }
        }
    })
}

function verDetallePaquetePromocional($idPaquete) {
    $('#tableDetalle').find('#tableDetalleBody').remove();
    $('#tableDetalle').append('<tbody id="tableDetalleBody"></tbody>')
    $('#totalPaquete').val('');
    var tipoVenta = $('#tipoVenta').val();
    $.ajax({
        type: 'GET',
        url: 'obtener-items-paquete-promocional',
        data: {
            'idPaquete': $idPaquete,
        },
        success: function (data) {
            var total = 0;
            var importe = 0;
            for (var i = 0; i < data.length; i++) {
                if (data[i]['idTipoItems'] == 1) {
                    var codigo = "PRO"
                } else {
                    var codigo = "SER"
                }
                if (tipoVenta == 2) {
                    precio = redondeo(parseFloat(data[i]['Precio'] / 1.18));
                } else {
                    precio = data[i]['Precio'];
                }
                var fila = '<tr>' +
                    '<td>' + codigo + '-' + data[i]['IdArticulo'] + '</td>' +
                    '<td>' + data[i]['NombreArticulo'] + '</td>' +
                    '<td>' + precio + '</td>' +
                    '<td>' + data[i]['cantidad'] + '</td>' +
                    '</tr>';
                importe = parseFloat(precio) * data[i]['cantidad'];
                total += importe;
                // $('#tableDetalle tr:last').after(fila);
                // $('#tableDetalleBody').find('tbody').append( fila );
                $('#tableDetalleBody').append(fila);
            }
            $('#totalPaquete').val(redondeo(total));
        }
    })
    $('.detallePaquetePromocional').modal('show');
}



