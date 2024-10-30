$('#btnHabilitarFirma').click(() => {
    $('#panelDigital').removeClass('disabled-elemento');
    $('#btnBloquearFirma').removeClass('d-none');
    $('#btnHabilitarFirma').addClass('d-none');
})
$('#btnBloquearFirma').click(() => {
    $('#panelDigital').addClass('disabled-elemento');
    $('#btnBloquearFirma').addClass('d-none');
    $('#btnHabilitarFirma').removeClass('d-none');
    signatureFirma.clear()
})

let btnLimpiar = document.getElementById("btnLimpiarFirma");
let panelDigital = document.getElementById("panelDigital");
let signatureFirma = new SignaturePad(panelDigital, {
    penColor: 'rgb(0, 0, 0)'
});

function resizePanelDigital() {
    var ratio = Math.max(window.devicePixelRatio || 1, 1);
    panelDigital.width = panelDigital.offsetWidth * ratio;
    panelDigital.height = panelDigital.offsetHeight * ratio;
    panelDigital.getContext("2d").scale(ratio, ratio);
    signatureFirma.clear();
}
resizePanelDigital();

btnLimpiar.addEventListener('click', function () {
    signatureFirma.clear()
});


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
                generarImagenFirma();
                document.formControlCalidad.submit();
            }
        });
})

function generarImagenFirma() {
    let inputCodigoFirma = document.getElementById("imagenCodigoFirma");
    let dataFirma = signatureFirma.toData();
    if (dataFirma.length != 0) {
        var trimmedCanvas = trimCanvas(panelDigital);
        var dataCodigoImagenFirma = trimmedCanvas.toDataURL('image/png');
        inputCodigoFirma.setAttribute('value', dataCodigoImagenFirma);
    }
}

function obtenerPrioridadControl() {
    let cantidadRadioEstadoSatisfactorio = 0;
    let cantidadRadioEstadoAtencionFutura = 0;
    let cantidadRadioEstadoAtencionInmediata = 0;
    let estado = 'Sin Inspeccion';
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