const cargarProductosEnVista = (data, precioExo, moneda) => {
    $('#listaProductos').empty();
    for (var i = 0; i < data["data"].length; i++) {
        if (data["data"][i]["Codigo"] != null) {
            var codigo = '<span class="text-success font-weight-bold fs-14">Codigo Barra: ' +
                data["data"][i]["Codigo"] + '</span>'
        } else {
            var codigo = "";
        }

        if (sucExonerado == 1) {
            precioExo = '<div class="col-6">' +
                '<label class="col-form-label-sm">Precio Venta</label>' +
                '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                ["Precio"] + '" class="form-control text-success text-center" readonly />' +
                '</div>' +
                '<div class="col-6">' +
                '<label class="col-form-label-sm">Precio Costo</label>' +
                '<input value="' + redondeo(parseFloat(data["data"][i]["Precio"]) / 1.18) +
                '" class="form-control text-danger text-center" readonly />' +
                '</div>';

        } else {
            precioExo = '<div class="col-12">' +
                '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
                ["Precio"] + '" class="form-control text-success text-center" readonly />' +
                '</div>';
        }
        $('#listaProductos').append('<div class="product col-12 col-md-6">' +
            '<div class="card">' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-12">' +
            '<span id="p1-' + data["data"][i]["IdArticulo"] +
            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
            "Descripcion"
            ] + '</span>' +
            '</div>' +
            '<div class="col-12 d-flex">' +
            '<span class="fs-22" style="line-height: 2;">' + moneda + '</span>' +
            '<input id="p2-' + data["data"][i]["IdArticulo"] +
            '" class="form-control product-price fs-16" value="' + data["data"][i][
            "Costo"
            ] + '" type="number" step="any"/>' +
            '</div>' +
            '<div class="col-12">' +
            '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
            '</div>' +
            '</div>' +
            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data[
            "data"][i]["UM"] + '"/>' +
            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
            '" value="' +
            data["data"][i]["IdUnidadMedida"] + '"/>' +
            '<div class="form-group col-12" hidden>' +
            '<label class="col-form-label-sm">Cantidad</label>' +
            '<input id="p4-' + data["data"][i]["IdArticulo"] +
            '" type="number" min="1" value="1" class="form-control text-center" />' +
            '</div>' +
            '<div class="form-group col-12">' +
            // '<label class="col-form-label-sm">Precio Venta</label>' +
            '<div class="row d-flex">' +
            precioExo +
            '<div class="col-12 mt-2">' +
            codigo +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info col-12">' +
            '<a class="bg-info color-white" onclick="agregarProducto(' + data["data"][i][
            "IdArticulo"
            ] + ')" href="javascript:void(0);">' +
            '<i class="list-icon material-icons">add</i>Agregar' +
            '</a>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '</div>');
    }

    $('#paginasProductos').empty();
    var primero = '';
    var ultimo = '';
    var anterior = '';
    var paginas = '';
    var siguiente = '';
    if (data["prev_page_url"] !== null) {
        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] +
            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] +
            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
    } else {
        primero =
            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior =
            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
    }

    if (data["current_page"] < 3) {
        for (var i = 1; i <= 5 + 2; i++) {
            if (i > 0 && i <= data["last_page"]) {
                if (i == data["current_page"]) {
                    paginas +=
                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                        i + '</a></li>';
                } else {
                    paginas +=
                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                        '">' + i + '</a></li>';
                }
            }
        }
    } else {
        if (data["last_page"] > 2) {
            if (data["current_page"] > data["last_page"] - 2) {
                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                    if (i > 0 && i <= data["last_page"]) {
                        if (i == data["current_page"]) {
                            paginas +=
                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                i + '</a></li>';
                        } else {
                            paginas +=
                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                i + '">' + i + '</a></li>';
                        }
                    }
                }
            }
        }
    }

    if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
        for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
            if (i > 0 && i <= data["last_page"]) {
                if (i == data["current_page"]) {
                    paginas +=
                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                        i + '</a></li>';
                } else {
                    paginas +=
                        '<li class="page-item"><a class="page-link" href="productos?page=' + i +
                        '">' + i + '</a></li>';
                }
            }
        }
    }

    if (data["next_page_url"] !== null) {
        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (data[
            "current_page"] + 1) +
            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' + data[
            "last_page"] +
            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    } else {
        siguiente =
            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo =
            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    }

    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
    $('#paginasProductos').append(concatenacion);
}