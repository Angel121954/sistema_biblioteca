function editarLibro(btn) {
  const id = btn.dataset.id;
  const titulo = btn.dataset.titulo;
  const autor = btn.dataset.autor;
  const categoria = btn.dataset.categoria;
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
              <label for="swal_categoria" class="form-label fw-semibold">Categoría</label>
              <input id="swal_categoria" type="text" class="form-control form-control-lg shadow-sm"
                     placeholder="Ingrese la categoría" required value="${categoria}">
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
    preConfirm: () => {
      const titulo = document.querySelector("#swal_titulo").value.trim();
      const autor = document.querySelector("#swal_autor").value.trim();
      const categoria = document.querySelector("#swal_categoria").value.trim();
      const cantidad = document.querySelector("#swal_cantidad").value;

      if (!titulo || !autor || !categoria) {
        Swal.showValidationMessage(
          "Por favor, complete todos los campos correctamente."
        );
        return false;
      }

      const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
      if (!regex.test(titulo)) {
        Swal.fire("Campo inválido", "El titulo debe ser válido.", "warning");
        return;
      }

      if (!regex.test(autor)) {
        Swal.fire("Campo inválido", "El autor debe ser válido.", "warning");
        return;
      }

      if (!regex.test(categoria)) {
        Swal.fire("Campo inválido", "La categoría debe ser válida.", "warning");
        return;
      }

      if (cantidad <= 0) {
        Swal.fire(
          "Campo inválido",
          "La cantidad del libro debe ser válida.",
          "warning"
        );
        return;
      }

      const fd = new FormData();
      fd.append("id_libro", id);
      fd.append("titulo_libro", titulo);
      fd.append("autor_libro", autor);
      fd.append("categoria_libro", categoria);
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
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
