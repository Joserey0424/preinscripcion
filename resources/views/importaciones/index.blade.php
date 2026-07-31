@extends('layouts.app')

@section('content')
    <div class="container mt-5">

        <div class="row justify-content-center">

            <div class="col-md-7">

                <div class="card shadow">

                    <div class="card-header bg-primary text-white">

                        <h4 class="mb-0">

                            Importar archivo Excel

                        </h4>

                    </div>

                    <div class="card-body">

                        @if (session()->has('resultado_importacion'))
                            <div id="correo-html" class="d-none">
                                {!! view('correos.errores-importacion', [
                                    'lider' => session('resultado_importacion')['lider'],
                                    'errores' => session('resultado_importacion')['errores'],
                                ])->render() !!}
                            </div>
                        @endif

                        <form action="{{ route('importaciones.preview') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <div class="mb-4">

                                <label class="form-label">

                                    Archivo Excel

                                </label>

                                <input type="file" class="form-control" name="archivo" accept=".xlsx,.xls">

                                @error('archivo')
                                    <small class="text-danger">

                                        {{ $message }}

                                    </small>
                                @enderror

                            </div>

                            <button class="btn btn-primary w-100">

                                Analizar archivo

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const resultado = @json(session('resultado_importacion'));

            const total = resultado.resultados.length;
            const importados = resultado.resultados.filter(r => r.ok).length;
            const noImportados = resultado.errores.length;

            let html = `
        <div class="text-start">

            <div class="row text-center mb-4">

                <div class="col">
                    <h2 class="text-success mb-0">${importados}</h2>
                    <small>Importados</small>
                </div>

                <div class="col">
                    <h2 class="text-danger mb-0">${noImportados}</h2>
                    <small>No importados</small>
                </div>

                <div class="col">
                    <h2 class="text-primary mb-0">${total}</h2>
                    <small>Total</small>
                </div>

            </div>
    `;

            if (noImportados > 0) {

                html += `
            <div class="alert alert-warning">
                Algunos colaboradores no pudieron ser importados.
            </div>

            <div style="max-height:300px;overflow:auto">

                <table class="table table-bordered table-sm">

                    <thead class="table-light">

                        <tr>
                            <th>Documento</th>
                            <th>Nombre</th>
                            <th>Motivo</th>
                        </tr>

                    </thead>

                    <tbody>
        `;

                resultado.errores.forEach(e => {

                    html += `
                <tr>
                    <td>${e.documento}</td>
                    <td>${e.nombre}</td>
                    <td>${e.motivo}</td>
                </tr>
            `;

                });

                html += `
                    </tbody>

                </table>

            </div>

            <div class="text-end mt-3">

                <button
                    type="button"
                    class="btn btn-primary"
                    onclick="copiarCorreo()">

                    <i class="bi bi-envelope"></i>
                    Copiar correo

                </button>

            </div>
        `;
            }

            html += `</div>`;

            Swal.fire({
                icon: noImportados > 0 ? 'warning' : 'success',
                title: 'Importación finalizada',
                html: html,
                width: 900,
                confirmButtonText: 'Cerrar'
            });

        });


        function copiarCorreo() {

            const html = document.getElementById('correo-html').innerHTML;

            const textarea = document.createElement('textarea');
            textarea.value = html;

            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';

            document.body.appendChild(textarea);

            textarea.select();

            document.execCommand('copy');

            document.body.removeChild(textarea);

            Swal.fire({
                icon: 'success',
                title: 'Correo copiado',
                text: 'Ahora puedes pegarlo en Zimbra.',
                timer: 1800,
                showConfirmButton: false
            });

        }
    </script>
@endsection
