{{-- Modal comprobar Permiso --}}
<div class="modal fade" id="modalValidarClaveSupervisor" tabindex="-1" role="dialog"
    aria-labelledby="exampleModalCenteredLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body">
                <div class="form-group text-center mt-3">
                    <input id="id" class="d-none" type="text" value="">
                    <label for="formGroupExampleInput">Ingrese la clave Supervisor para comprobar Permiso</label>
                    <input id="password" type="text" class="form-control text-center" placeholder="********"
                        style="-webkit-text-security: disc;" autocomplete="off">
                    <span class=" d-block text-danger font-weight-bold py-2" id="textoMensaje"></span>

                    <x-buttonLoader id='btnValidarClave'
                        data-ruta-validar-supervisor="{{ route('validarClaveSupervisor') }}">
                        @slot('textoBoton', 'Comprobar Permiso')
                        @slot('textoLoader', 'Validando')
                    </x-buttonLoader>
                    <hr>
                    <div class="text-center mt-2">
                        @if (Session::get('Cliente') == 1)
                            <a href="{{ url('cambiar-contrasena') }}">¿Has olvidado la contraseña?</a>
                        @else
                            <span class="text-danger">Si no recuerda la clave, inicie Sesión como Administrador y <br>
                                <b>proceda a Actualizar</b></span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Fin --}}
