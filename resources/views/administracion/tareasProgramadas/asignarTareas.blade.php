@extends('layouts.app')
@section('title', 'Crear Gastos')
@section('content')
    <div class="container">
        <div class="row  clearfix my-4">
            <section class="col-12 d-flex justify-content-between">
                <div>
                    <h6 class="page-title-heading mr-0 mr-r-5">Asignar Tarea a los Usuarios</h6>
                    <input type="hidden" name="idTarea" value="{{ $idTarea }}">
                </div>
                <div>
                    <button type="button" class="btn btn-primary" onclick="guardarDatos()">Guardar</button>
                </div>
            </section>
        </div>
        <div class="tab-pane fade show active" id="grupoSoles" role="tabpanel" aria-labelledby="nav-home-tab">
            <table id="table" class="table table-responsive-sm" style="width:100%">
                <thead>
                    <tr class="bg-primary">
                        <th scope="col">Sucursal</th>
                        <th scope="col">Nombre de Usuario</th>
                        <th scope="col">Login</th>
                        <th scope="col" class="text-center">Seleccionar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($usuarios as $usuarios)
                        <tr>
                            <td>{{ $usuarios->Sucursal }}</td>
                            <td>
                                {{ $usuarios->Nombre }}
                            </td>
                            <td>{{ $usuarios->Login }}</td>
                            <td class="text-center">
                                <div class="custom-control custom-checkbox">
                                    @if ($usuarios->checkValor == 1)
                                        <a class="" onclick="agregarDatos({{ $usuarios->idUsuario }})"
                                            href="javascript:void(0);">
                                            <input type="checkbox" class="custom-control-input"
                                                id="checkFila-{{ $usuarios->idUsuario }}" checked>
                                            <label class="custom-control-label"
                                                for="checkFila-{{ $usuarios->idUsuario }}"></label>
                                        </a>
                                    @else
                                        <a class="" onclick="agregarDatos({{ $usuarios->idUsuario }})"
                                            href="javascript:void(0);">
                                            <input type="checkbox" class="custom-control-input"
                                                id="checkFila-{{ $usuarios->idUsuario }}">
                                            <label class="custom-control-label"
                                                for="checkFila-{{ $usuarios->idUsuario }}"></label>
                                        </a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('scripts')
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [3, "desc"]
                    ],
                    language: {
                        processing: "Procesando...",
                        search: "Buscar:",
                        lengthMenu: "Mostrar _MENU_ registros",
                        info: "Registros del _START_ al _END_ de un total de _TOTAL_",
                        infoEmpty: "Registros del 0 al 0 de un total de 0 registros",
                        infoFiltered: "",
                        infoPostFix: "",
                        loadingRecords: "Cargando...",
                        zeroRecords: "No se encontraron resultados",
                        emptyTable: "Ningún dato disponible en esta tabla",
                        paginate: {
                            first: "Primero",
                            previous: "Anterior",
                            next: "Siguiente",
                            last: "Último"
                        },
                        aria: {
                            sortAscending: ": Activar para ordenar la columna de manera ascendente",
                            sortDescending: ": Activar para ordenar la columna de manera descendente"
                        }
                    }
                });
            });
        });
    </script>
    <script>
        var $arrayIds = <?php echo json_encode($listaIdUsuarios); ?>;
        $(function() {
            if ($arrayIds.length < 1) {
                $arrayIds = [];
            }
            console.log($arrayIds);
        })

        function agregarDatos(id) {
            $("#checkFila-" + id).change(function() {
                console.log(id);
                if (this.checked) {
                    $arrayIds.push(parseInt(id));
                    // $arrayIds.sort();
                    // console.log($arrayIds);
                } else {
                    var myIndex = $arrayIds.indexOf(id);
                    if (myIndex > -1) {
                        $arrayIds.splice(myIndex, 1);
                    }
                    // console.log("NO chekeado");
                    // console.log($arrayIds);
                }
            })
        }

        function guardarDatos() {
            var arrayIdUsuario = $arrayIds;
            var idTarea = $("input[name='idTarea']").val();
            var estadoTarea = "Activado";
            $.ajax({
                type: 'post',
                url: '../asignar-tarea-usuarios',
                data: {
                    "_token": "{{ csrf_token() }}",
                    "arrayIdUsuario": arrayIdUsuario,
                    "idTarea": idTarea,
                    "estadoTarea": estadoTarea
                },
                success: function(data) {
                    if (data[0] == "error") {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'No existen datos seleccionados',
                        })
                    } else {
                        Swal.fire({
                            title: 'Buen trabajo!',
                            text: 'Se registro Correctamente',
                            icon: 'success',
                            confirmButtonColor: '#3085d6',
                        }).then((result) => {
                            if (result.isConfirmed) {
                                window.location = '../../tareas-programadas';
                            }
                        })
                    }
                }
            })
        }
    </script>
@stop
