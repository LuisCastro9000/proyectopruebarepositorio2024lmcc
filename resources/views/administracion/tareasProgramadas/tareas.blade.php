@extends('layouts.app')
@section('title', 'Crear Gastos')
@section('content')
<link href='https://unpkg.com/boxicons@2.1.1/css/boxicons.min.css' rel='stylesheet'>
    <div class="container">
        <div class="row page-title clearfix mt-4">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Tareas Programadas</h6>
            </div>
            <div class="page-title-rigth">
                {{-- <button type="submit" class="btn btn--verde">Eliminar Pdf</button> --}}
                <a href="../administracion/tareasProgramadas/lista-pdf"><button class="btn btn-block btn-primary ripple">Ver Pdf</button></a>
            </div>
        </div>
        <div class="tab-pane fade show active" id="grupoSoles" role="tabpanel" aria-labelledby="nav-home-tab">
            <table id="tableSoles" class="table table-responsive-sm" style="width:100%">
                <thead>
                    <tr class="bg-primary">
                        <th scope="col">FechaCreacion</th>
                        <th scope="col" class="text-center">NombreTarea</th>
                        <th scope="col" class="text-center">Opciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($listaTareas as $item)
                        <tr>
                            <td scope="row">{{ $item->FechaCreacion }}</td>
                            <td align="center">{{ $item->NombreTarea }}</td>
                            <td class="text-center">
                                <a href="../administracion/tareasProgramadas/asignar-tarea/{{ $item->IdTarea }}" title="Asignar usuarios a la tarea"><i class='bx bxs-user-plus fs-32'></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
@section('scripts')
<script src="https://unpkg.com/boxicons@2.1.1/dist/boxicons.js"></script>
@stop
