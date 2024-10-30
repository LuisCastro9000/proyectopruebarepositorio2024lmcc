
// Codigo para cargar poner el focus en input del modal
$('body').on('shown.bs.modal', '.modalcomprobarPermiso', function () {
    $('input:visible:enabled:first', this).focus();
});
// Fin

let idControl = '';

document.addEventListener("click", function (event) {
    if (event.target.matches('.btnEditar')) {
        event.preventDefault();
        const estadoCotizacion = document.getElementById(event.target.id).dataset.estado;
        idControl = document.getElementById(event.target.id).dataset.id;
        if (estadoCotizacion == 2) {
            redireccionarVistas(idControl);
        } else {
            abriModal();
        }
    }
});

function abriModal() {
    $(".modalcomprobarPermiso").modal('show');
    $('#password').removeClass('border-danger');
    $("p").remove();
    $('.modalcomprobarPermiso').modal('show');
}

$('#btnComprobar').click(function () {
    validarSupervisor();
})

function validarSupervisor() {
    var password = $('#password').val();
    if (password !== "") {
        $.ajax({
            type: "get",
            url: "comprobar-permiso",
            data: {
                'password': password
            },
            success: function (data) {
                console.log(data);
                $('p').remove();
                if (data[0] == 'Success') {
                    // CerrarModal()
                    $(".modalcomprobarPermiso").modal('hide');
                    redireccionarVistas(idControl);
                    $('#password').val("");
                    $('#btnActualizar').removeClass('d-none');

                } else {
                    $('#mensaje').append(
                        '<p class="text-center text-danger font-weight-bold">Error la clave no coincide</p>'
                    )
                    $('#password').val("");
                    $('#password').focus();
                    $('#password').addClass('border-danger');
                }
            }
        })
    } else {
        $('p').remove();
        $('#mensaje').append(
            '<p class="text-center text-danger font-weight-bold">Por favor ingrese la clave</p>');
        $('#password').addClass('border-danger');
        $('#password').focus();
    }
}

function redireccionarVistas(id) {
    var url = "{{ route('controlCalidad.edit', ':id') }}";
    url = url.replace(':id', id);
    location.href = url;
}



// const acciones = document.getElementById("seccionAcciones");
// acciones.addEventListener("click", function(event) {
//     if (event.target && event.target.tagName === 'A') {
//         console.log(event.target.tagName);
//         event.preventDefault()
//         console.log(event.target);
//     }
// });

// document.addEventListener("click", function(event) {
//     if (event.target.matches('.btnEditar')) {
//         const estadoCotizacion = document.getElementById(event.target.id).dataset.estado;
//         const idControl = document.getElementById(event.target.id).dataset.idControl;
//         console.log(estadoCotizacion);
//         event.preventDefault();
//         if (estadoCotizacion == 2) {
//             var url = "{{ route('controlCalidad.show', ':id') }}";
//             url = url.replace(':id', idControl);
//             // location.href = url;
//         } else {
//             alert('estado3')
//         }
//     }
// });