// Variable para desabilitar boton de agregarArticulo si el stock es igual a 0
let desabilitado = '';
// -------------------
const showIconoCandado = (codigo) => {
    realizarAccionShow(codigo);
}
const hideIconoCandado = (codigo) => {
    realizarAccionHide(codigo);
}


const showAllIconoCandado = (codigo) => {
    if (codigo.includes('GRUPO')) {
        const grupoEncontrado = arrayGrupos.includes(codigo);
        if (grupoEncontrado) {
            realizarAccionShow(codigo);
        }
    } else {
        const articuloEncontrado = cotizacion.articulos.some(function (articulo) {
            return articulo.Codigo === codigo;
        });
        if (articuloEncontrado) {
            realizarAccionShow(codigo);
        }
        // SE BLOQUEA LOS ARTICULOS QUE YA ESTAN DENTRO DE LOS PAQUETES
        if (arrayArticulosPaquetePromo.length >= 1) {
            arrayArticulosPaquetePromo.forEach((articulo) => {
                realizarAccionShow(articulo.Codigo);
            })
        }
    }
}

const hideAllIconoCandado = () => {
    realizarAccionHide(null, true);
}

const realizarAccionShow = (codigo) => {
    $(`#iconoCandado${codigo}`).removeClass('d-none');
    $(`#iconoAgregar${codigo}`).addClass('d-none');
    $(`#botonAgregarArticulo${codigo}`).prop('disabled', true);
    $(`#botonAgregarArticulo${codigo}`).addClass('botonAgregarArticuloDisabled');
    $(`#iconoCandado${codigo}`).addClass('iconoCandadoVisible');
    $(`#iconoAgregar${codigo}`).addClass('iconoAgregarOculto');
}


const realizarAccionHide = (codigo, hideAll = false) => {
    if (hideAll) {
        $(`.iconoCandadoVisible`).addClass('d-none');
        $(`.iconoAgregarOculto`).removeClass('d-none');
        $(`.botonAgregarArticuloDisabled`).prop('disabled', false);
        // REMOVER LAS CLASES
        $(`.iconoCandadoVisible`).removeClass('iconoCandadoVisible');
        $(`.iconoAgregarOculto`).removeClass('iconoAgregarOculto');
        $(`.botonAgregarArticuloDisabled`).removeClass('botonAgregarArticuloDisabled');
    } else {
        $(`#iconoCandado${codigo}`).addClass('d-none');
        $(`#iconoAgregar${codigo}`).removeClass('d-none');
        $(`#botonAgregarArticulo${codigo}`).prop('disabled', false);
        // REMOVER LAS CLASES
        removeClass(codigo);
        // SE DESBLOQUEA LOS ARTICULOS QUE ESTAN DENTRO DE LOS PAQUETES
        if (codigo.includes('PAQ')) {
            // Se obtiene solo en numero de la cadena Id
            const idPaquete = parseInt(codigo.substring(4));
            const itemsPaquetePromo = arrayArticulosPaquetePromo.filter(item => item.IdPaquetePromocional === idPaquete);
            itemsPaquetePromo.forEach((articulo) => {
                $(`#iconoCandado${articulo.Codigo}`).addClass('d-none');
                $(`#iconoAgregar${articulo.Codigo}`).removeClass('d-none');
                $(`#botonAgregarArticulo${articulo.Codigo}`).prop('disabled', false);
                // REMOVER LAS CLASES
                removeClass(articulo.Codigo);
            })
        }
    }
}

const removeClass = (codigo) => {
    $(`#iconoCandado${codigo}`).removeClass('iconoCandadoVisible');
    $(`#iconoAgregar${codigo}`).removeClass('iconoAgregarOculto');
    $(`#iconoAgregar${codigo}`).removeClass('botonAgregarArticuloDisabled');
}

const cargarProductosEnModal = (data, seccionArticulos, seccionPaginador, moneda) => {
    let precioExo = '';
    $('' + seccionArticulos + '').empty();

    for (var i = 0; i < data["data"].length; i++) {
        var codigo = '';
        if (data["data"][i]["Codigo"] != null) {
            codigo = data["data"][i]["Codigo"];
        }
        if (data["data"][i]["Stock"] < 1) {
            stock = `<button type="button" ${desabilitado} data-id="${data["data"][i]["IdArticulo"]}" id="botonAgregarArticuloPRO-${data["data"][i]["IdArticulo"]}" class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarProducto"><span id="iconoAgregarPRO-${data["data"][i]["IdArticulo"]}"><i class="list-icon material-icons">add</i>Agregar Producto (Agotado)</span><span id="iconoCandadoPRO-${data["data"][i]["IdArticulo"]}" class="d-none"><i class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>`
        } else {
            stock = `<button type="button" data-id="${data["data"][i]["IdArticulo"]}" id="botonAgregarArticuloPRO-${data["data"][i]["IdArticulo"]}" class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarProducto"><span id="iconoAgregarPRO-${data["data"][i]["IdArticulo"]}"><i class="list-icon material-icons">add</i>Agregar Producto</span><span id="iconoCandadoPRO-${data["data"][i]["IdArticulo"]}" class="d-none"><i class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>`
        }
        if (data["data"][i]["Stock"] < 1) {
            canti = '<input id="p4-' + data["data"][i]["IdArticulo"] +
                '" type="number" min="0" value="0" class=" text-center" />';
        } else {
            canti = '<input id="p4-' + data["data"][i]["IdArticulo"] +
                '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] +
                '" class=" text-center" />';
        }
        if (sucExonerado == 1) {
            precioExo = '<div class="col-6">' + moneda +
                '<span id="p2-' + data["data"][i]["IdArticulo"] +
                '" class="text-danger product-price fs-16">' + redondeo(parseFloat(data[
                    "data"][
                    i
                ]["Precio"]) / 1.18) + '</span>' +
                '</div>';
        }
        $('' + seccionArticulos + '').append('<div class="product col-12 col-md-6">' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-6">' + moneda +
            '<span id="p2-' + data["data"][i]["IdArticulo"] +
            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
            '</div>' +
            precioExo +
            '<div class="col-12">' +
            '<span id="p1-' + data["data"][i]["IdArticulo"] +
            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
            "Descripcion"
            ] + '</span>' +
            '</div>' +
            '<div class="col-12">' +
            '<span class="text-success fs-12">' + codigo + '</span>' +
            '</div>' +
            '<div class="col-12">' +
            '<span class="text-muted">' + data["data"][i]["Marca"] + '/' + data["data"][
            i
            ][
            "Categoria"
            ] + '/' + '</span>' + '<span class="text-danger fs-13">Stock :' + data[
            "data"][
            i
            ]["Stock"] + '</span>' +
            '</div>' +
            '</div>' +
            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' +
            data[
            "data"][i]["UM"] + '"/>' +
            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] +
            '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
            '<div class="form-group mt-2" hidden>' +
            '<label class="col-form-label-sm">Cantidad </label>' +
            canti +
            '</div>' +
            '<div class="form-group" hidden>' +
            '<label class="col-form-label-sm">Descuento </label>' +
            '<input id="p5-' + data["data"][i]["IdArticulo"] +
            '" value="0.0" class="text-center" />' +
            '</div>' +
            '<div hidden>' +
            '<div class="form-group col-12">' +
            '<label class="col-form-label-sm">Costo</label>' +
            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data[
            "data"][i]
            ["Costo"] + '" class="form-control text-center" />' +
            '</div>' +
            '</div>' +
            '<div hidden>' +
            '<div class="form-group col-12">' +
            '<label class="col-form-label-sm">Stock </label>' +
            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data[
            "data"][i]
            ["Stock"] + '" class="form-control text-center"/>' +
            '</div>' +
            '</div>' +
            '<div hidden>' +
            '<input id="p8-' + data["data"][i]["IdArticulo"] + '" value="' + data[
            "data"][i]
            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info col-12">' + stock +
            '</div>' +
            '</div>' +
            '</div>');
        // =====================================
        showAllIconoCandado(`PRO-${data["data"][i]["IdArticulo"]}`);
        // =====================================
    }
    $('' + seccionPaginador + '').empty();
    var primero = '';
    var ultimo = '';
    var anterior = '';
    var paginas = '';
    var siguiente = '';
    if (data["prev_page_url"] !== null) {
        primero = '<li class="page-item"><a class="page-link" href="' + data[
            "first_page_url"] +
            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior = '<li class="page-item"><a class="page-link" href="' + data[
            "prev_page_url"] +
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
                        '<li class="page-item"><a class="page-link" href="productos?page=' +
                        i +
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
                        '<li class="page-item"><a class="page-link" href="productos?page=' +
                        i +
                        '">' + i + '</a></li>';
                }
            }
        }
    }
    if (data["next_page_url"] !== null) {
        siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (
            data[
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
    $('' + seccionPaginador + '').append(concatenacion);
}

const cargarServiciosEnModal = (data, seccionArticulos, seccionPaginador, moneda) => {
    let precioExoDol = '';
    let codigo = '';
    $('' + seccionArticulos + '').empty();
    for (var i = 0; i < data["data"].length; i++) {
        const boton = `<button type="button"  id="botonAgregarArticuloSER-${data["data"][i]["IdArticulo"]}" data-id="${data["data"][i]["IdArticulo"]}" class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarServicio"><span id="iconoAgregarSER-${data["data"][i]["IdArticulo"]}"><i class="list-icon material-icons">add</i>Agregar Producto</span><span id="iconoCandadoSER-${data["data"][i]["IdArticulo"]}" class="d-none"><i class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>`
        if (data["data"][i]["Codigo"] != null) {
            codigo = data["data"][i]["Codigo"];
        }
        if (sucExonerado == 1) {
            precioExoDol = '<div class="col-6">' + moneda +
                '<span  class="text-danger product-price fs-16">' + redondeo(parseFloat(data[
                    "data"][i]["Precio"]) / 1.18) + '</span>' +
                '</div>';
        }
        $('' + seccionArticulos + '').append('<div class="product col-12 col-md-6">' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-6">' + moneda +
            '<span id="s2-' + data["data"][i]["IdArticulo"] +
            '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
            '</div>' +
            precioExoDol +
            '<div class="col-12">' +
            '<span id="s1-' + data["data"][i]["IdArticulo"] +
            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
            "Descripcion"
            ] + '</span>' +
            '</div>' +
            '<div class="col-12">' +
            '<span class="text-success fs-12">' + codigo + '</span>' +
            '</div>' +
            '</div>' +
            '<div class="form-group mt-2" hidden>' +
            '<label class="col-form-label-sm">Cantidad </label>' +
            '<input id="s5-' + data["data"][i]["IdArticulo"] +
            '" type="number" min="1" value="1" class="text-center" />' +
            '</div>' +
            '<div class="form-group" hidden>' +
            '<label class="col-form-label-sm">Descuento </label>' +
            '<input id="s3-' + data["data"][i]["IdArticulo"] +
            '" value="0.0" class="text-center" />' +
            '</div>' +
            '<div hidden>' +
            '<input id="s7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]
            ["IdTipoMoneda"] + '" class="form-control text-center"/>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info col-12">' + boton +
            '</div>' +
            '</div>' +
            '</div>');
        // =====================================
        showAllIconoCandado(`SER-${data["data"][i]["IdArticulo"]}`);
        // =====================================
    }

    $('' + seccionPaginador + '').empty();
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
        anterior =
            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a>';
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
                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
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
                                '<li class="page-item"><a class="page-link" href="servicios?page=' +
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
                        '<li class="page-item"><a class="page-link" href="servicios?page=' + i +
                        '">' + i + '</a></li>';
                }
            }
        }
    }

    if (data["next_page_url"] !== null) {
        siguiente = '<li class="page-item"><a class="page-link" href="servicios?page=' + (data[
            "current_page"] + 1) +
            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link" href="servicios?page=' + data[
            "last_page"] +
            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    } else {
        siguiente =
            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo =
            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    }

    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
    $('' + seccionPaginador + '').append(concatenacion);
}

const cargarGruposEnModal = (data, cardXGrupo, paginasGrupos) => {
    $('' + cardXGrupo + '').empty();
    for (var i = 0; i < data["data"].length; i++) {
        boton = `<button type="button" data-id="${data["data"][i]["IdGrupo"]}" id="botonAgregarArticuloGRUPO-${data["data"][i]["IdGrupo"]}" class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarGrupo"><span id="iconoAgregarGRUPO-${data["data"][i]["IdGrupo"]}"><i class="list-icon material-icons">add</i>Agregar</span><span id="iconoCandadoGRUPO-${data["data"][i]["IdGrupo"]}" class="d-none"><i class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>`

        $('' + cardXGrupo + '').append('<div class="product col-12 col-md-6">' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-12">' +
            '<span id="p1-' + data["data"][i]["IdGrupo"] +
            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
            "NombreGrupo"
            ] + '</span>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info col-12">' + boton +
            '</div>' +
            '</div>' +
            '</div>');
        showAllIconoCandado(`GRUPO-${data["data"][i]["IdGrupo"]}`);
    }
    $('' + paginasGrupos + '').empty();
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
    $('' + paginasGrupos + '').append(concatenacion);
}

const cargarPaquetesPromoEnModal = (data, cardPaquetePormocional, paginasPaquetesPromocional, moneda) => {
    $('' + cardPaquetePormocional + '').empty();
    for (var i = 0; i < data["data"].length; i++) {
        if (sucExonerado == 1) {
            precioExonerado = `<div class="col-6">${moneda}<span class="text-danger product-price fs-16">${parseFloat(data["data"][i]["Total"] / 1.18).toFixed(2)}</span></div>`;
        }

        boton = `<button type="button" data-id="${data["data"][i]["IdPaquetePromocional"]}" id="botonAgregarArticuloPAQ-${data["data"][i]["IdPaquetePromocional"]}" class="btn btn-info btn-sm border-0 btn-block fs-12 py-2 rounded-0 botonAgregarPaquetePromo"><span id="iconoAgregarPAQ-${data["data"][i]["IdPaquetePromocional"]}"><i class="list-icon material-icons">add</i>Agregar</span><span id="iconoCandadoPAQ-${data["data"][i]["IdPaquetePromocional"]}" class="d-none"><i class='bx bxs-lock-alt fs-18 mr-1'></i>Agregado</span></button>`

        $('' + cardPaquetePormocional + '').append('<div class="product col-12 col-md-6">' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-12">' +
            '<span id="nombre-' + data["data"][i]["IdPaquetePromocional"] +
            '" class="product-title font-weight-bold fs-16">' + data["data"][i][
            "NombrePaquete"
            ] + '</span>' +
            '</div>' +
            `<div class="col-12">
            <div class="col-6">${moneda}<span id="precio-${data["data"][i]["IdPaquetePromocional"]}" class="product-price fs-16">${parseFloat(data["data"][i]["Total"]).toFixed(2)}</span> </div>
            ${precioExonerado}
            </div>
            <div class="col-12" hidden>
            <input id="costo-${data["data"][i]["IdPaquetePromocional"]}" value="${data["data"][i]["Costo"]}" class="form-control text-center" />
            <input id="etiqueta-${data["data"][i]["IdPaquetePromocional"]}" value="${data["data"][i]["Etiqueta"]}" class="form-control text-center" />
            <input id="idTipoMoneda-${data["data"][i]["IdPaquetePromocional"]}" value="${data["data"][i]["IdTipoMoneda"]}"                               class="form-control text-center" />
            </div>` +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info col-12">' + boton +
            '</div>' +
            '</div>' +
            '</div>');
        // =====================================
        showAllIconoCandado(`PAQ-${data["data"][i]["IdPaquetePromocional"]}`);
        // =====================================
    }
    $('' + paginasPaquetesPromocional + '').empty();
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
    $('' + paginasPaquetesPromocional + '').append(concatenacion);
}