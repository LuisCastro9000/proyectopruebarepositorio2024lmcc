// =====  METODOS =====

const ocultarMensajeError = (elemento) => {
    if ($(elemento).val().trim() !== '') {
        $(elemento).siblings('.error').addClass('d-none');
    }
}

const isFormularioValido = (formulario) => {
    let isFormValid = true;
    $(`${formulario} :input`).each(function () {
        // Verifica si el campo está vacío (para input) o no se ha seleccionado una opción (para select)
        if ($(this).is('input') && $(this).val().trim() === '' ||
            $(this).is('select') && ($(this).val() === '0' || $(this).val() === '')) {
            // Muestra el mensaje de error asociado
            $(this).siblings('.error').removeClass('d-none');
            // Establece isFormValid en falso
            isFormValid = false;
        }
    });
    return isFormValid;
}

$(".inputNumeroEntero").on('keypress', function (event) {
    if (event.key < '0' || event.key > '9') {
        event.preventDefault();
        return false;
    }
});

$(".inputLetrasMayusculas").on('input', function (event) {
    const valueInput = $(this).val();
    $(this).val(valueInput.toUpperCase());
});



// $(".inputNumeroEntero").on('input', function (event) {
//     const input = event.target;
//     const valor = input.value;

//     // Eliminar caracteres no numéricos
//     const valorNumerico = valor.replace(/\D/g, '');
//     let valorCapturado = valorNumerico;

//     if (hasDigitoMaximo(input)) {
//         valorCapturado = hasDigitoMaximo(input);
//     }
//     // Actualizar el valor del input
//     input.value = valorCapturado;
// });


// const hasDigitoMaximo = (input) => {
//     // Obtener el atributo data-maximoDigitos
//     const maximoDigitos = $(input).data('maximoDigitos');

//     if (maximoDigitos !== undefined) {
//         valorCapturado = valorNumerico.slice(0, maximoDigitos);
//         return valorCapturado;
//     }
//     return false;
// }


$(document).on('input', function (event) {
    const inputObjetivo = $(event.target).closest('[data-toggle="validarOnInput"]');
    if (inputObjetivo.length > 0) {
        validar(inputObjetivo);
    }
})

function validar(inputObjetivo) {
    const atributoData = inputObjetivo.data();
    Object.entries(atributoData).forEach(([clave, valor]) => {
        switch (clave) {
            case 'maximoDigitos':
                const digitosMaximos = inputObjetivo.val().slice(0,
                    valor);
                inputObjetivo.val(digitosMaximos);
                break;
            case 'numeroEntero':
                const numeroEntero = inputObjetivo.val().replace(/\D/g, '');
                inputObjetivo.val(numeroEntero);
                break;
            case 'alfanumerico':
                const alfaNumerico = inputObjetivo.val().replace(/[^a-zA-Z0-9]/g, '');
                inputObjetivo.val(alfaNumerico);
                break;
            case 'convertirMayusculas':
                const letraMayusculas = inputObjetivo.val().toUpperCase();
                inputObjetivo.val(letraMayusculas);
                break;
        }
    })
}

// $(".inputNumeroEntero").on('input', function (event) {
//     const input = event.target;
//     const valor = input.value;

//     // Eliminar cualquier caracter que no sea un dígito
//     valorUno = valor.replace(/\D/g, '');

//     // Limitar la longitud a 4 dígitos
//     Dos = valorUno.substring(0, 4);

//     // Actualizar el valor del input
//     input.value = Dos;
//     console.log(Dos);
// });

