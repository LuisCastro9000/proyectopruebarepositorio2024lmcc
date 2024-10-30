function modalEliminar(id) {
    $("#idUsuario").val(id);
}
$(function() {
    $("#exampleModal button.btnEliminar").on("click", function(e) {
        var id = $("#idUsuario").val();
        window.location = 'usuarios/' + id + '/delete';
    });
});