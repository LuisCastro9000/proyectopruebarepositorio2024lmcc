{{-- Modal enviar pdf x Whatsapp --}}
<section class="modal fade" id="modalEnviarWhatsApp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            {!! Form::open([
                'method' => 'GET',
                'target' => 'blank',
                'id' => 'formularioEnviarWhatsApp',
            ]) !!}
            <div class="modal-body text-center">
                <div class="form-group">
                    <section id="seccionClienteConCelular" class="d-none">
                        <label class="fs-16" for="numeroCelular">Número de celular
                            Registrado como teléfono de contacto del cliente</label>
                    </section>
                    <section id="seccionClienteSinCelular" class="d-none">
                        <label class="fs-16" for="numeroCelular">Ingresar número de
                            celular</label>
                    </section>
                    <input type="text" class="form-control text-center" name="numeroCelular" id="numeroCelular"
                        value="">
                </div>
                <section id="seccionMensajeClienteConCelular" class="d-none">
                    <p>Si aún es válido Continue caso contrario <br> vuelva a digitarlo
                    </p>
                </section>
            </div>
            <div class="modal-footer d-flex justify-content-center justify-content-sm-end">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" id="btnEnviarWhatsApp" class="btn btn-primary">Enviar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</section>
{{-- Fin --}}
