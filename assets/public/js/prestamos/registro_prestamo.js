document.addEventListener("DOMContentLoaded", () => {
  const btn = document.querySelector("#btn_registro_prestamo");

  btn.addEventListener("click", () => {
    const reservas = JSON.parse(btn.dataset.reserva);

    let opcionesReservas =
      '<option value="" disabled selected>Seleccione una reserva</option>';
    reservas.forEach((r) => {
      const fecha = new Date(r.fecha).toLocaleString("es-CO");
      opcionesReservas += `<option value="${r.id_reserva}">${fecha}</option>`;
    });

    Swal.fire({
      title: '<h2 class="fw-bold mb-3 text-primary">Registro de préstamo</h2>',
      html: `
        <form id="frm_registro_prestamo" class="text-start" novalidate style="max-width: 450px; margin: 0 auto;">
          <div class="form-floating mb-3">
            <input name="fecha_prestamo" id="fecha_prestamo" type="datetime-local" class="form-control" required>
            <label for="fecha_prestamo"><i class="bi bi-calendar-event"></i> Fecha del préstamo</label>
          </div>

          <div class="form-floating mb-3">
            <input name="fecha_devolucion" id="fecha_devolucion" type="datetime-local" class="form-control" required>
            <label for="fecha_devolucion"><i class="bi bi-calendar-check"></i> Fecha de devolución</label>
          </div>

          <div class="form-floating mb-3">
            <select name="reservas_id_reserva" id="reservas_id_reserva" class="form-select" required>
              ${opcionesReservas}
            </select>
            <label for="reservas_id_reserva"><i class="bi bi-journal-bookmark"></i> Reserva</label>
          </div>

          <!-- Información dinámica -->
          <div id="info_reserva" class="mb-4 text-center text-secondary fw-semibold"></div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            Guardar préstamo
          </button>
        </form>
      `,
      showConfirmButton: false,
      width: 600,
      background: "#fdfdfd",
      customClass: {
        popup: "shadow-lg rounded-4 border-0",
      },
      didOpen: () => {
        const form = document.querySelector("#frm_registro_prestamo");
        const selectReserva = document.querySelector("#reservas_id_reserva");
        const infoDiv = document.querySelector("#info_reserva");

        selectReserva.addEventListener("change", (e) => {
          const idSeleccionado = e.target.value;
          const reserva = reservas.find((r) => r.id_reserva == idSeleccionado);

          if (reserva) {
            infoDiv.innerHTML = `
              <div class="border rounded-3 p-3 bg-light">
                <p class="mb-1"><i class="bi bi-person-circle"></i> <b>Usuario:</b> ${reserva.nombre_usuario}</p>
                <p class="mb-0"><i class="bi bi-book"></i> <b>Libro:</b> ${reserva.titulo_libro}</p>
              </div>
            `;
          }
        });
        form.addEventListener("submit", (e) => {
          e.preventDefault();
          const formData = new FormData(form);

          fetch("assets/controladores/prestamos/registro_prestamo.php", {
            method: "POST",
            body: formData,
          })
            .then((r) => r.text())
            .then((res) => {
              console.log("Respuesta del servidor:", res);
              if (res.trim() === "ok") {
                Swal.fire(
                  "¡Éxito!",
                  "Préstamo registrado correctamente",
                  "success"
                ).then(() => location.reload());
              } else {
                Swal.fire("Error", res, "error");
              }
            })
            .catch(() => {
              Swal.fire("Error", "Hubo un problema con la petición", "error");
            });
        });
      },
    });
  });
});
