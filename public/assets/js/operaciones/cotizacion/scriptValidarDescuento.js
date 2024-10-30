$(document).ready(function () {
    $(document).on('change', (e) => {
        const inputModificarDescuento = $(e.target).closest('.inputModificarDescuento');

        if (inputModificarDescuento.length > 0 && isAdministrador !== 'Administrador') {
            const tipoMoneda = $("#tipoMoneda").val();
            validarInputDescuento(inputModificarDescuento, tipoMoneda)
        }
    });

    const validarInputDescuento = (inputModificarDescuento, tipoMoneda) => {
        const descuentoActual = inputModificarDescuento.val();
        if (descuentoMaximoSoles !== null && tipoMoneda == 1) {
            if (parseFloat(descuentoActual) > parseFloat(descuentoMaximoSoles)) {
                inputModificarDescuento.val(descuentoMaximoSoles);
                calcularNuevoImporte(inputModificarDescuento);
                swal("Descuento Máximo por items", `Ha excedido el límite del descuento máximo(${descuentoMaximoSoles}) por items en soles, establecido por el administrador.`, "warning");
            }
        }
        if (descuentoMaximoDolares !== null && tipoMoneda == 2) {
            if (parseFloat(descuentoActual) > parseFloat(descuentoMaximoDolares)) {
                inputModificarDescuento.val(descuentoMaximoDolares);
                calcularNuevoImporte(inputModificarDescuento);
                swal("Descuento Máximo por items", `Ha excedido el límite del descuento máximo(${descuentoMaximoDolares}) por items en dólares, establecido por el administrador.`, "warning");
            }
        }
    }
})
