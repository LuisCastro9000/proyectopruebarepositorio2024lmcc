// Codigo para posicionar el focus en el primer input del modal
$('.modal').on('shown.bs.modal', function () {
    $("input:text:visible:first").focus();
})

const showButtonLoader = (nombreBoton = '.btnLoader') => {
    const accion = $(nombreBoton).data('accion');
    $(`#btnTexto${accion}`).addClass('d-none')
    $(`#seccionLoader${accion}`).removeClass('d-none');
    $(`${nombreBoton}`).attr('disabled', true);
}

const hideButtonLoader = (nombreBoton = '.btnLoader') => {
    const accion = $(nombreBoton).data('accion');
    $(`#btnTexto${accion}`).removeClass('d-none');
    $(`#seccionLoader${accion}`).addClass('d-none');
    $(`${nombreBoton}`).attr('disabled', false);
}