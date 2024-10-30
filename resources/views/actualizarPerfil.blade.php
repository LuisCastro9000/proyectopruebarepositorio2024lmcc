@extends('layouts.app')
@section('title', 'Actualizar Perfil')
@section('content')
    <link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">

        @if (session::has('status'))
            <div class="alert alert-success">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                {{ session('status') }}
            </div>
        @endif
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="row mt-4 justify-content-center">
            <div class="col-12 col-md-8">
                @error('status')
                    <div class="alert alert-danger">{{ $message }}</div>
                @enderror
                <div class="jumbotron jumbotron-fluid border-radius--20">
                    <div class="container">
                        <section class="mb-3 text-center">
                            <h6 class="page-title-heading mr-0 mr-r-5">Actualizar Datos</h6>
                        </section>
                        <section>
                            <form method="Post" action="{{ url('/enviar-datos/actualizar') }}">
                                {{-- {!! Form::open(['url' => '/enviar-datos/actualizar', 'method' => 'POST', 'files' => true]) !!} --}}
                                @csrf
                                <div class="form-group">
                                    <label for="formGroupExampleInput">Nombre</label>
                                    <input type="text" class="form-control py-2" id="formGroupExampleInput"
                                        value="{{ $usuarioSelect->Nombre }}" name="nombre">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Dirección</label>
                                    <input type="text" class="form-control py-2" id="formGroupExampleInput2"
                                        value="{{ $usuarioSelect->Direccion }}" name="direccion">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Dni</label>
                                    <input type="text" class="form-control py-2" id="formGroupExampleInput2"
                                        value="{{ $usuarioSelect->DNI }}" name="dni">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Celular</label>
                                    <input type="text" class="form-control py-2" id="formGroupExampleInput2"
                                        value="{{ $usuarioSelect->Telefono }}" name="celular">
                                </div>
                                <div class="form-group">
                                    <label for="formGroupExampleInput2">Email</label>
                                    <input type="text" class="form-control py-2" id="formGroupExampleInput2"
                                        value="{{ $usuarioSelect->Email }}" name="email">
                                </div>
                                <div class="text-center mt-4">
                                    <button type="submit" class=" btn btn--verde">Actualizar</button>
                                    <button type="button" class="btn btn--verde" id="btnEditar">Editar</button>
                                     <button type="submit" class=" btn btn--celeste font-weight-bold d-none"  name="btn" value="2">Acepto que están Correctos</button>
                                </div>
                                {{-- {!! Form::close() !!} --}}
                            </form>
                        </section>
                    </div>
                </div>

            </div>
        </div>
    </div>

@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="{{ asset('assets/js/perfil/actualizarPerfil.js') }}"></script>
@stop
