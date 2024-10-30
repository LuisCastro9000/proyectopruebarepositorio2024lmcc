///// Servicios ////
$("#inputBuscar").keyup(function () {
    var textoBusqueda = $("#inputBuscar").val();
    var tipoMoneda;
    if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
        if ($('#radio1').is(':checked')) {
            tipoMoneda = 1;
        } else {
            tipoMoneda = 2;
        }
        listarServicios(textoBusqueda, tipoMoneda);
    }
});

$("input[name=radioOption]").change(function () {
    var textoBusqueda = "";
    if ($(this).val() == 1) {
        listarServicios(textoBusqueda, 1);
    } else {
        listarServicios(textoBusqueda, 2);
    }
});

function listarServicios(textoBusqueda, tipoMoneda) {
    var moneda;
    if (tipoMoneda == 1) {
        moneda = 'S/';
    } else {
        moneda = '$';
    }
    $.ajax({
        type: 'get',
        // url: 'buscar-servicios',
        url: rutaBuscarServicios,
        data: {
            'textoBuscar': textoBusqueda,
            'tipoMoneda': tipoMoneda
        },
        success: function (data) {
            // console.log(data);
            cargarDataServicios(data, moneda)
        }
    });
}

$(document).on('click', '.generar', function () {
    var codigo = $("#codBarra").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
});

$(document).on('click', '.pagServ a', function (e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    getServicios(page);
});

function getServicios(page) {
    var textoBusqueda = $('#inputBuscar').val();
    var tipoMoneda;
    var moneda;
    if ($('#radio1').is(':checked')) {
        tipoMoneda = 1;
        moneda = 'S/';
    } else {
        tipoMoneda = 2;
        moneda = '$';
    }
    $.ajax({
        type: 'get',
        // url: 'servicios-almacen?page=' + page,
        url: `${rutaPaginarServicios}?page=${page}`,
        data: {
            'textoBuscar': textoBusqueda,
            'tipoMoneda': tipoMoneda
        },
        success: function (data) {
            console.log(data[0]);
            cargarDataServicios(data, moneda);
        }
    });
}

function cargarDataServicios(data, moneda) {
    $('#listaServicios').empty();
    for (var i = 0; i < data["data"].length; i++) {
        let imagen;
        if (data["data"][i]["Imagen"].includes(urlDominioAmazonS3) || data["data"][i]["Imagen"].includes(UrlImagenNotFound)) {
            imagen = data["data"][i]["Imagen"];
        } else if (data["data"][i]["Imagen"].includes(UrlDominioAmazonS3Antiguo)) {
            imagen = UrlImagenNotFound;
        } else {
            imagen = urlDominioAmazonS3 + data["data"][i]["Imagen"];
        }
        $('#listaServicios').append('<div class="product col-12 col-sm-6 col-md-4 col-lg-3">' +
            '<div class="card">' +
            '<div class="card-header">' +
            '<a href="servicios/' + data["data"][i]["IdArticulo"] + '/edit">' +
            '<img class="w-100" src="' + imagen + '" alt="">' +
            '</a>' +
            '</div>' +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-12">' +
            '<h5 class="product-title fs-18">' + data["data"][i]["Descripcion"] + '</h5>' +
            '</div>' +
            '<div class="col-6 text-center">' +
            '<span class="product-price fs-18">' + moneda + ' ' + data["data"][i]["Precio"] + '</span><span class="badge badge-warning">Con IGV</span>' +
            '</div>' +
            '<div class="col-6 text-center">' +
            '<span class="product-price text-danger fs-18">' + moneda + ' ' + ((data["data"][i]["Precio"]) / 1.18).toFixed(2) + '</span><span class="badge badge-warning">Sin IGV</span>' + '</div>' +
            '</div>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info"><a href="servicios/' + data["data"][i]["IdArticulo"] + '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
            // '<div class="product-info"><a onclick="modalEliminar(' + data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a></div>' +
            '<div class="product-info"><a class="btnEliminarConClaveSupervisor"  data-id-Articulo-Eliminar="' + data["data"][i]["IdArticulo"] + '"  href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a></div>' +
            '</div>' +
            '</div>' +
            '</div>');
    }

    // $('#listaServicios').empty();
    // for (var i = 0; i < data["data"].length; i++) {
    //     $('#listaServicios').append('<div class="product col-12 col-sm-6 col-md-4">' +
    //         '<div class="card">' +
    //         '<div class="card-header">' +
    //         '<a href="servicios/' + data["data"][i]["IdArticulo"] + '/edit">' +
    //         '<img src="' + data["data"][i]["Imagen"] + '" alt="">' +
    //         '</a>' +
    //         '</div>' +
    //         '<div class="card-body">' +
    //         '<div class="row">' +
    //         '<div class="col-12">' +
    //         '<h5 class="product-title">' + data["data"][i]["Descripcion"] + '</h5>' +
    //         '</div>' +
    //         '<div class="col-6">' +
    //         '<span class="product-price fs-18">' + moneda + ' ' + data["data"][i]["Precio"] + '</span>' +
    //         '</div>' +
    //         '<div class="col-6">' +
    //        '<span class="product-price text-danger fs-18">' + moneda + ' ' + ((data["data"][i]["Precio"]) / 1.18).toFixed(2) + '</span>' +
    //        '</div>' +
    //         '</div>' +
    //         '</div>' +
    //         '<div class="card-footer">' +
    //         '<div class="product-info"><a href="servicios/' + data["data"][i]["IdArticulo"] + '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
    //         '<div class="product-info"><a onclick="modalEliminar(' + data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a></div>' +
    //         '</div>' +
    //         '</div>' +
    //         '</div>');
    // }

    $('#paginas').empty();
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
        for (var i = 1; i <= 5; i++) {
            if (i > 0 && i <= data["last_page"]) {
                if (i == data["current_page"]) {
                    paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
                } else {
                    paginas += '<li class="page-item"><a class="page-link" href="servicios?page=' + i + '">' + i + '</a></li>';
                }
            }
        }
    } else {
        if (data["last_page"] > 2) {
            var a = 3;
            if (data["current_page"] == data["last_page"]) {
                a = 4;
            }
            if (data["current_page"] > data["last_page"] - 2) {
                for (var i = data["current_page"] - a; i <= data["last_page"]; i++) {
                    if (i > 0 && i <= data["last_page"]) {
                        if (i == data["current_page"]) {
                            paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
                        } else {
                            paginas += '<li class="page-item"><a class="page-link" href="servicios?page=' + i + '">' + i + '</a></li>';
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
                    paginas += '<li class="page-item"><a class="page-link" href="servicios?page=' + i + '">' + i + '</a></li>';
                }
            }
        }
    }

    if (data["next_page_url"] !== null) {
        siguiente = '<li class="page-item"><a class="page-link" href="servicios?page=' + (data["current_page"] + 1) + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link" href="servicios?page=' + data["last_page"] + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    } else {
        siguiente = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    }

    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
    $('#paginas').append(concatenacion);
}

function modalEliminar(id) {
    $.confirm({
        title: '',
        content: 'Desea Eliminar Servicio?',
        buttons: {
            confirmar: function () {
                window.location = 'servicios/' + id + '/delete'
            },
            cancelar: function () {
                $('#btnComprobar').val('');
            },
        }
    });
}


///// crear y editar  Servicio /////
$('#archivo').change(function (e) {
    addImage(e);
});

function addImage(e) {
    var file = e.target.files[0],
        imageType = /image.*/;

    if (!file.type.match(imageType))
        return;

    var reader = new FileReader();
    reader.onload = fileOnload;
    reader.readAsDataURL(file);
}

function fileOnload(e) {
    var result = e.target.result;
    $('#imgPrevia').attr("src", result);
}

function quitarPrecioIgv(valor) {
    var precioSinIgv = parseFloat(valor / 1.18);
    $("#preciosigv").val(redondeo(precioSinIgv));
}

function quitarCostoIgv(valor) {
    var costoSinIgv = parseFloat(valor / 1.18);
    $("#costosigv").val(redondeo(costoSinIgv));
}

function descargarFormatoExcel() {
    window.open("../almacen/descargar-formato-excel-servicios", "_blank");
}