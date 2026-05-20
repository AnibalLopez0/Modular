document.addEventListener("DOMContentLoaded", () => {
    cargarHistorial();
});

function cargarHistorial() {

    fetch('../Funciones/getMiHistorial.php') 
    .then(res => res.json())
    .then(data => {

        let contenedor = document.getElementById("listaHistorial");
        contenedor.innerHTML = "";

            data.forEach(c => {

                let card = `
                    <div class="conducta-row">

                        <div class="conducta-name">
                            <div>${c.titulo}</div>
                            <small class="text-muted fw-normal">${c.fecha}</small>
                        </div>

                        <div class="d-flex flex-column align-items-end gap-1 text-muted" style="font-size: 0.85rem;">
                            ${c.descripcion  ? `<span>${c.descripcion}</span>`                      : ''}
                            ${c.intensidad   ? `<span>Intensidad: ${c.intensidad}/10</span>`           : ''}
                            ${c.duracion     ? `<span>Duración: ${c.duracion}min</span>`           : ''}
                        </div>

                    </div>
                `;

                contenedor.innerHTML += card;
            });

    });
}