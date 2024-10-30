@extends('layouts.app')
@section('title', 'Notificar Mantenimiento')
@section('content')
    @if (session('error'))
        <div class="alert alert-danger">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            {{ session('error') }}
        </div>
    @endif

    <div class="container m-x-auto pl-3">
        <div class="row mt-4">
            <div class="col">
                <div class="mb-3 mb-sm-0">
                    <h6 class="page-title-heading mr-0 mr-r-5">Vehículos para Notificar Mantenimiento</h6>
                </div>
            </div>
        </div>
        <div class="row justify-content-center mt-4">

            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #28A745;">
                    <strong class="fs-14 text-white">Km Inicial</strong>
                    <br>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #148CBA;">
                    <strong class="fs-14 text-white">Km Intermedio</strong>
                    <br>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #FFC107;">
                    <strong class="fs-14 text-white">Km Aproximado</strong>
                    <br>
                </div>
            </div>
            <div class="col-md-2 col-sm-4 col-6 p-1">
                <div class="text-center border-radius--5" style="background-color: #ff0000;">
                    <strong class="fs-14 text-white">Km Alcanzado</strong>
                    <br>
                </div>
            </div>
        </div>
        <div class="row mt-4">
            <div class=" col-12 widget-holder">
                <div class="widget-bg">
                    <div class="widget-body clearfix">
                        <table id="table" class="table table-responsive-sm" style="width:100%">
                            <thead>
                                <tr class="bg-primary text-center">
                                    <th scope="col">Placa</th>
                                    <th scope="col">Cliente</th>
                                    <th scope="col">Fecha de Atención</th>
                                    <th scope="col">Próxima Fecha de Atención</th>
                                    <th scope="col">Lapso de Días</th>
                                    <th scope="col">Días Restantes</th>
                                    <th scope="col">Estado</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($listaSalidaVehicular as $vehiculo)
                                    <tr>
                                        <td>{{ $vehiculo->PlacaVehiculo }}</td>
                                        <td class="text-center">{{ $vehiculo->Nombre }}</td>
                                        <td class="text-center">{{ $vehiculo->FechaSalida }}</td>
                                        <td class="text-center">{{ $vehiculo->ProximaFecha }}</td>
                                        <td class="text-center">{{ $vehiculo->Periodo }}</td>
                                        <td class="text-center">{{ $vehiculo->Periodo - $vehiculo->DiasRestantes }}</td>
                                        @if (($listaSalidaVehicular[0]->DiasRestantes * 100) / $listaSalidaVehicular[0]->Periodo >= 0 &&
                                            ($listaSalidaVehicular[0]->DiasRestantes * 100) / $listaSalidaVehicular[0]->Periodo < 50)
                                            <td class="text-center"><span class="badge text-light"
                                                    style="background-color: #28A745;">Km Inicial</span>
                                        @endif
                                        @if (($listaSalidaVehicular[0]->DiasRestantes * 100) / $listaSalidaVehicular[0]->Periodo >= 50 &&
                                            ($listaSalidaVehicular[0]->DiasRestantes * 100) / $listaSalidaVehicular[0]->Periodo <= 90)
                                            <td class="text-center"><span class="badge text-light"
                                                    style="background-color:#148CBA;;">Km Intermedio</span>
                                            </td>
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop

@section('scripts')
    <script type="text/javascript">
        $(function() {
            $(document).ready(function() {
                $('#table').DataTable({
                    responsive: true,
                    "order": [
                        [0, "desc"]
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
@stop
