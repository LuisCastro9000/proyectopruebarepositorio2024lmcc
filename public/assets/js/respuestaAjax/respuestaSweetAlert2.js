
// Mensaje de ERROR
function respuestaErrorAjax(mensaje) {
    Swal.fire({
        icon: 'error',
        title: 'Error al enviar los Datos',
        text: mensaje,
    })
}

// Mensaje SATISFACTORIO
function respuestaSuccesAjax(mensaje) {
    Swal.fire({
        position: 'center',
        icon: 'success',
        text: mensaje,
        showConfirmButton: false,
        timer: 1500
    })
}

// Mensaje INFORMATIVOS

function respuestaInfoValidacion(titulo, mensaje, textBoton, url) {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'info',
        showDenyButton: true,
        confirmButtonText: textBoton,
        denyButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = url;
        }
    })
}

function respuestaInfoMensaje(titulo, mensaje) {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'info',
        confirmButtonText: 'Entendido'
    })
}

function respuestaInfoAjax(titulo, mensaje) {
    Swal.fire({
        icon: 'info',
        title: titulo,
        text: mensaje,
        confirmButtonText: 'Entendido'
    })
}

// MENSAJES DE PREGUNTA o CONFIRMACION
function mensajeDeConfimacion(titulo, mensaje) {
    Swal.fire({
        icon: 'question',
        title: titulo,
        text: mensaje,
        confirmButtonText: 'Entendido'
    })
}

function mensajeDeConfirmacionConSubmit(titulo, mensaje, nombreBoton, formulario) {
    Swal.fire({
        title: titulo,
        text: mensaje,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: nombreBoton,
        cancelButtonText: 'Cancelar',
        focusCancel: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            $('form').submit();
        }
    })
}

function mensajeDeConfirmacionConUrl() {
    Swal.fire({
        title: 'Desea Eliminar?',
        text: 'Si elimina el registro ya no podrá recuperarse',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Entendido',
        cancelButtonText: 'Cancelar',
        focusCancel: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'url';
        }
    })
}

// MENSAJES DE ADVENTENCIA

function mensajeDeAdvetanciaConContenido() {
    Swal.fire({
        title: 'Desea Eliminar?',
        text: 'Si elimina el registro ya no podrá recuperarse',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Entendido',
        cancelButtonText: 'Cancelar',
        focusCancel: false,
        reverseButtons: true
    }).then((result) => {
        if (result.isConfirmed) {
            window.location = 'url';
        }
    })
}