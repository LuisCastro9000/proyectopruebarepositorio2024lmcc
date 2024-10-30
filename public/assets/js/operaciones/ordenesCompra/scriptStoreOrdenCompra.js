// Declarando variables
let tipoCompra = $('#tipoCompra').val();
let igv = parseFloat((18 / 100) + 1);

// Se crea la clase Compra
class OrdenCompra {
    constructor(proveedor, tipoMoneda, fechaEmision, fechaRecepcion, tipoPago, plazoCredito, tipoCompra, serie,
        numero, observacion, estadoCompra) {
        this.IdProveedor = proveedor;
        this.IdTipoMoneda = tipoMoneda;
        this.FechaEmision = fechaEmision;
        this.FechaRecepcion = fechaRecepcion;
        this.IdTipoPago = tipoPago;
        this.DiasPlazoCredito = plazoCredito;
        this.TipoCompra = tipoCompra;
        this.Serie = serie;
        this.Numero = numero;
        this.Observacion = observacion;
        this.SubTotal = 0;
        this.Igv = 0;
        this.Total = 0;
        this.Estado = estadoCompra;
        this.Articulos = [];
    };

    agregarArticulo(id, codigo, cantidad, precioCosto, importe) {
        const articulo = {
            IdArticulo: id,
            CodigoArticulo: codigo,
            Cantidad: cantidad,
            PrecioCosto: precioCosto,
            Importe: importe
        };
        this.Articulos.push(articulo);
    }

    updateArticulo(idArticulo, cantidad, costo, importe) {
        const indice = this.Articulos.findIndex((item => item.IdArticulo === idArticulo));
        if (indice > -1) {
            this.Articulos[indice].Cantidad = cantidad;
            this.Articulos[indice].Importe = importe;
            this.Articulos[indice].PrecioCosto = costo;
        }
    }

    actualizaTotalesCompra() {
        this.Total = Number(this.Articulos.reduce((acumulador, { Importe }) => acumulador + Importe, 0).toFixed(2));
        this.SubTotal = tipoCompra == 1 ? Number((this.Total / igv).toFixed(2)) : Number((this.Total).toFixed(2));
        this.Igv = tipoCompra == 1 ? Number((this.Total - this.SubTotal).toFixed(2)) : 0;
    }

    eliminarArticulo(idArticulo) {
        const index = this.Articulos.findIndex((item => item.IdArticulo === idArticulo));
        if (index > -1) {
            this.Articulos.splice(index, 1);
        }
    }

    eliminarTodosLosArticulos() {
        this.Articulos.splice(0, this.Articulos.length);
        this.actualizaTotalesCompra();
    }

    setDatosCompra(datos) {
        this.IdProveedor = datos.proveedor;
        this.IdTipoMoneda = datos.tipoMoneda;
        this.FechaEmision = datos.fechaEmision;
        this.FechaRecepcion = datos.fechaRecepcion;
        this.IdTipoPago = datos.tipoPago;
        this.DiasPlazoCredito = datos.plazoCredito;
        this.TipoCompra = datos.tipoCompra;
        this.Serie = datos.serie;
        this.Numero = datos.numero;
        this.Observacion = datos.observacion;
        this.Estado = datos.estadoCompra;
    }

    storageCompra(e) {
        $('.btnGuardarOrdenCompra').attr('disabled', 'disabled');
        const valueBotonOrdenCompra = $(e.target).val();
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
            method: tipoPeticion,
            url: urlOrdenCompra,
            data: {
                "_token": token,
                "valueBotonOrdenCompra": valueBotonOrdenCompra,
                "MontoEfect": montoEfect,
                "MontoCuenta": montoCuenta,
                "nroOperacion": nroOperacion,
                "CuentaBancaria": cuentaBancaria,
                "Interes": interes,
                "tipoPago": tipoPago,
                "valorCambio": valorCambio,
                'fechaDepositoCompra': fechaDepositoCompra,
                'idCompraPendiente': idCompraPendiente,
                'ordenCompra': JSON.stringify(this)
            },
            success: function (data) {
                if (data.respuesta == 'error') {
                    alert(data.mensaje);
                    $('.btnGuardarOrdenCompra').removeAttr("disabled", false);
                }
                if (data.respuesta == 'errorTransaccion') {
                    alert(data.mensaje);
                }
                if (data.respuesta == 'errorCajaCerrada') {
                    alert(data.mensaje);
                    window.location = '../../caja/cierre-caja';
                }
                if (data.respuesta == 'success') {
                    swal({
                        title: "Excelente!",
                        text: data.mensaje,
                        icon: "success",
                        button: "Ok",
                        closeOnClickOutside: false
                    })
                        .then((Entendido) => {
                            if (Entendido) {
                                if (data.accion == 'update') {
                                    window.location = '../../ordenes-compra/' + data.id;
                                } else {
                                    window.location = '../ordenes-compra/' + data.id;
                                }
                            }
                        });
                }
            }
        });
    }
}


// Se inicializa una instancia de la  clase compra
let ordenCompra = new OrdenCompra();
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
        ordenCompra.updateArticulo(idArticulo, cantidad, parseFloat(costo), importe);
        actualizarTotales();
    }

    if (e.target.matches('#tipoCompra')) {
        tipoCompra = $(e.target).val();
        limpiarArticulos();
    }

    if (e.target.matches('#cuentaBancaria')) {
        if ($(e.target).val() != 0) {
            $('#montoEfectivo').val(redondeo(0));
            $('#pagoCuenta').val(redondeo(ordenCompra.Total));
        } else {
            $('#montoEfectivo').val(redondeo(ordenCompra.Total));
            $('#pagoCuenta').val(redondeo(0));
        }
    }
})

$(document).on('click', function (e) {
    // detectar el click en el boton EliminarArticulo
    if (e.target.matches('.btnEliminarArticulo') || e.target.matches('.btnEliminarArticulo *')) {
        const tableRow = e.target.closest('tr');
        const idArticulo = $(tableRow).data('id-articulo');
        ordenCompra.eliminarArticulo(idArticulo);
        tableRow.remove();
        actualizarTotales();
    }

    // detectar el click en el boton GuardarCompra
    if (e.target.closest('.btnGuardarOrdenCompra')) {
        const estadoCompra = $(e.target).val();
        capturarDatosCompra(estadoCompra);
        ordenCompra.storageCompra(e);
    }
})
// Fin

const capturarDatosCompra = (estadoCompra) => {
    const datosCompra = {
        proveedor: $("#proveedores").val(),
        fechaEmision: $("#fechaEmision").val(),
        fechaRecepcion: $("#fechaRecepcion").val(),
        serie: $("#serie").val(),
        numero: $("#numero").val(),
        observacion: $("#observacion").val(),
        tipoPago: $("#tipoPago").val(),
        plazoCredito: $("#_plazoCredito").val(),
        tipoMoneda: $("#tipoMoneda").val(),
        tipoCompra: tipoCompra,
        estadoCompra: estadoCompra
    };
    ordenCompra.setDatosCompra(datosCompra);
}

const actualizarTotales = () => {
    // Actualizar los totales de la Compra
    ordenCompra.actualizaTotalesCompra();
    // Actualizar los totales en el DOM
    if (tipoCompra == 1) {
        $('#opGravada').val(redondeo(ordenCompra.SubTotal));
        $('#opExonerado').val(redondeo(0));
        $('#igv').val(redondeo(ordenCompra.Igv));
    } else {
        $('#opGravada').val(redondeo(0));
        $('#opExonerado').val(redondeo(ordenCompra.SubTotal));
        $('#igv').val(redondeo(ordenCompra.Igv));
    }
    $('#total').val(redondeo(ordenCompra.Total));
}

// Eliminar todos los articulos del DOM y de la clase Compra
const limpiarArticulos = () => {
    // Limpiar la clase
    ordenCompra.eliminarTodosLosArticulos();
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
    if (ordenCompra.Articulos.some(item => item.IdArticulo == id) == true) {
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
    ordenCompra.agregarArticulo(id, `PRO-${id}`, cantidad, parseFloat(redondeo(precioCosto)), parseFloat(redondeo(importeFinal)));
    actualizarTotales();
}

function redondeo(num) {
    if (num == 0 || num == "0.00") return "0.00";
    if (!num || num == 'NaN') return '-';
    if (num == 'Infinity') return '&#x221e;';
    num = num.toString().replace(/\$|\,/g, '');
    if (isNaN(num))
        num = "0";
    sign = (num == (num = Math.abs(num)));
    num = Math.floor(num * 100 + 0.50000000001);
    cents = num % 100;
    num = Math.floor(num / 100).toString();
    if (cents < 10)
        cents = "0" + cents;
    for (var i = 0; i < Math.floor((num.length - (1 + i)) / 3); i++)
        num = num.substring(0, num.length - (4 * i + 3)) + '' + num.substring(num.length - (4 * i + 3));
    return (((sign) ? '' : '-') + num + '.' + cents);
}