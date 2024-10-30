////// traspasos /////
function modalEliminar(id) {
    $.confirm({
        title: '',
        content: 'Desea Eliminar Almacén?',
        buttons: {
            confirmar: function() {
                window.location = 'traspasos/' + id + '/delete';
            },
            cancelar: function() {

            }
        }
    });
}

///// realizar traspasos /////