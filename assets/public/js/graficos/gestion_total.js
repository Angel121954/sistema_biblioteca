document.addEventListener("DOMContentLoaded", () => {
  // Espera a que el DOM esté listo
  fetch("assets/controladores/graficos/gestion_total.php")
    .then((response) => response.json())
    .then((data) => {
      const canvas = document.querySelector("#grafico_totales");

      if (!canvas) {
        console.error("No se encontró el canvas con id grafico_totales");
        return;
      }

      const ctx = canvas.getContext("2d");

      new Chart(ctx, {
        type: "bar",
        data: {
          labels: ["Libros", "Reservas", "Préstamos", "Usuarios"],
          datasets: [
            {
              label: "Totales del sistema",
              data: [
                data.total_libros,
                data.total_reservas,
                data.total_prestamos,
                data.total_usuarios,
              ],
              backgroundColor: ["#4CAF50", "#2196F3", "#FFC107", "#F44336"],
              borderWidth: 1,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { position: "top" },
            title: { display: true, text: "Resumen General del Sistema" },
          },
          scales: {
            y: { beginAtZero: true },
          },
        },
      });
    })
    .catch((error) => console.error("Error cargando datos:", error));
});
