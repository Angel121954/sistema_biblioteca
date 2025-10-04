document
  .querySelector("#btn_registro_usuario")
  .addEventListener("click", (e) => {
    const btn = e.currentTarget;
    const tipo_usuario = JSON.parse(btn.dataset.tiposUsuarios);

    Swal.fire({
      title: "Registro usuario",
      html: `
      <form id="frm_registro_usuario" class="needs-validation" novalidate>
        <div class="form-floating mb-3">
          <input name="nombre_usuario" type="text" class="form-control" id="nombre_usuario" placeholder="Nombre" required>
          <label for="nombre_usuario">Nombres</label>
        </div>

        <div class="form-floating mb-3">
          <input name="apellido_usuario" type="text" class="form-control" id="apellido_usuario" placeholder="Apellido" required autocomplete="off">
          <label for="apellido_usuario">Apellidos</label>
        </div>

        <div class="form-floating mb-3">
          <input name="email_usuario" type="email" class="form-control" id="email_usuario" placeholder="Correo" required autocomplete="email">
          <label for="email_usuario">Correo electrónico</label>
        </div>

        <div class="form-floating mb-3">
          <input id="contrasena_usuario" name="contrasena_usuario" type="password" class="form-control" placeholder="Contraseña" required autocomplete="new-password">
          <label for="contrasena_usuario">Contraseña</label>
        </div>

        <div class="form-floating mb-3">
          <select id="tipo_usuario" name="tipo_usuario" class="form-select" required>
            <option disabled selected>Seleccione el tipo de usuario</option>
          </select>
          <label for="tipo_usuario">Tipo de usuario</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Guardar cambios</button>
      </form>
      `,
      showConfirmButton: false,
      didOpen: () => {
        const selectTipoUsuario = document.querySelector("#tipo_usuario");
        tipo_usuario.forEach((t) => {
          const option = document.createElement("option");
          option.value = t.id_tipo_usuario;
          option.textContent = t.nombre_tipo_usuario;
          selectTipoUsuario.appendChild(option);
        });

        document
          .querySelector("#frm_registro_usuario")
          .addEventListener("submit", function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch("assets/controladores/registro_usuario.php", {
              method: "POST",
              body: formData,
            })
              .then((r) => r.text())
              .then((res) => {
                console.log("Respuesta del servidor:", res);
                if (res.trim() === "ok") {
                  Swal.fire(
                    "Éxito",
                    "Usuario agregado correctamente",
                    "success"
                  ).then(() => location.reload());
                } else {
                  Swal.fire("Error", res, "error");
                }
              })
              .catch(() =>
                Swal.fire("Error", "Hubo un problema con la petición", "error")
              );
          });
      },
    });
  });
