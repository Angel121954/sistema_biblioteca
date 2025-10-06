document
  .querySelector("#btn_registro_reserva")
  .addEventListener("click", () => {
    const libros = JSON.parse(
      document.querySelector("#btn_registro_reserva").dataset.libros
    );
    let opcionesLibros =
      '<option value="" disabled selected>Seleccione un libro</option>';
    libros.forEach((l) => {
      opcionesLibros += `<option value="${l.id_libro}">${l.titulo_libro}</option>`;
    });

    Swal.fire({
      title: '<h2 class="fw-bold mb-3 text-primary">Registro de prestamo</h2>',
      html: `
        <form id="frm_registro_reserva" class="text-start" novalidate style="max-width: 450px; margin: 0 auto;">
          <div class="form-floating mb-3">
            <input name="fecha" id="fecha" type="datetime-local" class="form-control" required>
            <label for="fecha"><i class="bi bi-calendar-event"></i> Fecha</label>
          </div>

          <div class="form-floating mb-4">
            <select name="id_libro" id="id_libro" class="form-select" required>
              ${opcionesLibros}
            </select>
            <label for="id_libro"><i class="bi bi-book"></i> Libro</label>
          </div>

          <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
            Guardar reserva
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
        const form = document.querySelector("#frm_registro_reserva");

        form.addEventListener("submit", (e) => {
          e.preventDefault();

          const formData = new FormData(form);

          fetch("assets/controladores/reservas/registro_reserva.php", {
            method: "POST",
            body: formData,
          })
            .then((r) => r.text())
            .then((res) => {
              console.log("Respuesta del servidor:", res);

              if (res.trim() === "ok") {
                Swal.fire(
                  "¡Éxito!",
                  "Reserva registrada correctamente",
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
