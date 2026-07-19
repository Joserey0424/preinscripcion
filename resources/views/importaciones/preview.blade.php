@extends('layouts.app')

@section('content')
    <div class="container mt-4">
        @if (count($resultado['erroresArchivo']))
            <div class="alert alert-danger">

                <strong>

                    El archivo contiene errores.

                </strong>

                <ul class="mb-0 mt-2">

                    @foreach ($resultado['erroresArchivo'] as $error)
                        <li>

                            {{ $error }}

                        </li>
                    @endforeach

                </ul>

            </div>
        @endif

        <h3>Vista previa del archivo</h3>

        <div class="card mb-3">
            <div class="card-body">
                <strong>Líder:</strong> {{ $resultado['lider']['nombre'] }}<br>
                <strong>Identificación:</strong> {{ $resultado['lider']['identificacion'] }}
            </div>
        </div>

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    {{-- <th>Fila</th> --}}
                    <th>Nombre</th>
                    <th>Documento</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Observaciones</th>
                </tr>
            </thead>

            <tbody>

                @foreach ($resultado['registros'] as $fila)
                    <tr>
                        {{-- <td>{{ $fila['fila'] }}</td> --}}
                        <td>{{ $fila['nombre'] }}</td>
                        <td>{{ $fila['documento'] }}</td>
                        <td>{{ $fila['fecha'] }}</td>
                        <td>

                            @if ($fila['estado'] == 'OK')
                                <span class="badge bg-success">

                                    Correcto

                                </span>
                            @else
                                <span class="badge bg-danger">

                                    Error

                                </span>
                            @endif

                        </td>

                        <td>

                            {{ implode(', ', $fila['errores']) }}

                        </td>
                    </tr>
                @endforeach

            </tbody>

        </table>

        <div class="d-flex justify-content-end mt-4">

            @if (count($resultado['erroresArchivo']) == 0)
                <div class="text-end mt-4">

                    <form method="POST" action="{{ route('importaciones.importar') }}">

                        @csrf

                        <button class="btn btn-success">

                            Importar registros válidos

                        </button>

                    </form>

                </div>
            @endif

        </div>

    </div>
@endsection
