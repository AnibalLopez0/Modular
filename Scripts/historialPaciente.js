document.addEventListener("DOMContentLoaded", () => {
    cargarHistorial();
});

function cargarHistorial() {

    fetch(`../Funciones/getHistorialPaciente.php?id=${ID_PACIENTE}`)
    .then(res => res.json())
    .then(data => {

        let contenedor = document.getElementById("listaHistorial");
        contenedor.innerHTML = "";

        if (data.length === 0) {
            return;
        }

        data.forEach(c => {

            let card = `
                <div class="conducta-row">

                    <strong>${c.titulo}</strong><br>

                    ${c.descripcion ?? ''}<br>

                    Fecha: ${c.fecha ?? ''}<br>

                    Intensidad: ${c.intensidad ?? '-'}/10<br>
                    Duración en: ${c.duracion ?? '-'}min

                </div>
            `;

            contenedor.innerHTML += card;
        });

    })
    .catch(err => console.error("Error:", err));
}