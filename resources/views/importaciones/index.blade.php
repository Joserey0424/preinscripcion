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

                    @if(session('success'))

                        <div class="alert alert-success">

                            {{ session('success') }}

                        </div>

                    @endif

                    <form
                        action="{{ route('importaciones.preview') }}"
                        method="POST"
                        enctype="multipart/form-data">

                        @csrf

                        <div class="mb-4">

                            <label class="form-label">

                                Archivo Excel

                            </label>

                            <input
                                type="file"
                                class="form-control"
                                name="archivo"
                                accept=".xlsx,.xls">

                            @error('archivo')

                                <small class="text-danger">

                                    {{ $message }}

                                </small>

                            @enderror

                        </div>

                        <button
                            class="btn btn-primary w-100">

                            Analizar archivo

                        </button>

                    </form>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection