@extends('layouts.app')

@section('content')
    <div class="mb-4">

        <h2 class="fw-bold mb-1">

            Dashboard

        </h2>

        <span class="text-muted">

            Resumen general del proceso de reinducción

        </span>

    </div>

    <div class="row g-4 mb-4">

        <div class="col-lg-4">

            <a href="{{ route('importaciones.historial') }}" class="text-decoration-none text-dark">

                <div class="card border-0 shadow-lg h-100">

                    <div class="card-body d-flex align-items-center">

                        <div class="rounded-circle bg-primary bg-opacity-10 p-3 me-3">

                            <i class="bi bi-upload text-primary fs-2"></i>

                        </div>

                        <div>

                            <div class="text-muted">

                                Importaciones

                            </div>

                            <h2 class="fw-bold mb-0">

                                {{ $totalImportaciones }}

                            </h2>

                        </div>

                    </div>

                </div>

            </a>

        </div>

        <div class="col-lg-4">

            <a href="{{ route('asignaciones.index') }}" class="text-decoration-none text-dark">

                <div class="card border-0 shadow-lg h-100">
                    <div class="card-body d-flex align-items-center">

                        <div class="rounded-circle bg-success bg-opacity-10 p-3 me-3">

                            <i class="bi bi-people-fill text-success fs-2"></i>

                        </div>

                        <div>

                            <div class="text-muted">

                                Colaboradores asignados

                            </div>

                            <h2 class="fw-bold mb-0">

                                {{ $totalAsignaciones }}

                            </h2>

                        </div>

                    </div>

                </div>
            </a>

        </div>

        <div class="col-lg-4">

            {{-- <a href="{{ route('fechas.index') }}" class="text-decoration-none text-dark"> --}}

            <div class="card border-0 shadow-lg h-100">

                <div class="card-body d-flex align-items-center">

                    <div class="rounded-circle bg-warning bg-opacity-10 p-3 me-3">

                        <i class="bi bi-calendar-event text-warning fs-2"></i>

                    </div>

                    <div>

                        <div class="text-muted">

                            Fechas disponibles

                        </div>

                        <h2 class="fw-bold mb-0">

                            {{ $totalFechas }}

                        </h2>

                    </div>

                </div>

            </div>
            {{-- </a> --}}
        </div>

    </div>

    <div class="card border-0 shadow-lg">

        <div class="card-header bg-white border-0 py-4">

            <h5 class="fw-bold mb-0">

                <i class="bi bi-bar-chart-fill text-primary me-2"></i>

                Ocupación por sesión

            </h5>

        </div>

        <div class="card-body">

            @foreach ($fechas as $fecha)
                @php

                    $color = 'success';

                    if ($fecha->porcentaje >= 90) {
                        $color = 'danger';
                    } elseif ($fecha->porcentaje >= 70) {
                        $color = 'warning';
                    }

                @endphp

                <div class="mb-4">

                    <div class="d-flex justify-content-between mb-2">

                        <div>

                            <strong>

                                {{ $fecha->descripcion }}

                            </strong>

                        </div>

                        <div class="fw-bold">

                            {{ $fecha->ocupados }}

                            /

                            {{ $fecha->cupo_maximo }}

                        </div>

                    </div>

                    <div class="progress rounded-pill" style="height:18px;">

                        <div class="progress-bar bg-{{ $color }} progress-bar-striped progress-bar-animated"
                            role="progressbar" style="width: {{ $fecha->porcentaje }}%">

                            {{ $fecha->porcentaje }}%

                        </div>

                    </div>

                    <div class="d-flex justify-content-between mt-2">

                        <small class="text-muted">

                            Disponibles:

                            {{ $fecha->disponibles }}

                        </small>

                        <small class="text-muted">

                            {{ $fecha->porcentaje }} % ocupado

                        </small>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

    
@endsection
