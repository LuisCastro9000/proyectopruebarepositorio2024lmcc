let $inputSerie = document.getElementById("serie");
$inputSerie.addEventListener("keyup", () => ConvertirLetrasAmayusculas($inputSerie));
$inputSerie.addEventListener( "input", ()=> maximoDigito($inputSerie, 4));

let $inpuNumero = document.getElementById("numero");
$inpuNumero.addEventListener("blur", () => CompletarCeros($inpuNumero));
$inpuNumero.addEventListener("input", () => maximoDigito( $inpuNumero, 8));


// METODOS JS

function ConvertirLetrasAmayusculas(variable) {
    variable.value = variable.value.toUpperCase();
}

function CompletarCeros(variable) {
    if (variable.value !== "") {
        while (variable.value.length < 8) {
            variable.value = "0" + variable.value;
        }       
    }
}

function maximoDigito(variable, num) {
    if (variable.value.length > num) {
        variable.value = variable.value.slice(0, num)
    }
}


// $inpuNumero.addEventListener( "keyup", ()=> maximoDigito($inpuNumero));

// function maximoDigito(variable) {
//     let expReg =  /^[A-Za-z0-9]+$/
//     if (expReg.test(variable.value)) {
//         alert("digistos correctos")
//     }else{
//         alert("solo acepta numeros y letras")
//     }  
// }

// function CompletarCero(variable) {
//     if (variable.value !== "") {
//         variable.value.padStart(8, 0);
//     }
// }
