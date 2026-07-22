@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <div>

        <h2 class="fw-bold mb-0">

            Historial de Importaciones

        </h2>

        <small class="text-muted">

            Administra las cargas realizadas.

        </small>

    </div>

</div>

<div class="row">

@forelse($importaciones as $importacion)

<div class="col-lg-4 mb-4">

    <div class="card border-0 shadow-lg h-100">

        <div class="card-body">

            <div class="d-flex justify-content-between">

                <span class="badge bg-primary">

                    #{{ $importacion->id }}

                </span>

                @if($importacion->estado=='REVERTIDA')

                    <span class="badge bg-danger">

                        Eliminada

                    </span>

                @else

                    <span class="badge bg-success">

                        Activa

                    </span>

                @endif

            </div>

            <h5 class="mt-3">

                {{ $importacion->archivo_original }}

            </h5>

            <hr>

            <p class="mb-1">

                <strong>Líder:</strong>

                {{ $importacion->lider_nombre }}

            </p>

            <p class="mb-1">

                <strong>Documento:</strong>

                {{ $importacion->lider_documento }}

            </p>

            <p class="mb-1">

                <strong>Fecha:</strong>

                {{ $importacion->created_at->format('d/m/Y h:i A') }}

            </p>

            <hr>

            <div class="row text-center">

                <div class="col">

                    <h4 class="text-success">

                        {{ $importacion->cantidad_importados }}

                    </h4>

                    <small>

                        Correctos

                    </small>

                </div>

                <div class="col">

                    <h4 class="text-danger">

                        {{ $importacion->cantidad_conflictos }}

                    </h4>

                    <small>

                        Incorrectos

                    </small>

                </div>

            </div>

        </div>

        @if($importacion->estado!='REVERTIDA')

        <div class="card-footer bg-white border-0">

            <button

                class="btn btn-outline-danger w-100 eliminar"

                data-id="{{ $importacion->id }}">

                <i class="bi bi-trash"></i>

                Eliminar importación

            </button>

        </div>

        @endif

    </div>

</div>

@empty

<div class="col-12">

    <div class="alert alert-info">

        No existen importaciones.

    </div>

</div>

@endforelse

</div>

<div class="mt-3">

{{ $importaciones->links() }}

</div>

@endsection

@push('scripts')

@push('scripts')
<script>

const token = document
    .querySelector('meta[name="csrf-token"]')
    .content;

document.querySelectorAll(".eliminar").forEach(btn => {

    btn.addEventListener("click", async function () {

        if (!confirm("¿Desea eliminar esta importación?\n\nTodas las asignaciones asociadas quedarán desactivadas.")) {

            return;

        }

        const response = await fetch(

            "/importaciones/" + this.dataset.id,

            {

                method: "DELETE",

                headers: {

                    "X-CSRF-TOKEN": token,

                    "Accept": "application/json"

                }

            }

        );

        const data = await response.json();

        if (data.success) {

            location.reload();

        } else {

            alert("No fue posible eliminar la importación.");

        }

    });

});

</script>
@endpush