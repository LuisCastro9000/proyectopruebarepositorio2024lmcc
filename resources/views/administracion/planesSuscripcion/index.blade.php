@extends('layouts.app')
@section('title', 'Planes-Suscripción')
@section('content')
    <div class="container">
        @if (session('success'))
            <div class="alert alert-info mt-4">
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                {{ session('success') }}
            </div>
        @endif
        <div class="row page-title clearfix mt-4">
            <div class="page-title-left">
                <h6 class="page-title-heading mr-0 mr-r-5">Planes de Suscripción</h6>
            </div>
        </div>
        <section class="row">
            @foreach ($planesSuscripcion as $plan)
                <article class="col-12 col-md-3">
                    <div class="card bg-celeste">
                        <div class="card-body text-center">
                            <span class="d-block fs-20 font-weight-bolder text-uppercase">{{ $plan->Nombre }}</span>
                            <a href="{{ route('planesSuscripcion.edit', $plan->IdPlanSuscripcion) }}">Ver Detalle</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </section>
    </div>
@stop
@section('scripts')
@stop
