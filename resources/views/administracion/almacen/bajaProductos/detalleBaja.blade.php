@extends('layouts.app')
@section('title', 'Detalle Baja')
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-info mt-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        <section
            class="d-flex justify-content-center flex-wrap align-items-end justify-content-sm-between flex-column flex-sm-row my-4">
            <section>
                <h6>Detalle Baja</h6>
            </section>
            <section class="d-flex align-items-center flex-wrap justify-content-center">
                <a class="p-1" href="{{ route('baja-productos.obtener-pdf', [$idDetalleBaja, 'Descargar']) }}"
                    target="_blank"><button class="btn btn-block btn-primary ripple"><i
                            class="list-icon material-icons fs-20">picture_as_pdf</i> Vale de Baja</button></a>
                <button type="button" class="border-0 bg-transparent cursor-pointer" data-toggle="modal"
                    data-target="#modalUsuarios">
                    <img class="logo-expand" alt="" width="40" src="{{ asset('assets/img/whatsapp.png') }}">
                </button>
            </section>
        </section>
        {{-- seccion detalle --}}
        <section class="jumbotron bg-white text-secondary">
            <table id="table" class="table table-bordered table-responsive-sm" style="width: 100%">
                <thead>
                    <tr class="bg-success-dark text-white text-center">
                        <th>CódigoBarra</th>
                        <th>Descripción</th>
                        <th>FechaBaja</th>
                        <th>Cantidad de Bajas</th>
                        <th>Nuevo Stock</th>
                        <th>Motivo</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($detalleBaja as $baja)
                        <tr class="text-center">
                            <td scope="row">{{ $baja->CodigoBarra }}</td>
                            <td scope="row">{{ $baja->NombreArticulo }}</td>
                            <td scope="row">{{ $baja->FechaBaja }}</td>
                            <td scope="row">{{ $baja->CantidadBajas }}</td>
                            <td scope="row">{{ $baja->NuevoStock }}</td>
                            <td>
                                @if ($baja->IdMotivo == 1)
                                    Consumo Interno
                                @elseif($baja->IdMotivo == 2)
                                    Producto Vencido
                                @elseif($baja->IdMotivo == 3)
                                    Perdida y/o Extravio
                                @else
                                    {{ $baja->DescripcionMotivo }}
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </section>
    </div>
    <section>
        <div class="modal fade" id="modalUsuarios" tabindex="-1" role="dialog" aria-labelledby="exampleModal3Label"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered" role="document">
                <div class="modal-content">
                    <form id="formularioEnviarWhatsApp"
                        action="{{ route('baja-productos.obtener-pdf', [$idDetalleBaja, 'EnviarPorWhatsApp']) }}"
                        method="post" target="_blank">
                        @method('GET')
                        <div class="modal-header d-flex justify-content-center">
                            <h5 class="modal-title" id="exampleModal3Label">Lista de usuarios</h5>
                        </div>
                        <div class="modal-body">
                            <div class="form-group form-material">
                                <select id="selectUsuarios" class="m-b-10 form-control select2-hidden-accessible"
                                    id="cliente" name="cliente" data-placeholder="Seleccione producto"
                                    data-toggle="select2" tabindex="-1" aria-hidden="true">
                                    <option value="0">Seleccione Usuario</option>
                                    @foreach ($listaUsuarios as $usuario)
                                        <option value="{{ $usuario->IdUsuario }} ">{{ $usuario->Nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control input-transparent--gris" id="inputTelefono"
                                    placeholder="Teléfono Cliente" name="inputTelefono">
                                <input type="hidden" class="form-control" id="inputNombreUsuario"
                                    name="inputNombreUsuario">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Enviar WhatsApp</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
@stop
@section('scripts')
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "paging": false,
                    "ordering": false,
                    "info": false,
                    "searching": false
                });

                // Quitar el Blur del input
                $('.modal').on('shown.bs.modal', function() {
                    $("input:text:visible:first").blur();
                })

            });

        });
    </script>
    <script>
        $(document).on('change', '#selectUsuarios', function() {
            const listaUsuarios = @json($listaUsuarios);
            const idUsuario = $(this).val();
            if (parseInt(idUsuario) === 0) {
                $('#inputTelefono').val('');
                $('#inputNombreUsuario').val('');
                return false;
            }
            const resultado = listaUsuarios.find(usuario => usuario.IdUsuario === parseInt(idUsuario));
            $('#inputTelefono').val(resultado.Telefono);
            $('#inputNombreUsuario').val(resultado.Nombre);
        })
    </script>
@stop
