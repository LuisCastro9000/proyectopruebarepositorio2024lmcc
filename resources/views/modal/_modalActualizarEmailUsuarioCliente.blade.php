<div id='modalActualizarEmailUsuarioCliente' class="modal fade" tabindex="-1" role="dialog"
    aria-labelledby="myLargeModalLabel" aria-hidden="true" id="myModa">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body px-4">
                <div class="row">
                    <div class="col-12 mt-4 mb-2">
                        @if ($usuarioSelect->Email == null)
                            <p class="text-justify fs-16 font-weight-bold">Por favor ingrese el correo electrónico de la
                                empresa. Es IMPORTANTE para el envío PRÓXIMAMENTE de las comunicaciones internas y los
                                documentos digitales (XML, CDR) generados durante el día.</p>
                        @else
                            <p class="text-justify fs-16 font-weight-bold text-dark">Por favor, valida el correo
                                electrónico de la empresa. Es IMPORTANTE para el envío PRÓXIMAMENTE de las
                                comunicaciones internas y los documentos digitales (XML, CDR) generados durante el día.
                            </p>
                        @endif
                    </div>
                    <hr>
                    <div class="col-12">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control border border-primary font-weight-bolder py-2"
                                id="inputEmailCliente" value="{{ $usuarioSelect->Email }}" name="email"
                                placeholder="Ingrese su Email">
                            <span class="text-danger error-message" id="correousuario-error"></span>
                        </div>
                        <div class="text-center mt-4">
                            <x-buttonLoader class='btnActualizarPerfil' accion='actualizarCorreo' color='btn btn--verde'
                                value="btnActualizarEmailUsuarioCliente">
                                @slot('textoBoton', 'Entendido')
                                @slot('textoLoader', 'Actualizando')
                            </x-buttonLoader>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
