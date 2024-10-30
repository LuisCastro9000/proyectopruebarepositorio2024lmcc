
const utilidades = function () {
    return {
        showLoadingOverlay: function (mensaje = 'Por favor, espere un momento...') {
            const customElement = $("<div>", {
                "css": {
                    'position': "absolute",
                    "font-size": '20px',
                    "text-align": "center",
                    'color': "#f6851a",
                    'padding': '10px',
                },
                "html": mensaje
            });
            $.LoadingOverlay("show", {
                image: "",
                custom: customElement
            });
        },

        hideLoadingOverlay: function () {
            $.LoadingOverlay("hide");
        },

        validarClaveSupervisor: function (successCallback) {
            // obtener datos del formulario formValidarClaveSupervisor
            const ruta = $('#formValidarClaveSupervisor').attr('action');
            const contrasena = $('#password').val();
            if (contrasena === '') {
                $('#textoMensaje').text('Por favor ingrese la clave')
                $('#password').addClass('border-danger');
                $('#password').focus();
                return true;
            }
            $('#modalValidar').modal('hide');
            utilidades.showLoadingOverlay('Validando la clave. <br> espere un momento ....');
            $('#textoMensaje').empty();
            $('#password').removeClass('border-danger');
            $.ajax({
                url: ruta,
                method: 'GET',
                data: {
                    'password': contrasena
                },
                success: function (data) {
                    utilidades.hideLoadingOverlay();
                    if (data[0] === 'Success') {
                        if (successCallback && typeof successCallback === 'function') {
                            successCallback(data);
                        }
                    } else {
                        $('#textoMensaje').text('Error la clave no coincide')
                        $('#password').val("");
                        $('#password').focus();
                        $('#password').addClass('border-danger');
                        $('#modalValidar').modal('show');
                    }
                },
                error: function (xhr, status, error) {
                    utilidades.hideLoadingOverlay();
                    if (errorCallback && typeof errorCallback === 'function') {
                        errorCallback(xhr, status, error);
                    }
                }
            });
        }
    };
}();