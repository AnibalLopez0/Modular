function irPlantillas() {
    window.location.href = "Plantillas.php";
}
if (ROL === "terapeuta") {

    // 📋 PACIENTES
    fetch('../Funciones/getPacientes.php')
    .then(res => res.json())
    .then(data => {

        let activos = document.getElementById("activos");
        let inactivos = document.getElementById("inactivos");

        activos.innerHTML = "";
        inactivos.innerHTML = "";

        data.forEach(p => {

            if (p.is_active == 1) {

                let card = `

                            <div class="conducta-row mb-2 mt-2">
                            <a class="icon-btn" data-bs-toggle="modal" data-bs-target="#modalEditarPaciente" 
                            title="Configurar" onclick="configPaciente(${p.id_usuario})">
                                <i class="bi bi-gear-fill"></i>
                            </a>
                            <a class="icon-btn danger" title="Eliminar" onclick="eliminarPaciente(${p.id_usuario})">
                                <i class="bi bi-trash-fill"></i>
                            </a>
                            <span class="conducta-name">${p.username}</span>
                            <a class="icon-btn" title="Ver dashboard" onclick="verPaciente(${p.id_usuario})">
                                <i class="bi bi-plus-lg"></i>
                            </a>
                            <a class="icon-btn" title="Historial" onclick="verHistorial(${p.id_usuario})">
                                <i class="bi bi-calendar-fill"></i>
                            </a>
                            </div>

                `;

                activos.innerHTML += card;

            } else {

                let card = `

                        <div class="conducta-row mb-2 mt-2">
                            <a class="icon-btn" title="Reactivar" onclick="reactivarPaciente(${p.id_usuario})">
                                <i class="bi bi-arrow-counterclockwise"></i>
                            </a>
                            <span class="conducta-name">${p.username}</span>
                        </div>
                `;

                inactivos.innerHTML += card;
            }

        });

    })
    .catch(err => console.error("Error cargando pacientes:", err));


} else {

    // CONDUCTAS DEL PACIENTE
    fetch('../Funciones/getMisConductas.php')
    .then(res => res.json())
    .then(data => {

        let contenedor = document.getElementById("misRegistros");

        contenedor.innerHTML = ""; //  evitar duplicados

        data.forEach(c => {

            let card = `
                <div class="card">
                    <strong>${c.titulo}</strong><br>
                    ${c.descripcion ?? ''}<br>
                    Fecha: ${c.fecha ?? ''}<br>
                    Intensidad: ${c.intensidad ?? '-'}<br>
                    Duración: ${c.duracion ?? '-'}
                </div>
            `;

            contenedor.innerHTML += card;
        });

    })
    .catch(err => console.error("Error cargando conductas:", err));
}


// ==========================
// PACIENTES (MODAL)
// ==========================




// ==========================
// GUARDAR PACIENTE
// ==========================
function guardarPaciente() {

    let username = document.getElementById("username").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    fetch('../Funciones/crearPaciente.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `username=${username}&email=${email}&password=${password}`
    })
    .then(res => res.text())
    .then(res => {
        location.reload();
    })
    .catch(err => console.error("Error creando paciente:", err));
}

function configPaciente(id) {

    fetch(`../Funciones/getPacienteById.php?id=${id}`)
    .then(res => res.json())
    .then(p => {

        document.getElementById("edit_id").value = p.id_usuario;
        document.getElementById("edit_username").value = p.username;
        document.getElementById("edit_email").value = p.email;
        document.getElementById("edit_fecha").value = p.fecha_nacimiento ?? '';
        document.getElementById("edit_sexo").value = p.sexo ?? '';

    });
}



// ==========================
// ACTUALIZAR PACIENTE
// ==========================
function actualizarPaciente() {

    let id = document.getElementById("edit_id").value;
    let username = document.getElementById("edit_username").value;
    let email = document.getElementById("edit_email").value;
    let fecha = document.getElementById("edit_fecha").value;
    let sexo = document.getElementById("edit_sexo").value;

    fetch('../Funciones/actualizarPaciente.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}&username=${username}&email=${email}&fecha=${fecha}&sexo=${sexo}`
    })
    .then(res => res.text())
    .then(res => {
        location.reload();
    });
}

// ==========================
// DESACTIVAR PACIENTE
// ==========================

function eliminarPaciente(id) {


    fetch('../Funciones/eliminarPaciente.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(res => {
        location.reload();
    })
    .catch(err => console.error("Error:", err));
}


// ==========================
// ACTIVAR PACIENTE
// ==========================
 

function reactivarPaciente(id) {


    fetch('../Funciones/reactivarPaciente.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id=${id}`
    })
    .then(res => res.text())
    .then(res => {
        location.reload();
    })
    .catch(err => console.error("Error:", err));
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

function abrirRegistro(id, titulo, usaIntensidad, usaDuracion) {

    document.getElementById("reg_id_plantilla").value = id;
    document.getElementById("reg_titulo").innerText = titulo;

    // mostrar/ocultar campos
    document.getElementById("campoIntensidad").style.display = usaIntensidad ? "block" : "none";
    document.getElementById("campoDuracion").style.display = usaDuracion ? "block" : "none";

    document.getElementById("modalRegistro").style.display = "flex";
}

function guardarRegistro() {

    let id_plantilla = document.getElementById("reg_id_plantilla").value;
    let descripcion = document.getElementById("reg_descripcion").value;
    let intensidad = document.getElementById("reg_intensidad").value;
    let duracion = document.getElementById("reg_duracion").value;

fetch('../Funciones/guardarConducta.php', {
    method: 'POST',
    credentials: 'same-origin', //  MUY IMPORTANTE
    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
    body: `id_plantilla=${id_plantilla}&descripcion=${descripcion}&intensidad=${intensidad}&duracion=${duracion}`
})
    .then(res => res.text())
    .then(res => {
        alert(res);
        cerrarRegistro();
        location.reload();
    });
}

function cerrarRegistro() {
    document.getElementById("modalRegistro").style.display = "none";
}


let intensidad = document.getElementById("reg_intensidad").value;
let duracion = document.getElementById("reg_duracion").value;
intensidad = intensidad === "" ? null : intensidad;
duracion = duracion === "" ? null : duracion;

function verHistorialCompleto() {
    window.location.href = "Historial.php";
}

function irRecordatorios() {
    window.location.href = "Recordatorios.php";
}


function cargarPacientes() {
    fetch('../Funciones/getPacientes.php')
    .then(res => res.json())
    .then(data => {
        
        let select =
        document.getElementById('rec_paciente');
        
        select.innerHTML =
        `<option value="">Seleccionar paciente</option>`;
        
        data.forEach(p => {
            
            if (p.is_active == 1) {
                
                select.innerHTML += `
                <option value="${p.id_usuario}">
                ${p.username}
                </option>
                `;
            }
        });
        
        document.getElementById('rec_mensaje').value = '';
    });

}


function enviarRecordatorio() {

    let id_paciente = document.getElementById("rec_paciente").value;
    let mensaje = document.getElementById("rec_mensaje").value;

    if (!id_paciente) {
        alert("Selecciona un paciente");
        return;
    }

    if (!mensaje.trim()) {
        alert("El mensaje no puede estar vacío");
        return;
    }

    fetch('../Funciones/crearRecordatorio.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `id_paciente=${id_paciente}&mensaje=${encodeURIComponent(mensaje)}`
    })
    .then(res => res.text())
    .then(res => {
        alert(res);
    })
    .catch(err => console.error("Error:", err));
}

function verHistorial(id) {
    window.location.href = `HistorialPaciente.php?id=${id}`;
}

function verPaciente(id) {
    window.location.href = `Dashboard.php?id=${id}`;
}