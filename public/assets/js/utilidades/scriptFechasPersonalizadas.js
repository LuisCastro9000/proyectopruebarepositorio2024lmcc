
// Este script utiliza las variables proporcionadas por el módulo 'UTILIDADES', que se encuentra en el archivo asset/js/utilidades/utilidades.js.


// Este script se utilizan las variables proporcionadas por el módulo 'variablesBlade',
// el cual está declarado en las vistas que utilizan filtros personalizados.
// Asegúrate de que el módulo 'variablesBlade' esté definido y contenga las siguientes variables:
//   - fecha
//   - fechaInicial
//   - fechaFinal
// Dichas variables se utilizan para configurar el comportamiento de esta sección del código.

if (typeof variablesBlade !== 'undefined') {

    let metodoObtenerDatos = '';
    let tipoRangoFechas = '';
    $('#datepickerIni').on('change', function () {
        $('#datepickerFin').val('');
        if (tipoRangoFechas.includes("mensual") || tipoRangoFechas.includes("anual")) {
            const fechaInicial = $(this).val();
            establecerRangoFecha(fechaInicial);
        }
    })

    $('#btnBuscarConFechasPersonalizadas').click(function () {
        if (metodoObtenerDatos === 'submit') {
            utilidades.showLoadingOverlay();
        } else {
            variablesBlade.ejecutarPeticionAjax();
        }
    })


    $(function () {
        tipoRangoFechas = $('.selectorFiltroFecha').data('tipoRangoFechas');
        $('#seccionFechasPersonalizadas').hide();
        if (variablesBlade.fecha == '9') {
            if ((tipoRangoFechas.includes("mensual") || tipoRangoFechas.includes("anual")) && variablesBlade.fechaInicial !== '') {
                establecerRangoFecha(variablesBlade.fechaInicial);
            }
            $('#seccionFechasPersonalizadas').show();
            $('#datepickerIni').val(variablesBlade.fechaInicial);
            $('#datepickerFin').val(variablesBlade.fechaFinal);
        }
        $('#idFecha option[value=' + variablesBlade.fecha + ']').prop('selected', true);
    })

    $('.selectorFiltroFecha').on('change', function () {
        const obtenerDatos = $(this).data('obtenerDatos');
        metodoObtenerDatos = $(this).data('metodoObtenerDatos');

        if ($(this).val() == '9') {
            $('#seccionFechasPersonalizadas').show();
        } else {
            $('#seccionFechasPersonalizadas').hide();
            $('#datepickerIni').val('');
            $('#datepickerFin').val('');
            if (obtenerDatos === true) {
                if (metodoObtenerDatos == 'submit') {
                    utilidades.showLoadingOverlay();
                    const formulario = $(this).closest('form').attr('id');
                    $(`#${formulario}`).submit();
                } else {
                    variablesBlade.ejecutarPeticionAjax();
                }
            }
        }
    })

    function establecerRangoFecha(fechaInicial) {
        const fechaFormateada = formatearFecha(fechaInicial);
        let fechaNueva = '';
        if (tipoRangoFechas.includes("mensual")) {
            fechaNueva = sumarMeses(`${fechaFormateada} 00:00:00`, 6);
        }
        if (tipoRangoFechas.includes("anual")) {
            fechaNueva = sumarAnios(`${fechaFormateada} 00:00:00`, 2);
        }
        // Obtén el objeto datepicker
        const datepicker = $('#datepickerFin').datepicker();
        // Actualiza la fecha máxima permitida
        datepicker.data('datepicker').setStartDate(fechaInicial);
        datepicker.data('datepicker').setEndDate(fechaNueva.toLocaleDateString());
    }

    function sumarMeses(fecha, cantidadMeses) {
        const nuevaFecha = new Date(fecha);
        nuevaFecha.setMonth(nuevaFecha.getMonth() + cantidadMeses);
        return nuevaFecha;
    }

    function sumarAnios(fecha, cantidadAnios) {
        const nuevaFecha = new Date(fecha);
        nuevaFecha.setFullYear(nuevaFecha.getFullYear() + cantidadAnios);
        return nuevaFecha;
    }

    function formatearFecha(fecha) {
        const partesFecha = fecha.split('/');
        return `${partesFecha[2]}/${partesFecha[1]}/${partesFecha[0]}`;
    }

    function compararFechas(fechaInicial, fechaFinal) {
        // Convertir las fechas a objetos Date
        const newFechaInicial = new Date(fechaInicial.split('/').reverse().join('-'));
        const newFechaFinal = new Date(fechaFinal.split('/').reverse().join('-'));

        // Comparar los objetos Date
        return newFechaInicial > newFechaFinal;
    }
}