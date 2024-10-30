
// CODIGO PARA ACTIVAR Y DESACTIVAR INPUT MEDIDA DE FRENOS
document.addEventListener("click", function (event) {
    if (event.target.matches('.radioActivarMedida')) {
        const dataId = event.target.dataset.idcontrol;
        const inputMedida = document.getElementById('inputMedida' + dataId);
        if (inputMedida.hasAttribute('disabled')) {
            document.getElementById('inputMedida' + dataId).disabled = false;
        }
    }

    if (event.target.matches('.radioDesactivarMedida')) {
        const dataId = event.target.dataset.idcontrol;
        const inputMedidad = document.getElementById('inputMedida' + dataId);
        inputMedidad.disabled = true;
        inputMedidad.value = '';
    }
});


// FUNCION PARA OBTENER PROORIDAD DEL CONTROL DE CALIDAD EN LA VISTA DE CREAR Y EDITAR
function obtenerPrioridadControl() {
    let cantidadRadioEstadoSatisfactorio = 0;
    let cantidadRadioEstadoAtencionFutura = 0;
    let cantidadRadioEstadoAtencionInmediata = 0;
    let estado = 'Urgente';
    $('.radioEstadoSatisfactorio:checked').each(function () {
        cantidadRadioEstadoSatisfactorio++;
    });
    $('.radioEstadoAtencionFutura:checked').each(function () {
        cantidadRadioEstadoAtencionFutura++;
    });
    $('.radioEstadoAtencionInmediata:checked').each(function () {
        cantidadRadioEstadoAtencionInmediata++;
    });

    if (cantidadRadioEstadoSatisfactorio > cantidadRadioEstadoAtencionFutura && cantidadRadioEstadoSatisfactorio >
        cantidadRadioEstadoAtencionInmediata) {
        estado = 'Bajo';
    } else if (cantidadRadioEstadoAtencionFutura > cantidadRadioEstadoSatisfactorio &&
        cantidadRadioEstadoAtencionFutura > cantidadRadioEstadoAtencionInmediata) {
        estado = 'Medio';
    } else if (cantidadRadioEstadoAtencionInmediata > cantidadRadioEstadoSatisfactorio &&
        cantidadRadioEstadoAtencionInmediata > cantidadRadioEstadoAtencionFutura) {
        estado = 'Urgente'
    }
    $('#inputRadioEstadoPrioridad').val(estado)
}

// CODIGO PARA ALMACENAR EL CONTROL DE CALIDAD
$('#btnControlCalidad').click((event) => {
    event.preventDefault();
    swal({
        title: "Está conforme los datos?",
        icon: "warning",
        buttons: true,
        dangerMode: true,
    })
        .then((entendido) => {
            if (entendido) {
                obtenerPrioridadControl();
                formularioControlCalidad.submit();
            }
        });
})
