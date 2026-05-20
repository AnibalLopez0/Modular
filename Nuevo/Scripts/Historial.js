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

                        ${c.descripcion ? `<span class="badge rounded-pill bg-secondary bg-opacity-25 text-dark fw-normal">${c.descripcion}</span>` : ''}

                        ${c.intensidad ? `<span class="icon-btn" title="Intensidad"> ${c.intensidad}</span>` : ''}
                        ${c.duracion   ? `<span class="icon-btn" title="Duración"> ${c.duracion}</span>`   : ''}

                    </div>
            `;

            contenedor.innerHTML += card;
        });

    });
}