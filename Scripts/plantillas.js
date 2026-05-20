
function guardarPlantilla() {

    let titulo = document.getElementById("tituloPlantilla").value;
    let intensidad = document.getElementById("usaIntensidad").checked ? 1 : 0;
    let duracion = document.getElementById("usaDuracion").checked ? 1 : 0;

    fetch('../Funciones/crearPlantilla.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `titulo=${titulo}&intensidad=${intensidad}&duracion=${duracion}`
    })
    .then(res => res.text())
    .then(res => {
        cerrarModalNueva();
        location.reload();
    });
}

document.addEventListener("DOMContentLoaded", () => {
    cargarPlantillas();
});


function editarPlantilla(id) {

    fetch(`../Funciones/getPlantillaById.php?id=${id}`)
    .then(res => res.json())
    .then(p => {

        document.getElementById("edit_id_plantilla").value = p.id_plantilla;
        document.getElementById("edit_titulo").value = p.titulo;
        document.getElementById("edit_intensidad").checked = p.usa_intensidad == 1;
        document.getElementById("edit_duracion").checked = p.usa_duracion == 1;

        document.getElementById("modalEditarPlantilla").style.display = "flex";
    });
}

function eliminarPlantilla(id) {

    if (!confirm("¿Eliminar esta plantilla?")) return;

    fetch('../Funciones/eliminarPlantilla.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(res => {
        cargarPlantillas(); // 🔥 sin recargar página
    });
}
document.addEventListener("DOMContentLoaded", () => {
    cargarPlantillas();
});

function cargarPlantillas() {

    fetch('../Funciones/getSintomas.php')
    .then(res => res.json())
    .then(data => {

        let activos = document.getElementById("listaActivas");
        let inactivos = document.getElementById("listaInactivas");

        activos.innerHTML = "";
        inactivos.innerHTML = "";

        data.forEach(p => {

            let card = "";

            // 🔹 ACTIVOS
                if (Number(p.is_active) === 1) {

                    card = `
                        <div class="conducta-row">
                            <button class="icon-btn" title="Editar" data-bs-toggle="modal" data-bs-target="#modalEditarPlantilla"
                             onclick="editarPlantilla(${p.id_plantilla})">
                                <i class="fa-solid fa-gear"></i>
                            </button>
                            <button class="icon-btn danger" title="Eliminar" onclick="eliminarPlantilla(${p.id_plantilla})">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                            <span class="conducta-name">${p.titulo}</span>
                        </div>
                    `;

                    document.getElementById('listaActivas').innerHTML += card;

                } else {

                    card = `
                        <div class="conducta-row" style="opacity: 0.65;">
                            <button class="icon-btn" title="Restaurar" onclick="restaurarPlantilla(${p.id_plantilla})">
                                <i class="fa-solid fa-rotate-left"></i>
                            </button>
                            <span class="conducta-name">${p.titulo}</span>
                        </div>
                    `;

                    document.getElementById('listaInactivas').innerHTML += card;
}

        });

    });
}
function restaurarPlantilla(id) {

    fetch('../Funciones/restaurarPlantilla.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(res => {
        cargarPlantillas();
    });
}

function abrirSelectorPlantilla() {

    fetch('../Funciones/getSintomas.php')
    .then(res => res.json())
    .then(data => {

        let contenedor = document.getElementById("listaPlantillasSelector");
        contenedor.innerHTML = "";

        data.forEach(p => {

            if (p.is_active == 1) {

                let btn = `
                    <button onclick="abrirRegistro(
                        ${p.id_plantilla},
                        '${p.titulo}',
                        ${p.usa_intensidad},
                        ${p.usa_duracion}
                    )">
                        ${p.titulo}
                    </button>
                `;

                contenedor.innerHTML += btn;
            }

        });

        document.getElementById("modalSelector").style.display = "flex";
    });
}

function cerrarSelector() {
    document.getElementById("modalSelector").style.display = "none";
}
function actualizarPlantilla() {

    let id = document.getElementById("edit_id_plantilla").value;
    let titulo = document.getElementById("edit_titulo").value;
    let intensidad = document.getElementById("edit_intensidad").checked ? 1 : 0;
    let duracion = document.getElementById("edit_duracion").checked ? 1 : 0;

    fetch('../Funciones/actualizarPlantilla.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&titulo=${titulo}&intensidad=${intensidad}&duracion=${duracion}`
    })
    .then(res => res.text())
    .then(res => {

        document.getElementById("modalEditarPlantilla").style.display = "none";
        cargarPlantillas();
    });
}