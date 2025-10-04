document.querySelector("#btn_registro_libro").addEventListener("click", () => {
  Swal.fire({
    title: "Registro de libro",
    html: `
      <form id="frm_registro_libro" class="needs-validation" novalidate>
        <div class="form-floating mb-3">
          <input name="titulo_libro" id="titulo_libro" type="text" class="form-control"
                 placeholder="Título" required autocomplete="off">
          <label for="titulo_libro">Título</label>
        </div>

        <div class="form-floating mb-3">
          <input name="autor_libro" id="autor_libro" type="text" class="form-control"
                 placeholder="Autor" required autocomplete="off">
          <label for="autor_libro">Autor</label>
        </div>

        <div class="form-floating mb-3">
          <input name="isbn_libro" id="isbn_libro" type="text" class="form-control"
                 placeholder="ISBN" required autocomplete="off">
          <label for="isbn_libro">ISBN</label>
        </div>

        <div class="form-floating mb-3">
          <input name="categoria_libro" id="categoria_libro" type="text" class="form-control"
                 placeholder="Categoría" required autocomplete="off">
          <label for="categoria_libro">Categoría</label>
        </div>

        <div class="form-floating mb-3">
          <select name="disponibilidad_libro" id="disponibilidad_libro" class="form-select" required>
            <option value="" disabled selected>Seleccione la disponibilidad</option>
            <option value="Disponible">Disponible</option>
            <option value="No disponible">No disponible</option>
            <option value="Prestado">Prestado</option>
          </select>
          <label for="disponibilidad_libro">Disponibilidad</label>
        </div>

        <div class="form-floating mb-3">
          <input name="cantidad_libro" id="cantidad_libro" type="number" class="form-control"
                 placeholder="Cantidad" required autocomplete="off">
          <label for="cantidad_libro">Cantidad</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">
          Guardar cambios
        </button>
      </form>
    `,
    showConfirmButton: false,
    didOpen: () => {
      const form = document.querySelector("#frm_registro_libro");

      form.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("assets/controladores/registro_libro.php", {
          method: "POST",
          body: formData,
        })
          .then((r) => r.text())
          .then((res) => {
            console.log("Respuesta del servidor:", res);

            if (res.trim() === "ok") {
              Swal.fire(
                "Éxito",
                "Libro registrado correctamente",
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
