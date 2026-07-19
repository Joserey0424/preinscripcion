<div class="modal fade" id="modalEditar" tabindex="-1">

    <div class="modal-dialog">

        <div class="modal-content">

            <form id="formEditar">

                @csrf

                @method('PUT')

                <div class="modal-header">

                    <h5 class="modal-title">

                        Editar asignación

                    </h5>

                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal">
                    </button>

                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        id="edit_id">

                    <div class="mb-3">

                        <label class="form-label">

                            Nombre

                        </label>

                        <input
                            type="text"
                            id="edit_nombre"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Documento

                        </label>

                        <input
                            type="text"
                            id="edit_documento"
                            class="form-control"
                            required>

                    </div>

                    <div class="mb-3">

                        <label class="form-label">

                            Fecha

                        </label>

                        <select
                            id="edit_fecha"
                            class="form-select"
                            required>

                            @foreach($fechas as $fecha)

                                <option value="{{ $fecha->id }}">

                                    {{ $fecha->descripcion }}

                                </option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="modal-footer">

                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">

                        Cancelar

                    </button>

                    <button
                        type="submit"
                        class="btn btn-primary">

                        Guardar cambios

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>