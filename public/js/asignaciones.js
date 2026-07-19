console.log("Asignaciones.js cargado");

const buscador = document.getElementById("buscarAsignacion");
const filtroFecha = document.getElementById("filtroFecha");
const tabla = document.getElementById("tablaAsignaciones");
const formulario = document.getElementById("formEditar");

const token = document
    .querySelector('meta[name="csrf-token"]')
    .content;

let temporizador = null;

/*
|--------------------------------------------------------------------------
| BUSCADOR
|--------------------------------------------------------------------------
*/

if (buscador) {

    buscador.addEventListener("input", () => {

        clearTimeout(temporizador);

        temporizador = setTimeout(() => {

            buscarAsignaciones();

        }, 300);

    });

}

if (filtroFecha) {

    filtroFecha.addEventListener("change", buscarAsignaciones);

}

/*
|--------------------------------------------------------------------------
| Buscar asignaciones
|--------------------------------------------------------------------------
*/

async function buscarAsignaciones() {

    const params = new URLSearchParams();

    if (buscador.value.trim() !== "") {

        params.append("buscar", buscador.value.trim());

    }

    if (filtroFecha.value !== "") {

        params.append("fecha", filtroFecha.value);

    }

    const response = await fetch(

        "/asignaciones/buscar?" + params.toString()

    );

    const datos = await response.json();

    let html = "";

    datos.forEach(a => {

        html += `
            <tr>

                <td>${a.documento}</td>

                <td>${a.nombre}</td>

                <td>${a.fecha.descripcion}</td>

                <td>
        `;

        /*
         * Solo mostrar acciones cuando hay búsqueda
         */

        if (buscador.value.trim() !== "") {

            html += `

                <button
                    class="btn btn-warning btn-sm editar"

                    data-id="${a.id}"

                    data-nombre="${a.nombre}"

                    data-documento="${a.documento}"

                    data-fecha="${a.fecha_id}">

                    <i class="bi bi-pencil"></i>

                </button>

                <button
                    class="btn btn-danger btn-sm eliminar"

                    data-id="${a.id}">

                    <i class="bi bi-trash"></i>

                </button>

            `;

        }

        html += `

                </td>

            </tr>

        `;

    });

    if (datos.length === 0) {

        html = `

            <tr>

                <td colspan="5" class="text-center py-4">

                    No se encontraron registros.

                </td>

            </tr>

        `;

    }

    tabla.innerHTML = html;

    inicializarBotones();

}

/*
|--------------------------------------------------------------------------
| BOTONES
|--------------------------------------------------------------------------
*/

function inicializarBotones() {

    /*
     * Editar
     */

    document.querySelectorAll(".editar").forEach(btn => {

        btn.onclick = function () {

            document.getElementById("edit_id").value =
                this.dataset.id;

            document.getElementById("edit_nombre").value =
                this.dataset.nombre;

            document.getElementById("edit_documento").value =
                this.dataset.documento;

            document.getElementById("edit_fecha").value =
                this.dataset.fecha;

            new bootstrap.Modal(

                document.getElementById("modalEditar")

            ).show();

        };

    });

    /*
     * Eliminar
     */

    document.querySelectorAll(".eliminar").forEach(btn => {

        btn.onclick = async function () {

            if (!confirm("¿Desea desactivar esta asignación?")) {

                return;

            }

            await fetch(

                "/asignaciones/" + this.dataset.id,

                {

                    method: "DELETE",

                    headers: {

                        "X-CSRF-TOKEN": token

                    }

                }

            );

            buscarAsignaciones();

        };

    });

}

/*
|--------------------------------------------------------------------------
| EDITAR
|--------------------------------------------------------------------------
*/

if (formulario) {

    formulario.addEventListener("submit", async function (e) {

        e.preventDefault();

        const id = document.getElementById("edit_id").value;

        const response = await fetch(

            "/asignaciones/" + id,

            {

                method: "PUT",

                headers: {

                    "Content-Type": "application/json",

                    "X-CSRF-TOKEN": token

                },

                body: JSON.stringify({

                    nombre: document.getElementById("edit_nombre").value,

                    documento: document.getElementById("edit_documento").value,

                    fecha_id: document.getElementById("edit_fecha").value

                })

            }

        );

        if (response.ok) {

            bootstrap.Modal
                .getInstance(
                    document.getElementById("modalEditar")
                )
                .hide();

            buscarAsignaciones();

        }

    });

}

/*
|--------------------------------------------------------------------------
| Inicializar
|--------------------------------------------------------------------------
*/

inicializarBotones();