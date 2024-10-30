@extends('layouts.app')
@section('title', 'Lista Documentos')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">

        @if (session::has('status'))
            <div class="alert alert-success">
                {{ session('status') }}
            </div>
        @endif
        <section class="mt-3">
            @if (session::has('succes'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('succes') }}
                </div>
            @endif
            @if (session::has('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    {{ session('error') }}
                </div>
            @endif
        </section>
        {{-- {!! Form::open([
            'url' => '/administracion/usuarios/enviar-documentos/' . $idUsuarioRetirado,
            'method' => 'POST',
            'name' => 'formComprimirDocumentos',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}
        <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
            <section class="d-flex justify-content-center justify-content-sm-end mb-3">
                <button class="btn btn-primary" type="submit" onclick="return confirmarActulizarPermisos()">Comprimir
                    Documentos</button>
            </section>
            @php $indice=1; @endphp
            @php $idMes=1; @endphp
            @foreach ($directorioXanio as $anio)
                <div id="accordion" role="tablist">
                    <div class="card">
                        <div class="card-header" role="tab" id="headingOne-{{ $indice }}">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="anio-{{ $indice }}"
                                    value="{{ $anio }}" onclick='obtenerPathXmes({{ $indice }})'
                                    name="anio[]">
                                <label class="custom-control-label collapsed" for="anio-{{ $indice }}"
                                    data-toggle="collapse" href="#collapseOne-{{ $indice }}" aria-expanded="false"
                                    aria-controls="collapseOne-{{ $indice }}">Año::
                                    {{ Str::substr($anio, 15) }}</label>
                            </div>
                        </div>

                        <div id="collapseOne-{{ $indice }}" class="collapse" role="tabpanel"
                            aria-labelledby="headingOne">
                            @foreach ($directoriosXmes as $mes)
                                @if (strpos($mes, Str::substr($anio, 15)) !== false)
                                    <section class="seccionMes my-4">
                                        <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                            <input type="checkbox"
                                                class="custom-control-input checkMes-{{ $indice }}"
                                                id="mes-{{ $idMes }}" value="{{ $mes }}" name="mes[]"
                                                disabled onclick="obtenerPathDocumentos({{ $idMes }})">
                                            <label class="custom-control-label" for="mes-{{ $idMes }}">Mes::
                                                {{ Str::substr($mes, 20) }}</label>
                                        </div>
                                        <section class="seccionDocumentos">
                                            @foreach ($directoriosXdocumentos as $documento)
                                                @if (strpos($documento, Str::substr($mes, 15)) !== false)
                                                    <div class="custom-control custom-checkbox offset-4 offset-sm-2">
                                                        @if (strpos($documento, 'FacturasBoletas') !== false)
                                                            <input type="checkbox"
                                                                class="custom-control-input checkDocumento-{{ $idMes }} checkDocumentoDisabled-{{ $indice }}"
                                                                id="documento-{{ $documento }}" disabled>
                                                            <label class="custom-control-label"
                                                                for="documento-{{ $documento }}">
                                                                {{ Str::substr($documento, -15) }}</label>
                                                        @elseif (strpos($documento, 'ResumenDiario') !== false)
                                                            <input type="checkbox"
                                                                class="custom-control-input checkDocumento-{{ $idMes }} checkDocumentoDisabled-{{ $indice }}"
                                                                id="documento-{{ $documento }}" disabled>
                                                            <label class="custom-control-label"
                                                                for="documento-{{ $documento }}">
                                                                {{ Str::substr($documento, -13) }}</label>
                                                        @elseif (strpos($documento, 'GuiasRemision') !== false)
                                                            <input type="checkbox"
                                                                class="custom-control-input checkDocumento-{{ $idMes }} checkDocumentoDisabled-{{ $indice }}"
                                                                id="documento-{{ $documento }}" disabled>
                                                            <label class="custom-control-label"
                                                                for="documento-{{ $documento }}">
                                                                {{ Str::substr($documento, -13) }}</label>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </section>

                                    </section>
                                @endif
                                @php $idMes++; @endphp
                            @endforeach
                        </div>

                    </div>
                </div>
                @php $indice++; @endphp
            @endforeach
        </div>
        {!! Form::close() !!} --}}

        {!! Form::open([
            'url' => '/administracion/usuarios/comprimir-documentos/' . $idUsuarioRetirado,
            'method' => 'POST',
            'name' => 'formComprimirDocumentos',
            'files' => true,
        ]) !!}
        {{ csrf_field() }}
        <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
            <section class="text-center mb-2">
                <label class="fs-16" for="numeroCelular">Ingrese el Nombre del Archivo .Zip</label>
                <input type="text" class="form-control text-center text-capitalize" name="nombreZip" id="nombreZip"
                    autofocus>
            </section>
            <section class="d-flex justify-content-center justify-content-sm-end mb-3">
                <button class="btn btn-primary" type="submit">Comprimir
                    Documentos</button>
                <a class="btn btn-primary text-light ml-2" type="submit" data-toggle="modal"
                    data-target="#modalUrlDocumentosZip">Ver
                    Url</a>
            </section>
            @php $indice=1; @endphp
            @php $idMes=1; @endphp
            @foreach ($directorioXanio as $anio)
                <div id="accordion" role="tablist">
                    <div class="card">
                        <div class="card-header" role="tab" id="headingOne-{{ $indice }}">
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="anio-{{ $indice }}"
                                    value="{{ $anio }}" onclick='obtenerPathXmes({{ $indice }})'
                                    name="anio[]">
                                <label class="custom-control-label collapsed" for="anio-{{ $indice }}"
                                    data-toggle="collapse" href="#collapseOne-{{ $indice }}" aria-expanded="false"
                                    aria-controls="collapseOne-{{ $indice }}">Año::
                                    {{ Str::substr($anio, 15) }}</label>
                            </div>
                        </div>

                        <div id="collapseOne-{{ $indice }}" class="collapse" role="tabpanel"
                            aria-labelledby="headingOne">
                            @foreach ($directoriosXmes as $mes)
                                @if ($mes->Anio == Str::substr($anio, 15))
                                    <section class="seccionMes my-4">
                                        <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                            <input type="checkbox"
                                                class="custom-control-input checkMes-{{ $indice }}"
                                                id="mes-{{ $idMes }}" value="{{ $mes->PathMes }}" name="mes[]"
                                                disabled onclick="obtenerPathDocumentos({{ $idMes }})">
                                            <label class="custom-control-label" for="mes-{{ $idMes }}">Mes::
                                                {{ $mes->Mes }}</label>
                                        </div>
                                        <section class="seccionDocumentos">
                                            @foreach ($directoriosXdocumentos as $documento)
                                                @if (strpos($documento->PathDocumento, Str::substr($mes->PathMes, 15)) !== false)
                                                    <article class="offset-4 offset-sm-2">
                                                        @if (strpos($documento->PathDocumento, 'FacturasBoletas') !== false)
                                                            <div class="row">
                                                                <div class="col-8 col-sm-3">
                                                                    <b>{{ Str::substr($documento->PathDocumento, -15) }}</b>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="badge badge-success">{{ $documento->CantidadArchivos }}</span>
                                                                </div>
                                                            </div>
                                                        @elseif (strpos($documento->PathDocumento, 'ResumenDiario') !== false)
                                                            <div class="row">
                                                                <div class="col-8 col-sm-3">
                                                                    <b>{{ Str::substr($documento->PathDocumento, -13) }}</b>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="badge badge-success">{{ $documento->CantidadArchivos }}</span>
                                                                </div>
                                                            </div>
                                                        @elseif (strpos($documento->PathDocumento, 'GuiasRemision') !== false)
                                                            <div class="row">
                                                                <div class="col-8 col-sm-3">
                                                                    <b>{{ Str::substr($documento->PathDocumento, -13) }}</b>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="badge badge-success">{{ $documento->CantidadArchivos }}</span>
                                                                </div>
                                                            </div>
                                                        @elseif (strpos($documento->PathDocumento, 'NotasCreditoDebito') !== false)
                                                            <div class="row">
                                                                <div class="col-8 col-sm-3">
                                                                    <b>{{ Str::substr($documento->PathDocumento, -18) }}</b>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="badge badge-success">{{ $documento->CantidadArchivos }}</span>
                                                                </div>
                                                            </div>
                                                        @elseif (strpos($documento->PathDocumento, 'BajaDocumentos') !== false)
                                                            <div class="row">
                                                                <div class="col-8 col-sm-3">
                                                                    <b>{{ Str::substr($documento->PathDocumento, -14) }}</b>
                                                                </div>
                                                                <div class="col-1">
                                                                    <span
                                                                        class="badge badge-success">{{ $documento->CantidadArchivos }}</span>
                                                                </div>
                                                            </div>
                                                        @endif
                                                    </article>
                                                @endif
                                            @endforeach
                                        </section>
                                    </section>
                                @endif
                                @php $idMes++; @endphp
                            @endforeach
                        </div>

                    </div>
                </div>
                @php $indice++; @endphp
            @endforeach
        </div>
        {!! Form::close() !!}

        <div class="modal fade bd-example-modal-lg" tabindex="-1" id="modalUrlDocumentosZip" role="dialog"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
                <div class="modal-content">
                    {!! Form::open([
                        'url' => '/administracion/usuarios/enviar-documentos-zip/' . $idUsuarioRetirado,
                        'method' => 'POST',
                        'name' => 'formComprimirDocumentos',
                        'files' => true,
                    ]) !!}
                    {{ csrf_field() }}
                    <div class="modal-body">
                        <section class="text-center mb-4 "><b class="fs-16 text-uppercase">Url Documentos Zip</b></section>
                        <section class="mb-4"><input type="text" class="form-control text-center"
                                name="correoUsuarioRetirado" autofocus value="{{ $correoUsuarioRetirado }}"
                                placeholder="pruebasoporte@autocontrol.pe">
                        </section>
                        @php($codigo = 1)
                        @if (count($UrlEnviados) >= 1)
                            <p class="text-danger">URl Enviadas</p>
                            @foreach ($UrlEnviados as $url)
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input"
                                        id="customCheck1{{ $codigo }}" name="urlDocumentos[]"
                                        value="{{ $url }}">
                                    <label class="custom-control-label ajustar-texto font-weight-normal"
                                        for="customCheck1{{ $codigo }}">{{ $url }}</label>
                                </div>
                                @php($codigo++)
                            @endforeach
                        @endif
                        @if (count($UrlDocumentosZip) >= 1)
                            <p class="text-danger">URl por Enviar</p>
                            @foreach ($UrlDocumentosZip as $url)
                                <div class="custom-control custom-checkbox mb-2">
                                    <input type="checkbox" class="custom-control-input"
                                        id="customCheck1{{ $codigo }}" name="urlDocumentos[]"
                                        value="{{ $url }}">
                                    <label class="custom-control-label ajustar-texto font-weight-normal"
                                        for="customCheck1{{ $codigo }}">{{ $url }}</label>
                                </div>
                                @php($codigo++)
                            @endforeach
                        @endif
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-primary">Enviar X Correo</button>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </div>

        {{-- <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
            <div id="accordion" role="tablist">
                <div class="card">
                    <div class="card-header" role="tab" id="headingOne">
                        <h5 class="mb-0">
                            <a data-toggle="collapse" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                Collapsible Group Item #1
                            </a>
                        </h5>
                    </div>

                    <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header" role="tab" id="headingTwo">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" href="#collapseTwo" aria-expanded="false"
                                aria-controls="collapseTwo">
                                Collapsible Group Item #2
                            </a>
                        </h5>
                    </div>
                    <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header" role="tab" id="headingTwo">
                        <h5 class="mb-0">
                            <a class="collapsed" data-toggle="collapse" href="#collapseTree" aria-expanded="false"
                                aria-controls="collapseTwo">
                                Collapsible Group Item #2
                            </a>
                        </h5>
                    </div>
                    <div id="collapseTree" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                        <div class="card-body">
                            Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid.
                        </div>
                    </div>
                </div>
            </div>
        </div> --}}

        {{-- <div class="jumbotron mt-3 bg-jumbotron bg-jumbotron--grisClaro">
            @foreach ($directorioXanio as $anio)
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" class="custom-control-input" id="anio-{{ $anio }}">
                    <label class="custom-control-label" for="anio-{{ $anio }}">Año::
                        {{ Str::substr($anio, 15) }}</label>
                    <section id="documentosXanio">
                        @foreach ($directoriosXmes as $mes)
                            @if (strpos($mes, Str::substr($anio, 15)) !== false)
                                <section id="mes">
                                    <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                        <input type="checkbox" class="custom-control-input" id="mes-{{ $mes }}">
                                        <label class="custom-control-label" for="mes-{{ $mes }}">Mes::
                                            {{ Str::substr($mes, 20) }}</label>
                                        <section id="documentos">
                                            @foreach ($directoriosXdocumentos as $documento)
                                                @if (strpos($documento, Str::substr($mes, 15)) !== false)
                                                    <div class="custom-control custom-checkbox offset-2 offset-sm-1">
                                                        @if (strpos($documento, 'FacturasBoletas') !== false)
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="mes-{{ $documento }}">
                                                            <label class="custom-control-label"
                                                                for="mes-{{ $documento }}">
                                                                {{ Str::substr($documento, -15) }}</label>
                                                        @elseif (strpos($documento, 'ResumenDiario') !== false)
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="mes-{{ $documento }}">
                                                            <label class="custom-control-label"
                                                                for="mes-{{ $documento }}">
                                                                {{ Str::substr($documento, -13) }}</label>
                                                        @elseif (strpos($documento, 'GuiasRemision') !== false)
                                                            <input type="checkbox" class="custom-control-input"
                                                                id="mes-{{ $documento }}">
                                                            <label class="custom-control-label"
                                                                for="mes-{{ $documento }}">
                                                                {{ Str::substr($documento, -13) }}</label>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                        </section>
                                    </div>
                                </section>
                            @endif
                        @endforeach
                    </section>
                </div>
            @endforeach
        </div> --}}

    </div>

@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function obtenerPathXmes(id) {
            if ($('#anio-' + id).prop('checked')) {
                $('.checkMes-' + id).removeAttr('disabled', 'disabled');
                $('.checkDocumentoDisabled-' + id).removeAttr('disabled', 'disabled');
                $('.checkMes-' + id).prop("checked", true);
            } else {
                $('.checkMes-' + id).prop("checked", false);
                $('.checkMes-' + id).attr('disabled', 'disabled');
                $('.checkDocumentoDisabled-' + id).attr('disabled', 'disabled');
            }
        }

        function obtenerPathDocumentos(id) {
            if ($('#mes-' + id).prop('checked')) {
                $('.checkDocumento-' + id).removeAttr('disabled', 'disabled');
                $('.checkDocumento-' + id).prop("checked", true);
            } else {
                $('.checkDocumento-' + id).prop("checked", false);
                $('.checkDocumento-' + id).attr('disabled', 'disabled');
            }
        }
    </script>
@stop
