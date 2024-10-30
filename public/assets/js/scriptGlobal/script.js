const ocultarLoader = (idBtn) => {
    // $('#seccionLoader').addClass('d-none');
    // $('#btnTexto').removeClass('d-none');
    // $(idBtn).removeAttr('disabled', 'disabled');

    const accion = $(idBtn).data('accion');
    $(`#seccionLoader${accion}`).addClass('d-none');
    $(`#btnTexto${accion}`).removeClass('d-none')
    $(`${idBtn}`).removeAttr('disabled', 'disabled');
}

const bloquearBoton = () => {
    $('#seccionLoader').addClass('d-none');
    $('#btnTexto').removeClass('d-none');
}

const mostrarLoader = (idBtn) => {
    // $('#seccionLoader').removeClass('d-none');
    // $('#btnTexto').addClass('d-none');
    // $(idBtn).attr('disabled', 'disabled');

    const accion = $(idBtn).data('accion');
    $(`#seccionLoader${accion}`).removeClass('d-none');
    $(`#btnTexto${accion}`).addClass('d-none')
    $(`${idBtn}`).attr('disabled', 'disabled');
};

const ocultarBoton = (idBtn) => {
    $(idBtn).animate({
        opacity: 0
    }, 'slow');
};

// Codigo para validar la clave supervisor
let urlEditar = '';
let urlEliminar = '';
let descripcionEvento = '';
let idArticuloEliminar = '';

const wasClickedButton = (elementoPadre) => {
    $("#modalValidarClaveSupervisor").modal('show');
    $('#password').removeClass('border-danger');
    $('#textoMensaje').empty();
    urlEditar = elementoPadre.data('rutaEditar');
}
$(document).on('click', function (e) {
    if (e.target.matches('.btnEditarConClaveSupervisor') || e.target.matches('.btnEditarConClaveSupervisor *')) {
        e.preventDefault();
        const elementoPadre = $(e.target).closest('.btnEditarConClaveSupervisor');
        wasClickedButton(elementoPadre);
    }

    if (e.target.matches('.btnEliminarConClaveSupervisor') || e.target.matches('.btnEliminarConClaveSupervisor *')) {
        e.preventDefault();
        const elementoPadre = $(e.target).closest('.btnEliminarConClaveSupervisor');
        wasClickedButton(elementoPadre);
        descripcionEvento = elementoPadre.data('descripcionEvento');
        idArticuloEliminar = elementoPadre.data('idArticuloEliminar');
    }


    // La ruta validar clave supervisor-> "{{ route('validarClaveSupervisor') }}"; se agrego en un dataset en el boton del modal
    // la condicion url sirve para verificar si despues de ingresar la clave supervisor el evento de editar o eliminar direcciona a otra vista
    // Si el evento de editar o eliminar no direccionan a otra vista se llama a la funcion isValidacionClaveSupervisorSuccess, esa funcion debe llamarse desde la vista e ingresar su respectiva logica.
    // Para cada boton(editar y eliminar) que llame al modalSupervisor, debe agregar una clase para editar .btnEditarConClaveSupervisor y para eliminar .btnEliminarConClaveSupervisor, si se requiere direccionar a otra vista agregar los data-set(routeName).
    // Si se realiza el evento de editar y eliminar en forma MASIVA O UNICA agregar el data-set ->  data-descripcion-Evento="eliminacionMasiva" y dentro de la funcion isValidacionClaveSupervisorSuccess, hacer las validacion si es masiso o unico dependediendo del caso ingresar la logica

    if (e.target.matches('#btnValidarClave') || e.target.matches('#btnValidarClave *')) {
        const elementoPadre = $(e.target).closest('#btnValidarClave');
        const uriValidarClaveSupervisor = elementoPadre.data('rutaValidarSupervisor')
        mostrarLoader('#btnValidarClave');
        $('#textoMensaje').empty();
        $('#password').removeClass('border-danger');
        var password = $('#password').val();
        if (password !== "") {
            $.ajax({
                type: "get",
                url: uriValidarClaveSupervisor,
                data: {
                    'password': password
                },
                success: function (data) {
                    if (data[0] == 'Success') {
                        if (typeof urlEditar !== 'undefined') {
                            $("#modalValidarClaveSupervisor").modal('hide');
                            ocultarBoton('#btnValidarClave');
                            window.location = urlEditar;
                            $('#password').val("");
                            $('#btnActualizar').removeClass('d-none');
                        }

                        if (typeof urlEliminar !== 'undefined') {

                        }
                        isValidacionClaveSupervisorSuccess();

                    } else {
                        ocultarLoader('#btnValidarClave');
                        $('#textoMensaje').text('Error la clave no coincide')
                        $('#password').val("");
                        $('#password').focus();
                        $('#password').addClass('border-danger');
                    }
                }
            })
        } else {
            ocultarLoader('#btnValidarClave');
            $('#textoMensaje').text('Por favor ingrese la clave')
            $('#password').addClass('border-danger');
            $('#password').focus();
        }
    }
})

// Funcion para validar que un input number acepte solo numeros entero o  decimal
const isNumeroEnteroOdecimal = (event) => {
    var expReg = new RegExp("^[0-9.]+$");
    var valueTecla = String.fromCharCode(event.charCode);
    if (!expReg.test(valueTecla)) {
        event.preventDefault();
        return false;
    }
}
// Funcion para validar que un input number acepte solo numeros entero
const isNumeroEntero = (event) => {
    var expReg = new RegExp("^[0-9.]+$");
    var valueTecla = String.fromCharCode(event.charCode);
    if (!expReg.test(valueTecla)) {
        event.preventDefault();
        return false;
    }
}

// Codigo para enviar mensaje a whatsApp desde el index
$(document).on('click', function (e) {
    if (e.target.matches('.btnWhatsApp') || e.target.matches('.btnWhatsApp *')) {
        e.preventDefault();
        $('#seccionClienteConCelular').addClass('d-none');
        $('#seccionClienteSinCelular').addClass('d-none');
        $('#numeroCelular').val('');
        const numeroCelular = $(e.target.parentNode).data("celular").toString();
        const routeName = $(e.target.parentNode).data('routename');
        if (numeroCelular != null) {
            if (numeroCelular.startsWith('9')) {
                $('#seccionClienteConCelular').removeClass('d-none');
                $('#numeroCelular').val(numeroCelular);
            } else {
                $('#seccionClienteSinCelular').removeClass('d-none');
            }
        }
        $('#formularioEnviarWhatsApp').attr('action', routeName);
        $('#modalEnviarWhatsApp').modal('show')
    }
});

$('#btnEnviarWhatsApp').click((event) => {
    event.preventDefault();
    $('#modalEnviarWhatsApp').modal('hide');
    setTimeout(() => {
        $('#formularioEnviarWhatsApp').submit();
    }, 200);
})

// Codigo para posicionar el focus en el primer input del modal
$('.modal').on('shown.bs.modal', function () {
    $("input:text:visible:first").focus();
})