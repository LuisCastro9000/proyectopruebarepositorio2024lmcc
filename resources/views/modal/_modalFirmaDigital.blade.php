{{-- Modal Dibujar Firma --}}
<div class="modal fade" id="modalDibujarFirma" role="dialog" aria-labelledby="exampleModalCenteredLabel" aria-hidden="true"
    style="display: block;">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <form action="{{ route('guardarFirmaDigital') }}" method="POST" name="formActualizarControlCalidad"
                target="blank" id="formularioCrearFirmaDigital">
                @csrf
                <section id="seccionFirma" class="firmaDigital col-12">
                    <h6 class="text-center">Dibujar Firma Digital</h6>
                    <section id="descargaFirma" class="d-flex justify-content-center my-2 flexGap--10">
                        <button id="btnLimpiarFirma" class="btn btn--naranja font-weight-bold" type="button">Limpiar
                            Firma</button>
                        <button id="btnHabilitarFirma" class="btn btn-success font-weight-bold" type="button">Habilitar
                            Firma</button>
                        <button id="btnBloquearFirma" class="btn btn-danger font-weight-bold d-none"
                            type="button">Bloquear
                            Firma</button>
                    </section>
                    <section class="d-flex justify-content-center">
                        <canvas style="width: 800px; height: 250px" id="panelDigital"
                            class="borderDashed borderDashed--firma disabled-elemento"></canvas>
                    </section>
                    <input type="hidden" id="inputCodigoFirma">
                    <input type="hidden" id="inputDescripcionEnlace">
                    <input type="hidden" id="inputIdControl">
                </section>
                <div class="modal-footer">
                    <button type="submit" id="btnModalCrearFirma" class="btn btn-secondary m-auto">Crear
                        Firma</button>
                </div>
            </form>
        </div>
    </div>
</div>
{{-- Fin --}}
