$(document).ready(function () {
    $('#modalDibujarFirma').css('display', 'none');
});

$('body').on('shown.bs.modal', '#modalEnviarWhatsApp', function () {
    $('input:visible:enabled:first', this).focus();
});

document.addEventListener("click", function (event) {
    if (event.target.matches('.btnEnviarWhatsApp *') || event.target.matches('.btnImprimirPdf') || event.target.matches('.btnImprimirPdf *') || event
        .target.matches('.btnDescargarPdf') || event.target.matches('.btnDescargarPdf *')) {
        event.preventDefault();
        let botonGenerar = '';
        if (event.target.tagName == 'I' || event.target.tagName == 'IMG') {
            botonGenerar = event.target.parentNode;
        } else {
            botonGenerar = event.target;
        }
        hasFirma(botonGenerar);
    }
})

function hasFirma(botonGenerar) {
    if (botonGenerar.dataset.contienefirma == 'si') {
        if (botonGenerar.dataset.enlace == 'whatsApp') {
            $(".formularioEnviarWhatsApp").attr("action",
                'http://44.231.5.212/public/vehicular/control-calidad/documento/' + botonGenerar
                    .dataset.id + '/whatsApp');
            if (botonGenerar.dataset.celular != '') {
                $('#numeroCelular').val(botonGenerar.dataset.celular);
                $('#seccionClienteConCelular').removeClass('d-none');
                $('#seccionMensajeClienteConCelular').removeClass('d-none');
                $('#seccionClienteSinCelular').addClass('d-none');
            } else {
                $('#numeroCelular').val('');
                $('#seccionClienteConCelular').addClass('d-none');
                $('#seccionMensajeClienteConCelular').addClass('d-none');
                $('#seccionClienteSinCelular').removeClass('d-none');
            }
            return $('#modalEnviarWhatsApp').modal('show');
        }
        return redireccionarVista(botonGenerar.dataset.id, botonGenerar.dataset.enlace);
    }


    if (botonGenerar.dataset.imagenfirma == 'tengoFirma') {
        $(".formularioEnviarWhatsApp").attr("action",
            'http://44.231.5.212/public/vehicular/control-calidad/documento/' + botonGenerar
                .dataset.id + '/whatsApp');
        $('#numeroCelular').val(botonGenerar.dataset.celular);
        $('#seccionClienteConCelular').removeClass('d-none');
        $('#seccionMensajeClienteConCelular').removeClass('d-none');
        return $('#modalEnviarWhatsApp').modal('show');
    } else {
        Swal.fire({
            title: 'Desea agregar firma Digital?',
            text: "El control de calidad, no contiene la firma de conformidad del cliente.",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Aceptar',
            cancelButtonText: 'Cancelar',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                $('#inputDescripcionEnlace').val(botonGenerar.dataset.enlace);
                $('#inputIdControl').val(botonGenerar.dataset.id);
                $('#numeroCelular').val(botonGenerar.dataset.celular);
                if (botonGenerar.dataset.enlace == 'whatsApp') {
                    $(".formularioEnviarWhatsApp").attr("action",
                        'http://44.231.5.212/public/vehicular/control-calidad/documento/' + botonGenerar
                            .dataset.id + '/whatsApp');
                    if (botonGenerar.dataset.celular != '') {
                        $('#seccionClienteConCelular').removeClass('d-none');
                        $('#seccionMensajeClienteConCelular').removeClass('d-none');
                        $('#seccionClienteSinCelular').addClass('d-none');
                    } else {
                        $('#seccionClienteConCelular').addClass('d-none');
                        $('#seccionMensajeClienteConCelular').addClass('d-none');
                        $('#seccionClienteSinCelular').removeClass('d-none');
                    }
                }
                $('#modalDibujarFirma').modal('show');
            } else {
                $('#numeroCelular').val(botonGenerar.dataset.celular);
                if (botonGenerar.dataset.enlace == 'whatsApp') {
                    $(".formularioEnviarWhatsApp").attr("action",
                        'http://44.231.5.212/public/vehicular/control-calidad/documento/' + botonGenerar
                            .dataset.id + '/whatsApp');
                    if (botonGenerar.dataset.celular != '') {
                        $('#seccionClienteConCelular').removeClass('d-none');
                        $('#seccionMensajeClienteConCelular').removeClass('d-none');
                        $('#seccionClienteSinCelular').addClass('d-none');
                    } else {
                        $('#seccionClienteConCelular').addClass('d-none');
                        $('#seccionMensajeClienteConCelular').addClass('d-none');
                        $('#seccionClienteSinCelular').removeClass('d-none');
                    }
                    return $('#modalEnviarWhatsApp').modal('show');
                }

                if (botonGenerar.dataset.whatsapp == 'whatsapp') {
                    return $('#modalEnviarWhatsApp').modal('show');
                }
                $('#modalEnviarWhatsApp').modal('hide');
                redireccionarVista(botonGenerar.dataset.id, botonGenerar.dataset.enlace);
            }
        })
    }
}


function redireccionarVista(id, enlace) {
    var url = "{{ route('imprimirControlCalidad', [':id', ':enlace']) }}";
    url = url.replace(':id', id);
    url = url.replace(':enlace', enlace);
    // location.href = url;
    window.open(url, '_blank');
}

