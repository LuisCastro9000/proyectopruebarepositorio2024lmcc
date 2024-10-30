// ====== VARIABLES GLOBALES =======;
let arrayArticulosPaquetePromo = [];
let arrayGrupos = [];

const obtenerValoresDom = (() => {
    return {
        selectTipoMonedaCotizacion: () => $('#tipoMoneda').val(),
        checkCotizacionSolesConDolares: () => $('#ventaSolesDolares').prop('checked') ? 1 : 0,
        inputTipoCambioVentas: () => $('#valorCambioVentas').val(),
        inputTipoCambioCompras: () => $('#valorCambioCompras').val(),
        inputEditarPrecio: () => $("#editarPrecio").val(),
        selectTipoVenta: () => $('#tipoVenta').val(),
        inputIdEstadoCotizacion: () => $('#inputIdEstadoCotizacion').val(),
        inputIdCliente: () => $('#inputIdClienteCotization').val(),
        inputIdCotizacion: () => $('#inputIdCotizacion').val()
    };
})();

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
        if (obtenerValoresDom.selectTipoVenta() == 2) {
            precio = redondearAnumero(precio / variablesJs.igv);
        }
        if (obtenerValoresDom.checkCotizacionSolesConDolares() == 1) {
            if (obtenerValoresDom.selectTipoMonedaCotizacion() == 1 && idTipoMonedaArticulo == 2) {
                precio = parseFloat(precio) * parseFloat(obtenerValoresDom.inputTipoCambioVentas());
            }
            if (obtenerValoresDom.selectTipoMonedaCotizacion() == 2 && idTipoMonedaArticulo == 1) {
                precio = parseFloat(precio) / parseFloat(obtenerValoresDom.inputTipoCambioCompras());
            }
        }
        return redondearAnumero(precio);
    }
    // Método para agregar un artículo desde el frontend
    agregarArticuloDesdeFronted(articulo, idTipoMonedaArticulo) {
        articulo.PrecioUnidadReal = this.convertirPrecio(articulo.PrecioUnidadReal, idTipoMonedaArticulo);
        articulo.Importe = this.calcularImporte(articulo.PrecioUnidadReal, articulo.Cantidad, articulo.Descuento);
        this.articulos.push(articulo);
        return articulo;
    }
    // Método para agregar un artículo obtenido desde la Base de Datos, recibe como parametro un Objeto
    agregarArticuloDesdeBackend(articulo) {
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

    updateDetalleArticulo({ idArticulo, detalle, idCliente }) {
        const articuloEncontrado = this.articulos.find((item => item.IdArticulo === idArticulo || item.IdPaquetePromocional === idArticulo));
        if (articuloEncontrado) {
            articuloEncontrado.Detalle = detalle;
            articuloEncontrado.IdCliente = idCliente;
        }
    }

    actualizaTotalesCotizacion() {
        this.Total = redondearAnumero(this.articulos.reduce((acumulador, { Importe }) => acumulador + Importe, 0));
        this.SubTotal = obtenerValoresDom.selectTipoVenta() == 1 ? redondearAnumero(this.Total / variablesJs.igv) : redondearAnumero(this.Total);
        this.Igv = obtenerValoresDom.selectTipoVenta() == 1 ? redondearAnumero(this.Total - this.SubTotal) : 0;
        this.Exonerada = redondearAnumero(this.articulos.reduce((acumulador, { Descuento }) => acumulador + Descuento, 0)); // Exonerado es el descuento
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
        if ((obtenerValoresDom.checkCotizacionSolesConDolares() == 1 || obtenerValoresDom.selectTipoMonedaCotizacion() == 2) && (parseFloat(
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
                url: variablesJs.routeObtenerArticulosGrupo,
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
                            // const codigo = data[i]['idTipoItems'] == 1 ? `PRO-${data[i]["IdArticulo"]}` : `SER-${data[i]["IdArticulo"]}`;
                            // const verificaTipo = data[i]['idTipoItems'] == 1 ? 1 : 4;
                            // const unidadMedida = data[i]['idTipoItems'] == 1 ? data[i]["UM"] : 'ZZ';
                            // const nuevoArticulo = {
                            //     IdCotizacion: '',
                            //     IdCliente: '',
                            //     IdArticulo: data[i]["IdArticulo"],
                            //     Codigo: codigo,
                            //     Detalle: '',
                            //     Descuento: 0,
                            //     VerificaTipo: verificaTipo,
                            //     Cantidad: redondearAnumero(data[i]["CantidadArticulo"]),
                            //     CantidadReal: 1,
                            //     PrecioUnidadReal: redondearAnumero(data[i]["Precio"]),
                            //     TextUnidad: unidadMedida,
                            //     Ganancia: 0,
                            //     Importe: 0,
                            //     IdPaquetePromocional: null
                            // }
                            console.log(data[i]);
                            const nuevoArticulo = crearObjetoArticulo(data[i], $tipoArticulo = 'grupoFrontend');
                            articuloEnTablaDom({ nuevoArticulo, id: data[i]["IdArticulo"], descripcion: data[i]["NombreArticulo"], idTipoMoneda: data[i]["idTipoMonedaItems"], idUnidadMedida: data[i]["IdUnidadMedida"], stock: data[i]["Stock"], accion: 'crearArticuloFrontend' });
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
            const idTipoMoneda = $(`#idTipoMoneda-${id}`).val()
            // Creando el objeto articulo Servicio
            // const nuevoArticulo = {
            //     IdCotizacion: '',
            //     IdCliente: '',
            //     IdArticulo: 0,
            //     Codigo: `PAQ-${id}`,
            //     Detalle: '',
            //     Descuento: 0,
            //     VerificaTipo: 4,
            //     Cantidad: 1,
            //     CantidadReal: 1,
            //     PrecioUnidadReal: $(`#precio-${id}`).text(),
            //     TextUnidad: 'ZZ',
            //     Ganancia: 0,
            //     Importe: 0,
            //     IdPaquetePromocional: id
            // }
            const nuevoArticulo = crearObjetoArticulo(id, $tipoArticulo = 'paqueteFrontend');
            articuloEnTablaDom({ nuevoArticulo, id, descripcion, idTipoMoneda, accion: 'crearArticuloFrontend' });
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
            // const nuevoArticulo = {
            //     IdCotizacion: '',
            //     IdCliente: '',
            //     IdArticulo: id,
            //     Codigo: `PRO-${id}`,
            //     Detalle: '',
            //     Descuento: redondearAnumero(descuento),
            //     VerificaTipo: 1,
            //     Cantidad: cantidad == 0 ? 1 : redondearAnumero(cantidad),
            //     CantidadReal: 1,
            //     PrecioUnidadReal: redondearAnumero(precio),
            //     TextUnidad: unidadMedida,
            //     Ganancia: 0,
            //     Importe: 0,
            //     IdPaquetePromocional: null
            // }
            const nuevoArticulo = crearObjetoArticulo(id, $tipoArticulo = 'productoFrontend');
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
            // const nuevoArticulo = {
            //     IdCotizacion: '',
            //     IdCliente: '',
            //     IdArticulo: id,
            //     Codigo: `SER-${id}`,
            //     Detalle: '',
            //     Descuento: redondearAnumero(descuento),
            //     VerificaTipo: 4,
            //     Cantidad: cantidad == 0 ? 1 : redondearAnumero(cantidad),
            //     CantidadReal: 1,
            //     PrecioUnidadReal: redondearAnumero(precio),
            //     TextUnidad: unidadMedida,
            //     Ganancia: 0,
            //     Importe: 0,
            //     IdPaquetePromocional: null
            // }
            const nuevoArticulo = crearObjetoArticulo(id, $tipoArticulo = 'servicioFrontend');
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
        productoAgregado = cotizacion.agregarArticuloDesdeFronted(nuevoArticulo, idTipoMoneda);
    } else {
        productoAgregado = cotizacion.agregarArticuloDesdeBackend(nuevoArticulo);
    }

    let step = '', max = 9999, boton = '', readonlyDetalle = '', readonlyPrecio = '', readonlyCantidad = '', dato = 'SER', bloquearEntradaCantidad = 'true', botonEliminar = '<button class="btn btn-primary p-1 btnEliminarItem"><i class="list-icon material-icons fs-16">clear</i></button>';


    if (nuevoArticulo.Codigo.includes('PRO')) {
        step = (idUnidadMedida == 1) ? step : '0.05';
        bloquearEntradaCantidad = (idUnidadMedida == 1) ? 'false' : 'true';
        dato = 'PRO';
        // readonlyPrecio = obtenerValoresDom.inputEditarPrecio() == 1 ? '' : 'readonly';
    }

    if (nuevoArticulo.Codigo.includes('PAQ')) {
        readonlyPrecio = 'readonly';
        readonlyCantidad = 'readonly';
        boton = `<button onclick="verDetallePaquetePromocional(${id},${idTipoMoneda})" class="btn btn-primary ml-1 p-1"><i class="list-icon material-icons fs-16">visibility</i></button>`;
        dato = 'PAQ';
    }

    // validacion para cotizacion en estado Proceso
    if (obtenerValoresDom.inputIdEstadoCotizacion() == 2) {
        max = (nuevoArticulo.Codigo.includes('PRO')) ? parseFloat(stock) : max;
    }
    // validacion para cotizacion en estado Finalizado
    if (obtenerValoresDom.inputIdEstadoCotizacion() == 3) {
        readonlyDetalle = 'readonly';
        readonlyPrecio = 'readonly';
        readonlyCantidad = 'readonly';
        botonEliminar = '';
    }

    const fila = `
        <tr data-id="${id}" data-etiqueta="${dato}">
            <td style="width:600px">
                ${productoAgregado.Codigo}
            </td>
            <td class="ajustar-texto">${descripcion}</td>
            <td>
                <input id="detalle${id}" type="text" value="${productoAgregado.Detalle}" ${readonlyDetalle}>
            </td>
            <td>${productoAgregado.TextUnidad}</td>
            <td>
                <input id="precio${id}" class="inputModificarImporte" type="number" value="${redondearAcadena(productoAgregado.PrecioUnidadReal)}" step="any" style="width:90px" ${readonlyPrecio}>
            </td>
            <td>
                <input id="descuento${id}" class="inputModificarImporte inputModificarDescuento" data-tipo-moneda="${idTipoMoneda}" step="any" type="number" min="0" value="${redondearAcadena(productoAgregado.Descuento)}" style="width:90px">
            </td>
            <td>
                <input id="cantidad${id}" class="inputModificarImporte" onkeydown="return ${bloquearEntradaCantidad};"  step="${step}" ${readonlyCantidad} type="number" min="1" max="${max}" value="${redondearAcadena(productoAgregado.Cantidad)}" style="width:60px">
            </td>
            <td>
                <input id="importe${id}" readonly type="number" value="${redondearAcadena(productoAgregado.Importe)}" step="any" style="width:100px">
            </td>
            <td>
                ${botonEliminar} ${boton}
            </td>
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
    $("#ventaSolesDolares").prop("checked", false);
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
    if (obtenerValoresDom.selectTipoVenta() == 1) {
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
            IdCotizacion: obtenerValoresDom.inputIdCotizacion(),
            IdCliente: obtenerValoresDom.inputIdCliente(),
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
        const idArticulo = item.IdArticulo == 0 ? item.IdPaquetePromocional : item.IdArticulo;
        const detalle = $(`#detalle${idArticulo}`).val();
        const idCliente = $('#clientes').val();
        const nuevoObjetoActualizar = {
            idArticulo: idArticulo,
            detalle: detalle,
            idCliente: idCliente
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
                if (obtenerValoresDom.selectTipoVenta() == 2) {
                    precio = redondeo(parseFloat(data[i]['Precio'] / variablesJs.igv));
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

function crearObjetoArticulo(datos, tipoArticulo) {

    let idCotizacion = '';
    let idCliente = '';
    let idArticulo = '';
    let codigo = '';
    let detalle = '';
    let descuento = '';
    let verificaTipo = '';
    let cantidad = '';
    let cantidadReal = 1;
    let precioUnidadReal = ''
    let textUnidad = '';
    let ganancia = 0;
    let importe = 0;
    let idPaquetePromocional = null;

    if (tipoArticulo == 'productoFrontend') {
        idArticulo = datos;
        textUnidad = $('#p3-' + datos).val();
        precioUnidadReal = $('#p2-' + datos).text();
        cantidad = $('#p4-' + datos).val();
        descuento = $('#p5-' + datos).val();
        codigo = `PRO-${datos}`;
        verificaTipo = 1;
    }

    if (tipoArticulo == 'servicioFrontend') {
        idArticulo = datos;
        textUnidad = 'ZZ';
        precioUnidadReal = $('#s2-' + datos).text();
        cantidad = $('#s5-' + datos).val();
        descuento = $('#s3-' + datos).val();
        codigo = `SER-${datos}`;
        verificaTipo = 4;
    }

    if (tipoArticulo == 'grupoFrontend') {
        idArticulo = datos["IdArticulo"];
        codigo = datos['idTipoItems'] == 1 ? `PRO-${datos["IdArticulo"]}` : `SER-${datos["IdArticulo"]}`;
        cantidad = datos["CantidadArticulo"];
        precioUnidadReal = datos["Precio"];
        verificaTipo = datos['idTipoItems'] == 1 ? 1 : 4;
        textUnidad = datos['idTipoItems'] == 1 ? datos["UM"] : 'ZZ';
    }

    if (tipoArticulo == 'paqueteFrontend') {
        idArticulo = 0;
        codigo = `PAQ-${datos}`;
        cantidad = 1;
        precioUnidadReal = $(`#precio-${datos}`).text();
        descuento = 0;
        textUnidad = 'ZZ';
        idPaquetePromocional = datos;
        verificaTipo = 4

    }

    if (tipoArticulo == 'articulosBackend') {
        idArticulo = datos["IdArticulo"];
        codigo = datos["Codigo"];
        detalle = datos["Detalle"];
        descuento = datos["Descuento"];
        verificaTipo = datos["VerificaTipo"];
        cantidad = datos["Cantidad"];
        cantidadReal = datos["CantidadReal"];
        precioUnidadReal = datos["PrecioUnidadReal"];
        textUnidad = datos["TextUnidad"];
        ganancia = datos["Ganancia"];
        importe = datos["Importe"];
        idPaquetePromocional = datos["IdPaquetePromocional"];
    }

    if (variablesJs.operacionCrud == 'editar') {
        idCotizacion = obtenerValoresDom.inputIdCotizacion();
        idCliente = obtenerValoresDom.inputIdCliente();
    }

    return {
        IdCotizacion: idCotizacion,
        IdCliente: idCliente,
        IdArticulo: idArticulo,
        Codigo: codigo,
        Detalle: detalle == null ? '' : detalle,
        Descuento: redondearAnumero(descuento),
        VerificaTipo: verificaTipo,
        Cantidad: cantidad == 0 || cantidad == null ? 1 : redondearAnumero(cantidad),
        CantidadReal: cantidadReal,
        PrecioUnidadReal: redondearAnumero(precioUnidadReal),
        TextUnidad: textUnidad,
        Ganancia: ganancia,
        Importe: importe,
        IdPaquetePromocional: idPaquetePromocional
    }
}