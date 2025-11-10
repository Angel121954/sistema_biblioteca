document.querySelector("#btn_registro_libro").addEventListener("click", (e) => {
  const btn = e.currentTarget;
  const categoria_libro = JSON.parse(btn.dataset.categorias);
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
          <input name="isbn_libro" id="isbn_libro" type="number" class="form-control"
                 placeholder="ISBN" required autocomplete="off">
          <label for="isbn_libro"><i class="bi bi-upc-scan"></i> ISBN</label>
        </div>

        <div class="form-floating mb-4">
          <select id="categoria_libro" name="categoria_libro" class="form-select" required>
            <option value="" disabled selected>Seleccione una categoría</option>
          </select>
          <label for="categoria_libro"><i class="bi bi-tag"></i> Categoría</label>
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
      const selectCategoriaLibro = document.querySelector("#categoria_libro");
      categoria_libro.forEach((c) => {
        const option = document.createElement("option");
        option.value = c.id_categoria;
        option.textContent = c.nombre_categoria;
        selectCategoriaLibro.appendChild(option);
      });

      const form = document.querySelector("#frm_registro_libro");

      form.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = new FormData(form);
        const titulo = document.querySelector("#titulo_libro").value.trim();
        const autor = document.querySelector("#autor_libro").value.trim();
        const isbn = document.querySelector("#isbn_libro").value.trim();
        const categoria = document.querySelector("#categoria_libro").value;
        const cantidad = document.querySelector("#cantidad_libro").value.trim();

        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

        if (!regex.test(titulo) || !titulo || titulo.length < 4) {
          Swal.showValidationMessage(
            "El título debe ser válido y con un mínimo de 4 caracteres."
          );
          return;
        }

        if (!regex.test(autor) || !autor || autor.length < 4) {
          Swal.showValidationMessage(
            "El autor debe ser válido y con un mínimo de 4 caracteres."
          );
          return;
        }

        if (!isbn || isbn.length < 5) {
          Swal.showValidationMessage(
            "El ISBN debe ser válido y con un mínimo de 5 caracteres."
          );
          return;
        }

        if (!categoria) {
          Swal.showValidationMessage("Debe seleccionar una categoría.");
          return;
        }

        if (!cantidad || cantidad <= 0) {
          Swal.showValidationMessage("La cantidad debe ser válida.");
          return;
        }

        Swal.fire({
          title: "Registrando libro...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        registroLibro();
        async function registroLibro() {
          try {
            const respuesta = await fetch(
              "assets/controladores/libros/registro_libro.php",
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
                "Libro agregado correctamente",
                "success"
              ).then(() => location.reload());
            } else if (
              res.includes(`El libro ${titulo} ya existe en el sistema`)
            ) {
              Swal.fire("Fallo", res, "question");
            } else {
              Swal.fire("Error", res, "error");
            }
          } catch (error) {
            Swal.fire("Error", "No se pudo conectar con el servidor", "error");
          }
        }
      });
    },
  });
});
