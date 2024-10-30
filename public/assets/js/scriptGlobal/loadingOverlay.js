/**
 * Funcion para mostrar el LoadingOverlay
 * @param {string} mensaje
 */
const showLoadingOverlay = (mensaje = 'Por favor, espere un momento...') => {
    let customElement = $(`<div>`, {
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
}

const hideLoadingOverlay = () => {
    $.LoadingOverlay("hide");
}

// function showLoader(texto, size) {
//     let customElement = $(`<div>${texto}</div>`).css({
//         'position': "absolute",
//         "border": "4px dashed gold",
//         "font-size": `${size}`,
//         "text-align": "center",
//         "padding": "10px",
//     });

//     $.LoadingOverlay("show", {
//         image: "",
//         custom: customElement
//     });
// }