{{-- seccion Modal --}}
<div id="seccionCrearEgreso" class="borderDashed borderDashed--turquesa bg-muted">
    <section class="d-flex justify-content-between align-items-center flex-wrap">
        <label for="checkGuardarEgreso" class="fs-14">Guardar Compra
            como
            Egreso de Caja</label>
        <label class="switch">
            <input id="checkGuardarEgreso" type="checkbox" name="checkGuardarEgreso">
            <span class="slider round"></span>
        </label>
    </section>
    <hr>
    <section class="d-flex justify-content-between mt-4 flex-wrap">
        <article class="text-center p-2 rounded card-informativo">
            <span id="totalCaja" class="fs-18"></span>
            <b class="d-block">Total actual en caja</b>
        </article>
        <article class="text-center p-2 rounded card-informativo">
            <span id="montoEgreso" class="fs-18">0.00</span>
            <b class="d-block">Total a descontar</b>
        </article>
        <article class="text-center p-2 rounded card-informativo">
            <span id="nuevoTotalCaja" class="fs-18">0.00</span>
            <b class="d-block">Total Restante en Caja</b>
        </article>
    </section>
    <section class="mt-3">
        <div class="form-group">
            <textarea id="inputDescripcionEgreso" class="w-100 p-2" rows="4"
                placeholder="Si necesita, escriba aquí sus observaciones" disabled></textarea>
        </div>
    </section>
    <section>
        <b>Nota: </b><span>Para guardar la Compra
            como un egreso, debe <b class="badge badge-warning">FINALIZAR LA
                COMPRA.</b></span>
        <input type="hidden" id="inputIdCaja" value="{{ $caja->IdCaja }}">
    </section>
</div>
{{-- Fin --}}
