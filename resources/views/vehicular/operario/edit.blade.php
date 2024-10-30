@extends('layouts.app')
@section('title', 'Editar Operario')
@section('content')
    <div class="container">
        <div class="row page-title clearfix">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Editar Operario</h6>
            </div>
            <!-- /.page-title-left -->
        </div>
        <!-- /.page-title -->
    </div>
    <!-- /.container -->
    <!-- =================================== -->
    <!-- Different data widgets ============ -->
    <!-- =================================== -->
    <div class="container">
        <div class="widget-list">
            <div class="row">
                <div class="col-md-12 widget-holder">
                    <div class="widget-bg">
                        <div class="widget-body clearfix">
                            {!! Form::open([
                                'url' => 'vehicular/administracion/operario/' . $operario->IdOperario,
                                'method' => 'PUT',
                                'class' => 'form-material formularioConFirma',
                            ]) !!}
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="col-md-6">
                                    <label for="nombres">Nombres y Apellidos</label>
                                    <input class="form-control" id="nombres" placeholder="Nombre y Apellido"
                                        type="text" name="nombres" value="{{ $operario->Nombres }}">
                                    <span class="text-danger font-size">{{ $errors->first('nombres') }}</span>
                                </div>
                                <div class="col-md-6">
                                    <label for="rol">Seleccionar Rol</label>
                                    <select class="form-control" name="rol">
                                        <option value="1">Ninguno</option>
                                        @foreach ($roles as $rol)
                                            @if ($rol->IdRolOperario > 1)
                                                @if ($rol->IdRolOperario == $operario->IdRolOperario)
                                                    <option selected value="{{ $rol->IdRolOperario }}">
                                                        {{ $rol->Descripcion }}</option>
                                                @else
                                                    <option value="{{ $rol->IdRolOperario }}">{{ $rol->Descripcion }}
                                                    </option>
                                                @endif
                                            @endif
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            {{-- seccion Firma --}}
                            <br>
                            @if ($operario->ImagenFirma != null)
                                <section>
                                    <div class="card-header text-center bg-griss font-weight-bold text-uppercase">
                                        Firma
                                    </div>
                                    <input type="hidden" name="inputImagenFirmaAnterior"
                                        value="{{ $operario->ImagenFirma }}">
                                    <article class="p-4">
                                        <div class="m-auto" style="width:100px; height:75px">
                                            @if (str_contains($operario->ImagenFirma, config('variablesGlobales.urlDominioAmazonS3')))
                                                <img src="{{ $operario->ImagenFirma }}" alt="Imagen Firma"
                                                    style="width:100%; height:100%">
                                            @else
                                                <img src="{{ config('variablesGlobales.urlDominioAmazonS3') . $operario->ImagenFirma }}"
                                                    alt="Imagen Firma" style="width:100%; height:100%">
                                            @endif
                                        </div>
                                    </article>
                                </section>
                            @endif
                            <section class="col-12 mt-4">
                                @include('lienzoFirma.lienzoFirma')
                            </section>
                            {{-- Fin --}}

                            <div class="form-actions btn-list mt-3 d-flex justify-content-end">
                                <button class="btn btn-info" type="submit">actualizar</button>
                                <a href="../../operario"><button class="btn btn-outline-default"
                                        type="button">Cancelar</button></a>
                            </div>
                            {!! Form::close() !!}
                        </div>
                        <!-- /.widget-body -->
                    </div>
                    <!-- /.widget-bg -->
                </div>
                <!-- /.widget-holder -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.widget-list -->
    </div>
    <!-- /.container -->
@stop

@section('scripts')
    <script src="{{ asset('assets/js/signature_pad.js') }}"></script>
    <script src="{{ asset('assets/js/canvas/recortarImagen.js') }}"></script>
    <script src="{{ asset('assets/js/lienzoFirma/lienzoFirma.js?v=' . time()) }}"></script>
@stop
