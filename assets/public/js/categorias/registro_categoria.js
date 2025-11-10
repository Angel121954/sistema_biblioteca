document
  .querySelector("#btn_registro_categoria")
  .addEventListener("click", (e) => {
    const btn = e.currentTarget;
    const categorias = JSON.parse(btn.dataset.categorias);
    Swal.fire({
      title: '<h2 class="fw-bold mb-3 text-primary">Registro de categoría</h2>',
      html: `
      <form id="frm_registro_categoria" class="needs-validation text-start" novalidate style="max-width: 450px; margin: 0 auto;">
        
        <div class="form-floating mb-3">
          <input name="nombre_categoria" id="nombre_categoria" type="text" class="form-control"
                 placeholder="Categoría" required autocomplete="off">
          <label for="nombre_categoria"><i class="bi bi-book"></i> Categoría</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2 fw-semibold">
          Guardar categoría
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
        const selectCategoriaLibro =
          document.querySelector("#nombre_categoria");
        categorias.forEach((c) => {
          const option = document.createElement("option");
          option.value = c.id_categoria;
          option.textContent = c.nombre_categoria;
          selectCategoriaLibro.appendChild(option);
        });

        const form = document.querySelector("#frm_registro_categoria");

        form.addEventListener("submit", (e) => {
          e.preventDefault();

          const formData = new FormData(form);
          const categoria = document
            .querySelector("#nombre_categoria")
            .value.trim();

          const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
          if (!categoria || !regex.test(categoria)) {
            Swal.showValidationMessage(
              "Por favor, ingrese una categoría válida."
            );
            return;
          }

          Swal.fire({
            title: "Registrando categoría...",
            text: "Por favor espere un momento.",
            allowOutsideClick: false,
            didOpen: () => Swal.showLoading(),
          });

          registroCategoria();
          async function registroCategoria() {
            try {
              const respuesta = await fetch(
                "assets/controladores/categorias/registro_categoria.php",
                {
                  method: "POST",
                  body: formData,
                }
              );
              const res = await respuesta.text();
              console.log("Respuesta del servidor:", res);

              if (res.trim() === "ok") {
                Swal.fire(
                  "¡Registrado!",
                  "Categoría agregada correctamente",
                  "success"
                ).then(() => location.reload());
              } else if (
                res.includes(
                  `La categoría ${categoria} ya existe en el aplicativo. Intenta con otro`
                )
              ) {
                Swal.fire("Fallo", res, "question");
              } else {
                Swal.fire("Error", res, "error");
              }
            } catch (error) {
              Swal.fire(
                "Error",
                "No se pudo conectar con el servidor",
                "error"
              );
            }
          }
        });
      },
    });
  });
