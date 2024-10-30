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

var btnLimpiar = document.getElementById("btnLimpiarFirma");
var panelDigital = document.getElementById("panelDigital");
var signatureFirma = new SignaturePad(panelDigital, {
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

document.getElementById('btnLimpiarFirma').addEventListener('click', function () {
    signatureFirma.clear()
});


// Codigo para cuando se dibuja la firma en un modal
$(document).ready(function () {
    $('#modalDibujarFirma').css('display', 'none');
});

$('#btnModalCrearFirma').click(() => {
    pegarCodigoFirmaEnValueInput();
})

function pegarCodigoFirmaEnValueInput() {
    let dataFirma = signatureFirma.toData();
    if (dataFirma.length != 0) {
        var trimmedCanvas = trimCanvas(panelDigital);
        var dataCodigoImagenFirma = trimmedCanvas.toDataURL('image/png');
        $('#inputCodigoFirma').val(dataCodigoImagenFirma)
    }
}