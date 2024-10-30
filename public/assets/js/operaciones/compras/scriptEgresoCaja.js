
$(document).ready(function () {
    let checkGuardarEgreso = 0;
    let tipoMoneda = $('#tipoMoneda').val();
    let totalMontoCaja = 0;
    let operacionCrud = '';

    $(function () {
        cambiarMontoCaja(tipoMoneda);
        if (operacionCrud === 'editar') {
            insertarDataTextArea();
        }
        mostrarMontoCajaEnPantalla();
    })

    $("#checkGuardarEgreso").click(function () {
        insertarDataTextArea();
        if ($(this).is(':checked')) {
            const resultado = isMontoCajaMayorQueCompra();
            if (resultado) {
                habilitarCheckGuardarEgreso();
                $('#checkGuardarEgreso').prop('checked', true);
            } else {
                $('#checkGuardarEgreso').prop('checked', false);
                mostrarMensajeError(
                    'No se puede registrar como egreso porque, el Total de la compra supero el Monto actual de Caja'
                );
            }
        } else {
            desabilitarCheckGuardarEgreso();
        }
    });

    $(document).on('click', function (e) {
        if (e.target.closest('.botonAgregarProducto') || e.target.closest('.btnEliminarArticulo')) {
            mostrarMontoCajaEnPantalla();
            if (checkGuardarEgreso && totalMontoCaja < compra.Total) {
                mostrarMensajeError(
                    'Se desactivo el Check registrar egreso, el Total de la compra supero el Monto actual de Caja'
                );
                desabilitarCheckGuardarEgreso();
            }
        }
    })
    $(document).on('change', function (e) {
        if (e.target.closest('.inputCantidad') || e.target.closest('.inputCosto')) {
            alert
            mostrarMontoCajaEnPantalla();
            if (checkGuardarEgreso && totalMontoCaja < compra.Total) {
                mostrarMensajeError(
                    'Se desactivo el Check registrar egreso, el Total de la compra supero el Monto actual de Caja'
                );
                desabilitarCheckGuardarEgreso();
            }
        }

        if (e.target.closest('#tipoPago') || e.target.closest('#cuentaBancaria')) {
            const valor = $(e.target).val();
            if (checkGuardarEgreso) {
                desabilitarCheckGuardarEgreso();
            }
            mostrarOcultarContenedorEgresoCaja(valor);
        }

        // Evento para el tipo de compra
        if (e.target.closest('#tipoCompra')) {
            reiniciarValores();
        }

        // Evento para el tipo de moneda
        if (e.target.closest('#tipoMoneda')) {
            reiniciarValores();
            tipoMoneda = $(e.target).val();
            cambiarMontoCaja(tipoMoneda);
        }
    })

    const cambiarMontoCaja = (tipoMoneda) => {
        if (parseInt(tipoMoneda) === 1) {
            $('#totalCaja').text(datosCaja.CajaTotalSoles);
            totalMontoCaja = datosCaja.CajaTotalSoles;
        }
        if (parseInt(tipoMoneda) === 2) {
            $('#totalCaja').text(datosCaja.CajaTotalDolares);
            totalMontoCaja = datosCaja.CajaTotalDolares;
        }
    }

    // Función para deshabilitar el check y reiniciar los valores
    const reiniciarValores = () => {
        if (checkGuardarEgreso) {
            desabilitarCheckGuardarEgreso();
        }
        $('#montoEgreso').text('0.00');
        $('#nuevoTotalCaja').text(totalMontoCaja);
    }

    const mostrarMensajeError = function (mensaje) {
        swal("Lo Sentimos", mensaje, "warning")
    };

    const desabilitarCheckGuardarEgreso = () => {
        checkGuardarEgreso = 0;
        $("#checkGuardarEgreso").prop('checked', false);
        $('#seccionCrearEgreso').addClass('bg-muted');
        $('.card-informativo').removeClass('bg-celeste');
        $('#inputDescripcionEgreso').prop('disabled', true);
    }

    const habilitarCheckGuardarEgreso = () => {
        checkGuardarEgreso = 1;
        $("#checkGuardarEgreso").prop('checked', true);
        $('#seccionCrearEgreso').removeClass('bg-muted');
        $('.card-informativo').addClass('bg-celeste');
        $('#inputDescripcionEgreso').prop('disabled', false);
    }

    const mostrarMontoCajaEnPantalla = () => {
        $('#montoEgreso').text(redondeo(compra.Total));
        const nuevoTotalCaja = parseFloat(redondeo(totalMontoCaja)) - parseFloat(redondeo(compra.Total));
        isMontoCajaMayorQueCompra() === true ? $('#nuevoTotalCaja').text(redondeo(nuevoTotalCaja)) : $(
            '#nuevoTotalCaja').text(redondeo(0));
    }

    const isMontoCajaMayorQueCompra = () => {
        return totalMontoCaja > compra.Total;
    }

    const mostrarOcultarContenedorEgresoCaja = (value) => {
        if (parseInt(value) == 0 || parseInt(value) == 1) {
            $('#seccionCrearEgreso').show();
        } else {
            $('#seccionCrearEgreso').hide();
        }
    }

    const insertarDataTextArea = () => {
        const tipoDocumento = $("#selectTipoComp option:selected").text().trim();
        const serie = $("#serie").val();
        const numero = $("#numero").val();
        const proveedor = eedor = $("#proveedores option:selected").text().trim();
        if (serie !== '' && numero !== '' && tipoDocumento !== '-' && proveedor !== '-') {
            $('#inputDescripcionEgreso').text(`${tipoDocumento} de compra ${serie}-${numero} de ${proveedor}`)
        } else {
            $('#inputDescripcionEgreso').text();
        }
    }

    $('#serie, #numero').on('blur', function () {
        insertarDataTextArea();
    })

    $('#selectTipoComp, #proveedores').on('change', function () {
        insertarDataTextArea();
    })

});