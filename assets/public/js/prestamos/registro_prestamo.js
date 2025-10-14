document.addEventListener("DOMContentLoaded", () => {
  const btn = document.querySelector("#btn_registro_prestamo");

  if (!btn) return;

  btn.addEventListener("click", () => {
    let reservasUsuarios = JSON.parse(btn.dataset.reserva);

    if (reservasUsuarios.length === 0) {
      Swal.fire({
        title: "Sin reservas",
        text: "No hay reservas disponibles para préstamo.",
        icon: "warning",
        iconColor: "#00c3ff",
        confirmButtonColor: "#4e73df",
        background: "#fdfdfd",
        customClass: {
          popup: "shadow-lg rounded-4 border-0",
        },
      });
      return;
    }

    const mostrarModal = () => {
      let opcionesReservas =
        '<option value="" disabled selected>Seleccione una reserva</option>';
      reservasUsuarios.forEach((r) => {
        opcionesReservas += `<option value="${r.id_reserva}">
          ${r.nombre_usuario} ${r.apellido_usuario ?? ""} - ${r.titulo_libro}
        </option>`;
      });

      Swal.fire({
        title:
          '<h2 class="fw-bold mb-3 text-primary">Registro de préstamo</h2>',
        html: `
          <form id="frm_registro_prestamo" class="text-start" novalidate style="max-width: 450px; margin: 0 auto;">
            <div class="form-floating mb-3">
              <select name="reservas_id_reserva" id="reservas_id_reserva" class="form-control" required>
                ${opcionesReservas}
              </select>
              <label for="reservas_id_reserva" class="mt-2">
                <i class="bi bi-journal-bookmark"></i> Reserva
              </label>
            </div>

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
          popup:
            "animate__animated animate__fadeInUp shadow-lg rounded-4 border-0",
        },

        didOpen: () => {
          const form = document.querySelector("#frm_registro_prestamo");
          const selectReserva = document.querySelector("#reservas_id_reserva");
          const infoDiv = document.querySelector("#info_reserva");

          selectReserva.addEventListener("change", (e) => {
            const idSeleccionado = e.target.value;
            const reserva = reservasUsuarios.find(
              (r) => r.id_reserva == idSeleccionado
            );

            if (reserva) {
              infoDiv.innerHTML = `
                <div class="border rounded-4 p-4 bg-light shadow-sm">
                  <ul class="list-unstyled mb-0">
                    <li class="mb-2">
                      <i class="bi bi-calendar-date text-secondary me-2 mb-1"></i>
                      <b>Fecha de reserva:</b><br>
                      <span class="text-muted mb-2">${reserva.fecha_reserva}</span>
                    </li>
                    <li>
                      <i class="bi bi-book text-secondary me-2"></i>
                      <b>Cantidad del libro:</b><br>
                      <span class="text-muted">${reserva.cantidad_libros}</span>
                    </li>
                  </ul>
                </div>
              `;
            }
          });

          form.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(form);
            const idSeleccionado = formData.get("reservas_id_reserva");

            if (!idSeleccionado) {
              Swal.fire(
                "Atención",
                "Seleccione una reserva antes de continuar.",
                "warning"
              );
              return;
            }

            Swal.fire({
              title: "Registrando préstamo...",
              text: "Por favor espere un momento.",
              allowOutsideClick: false,
              didOpen: () => Swal.showLoading(),
            });

            registrarPrestamo(); //* Hoisting
            async function registrarPrestamo() {
              const respuesta = await fetch(
                "assets/controladores/prestamos/registro_prestamo.php",
                {
                  method: "POST",
                  body: formData,
                }
              );
              const res = await respuesta.text();

              if (res.trim() === "ok") {
                Swal.fire({
                  title: "¡Éxito!",
                  text: "Préstamo registrado correctamente.",
                  icon: "success",
                  confirmButtonText: "Aceptar",
                  confirmButtonColor: "#4e73df",
                  background: "#fdfdfd",
                  customClass: {
                    popup: "shadow-lg rounded-4 border-0",
                  },
                }).then(() => {
                  location.reload();
                });
              } else {
                Swal.fire("Error", "Error al registrar préstamo.", "error");
              }
            }
          });
        },
      });
    };
    mostrarModal();
  });
});
