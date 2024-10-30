// Declarando variables
let tipoCompra = $('#tipoCompra').val();
let igv = parseFloat((18 / 100) + 1);

// Se crea la clase Compra
class Compra {
    constructor(proveedor, tipoMoneda, fecha, tipoComprobante, tipoPago, plazoCredito, tipoCompra, serie,
        numero, observacion, estadoCompra) {
        this.IdProveedor = proveedor;
        this.IdTipoMoneda = tipoMoneda;
        this.FechaCreacion = fecha;
        this.IdTipoComprobante = tipoComprobante;
        this.IdTipoPago = tipoPago;
        this.PlazoCredito = plazoCredito;
        this.TipoCompra = tipoCompra;
        this.Serie = serie;
        this.Numero = numero;
        this.Observacion = observacion;
        this.Subtotal = 0;
        this.Igv = 0;
        this.Total = 0;
        this.Estado = estadoCompra;
        this.articulos = [];
    };

    agregarArticulo(id, codigo, cantidad, precioCosto, importe) {
        const articulo = {
            IdArticulo: id,
            Codigo: codigo,
            Cantidad: cantidad,
            PrecioCosto: precioCosto,
            Importe: importe
        };
        this.articulos.push(articulo);
    }

    updateArticulo(idArticulo, cantidad, costo, importe) {
        const indice = this.articulos.findIndex((item => item.IdArticulo === idArticulo));
        if (indice > -1) {
            this.articulos[indice].Cantidad = cantidad;
            this.articulos[indice].Importe = importe;
            this.articulos[indice].PrecioCosto = costo;
        }
    }

    actualizaTotalesCompra() {
        this.Total = Number(this.articulos.reduce((acumulador, { Importe }) => acumulador + Importe, 0).toFixed(2));
        this.Subtotal = tipoCompra == 1 ? Number((this.Total / igv).toFixed(2)) : Number((this.Total).toFixed(2));
        this.Igv = tipoCompra == 1 ? Number((this.Total - this.Subtotal).toFixed(2)) : 0;
    }

    eliminarArticulo(idArticulo) {
        const index = this.articulos.findIndex((item => item.IdArticulo === idArticulo));
        if (index > -1) {
            this.articulos.splice(index, 1);
        }
    }

    eliminarTodosLosArticulos() {
        this.articulos.splice(0, this.articulos.length);
        this.actualizaTotalesCompra();
    }

    setDatosCompra(datos) {
        this.IdProveedor = datos.proveedor;
        this.IdTipoMoneda = datos.tipoMoneda;
        this.FechaCreacion = datos.fecha;
        this.IdTipoComprobante = datos.tipoComprobante;
        this.IdTipoPago = datos.tipoPago;
        this.PlazoCredito = datos.plazoCredito;
        this.TipoCompra = datos.tipoCompra;
        this.Serie = datos.serie;
        this.Numero = datos.numero;
        this.Observacion = datos.observacion;
        this.Estado = datos.estadoCompra;
    }

    storageCompra(e) {
        $('.guardarCompra').attr("disabled", true);
        let checkGuardarEgreso = 0;
        if ($("#checkGuardarEgreso").is(':checked')) {
            checkGuardarEgreso = 1;
        } else {
            checkGuardarEgreso = 0;
        }
        const valueBotonCompra = $(e.target).val();
        const inputDescripcionEgreso = $('#inputDescripcionEgreso').val();
        const inputIdCaja = $('#inputIdCaja').val();
        const montoEfect = $("#montoEfectivo").val();
        const montoCuenta = $("#pagoCuenta").val();
        const nroOperacion = $("#nroOperacion").val();
        const cuentaBancaria = $("#cuentaBancaria").val();
        const interes = $("#_interes").val();
        const valorCambio = $('#valorCambio').val();
        const tipoPago = $("#tipoPago").val();
        const fechaDepositoCompra = $('#fechaDepositoCompra').val();
        // Datos para la Opcion de editarCompra
        const idCompraPendiente = $("#idCompraPendiente").val();
        //Fin//
        $.ajax({
            type: 'post',
            url: urlCompra,
            data: {
                "_token": token,
                "valueBotonCompra": valueBotonCompra,
                "MontoEfect": montoEfect,
                "MontoCuenta": montoCuenta,
                "nroOperacion": nroOperacion,
                "CuentaBancaria": cuentaBancaria,
                "Interes": interes,
                "tipoPago": tipoPago,
                "valorCambio": valorCambio,
                "checkGuardarEgreso": checkGuardarEgreso,
                'inputDescripcionEgreso': inputDescripcionEgreso,
                'inputIdCaja': inputIdCaja,
                'fechaDepositoCompra': fechaDepositoCompra,
                'idCompraPendiente': idCompraPendiente,
                'compra': JSON.stringify(this)
            },
            success: function (data) {
                if (data[0] == 'error') {
                    alert(data[1]);
                    $('.guardarCompra').removeAttr("disabled", false);
                }
                if (data[0] == 'errorTransaccion') {
                    alert(data[1]);
                }
                if (data[0] == 'errorCajaCerrada') {
                    alert(data[1]);
                    window.location = '../../caja/cierre-caja';
                }
                if (data[0] == 'success') {
                    swal({
                        title: "Excelente!",
                        text: data[1],
                        icon: "success",
                        button: "Ok",
                        closeOnClickOutside: false
                    })
                        .then((Entendido) => {
                            if (Entendido) {
                                if (idCompraPendiente != null) {
                                    window.location =
                                        '../comprobante-generado/' +
                                        data[2];
                                } else {
                                    window.location =
                                        '../compras/comprobante-generado/' +
                                        data[2];
                                }
                            }
                        });
                }
            }
        });
    }
}


// Se inicializa una instancia de la  clase compra
let compra = new Compra();
// Fin

// Detectando eventos en el DOM
$(document).on('change', function (e) {
    if (e.target.matches('.inputCantidad') || e.target.matches('.inputCosto')) {
        const tableRow = e.target.closest('tr');
        const idArticulo = $(tableRow).data('id-articulo');
        const costo = $('#cost' + idArticulo).val();
        const cantidad = $('#cant' + idArticulo).val();
        // calculando el importe del articulo ( costo * cantidad)
        const importe = parseFloat(parseFloat(costo) * parseFloat(cantidad));
        // Se actualiza el importe del articulo
        $('#imp' + idArticulo).val(redondeo(importe));
        // Fin
        compra.updateArticulo(idArticulo, cantidad, parseFloat(costo), importe);
        actualizarTotales();
    }

    if (e.target.matches('#tipoCompra')) {
        tipoCompra = $(e.target).val();
        limpiarArticulos();
    }

    if (e.target.matches('#cuentaBancaria')) {
        if ($(e.target).val() != 0) {
            $('#montoEfectivo').val(redondeo(0));
            $('#pagoCuenta').val(redondeo(compra.Total));
        } else {
            $('#montoEfectivo').val(redondeo(compra.Total));
            $('#pagoCuenta').val(redondeo(0));
        }
    }
})

$(document).on('click', function (e) {
    // detectar el click en el boton EliminarArticulo
    if (e.target.matches('.btnEliminarArticulo') || e.target.matches('.btnEliminarArticulo *')) {
        const tableRow = e.target.closest('tr');
        const idArticulo = $(tableRow).data('id-articulo');
        compra.eliminarArticulo(idArticulo);
        tableRow.remove();
        actualizarTotales();
    }

    // detectar el click en el boton GuardarCompra
    if (e.target.matches('.guardarCompra')) {
        const estadoCompra = $(e.target).data('estadoCompra');
        capturarDatosCompra(estadoCompra);
        compra.storageCompra(e);
    }
})
// Fin

const capturarDatosCompra = (estadoCompra) => {
    const datosCompra = {
        tipoComprobante: $("#selectTipoComp").val(),
        proveedor: $("#proveedores").val(),
        fecha: $("#datepicker").val(),
        serie: $("#serie").val(),
        numero: $("#numero").val(),
        observacion: $("#observacion").val(),
        tipoPago: $("#tipoPago").val(),
        plazoCredito: $("#_plazoCredito").val(),
        tipoMoneda: $("#tipoMoneda").val(),
        tipoCompra: tipoCompra,
        estadoCompra: estadoCompra
    };
    compra.setDatosCompra(datosCompra);
}

const actualizarTotales = () => {
    // Actualizar los totales de la Compra
    compra.actualizaTotalesCompra();
    // Actualizar los totales en el DOM
    if (tipoCompra == 1) {
        $('#opGravada').val(redondeo(compra.Subtotal));
        $('#opExonerado').val(redondeo(0));
        $('#igv').val(redondeo(compra.Igv));
    } else {
        $('#opGravada').val(redondeo(0));
        $('#opExonerado').val(redondeo(compra.Subtotal));
        $('#igv').val(redondeo(compra.Igv));
    }
    $('#total').val(redondeo(compra.Total));
    if ($('#cuentaBancaria').val() != 0) {
        $('#pagoCuenta').val(redondeo(compra.Total));
    } else {
        $('#montoEfectivo').val(redondeo(compra.Total));
    }
}

// Eliminar todos los articulos del DOM y de la clase Compra
const limpiarArticulos = () => {
    // Limpiar la clase
    compra.eliminarTodosLosArticulos();
    // Limpiar el DOM
    $('#tablaAgregado tr:gt(0)').remove();
    $('#opGravada').val('');
    $('#opExonerado').val('');
    $('#igv').val('');
    $('#total').val('');
    $('#montoEfectivo').val(redondeo(0));
}

// FUNCION AGREGAR ARTICULO
function agregarProducto(id) {
    if (compra.articulos.some(item => item.IdArticulo == id) == true) {
        alert("Producto ya agregado, por favor de modificar la cantidad en vez de agregar más");
    } else {
        var descripcion = $('#p1-' + id).text();
        var unidadMedida = $('#p3-' + id).val();
        var precioVenta = $('#p5-' + id).val();
        var cantidad = $('#p4-' + id).val();
        var precioCosto = $('#p2-' + id).val();
        var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
        var step;
        if (idUnidadMedida == 1) {
            step = '';
        } else {
            step = '0.05';
        }

        if (tipoCompra == 2) {
            precioVenta = parseFloat(precioVenta / 1.18);
            precioCosto = parseFloat(precioCosto / 1.18);
        }

        var importe = parseFloat(parseFloat(precioCosto) * parseInt(cantidad, 10));
        var importeFinal = parseFloat(importe);

        productoEnTabla(id, descripcion, unidadMedida, precioVenta, precioCosto, cantidad, importeFinal, step);
    }
}

function productoEnTabla(id, descripcion, unidadMedida, precioVenta, precioCosto, cantidad, importeFinal, step) {

    $('#tablaAgregado tr:last').after(
        `<tr data-id-articulo="${id}">
        <td><input readonly type="text" value="PRO-${id}" style="width:80px">
        </td><td id="descrip${id}">${descripcion}</td>
        <td id="um${id}">${unidadMedida}</td>
        <td id="prec${id}">${redondeo(precioVenta)}</td>
        <td><input class="inputCosto" id="cost${id}" type="number" value="${redondeo(precioCosto)}"></td>
        <td><input class="inputCantidad" id="cant${id}" type="number" min="1" step="${step}" value="${cantidad}" style="width:100px"></td>
        <td><input class="inputImporte" id="imp${id}" step="any" readonly type="number" value="${redondeo(importeFinal)}" style="width:100px"></td>
        <td><button class="btn btn-primary btnEliminarArticulo"><i class="list-icon material-icons fs-16">clear</i></button></td>
        </tr>`);
    // Almacenar articulo en el detalle de la claseCompra
    compra.agregarArticulo(id, `PRO-${id}`, cantidad, parseFloat(redondeo(precioCosto)), parseFloat(redondeo(
        importeFinal)));
    actualizarTotales();
}