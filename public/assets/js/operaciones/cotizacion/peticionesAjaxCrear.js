
//  Utilizando variablesJs definidas en la vista (crear,editar,duplicar)
//  Utilizando obtenerValoresDom definidas en scriptOperacionesArticulos.js


document.addEventListener("DOMContentLoaded", function () {

    // ======== Obtener tipo cambio
    $('#ventaSolesDolares').change(function () {
        var tipoMoneda = $("#tipoMoneda").val();
        const fechaCreacion = $("#datepicker2").val();
        if ($(this).is(':checked')) {
            $.ajax({
                type: 'get',
                // url: 'select-productos',
                url: variablesJs.routeObtenerTipoCambio,
                data: {
                    "fechaCreacion": fechaCreacion
                },
                success: function (data) {
                    if (Object.entries(data).length > 0) {
                        $('#agregarArticuloSoles').removeClass('d-none');
                        $('#agregarGruposSoles').removeClass('d-none');
                        $('#agregarPaquetesPromocionalesSoles').removeClass('d-none');
                        $('#agregarArticuloDolares').removeClass('d-none');
                        $('#agregarGruposDolares').removeClass('d-none');
                        $('#agregarPaquetesPromocionalesDolares').removeClass('d-none');

                        $("#valorCambioCompras").val(data.TipoCambioCompras);
                        $("#valorCambioVentas").val(data.TipoCambioVentas);
                        if (tipoMoneda == 1) {
                            $("#cambioVentas").show();
                            $("#cambioCompras").hide();
                        } else {
                            $("#cambioCompras").show();
                            $("#cambioVentas").hide();
                        }
                    } else {
                        $("#tipoCambio").modal("show");
                    }
                }
            });
        } else {
            $("#cambioCompras").hide();
            $("#cambioVentas").hide();
            if (tipoMoneda == 1) {
                //moneda = 'S/';
                $('#agregarArticuloSoles').removeClass('d-none');
                $('#agregarGruposSoles').removeClass('d-none');
                $('#agregarPaquetesPromocionalesSoles').removeClass('d-none');
                $('#agregarArticuloDolares').addClass('d-none');
                $('#agregarGruposDolares').addClass('d-none');
                $('#agregarPaquetesPromocionalesDolares').addClass('d-none');
            } else {
                //moneda = '$';
                $('#agregarArticuloSoles').addClass('d-none');
                $('#agregarGruposSoles').addClass('d-none');
                $('#agregarPaquetesPromocionalesSoles').addClass('d-none');
                $('#agregarArticuloDolares').removeClass('d-none');
                $('#agregarGruposDolares').removeClass('d-none');
                $('#agregarPaquetesPromocionalesDolares').removeClass('d-none');
            }
        }
    });

    // ======== Codigo para mostrar los articulos por categoria en el modal
    $("#categoriaSoles").on('change', function () {
        const textoBusqueda = $("#inputBuscarProductosSoles").val();
        const idCategoria = $("#categoriaSoles").val();
        buscarProductosConAjax(textoBusqueda, idCategoria, 1);
    });

    $("#categoriaDolares").on('change', function () {
        const textoBusqueda = $("#inputBuscarProductosDolares").val();
        const idCategoria = $("#categoriaDolares").val();
        buscarProductosConAjax(textoBusqueda, idCategoria, 1);
    });

    // ======== Codigo para la busqueda de articulos en el modal
    $("#inputBuscarProductosSoles").keyup(function () {
        const textoBusqueda = $("#inputBuscarProductosSoles").val();
        if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
            var idCategoria = $("#categoriaSoles").val();
            buscarProductosConAjax(textoBusqueda, idCategoria, 1);
        }
    });

    $("#inputBuscarProductosDolares").keyup(function () {
        const textoBusqueda = $("#inputBuscarProductosDolares").val();
        if (textoBusqueda.length > 2 || textoBusqueda.length == 0) {
            var idCategoria = $("#categoriaDolares").val();
            buscarProductosConAjax(textoBusqueda, idCategoria, 2);
        }
    });

    $("#inputBuscarServiciosSoles").keyup(function () {
        var textoBusqueda = $("#inputBuscarServiciosSoles").val();
        if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
            buscarServiciosConAjax(textoBusqueda, 1);
        }
    });

    $("#inputBuscarServiciosDolares").keyup(function () {
        var textoBusqueda = $("#inputBuscarServiciosDolares").val();
        if (textoBusqueda.length > 3 || textoBusqueda.length == 0) {
            buscarServiciosConAjax(textoBusqueda, 2);
        }
    });

    function buscarProductosConAjax(textoBusqueda, idCategoria, tipoMoneda) {
        $.ajax({
            type: 'get',
            url: variablesJs.routeBuscarProductos,
            data: {
                'textoBuscar': textoBusqueda,
                'tipoMoneda': tipoMoneda,
                'idCategoria': idCategoria
            },
            success: function (data) {
                if (tipoMoneda == 1) {
                    listadoProductos = '#listaProductosSoles';
                    paginasProductos = '#paginasProductosSoles';
                } else {
                    listadoProductos = '#listaProductosDolares';
                    paginasProductos = '#paginasProductosDolares';
                }
                var moneda;
                var stock = '';
                if (tipoMoneda == 1) {
                    moneda = 'S/';
                } else {
                    moneda = '$';
                }
                cargarProductosEnModal(data, listadoProductos, paginasProductos, moneda);
            }
        });
    }

    function buscarServiciosConAjax(textoBusqueda, tipoMoneda) {
        $.ajax({
            type: 'get',
            url: variablesJs.routeBuscarServicios,
            data: {
                'textoBuscar': textoBusqueda,
                'tipoMoneda': tipoMoneda
            },
            success: function (data) {
                var moneda;
                if (tipoMoneda == 1) {
                    listadoServicios = '#listaServiciosSoles';
                    paginasServicios = '#paginasServiciosSoles';
                    moneda = 'S/';
                } else {
                    listadoServicios = '#listaServiciosDolares';
                    paginasServicios = '#paginasServiciosDolares';
                    moneda = '$';
                }
                cargarServiciosEnModal(data, listadoServicios, paginasServicios, moneda);
            }
        });
    }

    // ==== Codigo para paginar los articulos en el modal
    $(document).on('click', '.pagProdSoles a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var textoBusqueda = $('#inputBuscarProductosSoles').val();
        var idCategoria = $("#categoriaSoles").val();
        getProductos(page, textoBusqueda, idCategoria, 1);
    });

    $(document).on('click', '.pagProdDolares a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var textoBusqueda = $('#inputBuscarProductosDolares').val();
        var idCategoria = $("#categoriaDolares").val();
        getProductos(page, textoBusqueda, idCategoria, 2);
    });

    function getProductos(page, textoBusqueda, idCategoria, tipoMoneda) {
        $.ajax({
            type: 'get',
            url: variablesJs.routePaginarProductos + page,
            data: {
                'textoBuscar': textoBusqueda,
                'tipoMoneda': tipoMoneda,
                'idCategoria': idCategoria
            },
            success: function (data) {
                var moneda;
                if (tipoMoneda == 1) {
                    moneda = 'S/';
                    listadoProductos = '#listaProductosSoles';
                    paginasProductos = '#paginasProductosSoles';
                } else {
                    moneda = '$';
                    listadoProductos = '#listaProductosDolares';
                    paginasProductos = '#paginasProductosDolares';
                }
                cargarProductosEnModal(data, listadoProductos, paginasProductos, moneda);
            }
        });
    }

    $(document).on('click', '.pagServSoles a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var textoBusqueda = $('#inputBuscarServiciosSoles').val();
        getServicios(page, textoBusqueda, 1);
    });

    $(document).on('click', '.pagServDolares a', function (e) {
        e.preventDefault();
        var page = $(this).attr('href').split('page=')[1];
        var textoBusqueda = $('#inputBuscarServiciosDolares').val();
        getServicios(page, textoBusqueda, 2);
    });

    function getServicios(page, textoBusqueda, tipoMoneda) {
        $.ajax({
            type: 'get',
            url: variablesJs.routePaginarServicios + page,
            data: {
                'textoBuscar': textoBusqueda,
                'tipoMoneda': tipoMoneda
            },
            success: function (data) {
                var moneda;
                if (tipoMoneda == 1) {
                    moneda = 'S/';
                    listadoServicios = '#listaServiciosSoles';
                    paginasServicios = '#paginasServiciosSoles';
                } else {
                    moneda = '$';
                    listadoServicios = '#listaServiciosDolares';
                    paginasServicios = '#paginasServiciosDolares';
                }
                cargarServiciosEnModal(data, listadoServicios, paginasServicios, moneda);
            }
        });
    }
})


// ======== Guardar Tipo cambio
function guardaTipoCambio(elemento) {
    $(elemento).attr('disabled', 'true');
    var tipoCambioCompras = $("#tipoCambioCompras").val();
    var tipoCambioVentas = $("#tipoCambioVentas").val();
    var tipoMoneda = $("#tipoMoneda").val();
    var fechaCreacion = $("#datepicker2").val();
    console.log(fechaCreacion);
    // $.ajax({
    //     type: 'POST',
    //     // url: 'guardar-tipo-cambio',
    //     url: variablesJs.routeGuardarTipoCambio,
    //     data: {
    //         "_token": variablesJs.token,
    //         "tipoCambioCompras": tipoCambioCompras,
    //         "tipoCambioVentas": tipoCambioVentas,
    //         "fechaCreacion": fechaCreacion
    //     },
    //     success: function (data) {
    //         $(elemento).attr('disabled', false); // Habilitar el elemento
    //         if (data.respuesta == 'success') {
    //             if (tipoMoneda == 2) {
    //                 $('#agregarArticuloSoles').addClass('d-none');
    //                 $('#agregarGruposSoles').addClass('d-none');
    //                 $('#agregarPaquetesPromocionalesSoles').addClass('d-none');
    //                 $('#agregarArticuloDolares').removeClass('d-none');
    //                 $('#agregarGruposDolares').removeClass('d-none');
    //                 $('#agregarPaquetesPromocionalesDolares').removeClass('d-none');
    //             }
    //             if (obtenerValoresDom.checkCotizacionSolesConDolares() == 1) {
    //                 if (tipoMoneda == 1) {
    //                     $("#cambioCompras").hide();
    //                     $("#cambioVentas").show();

    //                     $('#agregarArticuloDolares').removeClass('d-none');
    //                     $('#agregarGruposDolares').removeClass('d-none');
    //                     $('#agregarPaquetesPromocionalesDolares').removeClass('d-none');
    //                 } else {
    //                     $("#cambioVentas").hide();
    //                     $("#cambioCompras").show();
    //                 }
    //             }
    //             $("#valorCambioVentas").val(tipoCambioVentas);
    //             $("#valorCambioCompras").val(tipoCambioCompras);
    //             $("#tipoCambio").modal("hide");
    //             swal("Registro Exitoso", "" + data.mensaje, "success");
    //         }
    //         alert(data.mensaje);
    //     }
    // });
}


