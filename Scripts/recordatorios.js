document.addEventListener("DOMContentLoaded", () => {
    cargarRecordatorios();
});

function cargarRecordatorios() {

    fetch('../Funciones/getRecordatorios.php')
.then(res => res.json())
.then(data => {

    let contenedor = document.getElementById("listaRecordatorios");
    contenedor.innerHTML = "";

    data.forEach(r => {

        let hoy = new Date().toISOString().split('T')[0];
        let esHoy = r.fecha.startsWith(hoy);

        let card = `
            <div class="card ${esHoy ? 'nuevo' : ''}">

                <div style="display:flex; justify-content:space-between;">
                    <strong>${r.remitente}</strong>
                    <small>${r.fecha}</small>
                </div>

                <p>${r.mensaje}</p>

            </div>
        `;

        contenedor.innerHTML += card;
    });

});
}


