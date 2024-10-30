///// Marcas //////
$("#inputBuscar").keyup(function() {
    var textoBusqueda = $("#inputBuscar").val();
    $.ajax({
        type: 'get',
        url: 'buscar-marcas',
        data: { 'textoBuscar': textoBusqueda },
        success: function(data) {
            cargarDataMarcas(data);
        }
    });
});

$(document).on('click', '.pagMar a', function(e) {
    e.preventDefault();
    var page = $(this).attr('href').split('page=')[1];
    getMarcas(page);
});

function getMarcas(page) {
    var textoBusqueda = $('#inputBuscar').val();
    $.ajax({
        type: 'get',
        url: 'marcas-almacen?page=' + page,
        data: {
            'textoBuscar': textoBusqueda
        },
        success: function(data) {
            cargarDataMarcas(data);
        }
    });
}

function cargarDataMarcas(data) {
    $('#listaMarcas').empty();
    for (var i = 0; i < data["data"].length; i++) {
        $('#listaMarcas').append('<div class="product col-12 col-sm-6 col-md-4">' +
            '<div class="card">' +
            '<div class="card-header">' +
            '<a href="javascript:void(0);">' +
            '<img src="' + data["data"][i]["Imagen"] + '" alt="">' +
            '</a>' +
            '</div>' +
            '<div class="card-body">' +
            '<div class="d-flex">' +
            '<h5 class="product-title">' + data["data"][i]["Nombre"] + '</h5>' +
            '</div>' +
            '<span class="text-muted">' + data["data"][i]["Descripcion"] + '</span>' +
            '</div>' +
            '<div class="card-footer">' +
            '<div class="product-info"><a href="marcas/' + data["data"][i]["IdMarca"] + '/edit"><i class="list-icon material-icons">edit</i>Editar</a></div>' +
            '<div class="product-info"><a onclick="modalEliminar(' + data["data"][i]["IdMarca"] + ')" href="javascript:void(0);"><i class="list-icon material-icons">remove_circle</i>Eliminar</a></div>' +
            '</div>' +
            '</div>' +
            '</div>');
    }

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
                    paginas += '<li class="page-item"><a class="page-link" href="marcas?page=' + i + '">' + i + '</a></li>';
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
                            paginas += '<li class="page-item"><a class="page-link" href="marcas?page=' + i + '">' + i + '</a></li>';
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
                    paginas += '<li class="page-item"><a class="page-link" href="marcas?page=' + i + '">' + i + '</a></li>';
                }
            }
        }
    }

    if (data["next_page_url"] !== null) {
        siguiente = '<li class="page-item"><a class="page-link" href="marcas?page=' + (data["current_page"] + 1) + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevron-right"></i></span></a></li>';
        ultimo = '<li class="page-item"><a class="page-link" href="marcas?page=' + data["last_page"] + '" aria-label="Next"><span aria-hidden="true"><i class="feather feather-chevrons-right"></i></span></a></li>';
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
        content: 'Desea Eliminar Marca?',
        buttons: {
            confirmar: function() {
                window.location = 'marcas/' + id + '/delete'
            },
            cancelar: function() {

            },
        }
    });
}


///// Crear y Editar Marca /////
$('#archivo').change(function(e) {
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