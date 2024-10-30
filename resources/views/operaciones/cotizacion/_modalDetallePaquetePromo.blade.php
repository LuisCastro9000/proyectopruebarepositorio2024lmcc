<div class="modal detallePaquetePromocional" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="text-center mt-4">
                <h5 class="modal-title" id="exampleModalLabel">Detalle Paquete
                    Promocional</h5>
                <hr>
            </div>
            <div class="modal-body">
                <table id="tableDetalle" class="table table-responsive-lg" style="width:100%">
                    <thead>
                        <tr class="bg-primary-contrast">
                            <th scope="col" data-tablesaw-priority="persist">
                                Código</th>
                            <th scope="col">Descripción</th>
                            <th scope="col">Precio</th>
                            <th scope="col">Cantidad</th>
                        </tr>
                    </thead>
                    <tbody id="tableDetalleBody">
                    </tbody>
                </table>
                <section class="d-flex justify-content-end align-items-center">
                    <label class="mr-2">Total:</label>
                    <article id="contenedorInputTotal">
                        <input id="totalPaquete" class="input-transparent" type="text" name="totalPaquete" readonly>
                    </article>
                </section>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
