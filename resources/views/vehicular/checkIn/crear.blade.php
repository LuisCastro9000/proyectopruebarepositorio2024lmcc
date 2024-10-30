@extends('layouts.app')
@section('title', 'Crear Inventario')
@section('content')
    <style type="text/css">
        .borderDotted {
            border-style: dotted;
            border-width: 2px;
            border-color: #3333;
            border-radius: 10px;
        }

        canvas {
            background-color: #FFFF;
        }

        .disabledLienzo {
            cursor: not-allowed;
            pointer-events: none;
        }

        input.form-control:read-only {
            background-color: #F9F9F9;
        }
    </style>
    <div class="container">
        <div class="row d-flex justify-content-center justify-content-md-end mt-4 flex-wrap align-items-center">
            <section>
                <a class="mr-0 mr-md-3" href="https://www.youtube.com/watch?v=DQdO7BCNq_Y&ab_channel=AutocontrolPeru"
                    target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white">
                        Video Instructivo vehículo <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                    </span>
                </a>

                <a class="mr-0 mr-md-3" href="https://www.youtube.com/watch?v=_pKwoZ4jADc" target="_blank">
                    <span class="btn btn-autocontrol-naranja ripple text-white">
                        Video Instructivo Moto <i class="list-icon material-icons fs-24 color-icon">videocam</i>
                    </span>
                </a>
            </section>
        </div>
        @if (session('error'))
            <div class="alert alert-danger">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('error') }}
            </div>
        @endif
    </div>

    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        {!! Form::open([
            'url' => '/vehicular/check-in',
            'method' => 'POST',
            'files' => true,
            'class' => '',
            'id' => 'myform',
        ]) !!}
        {{ csrf_field() }}

        {{-- Nueva seccion --}}
        <br>
        <input id="inputTipoVehiculo" type="hidden" name="tipoVehiculo" value="vehiculo">
        <section class="mt-4 jumbotron bg-jumbotron--white">
            <article class="d-flex justify-content-center justify-content-md-between flex-wrap">
                <h6 class="">Crear Inventario</h6>
                <ul class="nav nav-pills mb-3 lista" id="pills-tab" role="tablist">
                    <li class="nav-item">
                        <a class="nav-link btnTipoVehiculo active" id="tab-vehiculo" data-toggle="pill" href="#btn-Vehiculo"
                            role="tab" aria-controls="btn-Vehiculo" aria-selected="true" data-tipovehiculo="vehiculo"
                            data-imagen="img-vehiculo.jpeg">Vehículo</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btnTipoVehiculo" id="tab-moto" data-toggle="pill" href="#btn-moto"
                            role="tab" aria-controls="btn-moto" aria-selected="true" data-tipovehiculo="moto"
                            data-imagen="img-motocicleta.jpg">Moto</a>
                    </li>
                </ul>
            </article>
            <hr><br><br>


            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="formGroupExampleInput">Seleccione el Cliente</label>
                        <select class="form-control select2-hidden-accessible" id="clientes" name="cliente"
                            data-placeholder="Choose" data-toggle="select2" tabindex="-1" aria-hidden="true">
                            <option value="0">-</option>
                            {{-- @foreach ($clientes as $cliente)
                                <option value="{{ $cliente->IdCliente }}">{{ $cliente->RazonSocial }}</option>
                            @endforeach --}}
                        </select>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="serie">Serie</label>
                        <input id="serie" class="form-control py-2" placeholder="Serie" value="{{ $serie }}"
                            type="text" name="serie" maxlength="4" readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="correlativo">Correlativo</label>
                        <input id="numero" class="form-control py-2" placeholder="correlativo"
                            value="{{ $correlativo }}" type="text" maxlength="8" name="correlativo" readonly>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-4">
                    <div class="form-group">
                        <label for="color">Color</label>
                        <input type="text" class="form-control py-2" id="color" readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="anio">Año</label>
                        <input type="text" class="form-control py-2" id="anio" readonly>
                    </div>
                </div>
                <div class="col-4">
                    <div class="form-group">
                        <label for="kilometraje">Kilometraje</label>
                        <input type="text" class="form-control py-2" id="kilometraje" name='Kilometraje'>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <label for="selectVentaRapida">Autorizaciones</label>
                </div>
                <div class="col-md-6">
                    <input id="chboxAuto1" name="checkAutorizaciones[]" value="1" class="chboxAuto mt-3"
                        type="checkbox" /> <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo conducir mi
                        vehículo
                        para pruebas en exteriores del Taller</span>
                </div>
                <div class="col-md-6">
                    <input id="chboxAuto2" name="checkAutorizaciones[]" value="2" class="chboxAuto mt-3"
                        type="checkbox" /> <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Autorizo enviar mi vehículo
                        para trabajos de terceros en Talleres de su elección</span>
                </div>
                <div class="col-md-6">
                    <input id="chboxAuto3" name="checkAutorizaciones[]" value="3" class="chboxAuto mt-3"
                        type="checkbox" /> <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Declaro que no existen
                        elementos de valor dentro del vehículo</span>
                </div>
                <div class="col-md-6">
                    <input id="chboxAuto4" name="checkAutorizaciones[]" value="4" class="chboxAuto mt-3"
                        type="checkbox" /> <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">Acepto retirar el vehículo
                        en un máximo de
                    </span> <input style="width:40px" name="Dias" disabled> <span
                        class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">días, luego de finalizado el servicio;
                        caso contrario asumiré un costo de S/ </span> <input style="width:40px" name="Monto" disabled>
                    <span class="fs-13 pd-l-10 pd-l-0-rtl pd-r-50-rtl">diarios por cochera (interna y/o
                        externa)</span>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="selectVentaRapida">Nivel de Gasolina</label><br>
                        <div class="radiobox mt-4">
                            <label>
                                <input type="radio" name="radioNivelGasolina" checked value="25"> <span
                                    class="label-text m-4">25 %</span>
                            </label>
                            <label>
                                <input type="radio" name="radioNivelGasolina" value="50"> <span
                                    class="label-text m-4">50 %</span>
                            </label>
                            <label>
                                <input type="radio" name="radioNivelGasolina" value="75"> <span
                                    class="label-text m-4">75 %</span>
                            </label>
                            <label>
                                <input type="radio" name="radioNivelGasolina" value="100"> <span
                                    class="label-text m-4">100 %</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <div class="tab-content mt-4" id="pills-tabContent">
                @include('vehicular.checkIn._tableDatosVehiculo')

                <div id="seccionDigital">
                    {{-- Panel anomalias fisicas --}}
                    <div class="row">
                        <div class="col-12">
                            <section class="d-flex flex-wrap">
                                <p class="text-danger font-weight-bold">Si desea agregar ANOMALIAS FISICA y
                                    FIRMA DIGITAL habilite el Lienzo</p>
                                <article class="d-flex flex-wrap">
                                    <div class="form-check form-switch ml-0 ml-md-4">
                                        {{-- <input class="form-check-input" type="checkbox" id="checkAnomalias">
                                        <label class="form-check-label" for="checkAnomalias">Anomalias Fisicas</label> --}}
                                        <div class="custom-control custom-checkbox ">
                                            <input type="checkbox" class="custom-control-input" id="checkAnomalias">
                                            <label class="custom-control-label" for="checkAnomalias">Anomalias
                                                Fisicas</label>
                                        </div>
                                    </div>
                                    <div class="form-check form-switch ml-2 ml-md-3">
                                        {{-- <input class="form-check-input" type="checkbox" id="checkFirma">
                                        <label class="form-check-label" for="checkFirma">Firma Digital</label> --}}
                                        <div class="custom-control custom-checkbox ">
                                            <input type="checkbox" class="custom-control-input" id="checkFirma">
                                            <label class="custom-control-label" for="checkFirma">Firma
                                                Digital</label>
                                        </div>
                                    </div>
                                </article>
                            </section>
                        </div>

                        <div class="col-12 " id="contenedorAnomaliasFisicas">
                            <section class="description text-center">
                                <h6>Dibujar anomalías Físicas</h6>
                                <button id="btnDeshacer" class="btn btn--naranja my-1 font-weight-bold"
                                    type="button">Corregir
                                    Anomalías</button>
                            </section>
                            <div>
                                <section class="d-flex justify-content-center">
                                    <canvas id="canvasCarro" class=" borderDotted disabledLienzo"></canvas>
                                    <img id="imagenFondoCanvas" src="{{ asset('assets/img/img-vehiculo.jpeg') }}"
                                        class="d-none">
                                </section>
                                <input type="text" name="imagenCodigoCarro" id="imagenCodigoCarro" value=""
                                    class="d-none">
                                {{-- <a class="btn btn-primary" id="btnCarro">Generar codigo</a> --}}
                            </div>
                        </div>
                    </div>
                    {{-- Fin --}}
                    {{-- Panel de firma --}}
                    <div class="row mt-4">
                        <div id="contenedorFirmaDigital" class="firmaDigital col-12">
                            <h6 class="text-center">Dibujar Firma Digital</h6>
                            <section id="descargaFirma" class="d-flex justify-content-center my-2">
                                <button id="btnLimpiarFirma" class="btn btn--naranja font-weight-bold"
                                    type="button">Limpiar
                                    Firma</button>
                            </section>
                            <section class="d-flex justify-content-center">
                                <canvas style="width: 800px; height: 250px" id="panelDigital"
                                    class="borderDotted disabledLienzo"></canvas>
                            </section>
                            <input type="text" name="imagenCodigoFirma" id="imagenCodigoFirma" value=""
                                class="d-none">
                        </div>
                    </div>
                    {{-- Fin --}}
                </div>
            </div>
        </section>
        {{-- Fin --}}

        <div class="row">
            <div class="col-12">
                <section class="d-flex justify-content-end mt-3">
                    <button id="btnGenerar" class="btn btn-primary " type="submit">Generar
                        Inventario</button>
                </section>
            </div>
        </div>
        {{-- Fin --}}
        {!! Form::close() !!}

        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    {{-- <script src="{{ asset('assets/js/signaturePad_v4.0.3.js') }}"></script> --}}
    {{-- Nuevo script --}}
    <script>
        //CODIGO PARA MANTENER POSICIONADO EL INICIO DEL DIBUJO EN EL LUGAR DONDE PONGA EL DEDO O EL PUNTERO DEL MOUSE AL REDIRECCIONAR LA PANTALLA
        screen.orientation.addEventListener("change", function(e) {
            resizeLienzoCarro();
            resizePanelDigital();
            cargarImagen();
        });
        //Fin

        let imagenVehiculo = 'img-vehiculo.jpeg';
        $(document).ready(function() {
            let tipoVehiculo = 'vehiculo';
            $(".btnTipoVehiculo").click(function(e) {
                const tipoVehiculo = $(this).data('tipovehiculo');
                imagenVehiculo = $(this).data('imagen');

                $('#inputTipoVehiculo').val(tipoVehiculo);
                if (tipoVehiculo == 'vehiculo') {
                    atualizarSelectCliente(1);
                    cargarImagen(imagenVehiculo)
                } else {
                    atualizarSelectCliente(2);
                    cargarImagen(imagenVehiculo)
                }
                ejecutarAjax(tipoVehiculo);
            });

            $(function() {
                atualizarSelectCliente(1);
            })
        });

        const listaClientes = @json($clientes);
        const atualizarSelectCliente = (IdTipoVehiculo) => {
            $('#clientes').empty();
            $('#color').val('');
            $('#anio').val('');
            $('#kilometraje').val('');
            $('#clientes').append(`<option value="0"> - </option>`)
            listaClientes.map(function(item) {
                if (item.TipoVehicular == IdTipoVehiculo) {
                    $('#clientes').append(
                        `<option value= ${item.IdCliente}> ${item.RazonSocial} </option>`)
                }
            });
        }

        const ejecutarAjax = (tipoVehiculo) => {
            $.showLoading({
                name: 'circle-fade',
            });
            $.ajax({
                url: "{{ route('consultarDatosTipoVehiculo') }}",
                method: 'GET',
                data: {
                    tipoVehiculo: tipoVehiculo
                },
                success: function(data) {
                    $('.datosTipoVehiculo').html(data);
                    $.hideLoading();
                    cargarDataTable();

                    $(".chbox").click(function() {
                        var id = $(this).attr("id");
                        removeAtributoDisabledParaChbox(id);
                    });

                    $(".chboxAuto").click(function() {
                        var id = $(this).attr("id");
                        removeAtributoDisabledParaChboxAuto(id);
                    });
                },
                error: function(xhr, status, error) {
                    console.error(error);
                }
            });
        }

        const cargarDataTable = () => {
            $('.table').DataTable({
                "scrollX": true,
                "searching": false,
                "bPaginate": false,
                "ordering": false,
                "bInfo": false,
            });
        }

        const removeAtributoDisabledParaChbox = (id) => {
            if ($('#' + id).is(':checked')) {
                $('input[name="input' + id + '"]').prop('disabled', false);
                $('input[name="radioOption' + id + '"]').prop('disabled', false);
                $('input[name="radioOption' + id + '"]').prop('disabled', false);
                $('input[name="radioOption' + id + '"]').prop('disabled', false);
            } else {
                $('input[name="input' + id + '"]').prop('disabled', true);
                $('input[name="radioOption' + id + '"]').prop('disabled', true);
                $('input[name="radioOption' + id + '"]').prop('disabled', true);
                $('input[name="radioOption' + id + '"]').prop('disabled', true);
            }
        }

        const removeAtributoDisabledParaChboxAuto = (id) => {
            if (id == 'chboxAuto4') {
                if ($('#' + id).is(':checked')) {
                    $('input[name="Dias"]').prop('disabled', false);
                    $('input[name="Monto"]').prop('disabled', false);
                } else {
                    $('input[name="Dias"]').prop('disabled', true);
                    $('input[name="Monto"]').prop('disabled', true);
                }
            }
        }
        $(".chbox").click(function() {
            var id = $(this).attr("id");
            removeAtributoDisabledParaChbox(id);
        });

        $(".chboxAuto").click(function() {
            var id = $(this).attr("id");
            removeAtributoDisabledParaChboxAuto(id);
        });
    </script>
    {{-- Fin --}}




    <script type="text/javascript">
        // CODIGO PARA MANTENER POSICIONADO EL INICIO DEL DIBUJO EN EL LUGAR DONDE PONGA EL DEDO O EL PUNTERO DEL MOUSE AL REDIRECCIONAR LA PANTALLA
        screen.orientation.addEventListener("change", function(e) {
            resizeLienzoCarro();
            resizePanelDigital();
            cargarImagen(imagenVehiculo);
        });
        // FIN



        // CODIGO PARA LA IMAGEN DEL CARRO
        const $checkAnomalias = document.getElementById('checkAnomalias');
        const $contenedorAnomaliasFisicas = document.getElementById("contenedorAnomaliasFisicas");
        var canvasCarro = document.getElementById("canvasCarro");
        var signatureCarro = new SignaturePad(canvasCarro, {
            backgroundColor: 'rgb(255, 255, 255)',
        });

        function resizeLienzoCarro() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            canvasCarro.width = canvasCarro.offsetWidth * ratio;
            canvasCarro.height = canvasCarro.offsetHeight * ratio;
            canvasCarro.getContext("2d").scale(ratio, ratio);
            signatureCarro.clear();
        }
        resizeLienzoCarro();

        function cargarImagen(imagenVehiculo) {
            var assetImagenCarro = "{{ asset('assets/img/:imagen') }}";
            assetImagenCarro = assetImagenCarro.replace(':imagen', imagenVehiculo);
            $('#imagenFondoCanvas').attr('src', assetImagenCarro);

            if (/Android|android|webOS|webos|iPhone|iphone|iPad|ipad|iPod|ipod|BlackBerry|blackBerry|IEMobile|iemobile|opera mini|Opera Mini/i
                .test(navigator.userAgent)) {
                signatureCarro.fromDataURL(assetImagenCarro, {
                    ratio: 0
                });
            } else {
                signatureCarro.fromDataURL(assetImagenCarro, {
                    ratio: 1
                });
            }
        }
        cargarImagen(imagenVehiculo);


        $checkAnomalias.addEventListener('click', function() {
            if (this.checked == true) {
                canvasCarro.classList.remove("disabledLienzo")
            } else {
                signatureCarro.clear()
                cargarImagen(imagenVehiculo)
                canvasCarro.classList.add("disabledLienzo")
            }
        });

        document.getElementById('btnDeshacer').addEventListener('click', function() {
            var data = signatureCarro.toData();
            if (data) {
                data.pop();
                signatureCarro.fromData(data);
            }
        });

        SignaturePad.prototype.limpiarLienzo = function() {
            var image = document.getElementById('imagenFondoCanvas');
            var ctx = this._ctx;
            var canvas = this.canvas;
            ctx.fillStyle = this.backgroundColor;
            ctx.clearRect(0, 0, canvas.width, canvas.height);
            ctx.fillRect(0, 0, canvas.width, canvas.height);

            var windowWidth = window.innerWidth;
            if (windowWidth < 576) {
                ctx.drawImage(image, 0, 0, 270, 270);
            }

            if (windowWidth > 576 && windowWidth < 767.98) {
                ctx.drawImage(image, 0, 0, 320, 320);
            }

            if (windowWidth > 768 && windowWidth < 991.98) {
                ctx.drawImage(image, 0, 0, 440, 440);
            }

            if (windowWidth > 992 && windowWidth < 1199.98) {
                ctx.drawImage(image, 0, 0, 560, 560);
            }

            if (windowWidth > 1200) {
                ctx.drawImage(image, 0, 0, 560, 560);
            }

            this._data = [];
            this._reset();
            this._isEmpty = true;
        };

        SignaturePad.prototype.fromData = function(pointGroups) {
            var _this = this;
            this.limpiarLienzo();
            this._fromData(pointGroups, function(_a) {
                var color = _a.color,
                    curve = _a.curve;
                return _this._drawCurve({
                    color: color,
                    curve: curve
                });
            }, function(_a) {
                var color = _a.color,
                    point = _a.point;
                return _this._drawDot({
                    color: color,
                    point: point
                });
            });
            this._data = pointGroups;
        };
        //  Fin

        // CODIGO PARA LA FIRMA DIGITAL
        const $checkFirma = document.getElementById('checkFirma');
        const $contenedorFirmaDigital = document.getElementById("contenedorFirmaDigital");
        var btnLimpiar = document.getElementById("btnLimpiarFirma");
        var panelDigital = document.getElementById("panelDigital");
        var signatureFirma = new SignaturePad(panelDigital, {
            // backgroundColor: 'rgb(255, 255, 255)',
            penColor: 'rgb(0, 0, 0)'
        });

        function resizePanelDigital() {
            var ratio = Math.max(window.devicePixelRatio || 1, 1);
            panelDigital.width = panelDigital.offsetWidth * ratio;
            panelDigital.height = panelDigital.offsetHeight * ratio;
            panelDigital.getContext("2d").scale(ratio, ratio);
            signatureFirma.clear();
        }
        resizePanelDigital()

        $checkFirma.addEventListener('click', function(e) {
            if (this.checked == true) {
                panelDigital.classList.remove("disabledLienzo")
            } else {
                signatureFirma.clear()
                panelDigital.classList.add("disabledLienzo")
            }
        });


        function trimCanvas(c) {
            var ctx = c.getContext('2d'),
                copy = document.createElement('canvas').getContext('2d'),
                pixels = ctx.getImageData(0, 0, c.width, c.height),
                l = pixels.data.length,
                i,
                bound = {
                    top: null,
                    left: null,
                    right: null,
                    bottom: null
                },
                x, y;

            // Iterar sobre cada píxel para encontrar el más alto
            // y donde termina en cada eje
            for (i = 0; i < l; i += 4) {
                if (pixels.data[i + 3] !== 0) {
                    x = (i / 4) % c.width;
                    y = ~~((i / 4) / c.width);

                    if (bound.top === null) {
                        bound.top = y;
                    }

                    if (bound.left === null) {
                        bound.left = x;
                    } else if (x < bound.left) {
                        bound.left = x;
                    }

                    if (bound.right === null) {
                        bound.right = x;
                    } else if (bound.right < x) {
                        bound.right = x;
                    }

                    if (bound.bottom === null) {
                        bound.bottom = y;
                    } else if (bound.bottom < y) {
                        bound.bottom = y;
                    }
                }
            }

            // Calcula la altura y el ancho del contenido.
            var trimHeight = bound.bottom - bound.top,
                trimWidth = bound.right - bound.left,
                trimmed = ctx.getImageData(bound.left, bound.top, trimWidth, trimHeight);

            copy.canvas.width = trimWidth;
            copy.canvas.height = trimHeight;
            copy.putImageData(trimmed, 0, 0);

            // Recortar lienzo y enviar nueva version
            return copy.canvas;
        }

        document.getElementById('btnLimpiarFirma').addEventListener('click', function() {
            signatureFirma.clear()
        });

        // FIN
        document.getElementById('btnGenerar').addEventListener('click', function() {
            var inputCodigoCarro = document.getElementById("imagenCodigoCarro");
            var inputCodigoFirma = document.getElementById("imagenCodigoFirma");
            var dataCarro = signatureCarro.toData();
            var dataFirma = signatureFirma.toData();


            if (dataCarro.length === 0) {
                inputCodigoCarro.setAttribute('value', "");
            } else {
                var dataCodigoImagenCarro = signatureCarro.toDataURL('image/png');
                inputCodigoCarro.setAttribute('value', dataCodigoImagenCarro);
            }

            if (dataFirma.length === 0) {
                inputCodigoFirma.setAttribute('value', "");
            } else {
                var trimmedCanvas = trimCanvas(panelDigital);
                var dataCodigoImagenFirma = trimmedCanvas.toDataURL('image/png');
                inputCodigoFirma.setAttribute('value', dataCodigoImagenFirma);
            }
        });
    </script>

    {{-- FIN --}}

    <script>
        $("#clientes").change(function() {
            var idCliente = $('#clientes').val();
            $.ajax({
                type: 'get',
                url: 'data-vehiculo',
                data: {
                    'IdVehiculo': idCliente
                },
                success: function(result) {
                    console.log(result);
                    $('#color').val(result[0]["Color"]);
                    $('#anio').val(result[0]["Anio"]);
                }
            });
        });

        $('#btnGenerar').on('click', function() {
            var myForm = $("form#myform");
            if (myForm) {
                $(this).attr('disabled', true);
                $(myForm).submit();
            }
        });
    </script>


@stop
