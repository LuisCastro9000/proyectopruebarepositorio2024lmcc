<section class="row my-3">
    <form id="formRenovarSuscripcion" action="{{ route('pago-sucripcion.renovar-suscripcion') }}" method="POST">
        @csrf
        <input type="hidden" name="inputIdPagoSucripcion" id="inputIdPagoSucripcion">
        <div class="col-12">
            <section class="d-flex justify-content-between align-items-end">
                <h4>Sucursales con Suscripción</h4>
                <button type="submit" class="btn btn-info btn-sm mt-2">Renovar Suscripción</button>
            </section>
        </div>
        <div class="col">
            <div class="accordion custom-accordion pt-3">
                @foreach ($suscripciones as $suscripcion)
                    <div class="card mb-0">
                        <div class="card-header">
                            <section class="d-flex justify-content-between align-items-center">
                                <article>
                                    <h5 class="m-0">
                                        Sucursal {{ $suscripcion->Nombre }}
                                    </h5>
                                    @if ($suscripcion->EstadoSuscripcion === 'Activada')
                                        <span class="badge bg-success">{{ $suscripcion->EstadoSuscripcion }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ $suscripcion->EstadoSuscripcion }}</span>
                                    @endif
                                </article>
                                <article>
                                    <input type="checkbox" id="switch{{ $suscripcion->IdSucursal }}" data-switch="bool"
                                        name="checkRenovarSuscripcion[]" value="{{ $suscripcion->IdSucursal }}" />
                                    <label class="me-2" for="switch{{ $suscripcion->IdSucursal }}" data-on-label="Si"
                                        data-off-label="No" data-bs-toggle="collapse"
                                        data-bs-target="#panelsStayOpen-collapse{{ $suscripcion->IdSucursal }}"
                                        aria-expanded="true"
                                        aria-controls="panelsStayOpen-collapse{{ $suscripcion->IdSucursal }}"></label>
                                </article>
                            </section>
                        </div>
                        <div id="panelsStayOpen-collapse{{ $suscripcion->IdSucursal }}"
                            class="accordion-collapse collapse" aria-labelledby="panelsStayOpen-headingOne">
                            <div class="accordion-body">
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="example-select" class="form-label">Periodo</label>
                                            <select class="form-select"
                                                id="selectPeriodoSuscripcion{{ $suscripcion->IdSucursal }}"
                                                name="selectPeriodoSuscripcion{{ $suscripcion->IdSucursal }}">
                                                <option value="1">Mensual</option>
                                                <option value="2">Semestral</option>
                                                <option value="3">Anual</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3 position-relative z-index-1000"
                                            id="datepicker{{ $suscripcion->IdSucursal }}">
                                            <label class="form-label">Fecha Final Suscripción</label>
                                            <input type="text" class="form-control" data-provide="datepicker"
                                                data-date-autoclose="true" data-date-format="dd/mm/yyyy"
                                                name="inputFechaSuscripcion{{ $suscripcion->IdSucursal }}"
                                                value="{{ empty($suscripcion->FechaFinalContrato) ? '' : date('d/m/Y', strtotime($suscripcion->FechaFinalContrato)) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="mb-3 position-relative z-index-1000"
                                            id="datepickerCDT{{ $suscripcion->IdSucursal }}">
                                            <label class="form-label">Fecha Final CDT</label>
                                            <input type="text" class="form-control" data-provide="datepicker"
                                                data-date-autoclose="true" data-date-format="dd/mm/yyyy"
                                                name="inputFechaCdt{{ $suscripcion->IdSucursal }}"
                                                value="{{ empty($suscripcion->FechaFinalCDT) ? '' : date('d/m/Y', strtotime($suscripcion->FechaFinalCDT)) }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Monto</label>
                                            <input type="text" id="simpleinput" class="form-control"
                                                name="inputPrecio{{ $suscripcion->IdSucursal }}"
                                                value="{{ $suscripcion->MontoPago }}">
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="mb-3">
                                            <label for="simpleinput" class="form-label">Días Bloqueo</label>
                                            <input type="text" id="simpleinput" class="form-control"
                                                name="inputDiasBloqueo{{ $suscripcion->IdSucursal }}"
                                                value="{{ $suscripcion->Bloqueo }}">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </form>
</section>
<script>
    const suscripciones = @json($suscripciones);
    suscripciones.forEach(element => {
        $('#selectPeriodoSuscripcion' + element.IdSucursal + ' option[value=' + element.Plan + ']').prop(
            'selected',
            true);
    });

    $(document).ready(function() {
        $("#formRenovarSuscripcion").submit(function() {
            showLoaderMessage({
                mensaje: 'Procesando la renovación de suscripción.<br> Por favor, espere un momento... '
            });
        });
    });
</script>
