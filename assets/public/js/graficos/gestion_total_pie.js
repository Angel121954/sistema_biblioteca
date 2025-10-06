document.addEventListener("DOMContentLoaded", () => {
  // Espera a que el DOM esté listo
  fetch("assets/controladores/graficos/gestion_total.php")
    .then((response) => response.json())
    .then((data) => {
      const canvas = document.querySelector("#grafico_totales_pie");

      if (!canvas) {
        console.error("No se encontró el canvas con id grafico_totales_pie");
        return;
      }

      const ctx = canvas.getContext("2d");

      new Chart(ctx, {
        type: "pie",
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
              backgroundColor: ["#36A2EB", "#FF6384", "#FFCE56", "#4BC0C0"],
              borderColor: ["#1E88E5", "#E91E63", "#FBC02D", "#009688"],
              borderWidth: 2,
            },
          ],
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: "bottom",
              labels: {
                boxWidth: 15,
                padding: 15,
                font: { size: 13 },
              },
            },
            title: {
              display: true,
              text: "Resumen General del Sistema",
              font: { size: 18, weight: "bold" },
            },
          },
        },
      });
    })
    .catch((error) => console.error("Error cargando datos:", error));
});
