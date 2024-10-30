// ====== VARIABLES GLOBALES =======
let arrayArticulosPaquetePromo = [];
let arrayGrupos = [];
let tipoVenta = $('#tipoVenta').val();
let tipoMonedaCotizacion = $("#tipoMoneda").val();
let checkCotizacionSolesConDolares = 0;
let valorCambioVentas = 0;
let valorCambioCompras = 0;
let tipoCotizacion = $('.tipoCotizacion').val();
let editarPrecio = $("#editarPrecio").val();
let idEstadoCotizacion = $("#idEstadoCotizacion").val();

// ====== CREACION DE LA CLASE COTIZACION Y SUS METODOS ======
class Cotizacion {
    constructor() {
        this.IdCliente = null;
        this.IdTipoMoneda = null;
        this.IdCheckIn = null;
        this.IdOperario = null;
        this.Serie = null;
        this.Numero = null;
        this.IdEstadoCotizacion = 1;
        this.IdTipoAtencion = null;
        this.TipoCotizacion = null;
        this.FechaCreacion = null;
        this.FechaFin = null;
        this.Campo0 = null;
        this.Campo1 = null;
        this.Campo2 = null;
        this.TipoVenta = null;
        this.Exonerada = 0;
        this.Trabajos = null;
        this.Observacion = null;
        this.Estado = 1;
        this.MantenimientoActual = null;
        this.ProximoMantenimiento = null;
        this.PeriodoProximoMantenimiento = null;
        this.SubTotal = 0;
        this.Igv = 0;
        this.Total = 0;
        this.articulos = [];
    };

    calcularImporte(precio, cantidad, descuento) {
        const resultado = (precio * cantidad) - descuento;
        return redondearAnumero(resultado);
    }

    convertirPrecio(precio, idTipoMonedaArticulo) {
        if (tipoVenta == 2) {
            precio = redondearAnumero(precio / igv);
        }
        if (checkCotizacionSolesConDolares == 1) {
            if (tipoMonedaCotizacion == 1 && idTipoMonedaArticulo == 2) {
                precio = parseFloat(precio) * parseFloat(valorCambioVentas);
            }
            if (tipoMonedaCotizacion == 2 && idTipoMonedaArticulo == 1) {
                precio = parseFloat(precio) / parseFloat(valorCambioCompras);
            }
        }
        return redondearAnumero(precio);
    }

    // Método para crear un objeto de artículo
    crearObjetoArticulo({ idCotizacion = '', idCliente = 0, idArticulo, codigo, detalle = '', descuento, verificaTipo, cantidad, cantidadReal = 1, precioVenta, textUnidad, ganancia = 0,
        importe, idPaquetePromocional }) {
        return {
            IdCotizacion: idCotizacion,
            IdCliente: idCliente,
            IdArticulo: idArticulo,
            Codigo: codigo,
            Detalle: detalle,
            Descuento: descuento,
            VerificaTipo: verificaTipo,
            Cantidad: cantidad,
            CantidadReal: cantidadReal,
            PrecioUnidadReal: precioVenta,
            TextUnidad: textUnidad,
            Ganancia: ganancia,
            Importe: importe,
            IdPaquetePromocional: idPaquetePromocional
        };
    }
    // Método para agregar un artículo desde el frontend
    agregarArticuloDesdeFronted({ idCotizacion, idArticulo, codigo, cantidad, precioVenta, descuento, idTipoMonedaArticulo, textUnidad, idPaquetePromocional, verificaTipo }) {
        const precioConvertido = this.convertirPrecio(precioVenta, idTipoMonedaArticulo);
        const importe = this.calcularImporte(precioConvertido, cantidad, descuento);
        // se llama al metodo crearObjetoArticulo, recibe como parametro un Objeto
        const articulo = this.crearObjetoArticulo({ idCotizacion, idArticulo, codigo, descuento, verificaTipo, cantidad, precioVenta: precioConvertido, textUnidad, importe, idPaquetePromocional });
        this.articulos.push(articulo);
        return articulo;
    }
    // Método para agregar un artículo obtenido desde la Base de Datos, recibe como parametro un Objeto
    agregarArticuloDesdeBackend({ idCliente, idArticulo, codigo, detalle, descuento, verificaTipo, cantidad, cantidadReal, precioVenta, textUnidad, ganancia, importe,
        idPaquetePromocional }) {
        // se llama al metodo crearObjetoArticulo, recibe como parametro un Objeto
        const articulo = this.crearObjetoArticulo({ idCliente, idArticulo, codigo, detalle, descuento, verificaTipo, cantidad, cantidadReal, precioVenta, textUnidad, ganancia, importe, idPaquetePromocional });
        this.articulos.push(articulo);
        return articulo;
    }

    updateArticulo(idArticulo, cantidad, precioVenta, descuento) {
        const articuloEncontrado = this.articulos.find((item => item.IdArticulo === idArticulo || item.IdPaquetePromocional === idArticulo));
        if (articuloEncontrado) {
            const importe = this.calcularImporte(precioVenta, cantidad, descuento);
            articuloEncontrado.Cantidad = cantidad;
            articuloEncontrado.PrecioUnidadReal = precioVenta;
            articuloEncontrado.Descuento = descuento;
            articuloEncontrado.Importe = importe;
            return articuloEncontrado;
        }
    }

    updateDetalleArticulo({ idArticulo, detalle, idCotizacion, idCliente }) {
        const articuloEncontrado = this.articulos.find((item => item.IdArticulo === idArticulo || item.IdPaquetePromocional === idArticulo));
        if (articuloEncontrado) {
            articuloEncontrado.Detalle = detalle;
            articuloEncontrado.IdCliente = idCliente;
            articuloEncontrado.IdCotizacion = idCotizacion;
        }
    }

    actualizaTotalesCotizacion() {
        this.Total = this.articulos.reduce((acumulador, { Importe }) => acumulador + Importe, 0);
        this.SubTotal = tipoVenta == 1 ? redondearAnumero(this.Total / igv) : redondearAnumero(this.Total);
        this.Igv = tipoVenta == 1 ? redondearAnumero(this.Total - this.SubTotal) : 0;
        this.Exonerada = this.articulos.reduce((acumulador, { Descuento }) => acumulador + Descuento, 0); // Exonerado es el descuento
        // Devolver las propiedades actualizadas junto con la nueva propiedad "descuento"
        return { Total: redondearAcadena(this.Total), SubTotal: redondearAcadena(this.SubTotal), Igv: redondearAcadena(this.Igv), Descuento: redondearAcadena(this.Exonerada) };
    }

    eliminarItem(idItem) {
        const index = this.articulos.findIndex((item => item.IdArticulo === idItem || item.IdPaquetePromocional === idItem));
        if (index > -1) {
            this.articulos.splice(index, 1);
        }
    }

    eliminarTodosLosArticulos() {
        this.articulos.splice(0, this.articulos.length);
        this.actualizaTotalesCotizacion();
    }

    setDatosCotizacion(datos) {
        this.IdCliente = datos.IdCliente;
        this.IdTipoMoneda = datos.IdTipoMoneda;
        this.IdCheckIn = datos.IdCheckIn;
        this.IdOperario = datos.IdOperario;
        this.Serie = datos.Serie;
        this.Numero = datos.Numero;
        this.IdEstadoCotizacion = datos.IdEstadoCotizacion;
        this.IdTipoAtencion = datos.IdTipoAtencion;
        this.TipoCotizacion = datos.TipoCotizacion;
        this.FechaCreacion = datos.FechaCreacion;
        this.FechaFin = datos.FechaFin;
        this.Campo0 = datos.Campo0;
        this.Campo1 = datos.Campo1;
        this.Campo2 = datos.Campo2;
        this.TipoVenta = datos.TipoVenta;
        this.Trabajos = datos.Trabajos;
        this.Observacion = datos.Observacion;
        this.Estado = 1;
        this.MantenimientoActual = datos.MantenimientoActual;
        this.ProximoMantenimiento = datos.ProximoMantenimiento;
        this.PeriodoProximoMantenimiento = datos.PeriodoProximoMantenimiento;
    }

    storageCotizacion() {
        enviar();
    }
}
// Se inicializa una instancia de la  clase cotizacion
let cotizacion = new Cotizacion();
// Fin


// ====== DELEGANDO EVENTOS DE CLICK A ELEMENTOS DINAMICOS ======
$(document).on('click', (e) => {
    // DETECTAR EL CLICK EN BOTON DE AGREGAR GRUPO
    const botonClikeadoGrupo = e.target.closest('.botonAgregarGrupo');
    // DETECTAR EL CLICK EN BOTON DE AGREGAR PAQUETE PROMOCIONAL
    const botonClikeadoPaquetePromo = e.target.closest('.botonAgregarPaquetePromo');
    // DETECTAR EL CLICK EN BOTON DE AGREGAR PRODUCTO
    const botonClikeadoProducto = e.target.closest('.botonAgregarProducto');
    // DETECTAR EL CLICK EN BOTON DE AGREGAR SERVICIO
    const botonClikeadoServicio = e.target.closest('.botonAgregarServicio');
    // DETECTAR EL CLICK EN BOTON DE ELIMINAR ARTICULO
    const botonClikeadoEliminarArticulo = e.target.closest('.btnEliminarItem');

    // Función para obtener el valor del atributo 'data-id'
    const obtenerId = (elemento) => $(elemento).data('id');

    if (botonClikeadoGrupo) {
        const id = obtenerId(botonClikeadoGrupo);
        handleClickBotonGrupo(id);
    }

    if (botonClikeadoPaquetePromo) {
        const id = obtenerId(botonClikeadoPaquetePromo);
        handleClickBotonPaquetePromo(id);
    }

    if (botonClikeadoProducto) {
        const id = obtenerId(botonClikeadoProducto);
        handleClickBotonProducto(id);
    }

    if (botonClikeadoServicio) {
        const id = obtenerId(botonClikeadoServicio);
        handleClickBotonServicio(id)
    }

    if (botonClikeadoEliminarArticulo) {
        const fila = $(botonClikeadoEliminarArticulo).closest('tr');
        const id = fila.data('id');
        const etiqueta = fila.data('etiqueta');
        // Eliminar la fila del artículo
        fila.remove();
        // Eliminar el artículo de la cotización
        cotizacion.eliminarItem(id);
        // Actualizar los totales en la vista
        actualizarTotalesEnVista();
        // Ocultar el ícono del candado
        hideIconoCandado(`${etiqueta}-${id}`);
        // Si el artículo es un paquete promocional, eliminar los artículos del paquete
        if (etiqueta === 'PAQ') {
            eliminarArticulosPaquetePromo(id);
        }
    }
})
function handleClickBotonGrupo(id) {
    if (arrayGrupos.includes(id) == true) {
        alert("Grupo ya agregado");
    } else {
        showIconoCandado(`GRUPO-${id}`); // Muestra el ícono de candado para el grupo identificado por ${id}
        var valorCambioVentas = $("#valorCambioVentas").val();
        var valorCambioCompras = $("#valorCambioCompras").val();
        if ((checkCotizacionSolesConDolares == 1 || tipoMonedaCotizacion == 2) && (parseFloat(
            valorCambioVentas) == 0 || parseFloat(valorCambioCompras) == 0)) {
            $(".bs-modal-lg-grupos-soles").modal("hide");
            $(".bs-modal-lg-grupos-dolares").modal("hide");
            $("#tipoCambio").modal("show");
            // =====================================
            hideIconoCandado(`GRUPO-${id}`);
            // =====================================
        } else {
            $.ajax({
                type: 'GET',
                url: 'obtener-items-grupo',
                data: {
                    'idGrupo': id,
                },
                success: function (data) {
                    let arrayItemsDuplicados = data.filter(function (item) {
                        return arrayArticulosPaquetePromo.some((articulo) => articulo.IdArticulo === item.IdArticulo)
                    });

                    if (arrayItemsDuplicados.length >= 1) {
                        const nombresDuplicados = arrayItemsDuplicados.map(element => element.NombreArticulo.trim());
                        alert("Los siguientes artículos ya están dentro del paquete, por lo tanto no serán agregados: \n" +
                            nombresDuplicados.join("\n"));
                    }
                    for (var i = 0; i < data.length; i++) {
                        if (!cotizacion.articulos.some((articulo) => articulo.IdArticulo === data[i]["IdArticulo"]) && !arrayItemsDuplicados.some((articulo) => articulo.IdArticulo === data[i]["IdArticulo"])) {
                            // Nuevo codigo
                            if (data[i]["CantidadArticulo"] == null) {
                                var cantidad = 1;
                            } else {
                                var cantidad = data[i]["CantidadArticulo"];
                            }
                            // Fin
                            if (data[i]['idTipoItems'] == 1) {
                                productoEnTablaDom(data[i]["IdArticulo"], data[i]["NombreArticulo"], data[i]["UM"], data[i]["Precio"], cantidad, 0, data[i]["IdUnidadMedida"], data[i]["idTipoMonedaItems"], 'PRO');
                                // =====================================
                                showIconoCandado(`PRO-${data[i]["IdArticulo"]}`);
                                // =====================================

                            } else {
                                servicioEnTablaDom(data[i]["IdArticulo"], data[i]["NombreArticulo"], "ZZ",
                                    data[i]["Precio"], cantidad, 0, data[i]["idTipoMonedaItems"], 'SER');
                                // =====================================
                                showIconoCandado(`SER-${data[i]["IdArticulo"]}`);
                                // =====================================
                            }
                        }
                    }
                }
            })
            arrayGrupos.push(`GRUPO-${id}`);
        }
    }
}

function handleClickBotonPaquetePromo(id) {
    if (cotizacion.articulos.some(articulo => articulo.IdPaquetePromocional === id)) {
        alert("Paquete promocional ya agregado");
        return;
    }
    // =====================================
    showIconoCandado(`PAQ-${id}`);
    // =====================================
    $.ajax({
        type: 'GET',
        // url: 'obtener-items-paquete-promocional',
        url: '../obtener-items-paquete-promocional',
        data: {
            'idPaquete': id,
        },
        success: function (data) {
            let itemsDuplicados = data.filter(function (item) {
                return cotizacion.articulos.some(articulo => articulo.IdArticulo === item.IdArticulo);
            });

            if (itemsDuplicados.length >= 1) {
                const nombresDuplicados = itemsDuplicados.map(element => element.NombreArticulo.trim());
                alert("Los siguientes artículos agregados ya están dentro del paquete, retírelos para agregar el paquete promocional: \n" +
                    nombresDuplicados.join("\n"));
                // =====================================
                hideIconoCandado(`PAQ-${id}`);

                // =====================================
                return;
            }
            // Funcion para agregar los articulos del paquete promocional, para luego verificar si ya se encuentra agregado dentro del detalle de la cotizacion.
            crearArrayArticulosPaquetePromo(data, accion = 'crearArticuloDesdeFrontend');
            const descripcion = $('#nombre-' + id).text();
            // Creando el objeto articulo Servicio
            const nuevoArticulo = {
                idArticulo: 0,
                codigo: `PAQ-${id}`,
                cantidad: 1,
                precioVenta: $(`#precio-${id}`).text(),
                descuento: 0,
                idTipoMonedaArticulo: $(`#idTipoMoneda-${id}`).val(),
                textUnidad: 'ZZ',
                idPaquetePromocional: id,
                verificaTipo: 4
            }
            articuloEnTablaDom({ nuevoArticulo, id, descripcion, accion: 'crearArticuloFrontend' });
            return;
        }
    })
}

function handleClickBotonProducto(id) {
    let arrayItemsDuplicados = arrayArticulosPaquetePromo.filter(item => item.IdArticulo == id);
    if (arrayItemsDuplicados.length >= 1) {
        swal("Producto ya en lista!", "Este producto ya se encuentra agregado en listaaaaa.", "info");
    } else {
        var descripcion = $('#p1-' + id).text();
        var unidadMedida = $('#p3-' + id).val();
        var precio = $('#p2-' + id).text();
        var cantidad = $('#p4-' + id).val();
        var descuento = $('#p5-' + id).val();
        var costo = $('#p6-' + id).val();
        var stock = $('#p7-' + id).val();
        var idTipoMoneda = $('#p8-' + id).val();
        var idUnidadMedida = $('#IdUnidadMedida-' + id).val();
        var tipoVenta = $('#tipoVenta').val();
        if (cotizacion.articulos.some(articulo => articulo.IdArticulo === id)) {
            swal("Producto ya agregado", "por favor modificar la cantidad si desea agregar más", "info");
            return;
        } else {
            // =====================================
            showIconoCandado(`PRO-${id}`);
            // =====================================
            // Creando el objeto articulo Producto
            const nuevoArticulo = {
                idArticulo: id, codigo: `PRO-${id}`, cantidad: cantidad == 0 ? 1 : redondearAnumero(cantidad), precioVenta: redondearAnumero(precio), descuento: redondearAnumero(descuento), idTipoMonedaArticulo: idTipoMoneda, textUnidad: unidadMedida, idPaquetePromocional: null, verificaTipo: 1
            }
            articuloEnTablaDom({ nuevoArticulo, id, descripcion, idTipoMoneda, idUnidadMedida, stock, accion: 'crearArticuloFrontend' });
            return;
        }
    }
}

function handleClickBotonServicio(id) {
    let arrayItemsDuplicados = arrayArticulosPaquetePromo.filter(item => item.IdArticulo == id);
    if (arrayItemsDuplicados.length >= 1) {
        alert("Este servicio ya se encuentra dentro del paquete");
    } else {
        if (cotizacion.articulos.some(articulo => articulo.IdArticulo === id)) {
            alert("Servicio ya agregado, por favor de modificar la cantidad si desea agregar más");
        } else {
            const descripcion = $('#s1-' + id).text();
            const unidadMedida = 'ZZ';
            const precio = $('#s2-' + id).text();
            const cantidad = $('#s5-' + id).val();
            const descuento = $('#s3-' + id).val();
            const idTipoMoneda = $('#s7-' + id).val();
            // =====================================
            showIconoCandado(`SER-${id}`);
            // =====================================
            // Creando el objeto articulo Servicio
            const nuevoArticulo = {
                idArticulo: id, codigo: `SER-${id}`, cantidad: cantidad == 0 ? 1 : cantidad, precioVenta: precio, descuento: descuento, idTipoMonedaArticulo: idTipoMoneda, textUnidad: unidadMedida, idPaquetePromocional: null, verificaTipo: 4
            }
            articuloEnTablaDom({ nuevoArticulo, id, descripcion, idTipoMoneda, accion: 'crearArticuloFrontend' });
            return;
        }
    }
}

/**
 * Funcion para agregar un artículo a la vista de la tabla.
 * @param {Object} nuevoArticulo - El objeto que representa el nuevo producto.
 * @param {number} id - El ID del artículo (indicador para los input) => $("#input-ID").
 * @param {string} descripcion - La descripción del artículo.
 * @param {number} idUnidadMedida - El ID de la unidad de medida.
 * @param {string} accion - La acción indica si el artículo se agrega desde el Frontend o el Backend.
 */

function articuloEnTablaDom({ nuevoArticulo, id, descripcion, idTipoMoneda, idUnidadMedida = null, stock = null, accion }) {
    let productoAgregado = '';
    if (accion === 'crearArticuloFrontend') {
        productoAgregado = cotizacion.agregarArticuloDesdeFronted(nuevoArticulo);
    } else {
        productoAgregado = cotizacion.agregarArticuloDesdeBackend(nuevoArticulo);
    }

    let step = '', max = 9999, dato = 'SER', readonlyCantidad = '', boton = '', contenedorBontonQuitar, bloquearInputStock;
    if (nuevoArticulo.codigo.includes('PRO')) {
        step = (idUnidadMedida == 1) ? '' : '0.05';
        max = (idEstadoCotizacion == 2) ? parseFloat(stock) : 9999;
        dato = 'PRO';
    }
    const readonlyPrecio = (editarPrecio == 1) ? '' : 'readonly';
    if (nuevoArticulo.codigo.includes('PAQ')) {
        dato = 'PAQ';
        readonlyCantidad = 'readonly';
        boton = '<button onclick="verDetallePaquetePromocional(' + id + ', ' +
            ')" class="btn btn-primary ml-1 p-1"><i class="list-icon material-icons fs-16">visibility</i></button>'
    }

    if (idEstadoCotizacion == 3) {
        readonlyInput = 'readonly';
        contenedorBontonQuitar = '';
    } else {
        readonlyInput = '';
        contenedorBontonQuitar = `<td><button class="btn btn-primary p-1 btnEliminarItem"><i class="list-icon material-icons fs-16">clear</i></button>${boton}
                </td>`
    }
    bloquearInputStock = (idEstadoCotizacion == 2 && idUnidadMedida == 1) ? 'false' : 'true';

    const fila = `
            <tr data-id="${id}" data-etiqueta="${dato}">
                <td style="width:600px">
                    ${productoAgregado.Codigo}
                </td>
                <td>${descripcion}</td>
                <td>
                    <input id="detalle${id}" ${readonlyInput} type="text" value="${productoAgregado.Detalle == null ? '' : productoAgregado.Detalle}">
                </td>
                <td>${productoAgregado.TextUnidad}</td>
                <td>
                    <input id="precio${id}" ${readonlyInput} class="inputModificarImporte" type="number" value="${redondearAcadena(productoAgregado.PrecioUnidadReal)}" step="any" style="width:90px"}>
                </td>
                <td>
                    <input id="descuento${id}" class="inputModificarImporte inputModificarDescuento" data-tipo-moneda="${idTipoMoneda}" step="any" type="number" min="0" value="${redondearAcadena(productoAgregado.Descuento)}" style="width:90px">
                </td>
                <td>
                    <input id="cantidad${id}" ${readonlyInput} class="inputModificarImporte" onkeydown="return ${bloquearInputStock};"  step="${step}" type="number" min="1" max="${max}" ${readonlyCantidad} value="${redondearAcadena(productoAgregado.Cantidad)}" style="width:60px">
                </td>
                <td>
                    <input id="importe${id}" readonly type="number" value="${redondearAcadena(productoAgregado.Importe)}" step="any" style="width:100px">
                </td>
                ${contenedorBontonQuitar}
                
            </tr>`;
    $('#tablaAgregado tbody').append(fila);
    actualizarTotalesEnVista();  // Actualiza Op. Gravada, Op. Exonerada, Descuento, Igv, Total
}

// ====== DELEGAR EVENTOS DE CHANGE A ELEMENTOS DINAMICOS ======
$(document).on('change', (e) => {
    const inputModificarImporte = e.target.closest('.inputModificarImporte');
    if (inputModificarImporte) {
        calcularNuevoImporte(inputModificarImporte);
    }
});

// Evento para quitar el check de ventasSolesDolares al hacer click en el boton camcelar del modal Tipo cambio
$('#btnTipoCambioCancelar').click(function () {
    checkCotizacionSolesConDolares = 0;
    $("#ventaSolesDolares").prop("checked", false);
});


// Evento para traer el kilomotraje del cliente vehiculo seleccionado , solo para Cotizacion Vehicular
$("#inventario").change(function () {
    var inventario = $('#inventario').val();
    $.ajax({
        type: 'get',
        url: 'data-inventario',
        data: {
            'IdCheckList': inventario
        },
        success: function (result) {
            $('#campo1').val(result[0]["Kilometraje"]);
        }
    });
});

// ====== DELEGAR EVENTOS DE CLICK A ELEMENTOS ESTATICOS ======
$('#btnGenerar').click(function () {
    capturarDatosCotizacionDom();
    capturarDetalleDom();
    cotizacion.storageCotizacion();
})

// ======= CREACION DE FUNCIONES =======
const actualizarTotalesEnVista = () => {
    // Actualizar los totales de la Compra
    let cotizacionActualizada = cotizacion.actualizaTotalesCotizacion();
    // Actualizar los totales en el DOM
    if (tipoVenta == 1) {
        $('#opGravada').val(cotizacionActualizada.SubTotal);
        $('#opExonerado').val(redondearAcadena(0));
        $('#igv').val(cotizacionActualizada.Igv);
    } else {
        $('#opGravada').val(redondearAcadena(0));
        $('#opExonerado').val(cotizacionActualizada.SubTotal);
        $('#igv').val(cotizacionActualizada.Igv);
    }
    $('#descuento').val(cotizacionActualizada.Descuento);
    $('#total').val(cotizacionActualizada.Total);
}

// Eliminar todos los articulos del DOM y de la clase Compra
const limpiarArticulos = () => {
    // Limpiar la clase
    cotizacion.eliminarTodosLosArticulos();
    hideAllIconoCandado();
    arrayGrupos = [];
    arrayArticulosPaquetePromo = [];
    // Limpiar el DOM
    $('#tablaAgregado tr:gt(0)').remove();
    $('#opGravada').val('0.00');
    $('#opExonerado').val('0.00');
    $('#descuento').val('0.00');
    $('#igv').val('0.00');
    $('#total').val('0.00');
}

const calcularNuevoImporte = (elemento) => {
    const fila = $(elemento).closest('tr');
    const id = fila.data('id');
    const precio = $(`#precio${id}`).val();
    const cantidad = $(`#cantidad${id}`).val();
    const descuento = $(`#descuento${id}`).val();
    const articuloActualizado = cotizacion.updateArticulo(id, redondearAnumero(cantidad), redondearAnumero(precio), redondearAnumero(descuento));
    $(`#importe${id}`).val(redondearAcadena(articuloActualizado.Importe));
    actualizarTotalesEnVista();
}

const crearArrayArticulosPaquetePromo = (array, accion) => {
    console.log('articulos paquete promo');
    console.log(array)
    array.forEach(articulo => {
        let unidadMedida, verificaTipo, nuevoPrecioArticulo, nuevoImporteArticulo;
        if (accion === 'crearArticuloDesdeFrontend') {
            unidadMedida = (articulo.CodigoArticulo.includes('PRO')) ? articulo.UM : 'ZZ';
            verificaTipo = (unidadMedida === 'ZZ') ? 4 : 1;
            nuevoPrecioArticulo = cotizacion.convertirPrecio(redondearAnumero(articulo.Precio), articulo.idTipoMonedaItems);
            nuevoImporteArticulo = cotizacion.calcularImporte(redondearAnumero(nuevoPrecioArticulo), redondearAnumero(articulo.cantidad), descuento = 0);
        } else if (accion === 'crearArticuloDesdeBackend') {
            unidadMedida = articulo.TextUnidad;
            verificaTipo = articulo.VerificaTipo;
            nuevoPrecioArticulo = articulo.PrecioUnidadReal;
            nuevoImporteArticulo = articulo.Importe;
        }
        const articuloDelPaquete = {
            IdCotizacion: datosCotizacion.IdCotizacion,
            IdCliente: datosCotizacion.IdCliente,
            IdArticulo: articulo.IdArticulo,
            Codigo: (accion === 'crearArticuloDesdeFrontend') ? articulo.CodigoArticulo : articulo.Codigo,
            Detalle: (accion === 'crearArticuloDesdeFrontend') ? '' : articulo.Detalle,
            Descuento: (accion === 'crearArticuloDesdeFrontend') ? 0 : articulo.Descuento,
            VerificaTipo: verificaTipo,
            Cantidad: (accion === 'crearArticuloDesdeFrontend') ? articulo.cantidad : articulo.Cantidad,
            CantidadReal: (accion === 'crearArticuloDesdeFrontend') ? 1 : articulo.CantidadReal,
            PrecioUnidadReal: nuevoPrecioArticulo,
            TextUnidad: unidadMedida,
            Ganancia: (accion === 'crearArticuloDesdeFrontend') ? 0 : articulo.Ganancia,
            Importe: nuevoImporteArticulo,
            IdPaquetePromocional: articulo.IdPaquetePromocional
        }
        arrayArticulosPaquetePromo.push(articuloDelPaquete)
        // =====================================
        // Mostrar el icono candado
        showIconoCandado(articulo.CodigoArticulo);
        // =====================================
    });
};


const capturarDetalleDom = () => {
    cotizacion.articulos.map((item) => {
        let idArticulo = 0;
        if (item.IdArticulo == 0) {
            idArticulo = item.IdPaquetePromocional;
        } else {
            idArticulo = item.IdArticulo;
        }
        let detalle = $(`#detalle${idArticulo}`).val();
        const nuevoObjetoActualizar = {
            idArticulo: idArticulo,
            detalle: detalle,
            idCotizacion: datosCotizacion.IdCotizacion,
            idCliente: datosCotizacion.IdCliente
        }
        cotizacion.updateDetalleArticulo(nuevoObjetoActualizar)
    })
}

// const capturarDatosCotizacionDom = () => {
//     const atencion = $('#atencion').val();
//     let idVehiculo = '';
//     let mantenimientoActual = null;
//     let proximoMantenimiento = null;
//     let periodoProximoMantenimiento = null;
//     let idTipoAtencion = 5;
//     if (atencion == 1 || atencion == 6) {
//         mantenimientoActual = $("#mantenimientoActual").val();
//         proximoMantenimiento = $("#proximoMantenimiento").val();
//         periodoProximoMantenimiento = $("#periodoProximoMantenimiento").val();
//     }
//     if (tipoCotizacion == 2) {
//         idTipoAtencion = $('#atencion').val()
//         idVehiculo = $('#clientes').find('option:selected').data('idVehiculo');
//     }
//     const datosCotizacion = {
//         IdCliente: $('#clientes').val(),
//         IdTipoMoneda: $("#tipoMoneda").val(),
//         IdCheckIn: $('#inventario').val(),
//         IdOperario: $("#operario").val(),
//         Serie: $("#serie").val(),
//         Numero: $("#numero").val(),
//         IdEstadoCotizacion: 1,
//         IdTipoAtencion: idTipoAtencion,
//         TipoCotizacion: tipoCotizacion,
//         FechaCreacion: $("#datepicker2").val(),
//         FechaFin: $("#datepicker").val(),
//         Campo0: idVehiculo,
//         Campo1: $("#campo1").val(),
//         Campo2: $("#campo2").val(),
//         TipoVenta: $('#tipoVenta').val(),
//         Exonerada: $('#descuento').val(),
//         Trabajos: $("#trabajos").val(),
//         Observacion: $("#observacion").val(),
//         Estado: 1,
//         MantenimientoActual: mantenimientoActual,
//         ProximoMantenimiento: proximoMantenimiento,
//         PeriodoProximoMantenimiento: periodoProximoMantenimiento,
//     };
//     cotizacion.setDatosCotizacion(datosCotizacion);
// }
const eliminarArticulosPaquetePromo = (idPaquete) => {
    const nuevosArticulosPaquetePromo = arrayArticulosPaquetePromo.filter((articulo) => articulo.IdPaquetePromocional !== idPaquete);
    arrayArticulosPaquetePromo = nuevosArticulosPaquetePromo;
}

function verDetallePaquetePromocional($idPaquete) {
    $('#tableDetalle').find('#tableDetalleBody').remove();
    $('#tableDetalle').append('<tbody id="tableDetalleBody"></tbody>')
    $('#totalPaquete').val('');
    var tipoVenta = $('#tipoVenta').val();
    $.ajax({
        type: 'GET',
        url: 'obtener-items-paquete-promocional',
        data: {
            'idPaquete': $idPaquete,
        },
        success: function (data) {
            var total = 0;
            var importe = 0;
            for (var i = 0; i < data.length; i++) {
                if (data[i]['idTipoItems'] == 1) {
                    var codigo = "PRO"
                } else {
                    var codigo = "SER"
                }
                if (tipoVenta == 2) {
                    precio = redondeo(parseFloat(data[i]['Precio'] / 1.18));
                } else {
                    precio = data[i]['Precio'];
                }
                var fila = '<tr>' +
                    '<td>' + codigo + '-' + data[i]['IdArticulo'] + '</td>' +
                    '<td>' + data[i]['NombreArticulo'] + '</td>' +
                    '<td>' + precio + '</td>' +
                    '<td>' + data[i]['cantidad'] + '</td>' +
                    '</tr>';
                importe = parseFloat(precio) * data[i]['cantidad'];
                total += importe;
                // $('#tableDetalle tr:last').after(fila);
                // $('#tableDetalleBody').find('tbody').append( fila );
                $('#tableDetalleBody').append(fila);
            }
            $('#totalPaquete').val(redondeo(total));
        }
    })
    $('.detallePaquetePromocional').modal('show');
}


// REDONDEAR COMO NUMERICO
const redondearAnumero = (numero) => {
    return Number(redondeo(numero));
}
// REDONDEAR NUMERO A DOS DECIMALES COMO CADENA
const redondearAcadena = (numero) => {
    return redondeo(numero);
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


// $(document).on('change', '.inputPrecio', function () {
//     calcularNuevoImporte(this);
// });

// $(document).on('change', '.inputDescuento', function () {
//     calcularNuevoImporte(this);
// });

// // CLIKEANDO EL BOTON ELIMINAR ITEM
// $(document).on('click', '.btnEliminarItem', function () {
//     const fila = $(this).closest('tr');
//     const id = fila.data('id');
//     const etiqueta = fila.data('etiqueta');
//     fila.remove();
//     cotizacion.eliminarItem(id);
//     actualizarTotalesEnVista();
//     hideIconoCandado(`${id}${etiqueta}`)
//     eliminarArticulosPaquetePromo(id);
// });

// CALCULAR NUEVO IMPORTE DESDE LOS INPUT
// $(document).on('change', '.inputCantidad', function () {
//     // const val = $(this).val();
//     // $(this).val(parseFloat(val).toFixed(2));
//     calcularNuevoImporte(this);
// });
