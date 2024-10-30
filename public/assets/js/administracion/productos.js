///// Productos /////
function modalEliminar(id) {
    $.ajax({
        type: "get",
        url: "verificar-producto/" + id,
        success: function (data) {
            // console.log(data[1]);
            $.confirm({
                title: "Desea Eliminar Producto?",
                content: "El Producto tiene un stock de: " + data[1]["Stock"],
                buttons: {
                    confirmar: function () {
                        window.location = "productos/" + id + "/delete";
                    },
                    cancelar: function () { },
                },
            });
        },
    });
}

$("input[name=radioOption]").change(function () {
    var textoBusqueda = "";
    if ($(this).val() == 1) {
        listarProductos(textoBusqueda, 1);
    } else {
        listarProductos(textoBusqueda, 2);
    }
});

$("#inputBuscar").keyup(function () {
    var textoBusqueda = $("#inputBuscar").val();
    var tipoMoneda;
    if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
        if ($("#radio1").is(":checked")) {
            tipoMoneda = 1;
        } else {
            tipoMoneda = 2;
        }
        listarProductos(textoBusqueda, tipoMoneda);
    }
});

function listarProductos(textoBusqueda, tipoMoneda) {
    var moneda;
    if (tipoMoneda == 1) {
        moneda = "S/";
    } else {
        moneda = "$";
    }
    $.ajax({
        type: "get",
        // url: "buscar-productos",
        url: rutaBuscarProductos,
        data: {
            textoBuscar: textoBusqueda,
            tipoMoneda: tipoMoneda,
        },
        success: function (data) {
            getDataProductos(data, moneda);
        },
    });
}

$(document).on("click", ".pagProd a", function (e) {
    e.preventDefault();
    var page = $(this).attr("href").split("page=")[1];
    getProductos(page);
});

function getProductos(page) {
    var textoBusqueda = $("#inputBuscar").val();
    var tipoMoneda;
    var moneda;
    if ($("#radio1").is(":checked")) {
        tipoMoneda = 1;
        moneda = "S/";
    } else {
        tipoMoneda = 2;
        moneda = "$";
    }
    $.ajax({
        type: "get",
        // url: "productos-almacen?page=" + page,
        url: `${rutaPaginarProductos}?page=${page}`,
        data: {
            textoBuscar: textoBusqueda,
            tipoMoneda: tipoMoneda,
        },
        success: function (data) {
            console.log(data);
            getDataProductos(data, moneda);
        },
    });
}

$("#btnEnvio").on("click", function () {
    var myForm = $("form#myform");
    if (myForm) {
        $(this).attr("disabled", true);
        $("#alerta").text("Esto puede tardar varios minutos. Por favor no cierre ni retroceda esta pantalla");
        $(myForm).submit();
    }
});

function getDataProductos(data, moneda) {
    $("#listaProductos").empty();
    for (var i = 0; i < data["data"].length; i++) {
        let imagen;
        if (data["data"][i]["Imagen"].includes(urlDominioAmazonS3) || data["data"][i]["Imagen"].includes(UrlImagenNotFound)) {
            imagen = data["data"][i]["Imagen"];
        } else if (data["data"][i]["Imagen"].includes(UrlDominioAmazonS3Antiguo)) {
            imagen = UrlImagenNotFound;
        } else {
            imagen = urlDominioAmazonS3 + data["data"][i]["Imagen"];
        }
        $("#listaProductos").append(
            '<div class="product col-12 col-sm-6 col-md-4 col-lg-3">' +
            '<div class="card">' +
            '<div class="card-header">' +
            '<a href="productos/' +
            data["data"][i]["IdArticulo"] +
            '/edit">' +
            '<img src="' +
            // data["data"][i]["Imagen"] +
            // '" alt="">' +
            imagen +
            '" alt="">' +
            "</a>" +
            "</div>" +
            '<div class="card-body">' +
            '<div class="row">' +
            '<div class="col-12">' +
            '<h5 class="product-title fs-18">' +
            data["data"][i]["Descripcion"] +
            "</h5>" +
            "</div>" +
            '<div class="col-6 text-center">' +
            '<span class="product-price fs-18">' +
            moneda +
            " " +
            data["data"][i]["Precio"] +
            "</span>" + '<span class="badge badge-warning">Con IGV</span>' +
            "</div>" +
            // codigo Modificado
            '<div class="col-6 text-center">' +
            '<span class="product-price text-danger fs-18">' +
            moneda +
            " " +
            redondeo(data["data"][i]["Precio"] / 1.18) +
            "</span>" + '<span class="badge badge-warning">Sin IGV</span>' +
            "</div>" +
            "</div>" +
            '<span class="text-muted">' +
            data["data"][i]["Marca"] +
            "</span>" +
            "</div>" +
            '<div class="card-footer">' +
            '<div class="product-info"><a href="productos/' +
            data["data"][i]["IdArticulo"] +
            '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
            "<div class='product-info'><a class='btnEliminarConClaveSupervisor' data-id-Articulo-Eliminar=" +
            data["data"][i]["IdArticulo"] +
            "  href='javascript:void(0);'><i class='list-icon material-icons'>remove_circle</i>Eliminar</a></div>" +
            "</div>" +
            "</div>" +
            "</div>"
        );
        // Fin
        // CODIGO VERDADERO
        // '<div class="col-6">' +
        // '<span class="product-price text-danger fs-18">' + moneda + ' ' + redondeo((data["data"][i]["Precio"]) / 1.18) + '</span>' +
        // '</div>' +
        // '</div>' +
        // '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
        // '</div>' +
        // '<div class="card-footer">' +
        // '<div class="product-info"><a href="productos/' + data["data"][i]["IdArticulo"] + '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
        // '<div class="product-info"><a onclick="modalEliminar(' + data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a></div>' +
        // '</div>' +
        // '</div>' +
        // '</div>');
        // FIN
    }

    $("#paginas").empty();
    var primero = "";
    var ultimo = "";
    var anterior = "";
    var paginas = "";
    var siguiente = "";
    if (data["prev_page_url"] !== null) {
        primero =
            '<li class="page-item"><a class="page-link" href="' +
            data["first_page_url"] +
            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior =
            '<li class="page-item"><a class="page-link" href="' +
            data["prev_page_url"] +
            '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
    } else {
        primero =
            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
        anterior =
            '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
    }

    if (data["current_page"] < 3) {
        for (var i = 1; i <= 5; i++) {
            if (i > 0 && i <= data["last_page"]) {
                if (i == data["current_page"]) {
                    paginas +=
                        '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                        i +
                        "</a></li>";
                } else {
                    paginas +=
                        '<li class="page-item"><a class="page-link" href="productos?page=' + i + '">' + i + "</a></li>";
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
                            paginas +=
                                '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' +
                                i +
                                "</a></li>";
                        } else {
                            paginas +=
                                '<li class="page-item"><a class="page-link" href="productos?page=' +
                                i +
                                '">' +
                                i +
                                "</a></li>";
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
                        i +
                        "</a></li>";
                } else {
                    paginas +=
                        '<li class="page-item"><a class="page-link" href="productos?page=' + i + '">' + i + "</a></li>";
                }
            }
        }
    }

    if (data["next_page_url"] !== null) {
        siguiente =
            '<li class="page-item"><a class="page-link" href="productos?page=' +
            (data["current_page"] + 1) +
            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo =
            '<li class="page-item"><a class="page-link" href="productos?page=' +
            data["last_page"] +
            '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    } else {
        siguiente =
            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo =
            '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
    }

    var concatenacion = primero + anterior + paginas + siguiente + ultimo;
    $("#paginas").append(concatenacion);
}

// function getDataProductos(data, moneda) {
//     $('#listaProductos').empty();
//     for (var i = 0; i < data["data"].length; i++) {
//         $('#listaProductos').append('<div class="product col-12 col-sm-6 col-md-4 col-lg-3">' +
//             '<div class="card">' +
//             '<div class="card-header">' +
//             '<a href="productos/' + data["data"][i]["IdArticulo"] + '/edit">' +
//             '<img src="' + data["data"][i]["Imagen"] + '" alt="">' +
//             '</a>' +
//             '</div>' +
//             '<div class="card-body">' +
//             '<div class="row">' +
//             '<div class="col-12">' +
//             '<h5 class="product-title fs-18">' + data["data"][i]["Descripcion"] + '</h5>' +
//             '</div>' +
//             '<div class="col-6">' +
//             '<span class="product-price fs-18">' + moneda + ' ' + data["data"][i]["Precio"] + '</span>' +
//             '</div>' +
//             '<div class="col-6">' +
//             '<span class="product-price text-danger fs-18">' + moneda + ' ' + redondeo((data["data"][i]["Precio"]) / 1.18) + '</span>' +
//             '</div>' +
//             '</div>' +
//             '<span class="text-muted">' + data["data"][i]["Marca"] + '</span>' +
//             '</div>' +
//     // codigo Modificado
//             // '<div class="card-footer">' +
//             // '<div class="product-info"><a href="productos/' + data["data"][i]["IdArticulo"] + '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
//             // "<div class='product-info><a id='eliminarArticulo' data-toggle='modal' data-target='#exampleModalCentered' href='javascript:void(0);'><i class='list-icon material-icons'>remove_circle</i>Eliminar</a></div>" +
//             // '</div>' +
//             // '</div>' +
//             // '</div>');
//     // Fin
//             '<div class="card-footer">' +
//             '<div class="product-info"><a href="productos/' + data["data"][i]["IdArticulo"] + '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
//             '<div class="product-info"><a onclick="modalEliminar(' + data["data"][i]["IdArticulo"] + ')" href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a></div>' +
//             '</div>' +
//             '</div>' +
//             '</div>');
//     }

//     $('#paginas').empty();
//     var primero = '';
//     var ultimo = '';
//     var anterior = '';
//     var paginas = '';
//     var siguiente = '';
//     if (data["prev_page_url"] !== null) {
//         primero = '<li class="page-item"><a class="page-link" href="' + data["first_page_url"] + '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
//         anterior = '<li class="page-item"><a class="page-link" href="' + data["prev_page_url"] + '" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
//     } else {
//         primero = '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevrons-left"></i></span></a></li>';
//         anterior = '<li class="page-item"><a class="page-link disabled" aria-label="Previous"><span aria-hidden="true"><i class="feather feather-chevron-left"></i></span></a></li>';
//     }

//     if (data["current_page"] < 3) {
//         for (var i = 1; i <= 5; i++) {
//             if (i > 0 && i <= data["last_page"]) {
//                 if (i == data["current_page"]) {
//                     paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
//                 } else {
//                     paginas += '<li class="page-item"><a class="page-link" href="productos?page=' + i + '">' + i + '</a></li>';
//                 }
//             }
//         }
//     } else {
//         if (data["last_page"] > 2) {
//             var a = 3;
//             if (data["current_page"] == data["last_page"]) {
//                 a = 4;
//             }
//             if (data["current_page"] > data["last_page"] - 2) {
//                 for (var i = data["current_page"] - a; i <= data["last_page"]; i++) {
//                     if (i > 0 && i <= data["last_page"]) {
//                         if (i == data["current_page"]) {
//                             paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
//                         } else {
//                             paginas += '<li class="page-item"><a class="page-link" href="productos?page=' + i + '">' + i + '</a></li>';
//                         }
//                     }
//                 }
//             }
//         }
//     }

//     if (data["current_page"] >= 3 && data["current_page"] <= data["last_page"] - 2) {
//         for (var i = data["current_page"] - 2; i <= data["current_page"] + 2; i++) {
//             if (i > 0 && i <= data["last_page"]) {
//                 if (i == data["current_page"]) {
//                     paginas += '<li class="page-item active"><a class="page-link" href="javascript:void(0);">' + i + '</a></li>';
//                 } else {
//                     paginas += '<li class="page-item"><a class="page-link" href="productos?page=' + i + '">' + i + '</a></li>';
//                 }
//             }
//         }
//     }

//     if (data["next_page_url"] !== null) {
//         siguiente = '<li class="page-item"><a class="page-link" href="productos?page=' + (data["current_page"] + 1) + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
//         ultimo = '<li class="page-item"><a class="page-link" href="productos?page=' + data["last_page"] + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
//     } else {
//         siguiente = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
//         ultimo = '<li class="page-item"><a class="page-link disabled" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
//     }

//     var concatenacion = primero + anterior + paginas + siguiente + ultimo;
//     $('#paginas').append(concatenacion);
// }

$("#file").change(function (e) {
    add_Image(e);
});

function add_Image(e) {
    var file = e.target.files[0];
    $("#nombreArchivo").text(file.name);
}

function descargarFormato() {
    window.open("../almacen/descargar-formato", "_blank");
}

///// Crear, Editar Producto /////
$("#archivo").change(function (e) {
    addImage(e);
});

function addImage(e) {
    var file = e.target.files[0],
        imageType = /image.*/;

    if (!file.type.match(imageType)) return;

    var reader = new FileReader();
    reader.onload = fileOnload;
    reader.readAsDataURL(file);
}

function fileOnload(e) {
    var result = e.target.result;
    $("#imgPrevia").attr("src", result);
}

$(document).on("click", ".generar", function () {
    var codigo = $("#codBarra").val();
    JsBarcode("#barcode", codigo);
    $("#print").show();
});

$("#btnCrear").click(function () {
    window.scrollBy(0, -window.innerHeight);
    $.showLoading({
        name: "circle-fade",
    });
});

$("#tipoMoneda").on("change", function () {
    var tipoMoneda = $("#tipoMoneda").val();
    if (tipoMoneda == 2) {
        $.ajax({
            type: "get",
            url: "create/verificar-tipo-cambio",
            success: function (data) {
                if (data.length > 0) {
                    $("#valorCambio").val(data[0]["TipoCambioVentas"]);
                } else {
                    alert("Primero debe configurar tipo de cambio");
                    window.location = "../../../administracion/bancos/tipo-cambio";
                }
            },
        });
    } else {
        $("#valorCambio").val(0);
    }
});

function quitarPrecioIgv(valor) {
    var precioSinIgv = parseFloat(valor / 1.18);
    $("#preciosigv").val(redondeo(precioSinIgv));
}

function quitarCostoIgv(valor) {
    var costoSinIgv = parseFloat(valor / 1.18);
    $("#costosigv").val(redondeo(costoSinIgv));
}

///// Eliminacion Personalizada /////
var table = $("#table").DataTable({
    columnDefs: [
        {
            targets: 0,
            checkboxes: {
                selectRow: true,
            },
        },
    ],
    select: {
        style: "multi",
    },
    responsive: true,
    order: [[0, "desc"]],
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
            last: "Último",
        },
        aria: {
            sortAscending: ": Activar para ordenar la columna de manera ascendente",
            sortDescending: ": Activar para ordenar la columna de manera descendente",
        },
    },
});

function modalEliminarProductos() {
    var rows_selected = table.column(0).checkboxes.selected();
    $("#mostrarmodal").modal("show");
    var total = rows_selected.length;
    $("#total").text(total);
    var masCero = 0;
    var menosCero = 0;
    $.each(rows_selected, function (index, rowId) {
        var datos = rowId.split("-");
        var stock = parseInt(datos[1]);
        if (stock > 0) {
            masCero = masCero + 1;
        } else {
            menosCero = menosCero + 1;
        }
    });

    $("#masCero").text(masCero);
}

$("#form-eliminar").on("submit", function (e) {
    var form = this;
    var rows_selected = table.column(0).checkboxes.selected();
    $.each(rows_selected, function (index, rowId) {
        var datos = rowId.split("-");
        var ids = parseInt(datos[0]);
        $(form).append($("<input>").attr("type", "hidden").attr("name", "id[]").val(ids));
    });
});
