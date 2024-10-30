// Mensaje de ERROR
function respuestaErrorAjax(mensaje) {
    swal("Registro Fallido", mensaje, "error")
}

function respuestaErrorValidacion(mensaje) {
    swal("Error", mensaje, "error")
}

// Mensaje INFORMATIVOS
function respuestaInfoAjax(titulo, mensaje) {
    swal(titulo, mensaje, "info")
}


// Mensaje SATISFACTORIO
function respuestaSuccesAjax(mensaje) {
    swal("Registro Exitoso!", mensaje, "success")
}

function respuestaSuccesAjaxConTiempo(titulo, mensaje, time) {
    swal(titulo, mensaje, {
        buttons: false,
        timer: time,
    });
}

function respuestaSuccesAjaxSinConfirmacion() {
    alert('datos');
}

function respuestaSuccesAjaxConConfirmacion(mensaje, url) {
    swal({
        title: "Registro Exitoso",
        text: mensaje,
        icon: "success",
        button: "Ok",
        closeOnClickOutside: false,
        closeOnEsc: false,
    })
        .then((Entendido) => {
            if (Entendido) {
                window.location = url;
            }
        });
}
