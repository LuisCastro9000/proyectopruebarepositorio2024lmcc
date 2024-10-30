

//Crear el elemento y el contexto
const mainCanvas = document.getElementById("main-canvas");
const context = mainCanvas.getContext("2d");

let initialX;
let initialY;

const dibujar = (cursorX, cursorY) => {
    context.beginPath();
    context.moveTo(initialX, initialY);
    context.lineWidth = 5;
    context.strokeStyle = "#ff0000";
    context.lineCap = "round";
    context.lineJoin = "round";
    context.lineTo(cursorX, cursorY);
    context.stroke();

    initialX = cursorX;
    initialY = cursorY;
};

const mouseDown = (evt) => {
    initialX = evt.offsetX;
    initialY = evt.offsetY;
    dibujar(initialX, initialY);
    mainCanvas.addEventListener("mousemove", mouseMoving);
};

const mouseMoving = (evt) => {
    dibujar(evt.offsetX, evt.offsetY);
};

const mouseUp = () => {
    mainCanvas.removeEventListener("mousemove", mouseMoving);
};

mainCanvas.addEventListener("mousedown", mouseDown);
mainCanvas.addEventListener("mouseup", mouseUp);

// Insertar imagen en el contexto canvas


const btn = document.getElementById('btn');
let canvasImg = document.getElementById('canvas-img');
let canvas = canvasImg.getContext('2d');
let imgCar = new Image();
imgCar.src = " ../assets/img/img-vehiculo.jpeg ";
imgCar.onload = function () {
     canvas.drawImage(imgCar,0,0)
}

btn.addEventListener('click', function () {
     alert("me distes click");
})