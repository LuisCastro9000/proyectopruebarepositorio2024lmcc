var iden = 1;
var array = [];
var arrayIds = [];
$(function () {
    $("#selectMotivo").on('change', function () {
        var opcionMotivo = $("#selectMotivo").val();
        if (opcionMotivo == 4) {
            $('#otros').removeAttr('disabled');
        } else {
            $('#otros').attr('disabled', 'disabled');
        }
    });
});

function agregarProducto(id) {
    if (arrayIds.includes(id) == true) {
        alert("Producto ya agregado, por favor de modificar la cantidad si desea agregar más");
    } else {
        var descripcion = $('#p1-' + id).text();
        var marca = $('#p9-' + id).text();
        var codigoBarra = $('#p10-' + id).text();
        var unidadMedida = $('#p3-' + id).val();
        var precio = $('#p2-' + id).text();
        var cantidad = $('#p4-' + id).val();
        var descuento = $('#p5-' + id).val();
        var costo = $('#p6-' + id).val();
        var stock = $('#p7-' + id).val();
        var tipoMoneda = $('#p8-' + id).val();
        var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
        productoEnTabla(id, descripcion, marca, codigoBarra, unidadMedida, idUnidadMedida, precio, cantidad, descuento, costo, stock, tipoMoneda);
    }
}

function productoEnTabla(id, descripcion, marca, codigoBarra, unidadMedida, idUnidadMedida, precio, cantidad, descuento, costo, stock, tipoMoneda) {

    if (parseInt(cantidad, 10) > parseInt(stock, 10)) {
        alert("Sobrepaso el límite de este artículo en stock");
    } else {
        var moneda;
        if (tipoMoneda == 1) {
            moneda = 'Soles';
        } else {
            moneda = 'Dólares';
        }
        if (idUnidadMedida == 1) {
            step = '';
            bandInput = 'true';
        } else {
            step = '0.05';
            bandInput = 'true';
        }
        var importe = parseFloat(parseFloat(precio) * parseFloat(cantidad, 10));
        var importeFinal = parseFloat(importe) - parseFloat(descuento);
        var ganancia = parseFloat(importe) - parseFloat(parseFloat(costo) * parseFloat(cantidad, 10)) - parseFloat(descuento);
        var t = $('#tablaAgregado');
        var fila = '<tr id="row' + iden + '">' +
            '</td><td id="descrip' + iden + '" name="Descripcion[]">' + descripcion +
            '</td><td id="marca' + iden + '">' + marca +
            '</td><td id="codigoBarra' + iden + '" name="CodigoBarra[]">' + codigoBarra +
            '</td><td id="um' + iden + '">' + unidadMedida +
            '</td><td id="tipoMoneda' + iden + '">' + moneda +
            '</td><td id="stock' + iden + '">' + stock +
            '</td><td><input id="cant' + iden + '" name="Cantidad[]" onkeydown="return ' + bandInput + ';" step="' + step + '" onchange="calcular(this, ' + iden + ');" type="number" min="1" max="' + stock + '" value="' + cantidad + '" style="width:100px">' +
            '</td><td><button id="btn' + iden + '" onclick="quitar(' + iden + ',' + id + ')" class="btn btn-primary"><i class="list-icon material-icons fs-16">clear</i></button>' +
            '</td>' +
            '</tr>';
        $('#tablaAgregado tr:last').after(fila);

        var valores = new Array();
        valores.Id = id;
        valores.Row = iden;
        valores.Cantidad = parseFloat(cantidad, 10);
        valores.Stock = parseFloat(stock, 10);
        agregarArray(valores);
        iden++;
        arrayIds.push(id);
    }

}

function quitar(id, i) {
    var stock = $('#p7-' + i).val();
    var cantidad = $('#cant' + id).val();

    var valores = new Array();
    valores.Id = i;
    valores.Row = id;
    valores.Cantidad = parseFloat(cantidad, 10);
    valores.Stock = parseFloat(stock, 10);
    quitarArray(valores);


    $('#row' + id).remove();
    $('#' + id).remove();

    var index = arrayIds.indexOf(i);
    if (index > -1) {
        arrayIds.splice(index, 1);
    }
}

function agregarArray(datos) {
    var newArray = [];
    if (array.length > 0) {
        array.push(datos);
        $('#armarArray').append('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
            '<input id="ganancia' + datos.Row + '" name="Ganancia[]" value="' + datos.Ganancia + '"></input>' +
            '<input id="stock' + datos.Row + '" name="Stock[]" value="' + datos.Stock + '"></input>' +
            '</div>');
    } else {
        array.push(datos);
        $('#armarArray').append('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
            '<input id="ganancia' + datos.Row + '" name="Ganancia[]" value="' + datos.Ganancia + '"></input>' +
            '<input id="stock' + datos.Row + '" name="Stock[]" value="' + datos.Stock + '"></input>' +
            '</div>');
    }
}

function quitarArray(datos) {
    if (array.length > 0) {
        for (var i = 0; i < array.length; i++) {
            if (datos.Id == array[i]["Id"]) {
                $('#' + datos.Row).replaceWith('<div id="' + datos.Row + '"><input name="Id[]" value="' + datos.Id + '"></input>' +
                    '</div>');
            }
            if (array[i]["Cantidad"] == 0) {
                $('#' + datos.Row).replaceWith('<div id="' + datos.Row + '">');
            }
        }
    }
}

$.fn.delayPasteKeyUp = function (fn, ms) {
    var timer = 0;
    $(this).on("propertychange input", function () {
        clearTimeout(timer);
        timer = setTimeout(fn, ms);
    });
};


$("#inputBuscarCodigoProductos").delayPasteKeyUp(function () {
    var codigo = $("#inputBuscarCodigoProductos").val();
    if (codigo != '') {
        $.ajax({
            type: 'get',
            url: '../search-codigo-producto',
            data: { 'codigoBusqueda': codigo },
            success: function (data) {
                if (data.length > 0) {
                    var id = data[0]["IdArticulo"];
                    var descripcion = data[0]["Descripcion"];
                    var unidadMedida = data[0]["UM"];
                    var idUnidMedida = data[0]["IdUnidadMedida"];
                    var precio = data[0]["Precio"];
                    var cantidad = '1';
                    var descuento = '0.0';
                    var costo = data[0]["Costo"];
                    var stock = data[0]["Stock"];
                    var tipoMoneda = data[0]["IdTipoMoneda"];
                    if (arrayIds.includes(id) == true) {
                        alert("Producto ya agregado, por favor de modificar la cantidad si desea agregar más");
                    } else {
                        if (stock > 0) {
                            productoEnTabla(id, descripcion, unidadMedida, idUnidMedida, precio, cantidad, descuento, costo, stock, tipoMoneda);
                        } else {
                            alert("Producto sin stock");
                        }
                        $("#inputBuscarCodigoProductos").val("");
                    }
                } else {
                    $("#inputBuscarCodigoProductos").val("");
                    alert("No se encontro producto");
                }
            }
        });
    }
}, 500);

$("input[name=radioOption]").change(function () {
    var textoBusqueda = "";
    if ($(this).val() == 1) {
        listarProductos(textoBusqueda, 1);
    } else {
        listarProductos(textoBusqueda, 2);
    }
});

$("#inputBuscarProductos").keyup(function () {
    var textoBusqueda = $("#inputBuscarProductos").val();
    if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
        var tipoMoneda;
        if ($('#radio1').is(':checked')) {
            tipoMoneda = 1;
        } else {
            tipoMoneda = 2;
        }
        listarProductos(textoBusqueda, tipoMoneda);
    }
});

function listarProductos(textoBusqueda, tipoMoneda) {
    $.ajax({
        type: 'get',
        url: '../search-productos',
        data: {
            'textoBuscar': textoBusqueda,
            'tipoMoneda': tipoMoneda
        },
        success: function (data) {
            cargarProductos(data, tipoMoneda);
        }
    });
}

$(document).on('click', '.pagProd a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    getProductos(page);
});

function getProductos(page) {
    var textoBusqueda = $('#inputBuscarProductos').val();
    var tipoMoneda;
    if ($('#radio1').is(':checked')) {
        tipoMoneda = 1;
    } else {
        tipoMoneda = 2;
    }
    $.ajax({
        type: 'get',
        url: '../productos-baja?page=' + page,
        data: {
            'textoBuscar': textoBusqueda,
            'tipoMoneda': tipoMoneda
        },
        success: function (data) {
            cargarProductos(data, tipoMoneda);
        }
    });
}

function cargarProductos(data, tipoMoneda) {
    $('#listaProductos').empty();
    var moneda;
    if (tipoMoneda == 1) {
        moneda = 'S/';
    } else {
        moneda = '$';
    }
    for (var i = 0; i < data["data"].length; i++) {
        if (data["data"][i]["Stock"] < 1) {
            stock = '<a class="bg-info color-white fs-12 disabled" href="javascript:void(0);">Agotado</a>';
        } else {
            stock = '<a class="bg-info color-white fs-12" onclick="agregarProducto(' + data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);">' +
                '<i class="list-icon material-icons">add</i>Agregar' +
                '</a>';
        }
        $('#listaProductos').append('<div class="product col-12 col-md-6">' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-12">' + moneda +
            '<span id="p2-' + data["data"][i]["IdArticulo"] + '" class="product-price fs-16">' + data["data"][i]["Precio"] + '</span>' +
            '</div>' +
            '<div class="col-12">' +
            '<span id="p1-' + data["data"][i]["IdArticulo"] + '" class="product-title font-weight-bold fs-16">' + data["data"][i]["Descripcion"] + '</span>' +
            '</div>' +
            '<div class="col-12 d-flex justify-content-between">' +
            '<section><span class="text-muted">' + data["data"][i]["Marca"] + '</span> / <span class="text-muted">' + data["data"][i]["Categoria"] + '</span></section>' + '<section><span class="badge-autoncontrol badge-autoncontrol__danger">Stock: ' + data["data"][i]["Stock"] + '</span></section>' +
            // '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
            '</div>' +
            '</div>' +
            '<input hidden id="p3-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]["UM"] + '"/>' +
            '<input hidden id="IdUnidadMedida-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]["IdUnidadMedida"] + '"/>' +
            '<div class="form-group mt-2" hidden>' +
            '<label class="col-form-label-sm">Cantidad </label>' +
            '<input id="p4-' + data["data"][i]["IdArticulo"] + '" type="number" min="1" value="1" max="' + data["data"][i]["Stock"] + '" class="text-center" />' +
            '</div>' +
            '<div class="form-group" hidden>' +
            '<label class="col-form-label-sm">Descuento </label>' +
            '<input id="p5-' + data["data"][i]["IdArticulo"] + '" value="0.0" class="text-center" />' +
            '</div>' +
            '<div hidden>' +
            '<div class="form-group col-12">' +
            '<label class="col-form-label-sm">Costo</label>' +
            '<input id="p6-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]["Costo"] + '" class="form-control text-center" />' +
            '</div>' +
            '</div>' +
            '<div hidden>' +
            '<div class="form-group col-12">' +
            '<label class="col-form-label-sm">Stock</label>' +
            '<input id="p7-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]["Stock"] + '" class="form-control text-center"/>' +
            '</div>' +
            '</div>' +
            '<div hidden>' +
            '<div class="form-group col-12">' +
            '<label class="col-form-label-sm">Tipo Moneda </label>' +
            '<input id="p8-' + data["data"][i]["IdArticulo"] + '" value="' + data["data"][i]["IdTipoMoneda"] + '" class="form-control text-center"/>' +
            '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info col-12">' + stock +
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
        primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] + '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] + '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
    } else {
        primero = '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior = '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
    }


    if (data["current_page"] < 3) {
        for (var i = 1; i <= 5 + 2; i++) {
            if (i > 0 && i <= data["last_page"]) {
                if (i == data["current_page"]) {
                    paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
                } else {
                    paginas += '<li class="page-item"><a class="page-link" href="productos-baja?page=' + i + '">' + i + '</a></li>';
                }
            }
        }
    } else {
        if (data["last_page"] > 2) {
            if (data["current_page"] > data["last_page"] - 2) {
                for (var i = data["current_page"] - 4; i <= data["last_page"]; i++) {
                    if (i > 0 && i <= data["last_page"]) {
                        if (i == data["current_page"]) {
                            paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
                        } else {
                            paginas += '<li class="page-item"><a class="page-link" href="productos-baja?page=' + i + '">' + i + '</a></li>';
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
                    paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
                } else {
                    paginas += '<li class="page-item"><a class="page-link" href="productos-baja?page=' + i + '">' + i + '</a></li>';
                }
            }
        }
    }


    if (data["next_page_url"] !== null) {
        siguiente = '<li class="page-item"><a class="page-link" href="productos-baja?page=' + (data["current_page"] + 1) + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link" href="productos-baja?page=' + data["last_page"] + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    } else {
        siguiente = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    }

    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
    $('#paginasProductos').append(concatenacion);
}

$('#inputBuscarCodigoProductos').hide();
$('#lector').on('click', function () {
    if ($(this).is(':checked')) {
        $('#inputBuscarProductos').hide();
        $('#inputBuscarCodigoProductos').show();
    } else {
        $('#inputBuscarProductos').show();
        $('#inputBuscarCodigoProductos').hide();
    }
});