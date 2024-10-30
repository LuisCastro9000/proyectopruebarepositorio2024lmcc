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
        const valueBotonOrdenCompra = $(e.target).val();
        const inputDescripcionEgreso = $('#inputDescripcionEgreso').val();
        const montoEfect = $("#montoEfectivo").val();
        const montoCuenta = $("#pagoCuenta").val();
        const nroOperacion = $("#nroOperacion").val();
        const cuentaBancaria = $("#cuentaBancaria").val();
        const interes = $("#_interes").val();
        const valorCambio = $('#valorCambio').val();
        const tipoPago = $("#tipoPago").val();
        const fechaDepositoCompra = $('#fechaDepositoCompra').val();
        const idOrdenCompra = $('#idOrdenCompra').val();
        //Fin//
        $.ajax({
            type: 'post',
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
                'inputDescripcionEgreso': inputDescripcionEgreso,
                'fechaDepositoCompra': fechaDepositoCompra,
                'idOrdenCompra': idOrdenCompra,
                'compra': JSON.stringify(this)
            },
            success: function (data) {
                if (data.respuesta == 'error') {
                    alert(data.mensaje);
                    $('.guardarCompra').removeAttr("disabled", false);
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

                                window.location = `../../compras/comprobante-generado/${data.id}`;
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
        <td>${redondeo(precioCosto)}</td>
        <td>${cantidad}</td>
        <td>${redondeo(importeFinal)}</td>
        </tr>`);

    // Almacenar articulo en el detalle de la claseCompra
    compra.agregarArticulo(id, `PRO-${id}`, cantidad, parseFloat(redondeo(precioCosto)), parseFloat(redondeo(
        importeFinal)));
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