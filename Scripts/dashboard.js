document.addEventListener("DOMContentLoaded", () => {

    fetch(`../Funciones/getDatosDashboard.php?id=${ID_PACIENTE}`)
    .then(res => res.json())
    .then(data => {

        console.log("DATA:", data);

        // 🔒 Validación básica
        if (!data || !data.labels) {
            console.error("Datos inválidos:", data);
            return;
        }

        // ==========================
        // 🧾 VALORES RESUMEN
        // ==========================

        // FRECUENCIA TOTAL
        document.getElementById("valorFrecuencia").innerHTML =
            (data.total ?? 0) + " <span>episodios</span>";

        // INTENSIDAD PROMEDIO
        let promInt = (data.intensidad && data.intensidad.length)
            ? data.intensidad.reduce((a,b)=>a+Number(b),0) / data.intensidad.length
            : 0;

        document.getElementById("valorIntensidad").innerHTML =
            promInt.toFixed(1) + " <span>/ 10</span>";

        // DURACIÓN PROMEDIO
        let promDur = (data.duracion && data.duracion.length)
            ? data.duracion.reduce((a,b)=>a+Number(b),0) / data.duracion.length
            : 0;

        document.getElementById("valorDuracion").innerHTML =
            promDur.toFixed(0) + " <span>min</span>";

        // ==========================
        // 📈 FRECUENCIA
        // ==========================

        new Chart(document.getElementById('graficaFrecuencia'), {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [{
                    label: "Frecuencia de episodios",
                    data: data.frecuencia || [],
                    borderColor: '#7b5ea7',
                    backgroundColor: 'rgba(123,94,167,0.15)',
                    fill: true,
                    tension: 0.4
                }]
            }
        });

        // ==========================
        // 📊 INTENSIDAD
        // ==========================

        new Chart(document.getElementById('graficaIntensidad'), {
            type: 'bar',
            data: {
                
                labels: data.labels || [],
                datasets: [{
                    label: "Intensidad de episodios",
                    data: data.intensidad || [],
                    backgroundColor: '#7b5ea7'
                }]
            },
            options: {
                scales: {
                    y: { max: 10, beginAtZero: true }
                }
            }
        });

        // ==========================
        // ⏱️ DURACIÓN
        // ==========================

        new Chart(document.getElementById('graficaDuracion'), {
            type: 'line',
            data: {
                labels: data.labels || [],
                datasets: [{
                    label: "Duracion de episodios",
                    data: data.duracion || [],
                    borderColor: '#b0a8c8',
                    backgroundColor: 'rgba(176,168,200,0.15)',
                    fill: true,
                    tension: 0.4
                }]
            }
        });

    })
    .catch(err => console.error("Error cargando dashboard:", err));

});