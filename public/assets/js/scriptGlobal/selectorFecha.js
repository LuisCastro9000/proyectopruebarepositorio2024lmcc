
/**
 * Funcion para ocultar los input calendarios al cargar el DOM
 * @param {number} fecha 
 */
const inicializarElementosFechas = (fecha) => {
    if (fecha == '9') {
        showSeccionCalendario();
    } else {
        hideSeccionCalendario();
    }
    seleccionarOpcion(fecha);
}
const showSeccionCalendario = () => {
    $('#seccionInputCalendario').show();
}

const hideSeccionCalendario = () => {
    $('#seccionInputCalendario').hide();
    $('#datepickerIni').val('');
    $('#datepickerFin').val('');
}

const seleccionarOpcion = (fecha) => {
    $(`#idFecha option[value= ${fecha}]`).prop('selected', true);
}
