function editarLibro(btn) {
  const id = btn.dataset.id;
  const titulo = btn.dataset.titulo;
  const autor = btn.dataset.autor;
  const categoria_libro = JSON.parse(btn.dataset.categoria);
  const cantidad = btn.dataset.cantidad;

  Swal.fire({
    title: "Editar Libro",
    html: `
      <form id="frm_editar_libro" class="text-start mt-3">
        <div class="container">
          <div class="row g-3">
            <div class="col-md-6">
              <label for="swal_titulo" class="form-label fw-semibold">Título</label>
              <input id="swal_titulo" type="text" class="form-control form-control-lg shadow-sm"
                     placeholder="Ingrese el título" required value="${titulo}">
            </div>

            <div class="col-md-6">
              <label for="swal_autor" class="form-label fw-semibold">Autor</label>
              <input id="swal_autor" type="text" class="form-control form-control-lg shadow-sm"
                     placeholder="Ingrese el autor" required value="${autor}">
            </div>

            <div class="col-md-6">
            <label for="swal_autor" class="form-label fw-semibold">Categoría</label>
            <div class="form-floating mb-4">
          <select id="categoria_libro" name="categoria_libro" class="form-select" required>
            <option value="" disabled selected>Seleccione una categoría</option>
          </select>
          <label for="categoria_libro"><i class="bi bi-tag"></i> Categoría</label>
        </div>
        </div>

            <div class="col-md-6">
              <label for="swal_cantidad" class="form-label fw-semibold">Cantidad</label>
              <input id="swal_cantidad" type="number" min="1" class="form-control form-control-lg shadow-sm"
                     placeholder="Ingrese la cantidad" required value="${cantidad}">
            </div>
          </div>
        </div>
      </form>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: "Actualizar",
    cancelButtonText: "Cancelar",
    confirmButtonColor: "#3085d6",
    cancelButtonColor: "#d33",
    didOpen: () => {
      const selectCategoriaLibro = document.querySelector("#categoria_libro");
      categoria_libro.forEach((c) => {
        const option = document.createElement("option");
        option.value = c.id_categoria;
        option.textContent = c.nombre_categoria;
        selectCategoriaLibro.appendChild(option);
      });
    },
    preConfirm: () => {
      const titulo = document.querySelector("#swal_titulo").value.trim();
      const autor = document.querySelector("#swal_autor").value.trim();
      const categoria = document.querySelector("#categoria_libro").value.trim();
      const cantidad = document.querySelector("#swal_cantidad").value;

      const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

      if (!regex.test(titulo) || !titulo || titulo.length < 4) {
        Swal.showValidationMessage(
          "El título debe ser válido y con un minimo de 4 caracteres."
        );
        return;
      }

      if (!regex.test(autor) || !autor || autor.length < 4) {
        Swal.showValidationMessage(
          "El autor debe ser válido y con un minimo de 4 caracteres."
        );
        return;
      }

      if (!categoria) {
        Swal.showValidationMessage("La categoría debe ser válida.");
        return;
      }

      if (!cantidad || cantidad <= 0) {
        Swal.showValidationMessage("La cantidad debe ser válida.");
        return;
      }

      const fd = new FormData();
      fd.append("id_libro", id);
      fd.append("titulo_libro", titulo);
      fd.append("autor_libro", autor);
      fd.append("fk_categoria", categoria);
      fd.append("cantidad_libro", cantidad);

      return fd;
    },
  }).then(async (result) => {
    if (result.isConfirmed) {
      Swal.fire({
        title: "Editando libro...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "assets/controladores/libros/editar_libro.php",
        {
          method: "POST",
          body: result.value,
        }
      );
      const res = await respuesta.text();
      console.log("Respuesta del servidor:", res);
      if (res.trim() === "ok") {
        Swal.fire({
          title: "¡Actualizado!",
          text: "Libro modificado correctamente",
          icon: "success",
          confirmButtonColor: "#3085d6",
        }).then(() => location.reload());
      } else if (res.includes(`El libro ${titulo} ya existe en el sistema`)) {
        Swal.fire("Fallo", res, "question");
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
