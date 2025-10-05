document.querySelector("#btn_registro_libro").addEventListener("click", () => {
  Swal.fire({
    title: '<h2 class="fw-bold mb-3 text-primary">Registro de libro</h2>',
    html: `
      <form id="frm_registro_libro" class="needs-validation text-start" novalidate style="max-width: 450px; margin: 0 auto;">
        
        <div class="form-floating mb-3">
          <input name="titulo_libro" id="titulo_libro" type="text" class="form-control"
                 placeholder="Título" required autocomplete="off">
          <label for="titulo_libro"><i class="bi bi-book"></i> Título</label>
        </div>

        <div class="form-floating mb-3">
          <input name="autor_libro" id="autor_libro" type="text" class="form-control"
                 placeholder="Autor" required autocomplete="off">
          <label for="autor_libro"><i class="bi bi-person"></i> Autor</label>
        </div>

        <div class="form-floating mb-3">
          <input name="isbn_libro" id="isbn_libro" type="text" class="form-control"
                 placeholder="ISBN" required autocomplete="off">
          <label for="isbn_libro"><i class="bi bi-upc-scan"></i> ISBN</label>
        </div>

        <div class="form-floating mb-3">
          <input name="categoria_libro" id="categoria_libro" type="text" class="form-control"
                 placeholder="Categoría" required autocomplete="off">
          <label for="categoria_libro"><i class="bi bi-tags"></i> Categoría</label>
        </div>

        <div class="form-floating mb-4">
          <input name="cantidad_libro" id="cantidad_libro" type="number" class="form-control"
                 placeholder="Cantidad" required autocomplete="off" min="1">
          <label for="cantidad_libro"><i class="bi bi-123"></i> Cantidad</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
          Guardar libro
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
      const form = document.querySelector("#frm_registro_libro");

      form.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(form);

        fetch("assets/controladores/libros/registro_libro.php", {
          method: "POST",
          body: formData,
        })
          .then((r) => r.text())
          .then((res) => {
            console.log("Respuesta del servidor:", res);

            if (res.trim() === "ok") {
              Swal.fire(
                "¡Actualizado!",
                "Libro agregado correctamente",
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
