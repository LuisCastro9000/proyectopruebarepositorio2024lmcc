{{-- Modal enviar pdf x Whatsapp --}}
<div class="modal fade" id="modalEnviarWhatsApp" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            {!! Form::open([
                'route' => ['imprimirControlCalidad', [$idDocumento, 'whatsApp']],
                'method' => 'Get',
                'files' => true,
                'target' => 'blank',
                'class' => 'formularioEnviarWhatsApp',
            ]) !!}
            <div class="modal-body">
                <div class="row mt-4">
                    <div class="col-12 text-center">
                        <div class="form-group">
                            @if ($numeroCelular != null)
                                <label class="fs-16" for="numeroCelular">Número de celular
                                    Registrado como teléfono de contacto del cliente</label>
                            @else
                                <label class="fs-16" for="numeroCelular">Ingresar número de
                                    celular</label>
                            @endif
                            <input type="text" class="form-control text-center" name="numeroCelular"
                                id="numeroCelular" value="{{ $numeroCelular }}">
                            <input type="hidden" name="idCheck" value="{{ $idDocumento }}">
                        </div>
                        @if ($numeroCelular != null)
                            <p>Si aún es válido Continue caso contrario <br> vuelva a digitarlo
                            </p>
                        @endif
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="submit" class="btn btn-primary">Enviar</button>
            </div>
            {!! Form::close() !!}
        </div>
    </div>
</div>
{{-- Fin --}}

<script>
    document.querySelector('.formularioEnviarWhatsApp').addEventListener('submit', function(event) {
        $('#modalEnviarWhatsApp').modal('hide');
    });
</script>
