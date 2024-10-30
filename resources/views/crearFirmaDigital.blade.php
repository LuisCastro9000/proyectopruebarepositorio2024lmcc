@extends('layouts.app')
@section('title', 'Cear Firma Digital')
@section('content')
    <div class="container">
        {!! Form::open([
            'url' => '/actualizando-Firma-digital',
            'method' => 'POST',
            'files' => true,
            'class' => 'formularioConFirma',
        ]) !!}
        {{ csrf_field() }}

        @if ($usuarioSelect->ImagenFirma != null)
            <section class="card rounded-0 mt-3">
                <div class="card-header text-center bg-griss font-weight-bold fs-14 text-uppercase">
                    Firma
                </div>
                <div class="card-body">
                    <input type="hidden" name="inputImagenFirmaAnterior" value="{{ $usuarioSelect->ImagenFirma }}">
                    <article class="p-4">
                        <div class="m-auto" style="width:100px; height:75px">
                            @if (str_contains($usuarioSelect->ImagenFirma, config('variablesGlobales.urlDominioAmazonS3')))
                                <img src="{{ $usuarioSelect->ImagenFirma }}" alt="Imagen Firma"
                                    style="width:100%; height:100%">
                            @else
                                <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $usuarioSelect->ImagenFirma }}"
                                    alt="Imagen Firma" style="width:100%; height:100%">
                            @endif
                        </div>
                    </article>
                </div>
            </section>
        @endif
        <div class="card-header text-center bg-griss font-weight-bold mt-4 fs-14 text-uppercase">
            DIBUJAR FIRMA DIGITAL
        </div>
        <section id="seccionFirma" class="firmaDigital">
            <section id="descargaFirma" class="d-flex justify-content-center my-2 flexGap--10">
                {{-- <button id="btnLimpiarFirma" class="btn btn--naranja rounded-circle fs-20" type="button"><i
                        class='bx bxs-brush bx-rotate-180'></i></button>
                <button id="btnHabilitarFirma" class="btn btn-success rounded-circle fs-20" type="button"><i
                        class='bx bxs-lock'></i></button>
                <button id="btnBloquearFirma" class="btn btn-danger rounded-circle fs-20 d-none" type="button"><i
                        class='bx bxs-lock-open'></i></button> --}}


                <button id="btnLimpiarFirma" class="btn btn--naranja font-weight-bold" type="button">Limpiar
                    Firma</button>
                <button id="btnHabilitarFirma" class="btn btn-success font-weight-bold" type="button">Habilitar
                    Firma</button>
                <button id="btnBloquearFirma" class="btn btn-danger font-weight-bold d-none" type="button">Bloquear
                    Firma</button>
            </section>
            <section class="d-flex justify-content-center">
                <canvas style="width: 800px; height: 250px" id="panelDigital"
                    class="borderDashed borderDashed--firma disabled-elemento">texto</canvas>
            </section>
            <input type="hidden" name="inputImagenFirma" id="imagenCodigoFirma" value="">
        </section>
        <section class="d-flex justify-content-center mt-3">
            <button id="btnCrearFirma" class="btn btn-primary font-weight-bold" type="submit">Guardar Firma</button>
        </section>
        {!! Form::close() !!}
    </div>
@stop
@section('scripts')
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/lienzoFirma/lienzoFirma.js?v=' . time()) }}"></script>
    {{-- <script>
        $('#btnHabilitarFirma').click(() => {
            $('#panelDigital').removeClass('disabled-elemento');
            $('#btnBloquearFirma').removeClass('d-none');
            $('#btnHabilitarFirma').addClass('d-none');
        })
        $('#btnBloquearFirma').click(() => {
            $('#panelDigital').addClass('disabled-elemento');
            $('#btnBloquearFirma').addClass('d-none');
            $('#btnHabilitarFirma').removeClass('d-none');
            signatureFirma.clear()
        })

        var btnLimpiar = document.getElementById("btnLimpiarFirma");
        var panelDigital = document.getElementById("panelDigital");
        var signatureFirma = new SignaturePad(panelDigital, {
            penColor: 'rgb(0, 0, 0)'
        });

        function resizePanelDigital() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            panelDigital.width = panelDigital.offsetWidth * ratio;
            panelDigital.height = panelDigital.offsetHeight * ratio;
            panelDigital.getContext("2d").scale(ratio, ratio);
            signatureFirma.clear();
        }
        resizePanelDigital();

        document.getElementById('btnLimpiarFirma').addEventListener('click', function() {
            signatureFirma.clear()
        });

        $(document).ready(function() {
            $('#modalDibujarFirma').css('display', 'none');
        });
    </script> --}}
@stop
