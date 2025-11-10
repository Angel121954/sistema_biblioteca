function editarCategoria(btn) {
  const id = btn.dataset.id;
  const categoria = btn.dataset.nombre;

  Swal.fire({
    title:
      '<h3 class="fw-bold mb-3 text-primary">Editar información de la categoría</h3>',
    html: `
      <form id="frm_editar_categoria" class="text-start mt-3">
        <input type="hidden" name="id_categoria" value="${id}">

        <div class="mb-3">
          <label for="nombre_categoria" class="form-label fw-semibold">Nombre</label>
          <input name="nombre_categoria" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_categoria" placeholder="Ingrese el nombre de la categoría" required value="${categoria}">
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold shadow-sm"
          style="border-radius:10px; background:#3b82f6; border:none;">
          Guardar cambios
        </button>
      </form>
    `,
    showConfirmButton: false,
    width: 600,
    background: "#fdfdfd",
    customClass: {
      popup: "shadow-lg rounded-4 border-0 p-4",
    },
    didOpen: () => {
      const form = document.querySelector("#frm_editar_categoria");

      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        const categoria = form.nombre_categoria.value.trim();

        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!regex.test(categoria)) {
          Swal.showValidationMessage(
            "El nombre de la categoría debe ser válido."
          );
          return;
        }

        // Si todo es válido
        const formData = new FormData(form);

        Swal.fire({
          title: "Editando categoría...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        try {
          const respuesta = await fetch(
            "assets/controladores/categorias/editar_categoria.php",
            {
              method: "POST",
              body: formData,
            }
          );

          const res = await respuesta.text();
          console.log("Respuesta del servidor:", res);

          if (res.trim() === "ok") {
            Swal.fire({
              title: "Actualización exitosa",
              text: "La categoría ha sido modificada correctamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => location.reload());
          } else if (
            res.includes(
              "Está categoría ya existe en el sistema. Por favor ingresa otra"
            )
          ) {
            Swal.fire("Fallo", res, "question");
          } else {
            Swal.fire(
              "Error",
              res || "No se pudo actualizar la categoría.",
              "error"
            );
          }
        } catch (error) {
          console.error(error);
          Swal.fire("Error", "No se pudo conectar con el servidor.", "error");
        }
      });
    },
  });
}
