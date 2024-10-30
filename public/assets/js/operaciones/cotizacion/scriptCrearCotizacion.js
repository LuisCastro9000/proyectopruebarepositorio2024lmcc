// ====== VARIABLES GLOBALES =======
let arrayArticulosPaquetePromo = [];
let arrayGrupos = [];
let tipoVenta = $('#tipoVenta').val();
let tipoMonedaCotizacion = $("#tipoMoneda").val();
let checkCotizacionSolesConDolares = 0;
let valorCambioVentas = 0;
let valorCambioCompras = 0;
let tipoCotizacion = $('.radioTipoCotizacion').val();
let editarPrecio = $("#editarPrecio").val();

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
        this.Exonerada = null;
        this.Trabajos = null;
        this.Observacion = null;
        this.Estado = 1;
        this.MantenimientoActual = null;
        this.ProximoMantenimiento = null;
        this.PeriodoProximoMantenimiento = null;
        this.SubTotal = 0;
        this.Igv = 0;
        this.Total = 0;
        this.Estado = null;
        this.articulos = [];
    }

    calcularImporte(precio, cantidad, descuento) {
        const resultado = (precio * cantidad) - descuento;
        return redondearAnumerico(resultado);
    }

    convertirPrecio(precio, idTipoMonedaArticulo) {
        if (tipoVenta == 2) {
            precio = redondearAnumerico(precio / igv);
        }
        if (checkCotizacionSolesConDolares == 1) {
            if (tipoMonedaCotizacion == 1 && idTipoMonedaArticulo == 2) {
                precio = parseFloat(precio) * parseFloat(valorCambioVentas);
            }
            if (tipoMonedaCotizacion == 2 && idTipoMonedaArticulo == 1) {
                precio = parseFloat(precio) / parseFloat(valorCambioCompras);
            }
        }
        return redondearAnumerico(precio);
    }

    agregarArticulo(idArticulo, codigo, cantidad, precioVenta, descuento, idTipoMonedaArticulo, textUnidad, idPaquetePromocional, verificaTipo) {
        const nuevoPrecio = this.convertirPrecio(precioVenta, idTipoMonedaArticulo);
        const importe = this.calcularImporte(nuevoPrecio, cantidad, descuento);
        const articulo = {
            IdCliente: 0,
            IdArticulo: idArticulo,
            Codigo: codigo,
            Detalle: null,
            Descuento: descuento,
            VerificaTipo: verificaTipo,
            Cantidad: cantidad,
            CantidadReal: 1,
            PrecioUnidadReal: nuevoPrecio,
            TextUnidad: textUnidad,
            Ganancia: 0,
            Importe: importe,
            IdPaquetePromocional: idPaquetePromocional,
        };
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

    updateDetalleArticulo(idArticulo, detalle, idCliente) {
        const articuloEncontrado = this.articulos.find((item => item.IdArticulo === idArticulo || item.IdPaquetePromocional === idArticulo));
        if (articuloEncontrado) {
            articuloEncontrado.Detalle = detalle;
            articuloEncontrado.IdCliente = idCliente;
        }
    }

    actualizaTotalesCotizacion() {
        this.Total = this.articulos.reduce((acumulador, { Importe }) => acumulador + Importe, 0);
        this.SubTotal = tipoVenta == 1 ? this.Total / igv : this.Total;
        this.Igv = tipoVenta == 1 ? this.Total - this.SubTotal : 0;
        const descuento = this.articulos.reduce((acumulador, { Descuento }) => acumulador + Descuento, 0);
        // Devolver las propiedades actualizadas junto con la nueva propiedad "descuento"
        return { Total: redondearAcadena(this.Total), SubTotal: redondearAcadena(this.SubTotal), Igv: redondearAcadena(this.Igv), Descuento: redondearAcadena(descuento) };
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
        this.IdEstadoCotizacion = 1;
        this.IdTipoAtencion = datos.IdTipoAtencion;
        this.TipoCotizacion = datos.TipoCotizacion;
        this.FechaCreacion = datos.FechaCreacion;
        this.FechaFin = datos.FechaFin;
        this.Campo0 = datos.Campo0;
        this.Campo1 = datos.Campo1;
        this.Campo2 = datos.Campo2;
        this.TipoVenta = datos.TipoVenta;
        this.Exonerada = datos.Exonerada;
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
            arrayGrupos.push(parseInt((id)));
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
    var valorCambioVentas = $("#valorCambioVentas").val();
    var valorCambioCompras = $("#valorCambioCompras").val();
    if ((checkCotizacionSolesConDolares == 1 || tipoMonedaCotizacion == 2) && (parseFloat(
        valorCambioVentas) == 0 || parseFloat(valorCambioCompras) == 0)) {
        $(".bs-modal-lg-paquetesPromocionales-soles").modal("hide");
        $(".bs-modal-lg-paquetesPromocionales-dolares").modal("hide");
        $("#tipoCambio").modal("show");
        // =====================================
        hideIconoCandado(`PAQ-${id}`);
        // =====================================
    } else {
        $.ajax({
            type: 'GET',
            url: 'obtener-items-paquete-promocional',
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
                } else {
                    data.forEach((articulo) => {
                        // =====================================
                        showIconoCandado(articulo.CodigoArticulo);
                        // =====================================
                    })
                }
                // Funcion para agregar los articulos del paquete promocional, para luego verificar si ya se encuentra agregar dentro del detalle de la cotizacion.Articulos
                crearArrayArticulosPaquetePromo(data);
                const nombre = $('#nombre-' + id).text();
                const precio = $('#precio-' + id).text();
                const costo = $('#costo-' + id).val();
                const idTipoMoneda = $('#idTipoMoneda-' + id).val();
                const unidadMedida = 'ZZ';
                const descuento = 0;
                const cantidad = 1;
                servicioEnTablaDom(id, nombre, unidadMedida, precio, cantidad, descuento, idTipoMoneda, 'PAQ');
            }
        })
    }
}

function handleClickBotonProducto(id) {
    let arrayItemsDuplicados = arrayArticulosPaquetePromo.filter(item => item.IdArticulo == id);
    if (arrayItemsDuplicados.length >= 1) {
        swal("Producto ya en lista!", "Este producto ya se encuentra agregado en lista.", "info");
    } else {
        var valorCambioVentas = $("#valorCambioVentas").val();
        var valorCambioCompras = $("#valorCambioCompras").val();
        if ((checkCotizacionSolesConDolares == 1 || tipoMonedaCotizacion == 2) && (parseFloat(valorCambioVentas) == 0 || parseFloat(
            valorCambioCompras) == 0)) {
            $(".bs-modal-lg-productos-soles").modal("hide");
            $(".bs-modal-lg-productos-dolares").modal("hide");
            $("#tipoCambio").modal("show");
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
                productoEnTablaDom(id, descripcion, unidadMedida, precio, cantidad, descuento, idUnidadMedida, idTipoMoneda, 'PRO');
                return;
            }
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
            var valorCambioVentas = $("#valorCambioVentas").val();
            var valorCambioCompras = $("#valorCambioCompras").val();
            if ((checkCotizacionSolesConDolares == 1 || tipoMonedaCotizacion == 2) && (parseFloat(valorCambioVentas) == 0 ||
                parseFloat(valorCambioCompras) == 0)) {
                $(".bs-modal-lg-productos-soles").modal("hide");
                $(".bs-modal-lg-productos-dolares").modal("hide");
                $("#tipoCambio").modal("show");
            } else {
                $('#total').val('');
                $('#exonerada').val('');
                var descripcion = $('#s1-' + id).text();
                var unidadMedida = 'ZZ';
                var precio = $('#s2-' + id).text();
                var cantidad = $('#s5-' + id).val();
                var descuento = $('#s3-' + id).val();
                var costo = $('#s4-' + id).val();
                var idTipoMoneda = $('#s7-' + id).val();
                // =====================================
                showIconoCandado(`SER-${id}`);
                // =====================================
                servicioEnTablaDom(id, descripcion, unidadMedida, precio, cantidad, descuento, idTipoMoneda, 'SER')
            }
        }
    }
}


function productoEnTablaDom(id, descripcion, unidadMedida, precio, cantidad, descuento, idUnidadMedida, idTipoMoneda, etiqueta) {

    if (parseFloat(descuento) >= parseFloat(precio)) {
        alert("El descuento tiene que ser menor que el precio");
    } else {
        if (parseFloat(cantidad) == 0) {
            cantidad = 1;
        }
        if (idUnidadMedida == 1) {
            step = '';
            bandInput = 'false';
        } else {
            step = '0.05';
            bandInput = 'true';
        }

        var readonlyPrecio;
        if (editarPrecio == 1) {
            readonlyPrecio = '';
        } else {
            readonlyPrecio = 'readonly';
        }
        const articuloAgregado = cotizacion.agregarArticulo(id, `PRO-${id}`, redondearAnumerico(cantidad), redondearAnumerico(precio), redondearAnumerico(descuento), idTipoMoneda, unidadMedida, idPaquetePromocional = null, verificaTipo = 1);
        actualizarTotalesEnVista();
        const fila = `
        <tr data-id="${id}" data-etiqueta="${etiqueta}">
            <td style="width:600px">
                ${etiqueta}-${id}
            </td>
            <td>${descripcion}</td>
            <td>
                <input id="detalle${id}" type="text">
            </td>
            <td>${unidadMedida}</td>
            <td>
                <input id="precio${id}" class="inputModificarImporte" type="number" value="${redondearAcadena(articuloAgregado.PrecioUnidadReal)}" step="any" style="width:90px" ${readonlyPrecio}>
            </td>
            <td>
                <input id="descuento${id}" class="inputModificarImporte inputModificarDescuento" data-tipo-moneda="${idTipoMoneda}" step="any" type="number" min="0" value="${redondearAcadena(articuloAgregado.Descuento)}" style="width:90px">
            </td>
            <td>
                <input id="cantidad${id}" class="inputModificarImporte" step="${step}" type="number" min="1" value="${redondearAcadena(cantidad)}" style="width:60px">
            </td>
            <td>
                <input id="importe${id}" readonly type="number" value="${redondearAcadena(articuloAgregado.Importe)}" step="any" style="width:100px">
            </td>
            <td>
                <button class="btn btn-primary p-1 btnEliminarItem"><i class="list-icon material-icons fs-16">clear</i></button>
            </td>
        </tr>`;
        $('#tablaAgregado tbody').append(fila);
    }
}
function servicioEnTablaDom(id, descripcion, unidadMedida, precio, cantidad, descuento, idTipoMoneda, etiqueta) {
    if (parseFloat(descuento) >= parseFloat(precio)) {
        alert("El descuento tiene que ser menor que el precio");
    } else {
        // Nuevo codigo
        let idArticulo = 0;
        let idPaquetePromocional = 0;
        if (etiqueta == "PAQ") {
            idArticulo = 0;
            idPaquetePromocional = id;
            readOnly = 'readonly';
            boton = '<button onclick="verDetallePaquetePromocional(' + id + ', ' +
                idTipoMoneda +
                ')" class="btn btn-primary ml-1 p-1"><i class="list-icon material-icons fs-16">visibility</i></button>'
        } else {
            idArticulo = id;
            idPaquetePromocional = null;
            boton = '';
            readOnly = '';
        }
        // Fin

        const articuloAgregado = cotizacion.agregarArticulo(idArticulo, `${etiqueta}-${id}`, redondearAnumerico(cantidad), redondearAnumerico(precio),
            redondearAnumerico(descuento), idTipoMoneda, unidadMedida, idPaquetePromocional, verificaTipo = 4);
        actualizarTotalesEnVista();
        const fila = `<tr data-id="${id}" data-etiqueta="${etiqueta}">
        <td style="width:600px">${etiqueta}-${id}</td>
        <td>${descripcion}</td>
        <td><input id="detalle${id}" type="text"></td>
        <td>${unidadMedida}</td>
        <td><input id="precio${id}" class="inputModificarImporte" step="any" type="number" value="${redondearAcadena(articuloAgregado.PrecioUnidadReal)}" style="width:90px" ${readOnly}>
        </td>
        <td><input id="descuento${id}" class="inputModificarImporte inputModificarDescuento" data-tipo-moneda="${idTipoMoneda}" step="any" type="number" min="0" value="${redondearAcadena(articuloAgregado.Descuento)}" style="width:90px"></td>
        <td style="width:80px"><input id="cantidad${id}" class="inputModificarImporte" step="any" ${readOnly} type="number" min="1" max="9999" value="${redondearAcadena(articuloAgregado.Cantidad)}" style="width:60px"></td>
        <td><input id="importe${id}"step="any" readonly type="number" value="${redondearAcadena(articuloAgregado.Importe)}" style="width:100px"></td>
        <td>
        <button  class="btn btn-primary p-1 btnEliminarItem"><i class="list-icon material-icons fs-16">clear</i></button>${boton}</td>
        </tr>`;
        $('#tablaAgregado tbody').append(fila);
    }
}


// ====== DELEGAR EVENTOS DE CHANGE A ELEMENTOS ESTATICOS ======
$(document).on('change', (e) => {
    const inputModificarImporte = e.target.closest('.inputModificarImporte');
    if (inputModificarImporte) {
        calcularNuevoImporte(inputModificarImporte);
    }
});

$('#tipoVenta').change(function () {
    tipoVenta = $(this).val();
    limpiarArticulos();
})

$('#tipoMoneda').change(function () {
    tipoMonedaCotizacion = $(this).val();
    limpiarArticulos();
})

$('#ventaSolesDolares').change(function () {
    var tipoMoneda = $("#tipoMoneda").val();
    if ($(this).is(':checked')) {
        $.ajax({
            type: 'get',
            // url: 'select-productos',
            url: routeObtenerTipoCambio,
            data: {
                'tipoMoneda': tipoMoneda
            },
            success: function (data) {
                checkCotizacionSolesConDolares = 1; // asignamos el valor de 1 a la varibale global, declarados en parte superior
                if (Object.entries(data).length > 0) {
                    $('#agregarArticuloSoles').removeClass('d-none');
                    $('#agregarGruposSoles').removeClass('d-none');
                    $('#agregarPaquetesPromocionalesSoles').removeClass('d-none');
                    $('#agregarArticuloDolares').removeClass('d-none');
                    $('#agregarGruposDolares').removeClass('d-none');
                    $('#agregarPaquetesPromocionalesDolares').removeClass('d-none');

                    $("#valorCambioCompras").val(data.TipoCambioCompras);
                    $("#valorCambioVentas").val(data.TipoCambioVentas);
                    valorCambioCompras = data.TipoCambioCompras;
                    valorCambioVentas = data.TipoCambioVentas;
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
        limpiarArticulos();
        checkCotizacionSolesConDolares = 0;
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

// Evento para obtener los clientes dentro del select Clientes
$(".radioTipoCotizacion").change(function () {
    $('#clientes').empty();
    $('#clientes').append('<option value="0">-</option>');
    $.showLoading({
        name: 'circle-fade',
    });
    valorOpction = $(this).val();
    tipoCotizacion = $(this).val();
    getCliente(-1, valorOpction);
});

// ====== DELEGAR EVENTOS DE CLICK A ELEMENTOS ESTATICOS ======
$('#btnTipoCambioCancelar').click(function () {
    $("#tipoMoneda").val("1");
    tipoMonedaCotizacion = 1;
    checkCotizacionSolesConDolares = 0;
    $("#ventaSolesDolares").prop("checked", false);
});

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
    const articuloActualizado = cotizacion.updateArticulo(id, redondearAnumerico(cantidad), redondearAnumerico(precio), redondearAnumerico(descuento));
    $(`#importe${id}`).val(redondearAcadena(articuloActualizado.Importe));
    actualizarTotalesEnVista();
}


// CREANDO UN ARRAY DE OBJETOS DE LOS ARTICULOS DE LOS PAUQETES PROMOCIONALES
const crearArrayArticulosPaquetePromo = (array) => {
    array.forEach(element => {
        arrayArticulosPaquetePromo.push({
            IdArticulo: element.IdArticulo,
            IdPaquetePromocional: element.IdPaquetePromocional,
            Codigo: element.CodigoArticulo
        })
    });
};


const capturarDetalleDom = () => {
    const idCliente = $('#clientes').val();
    cotizacion.articulos.map((item) => {
        let idArticulo = 0;
        if (item.IdArticulo == 0) {
            idArticulo = item.IdPaquetePromocional;
        } else {
            idArticulo = item.IdArticulo;
        }
        let detalle = $(`#detalle${idArticulo}`).val();
        if (detalle === '') {
            detalle = null;
        }
        cotizacion.updateDetalleArticulo(idArticulo, detalle, idCliente)
    })
}

const capturarDatosCotizacionDom = () => {
    const atencion = $('#atencion').val();
    let idVehiculo = '';
    let mantenimientoActual = null;
    let proximoMantenimiento = null;
    let periodoProximoMantenimiento = null;
    let idTipoAtencion = 5;
    if (atencion == 1 || atencion == 6) {
        mantenimientoActual = $("#mantenimientoActual").val();
        proximoMantenimiento = $("#proximoMantenimiento").val();
        periodoProximoMantenimiento = $("#periodoProximoMantenimiento").val();
    }
    if (tipoCotizacion == 2) {
        idTipoAtencion = $('#atencion').val()
    }
    const datosCotizacion = {
        IdCliente: $('#clientes').val(),
        IdTipoMoneda: $("#tipoMoneda").val(),
        IdCheckIn: $('#inventario').val(),
        IdOperario: $("#operario").val(),
        Serie: $("#serie").val(),
        Numero: $("#numero").val(),
        IdEstadoCotizacion: 1,
        IdTipoAtencion: idTipoAtencion,
        TipoCotizacion: tipoCotizacion,
        FechaCreacion: $("#datepicker2").val(),
        FechaFin: $("#datepicker").val(),
        Campo0: $('#clientes option:selected').data('idVehiculo'),
        Campo1: $("#campo1").val(),
        Campo2: $("#campo2").val(),
        TipoVenta: $('#tipoVenta').val(),
        Exonerada: $('#descuento').val(),
        Trabajos: $("#trabajos").val(),
        Observacion: $("#observacion").val(),
        Estado: 1,
        MantenimientoActual: mantenimientoActual,
        ProximoMantenimiento: proximoMantenimiento,
        PeriodoProximoMantenimiento: periodoProximoMantenimiento
    };
    cotizacion.setDatosCotizacion(datosCotizacion);
}
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
const redondearAnumerico = (numero) => {
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
