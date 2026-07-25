@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mb-4">

    <div>
        <h2 class="mb-1">Dashboard</h2>
        <p class="text-muted mb-0">
            Resumen general del sistema de reinducción.
        </p>
    </div>

    <a href="{{ route('exportar.consolidado') }}"
       class="btn btn-success">
        <i class="bi bi-file-earmark-excel me-2"></i>
        Exportar consolidado
    </a>

</div>

<div class="card shadow-sm mb-4">

    <div class="card-body">

        <div class="row g-3">

            <div class="col-md-8">

                <label class="form-label">

                    Buscar colaborador

                </label>

                <input

                    id="buscarAsignacion"

                    type="text"

                    class="form-control"

                    placeholder="Nombre o documento">

            </div>

            <div class="col-md-4">

                <label class="form-label">

                    Fecha

                </label>

                <select

                    id="filtroFecha"

                    class="form-select">

                    <option value="">

                        Todas

                    </option>

                    @foreach($fechas as $fecha)

                        <option value="{{ $fecha->id }}">

                            {{ $fecha->descripcion }}

                        </option>

                    @endforeach

                </select>

            </div>

        </div>

    </div>

</div>

<div class="card shadow-sm">

    <div class="card-body p-0">

        <table class="table table-hover align-middle mb-0">

            <thead class="table-light">

                <tr>

                    <th width="150">

                        Documento

                    </th>

                    <th>

                        Nombre

                    </th>

                    <th>

                        Fecha

                    </th>

                    <th>

                        Lider

                    </th>

                    {{-- <th width="120">

                        Estado

                    </th> --}}

                    <th width="17">

                    </th>

                </tr>

            </thead>

            <tbody id="tablaAsignaciones">
                {{-- {{ dd($asignaciones); } --}}

                @forelse($asignaciones as $asignacion)

                    <tr>

                        <td>

                            {{ $asignacion->documento }}

                        </td>

                        <td>

                            {{ $asignacion->nombre }}

                        </td>

                        <td>

                            {{ $asignacion->fecha->descripcion }}

                        </td>

                        <td>

                            {{ $asignacion->importacion->lider_nombre }}

                        </td>
                        

                        {{-- <td>

                            <span class="badge bg-success">

                                Activo

                            </span>

                        </td> --}}

                        <td>

                            {{-- Las acciones aparecerán únicamente mediante AJAX --}}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5" class="text-center py-5">

                            No existen asignaciones.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

</div>

<div class="mt-3">

    {{ $asignaciones->links() }}

</div>

@include('asignaciones.modalEditar')

@push('scripts')
<script src="{{ secure_asset('js/asignaciones.js') }}"></script>
{{-- <script src="/js/asignaciones.js"></script> --}}
@endpush

@endsection