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

        <span class="badge bg-success">

            Activo

        </span>

    </td>

    <td>

        @if(request()->filled('buscar'))

            <button

                class="btn btn-warning btn-sm editar"

                data-id="{{ $asignacion->id }}"

                data-nombre="{{ $asignacion->nombre }}"

                data-documento="{{ $asignacion->documento }}"

                data-fecha="{{ $asignacion->fecha_id }}">

                <i class="bi bi-pencil"></i>

            </button>

            <button

                class="btn btn-danger btn-sm eliminar"

                data-id="{{ $asignacion->id }}">

                <i class="bi bi-trash"></i>

            </button>

        @endif

    </td>

</tr>

@empty

<tr>

    <td colspan="5" class="text-center py-4">

        No se encontraron asignaciones.

    </td>

</tr>

@endforelse