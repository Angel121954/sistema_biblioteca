document
  .querySelector("#btn_registro_usuario")
  .addEventListener("click", (e) => {
    const btn = e.currentTarget;
    const tipo_usuario = JSON.parse(btn.dataset.tiposUsuarios);

    Swal.fire({
      title: "Registrar nuevo usuario",
      html: `
      <form id="frm_registro_usuario" class="needs-validation" novalidate>
        <div class="mb-4">
          <input name="nombre_usuario" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_usuario" placeholder="Ingrese el nombre" required">
        </div>

        <div class="mb-4">
          <input name="apellido_usuario" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido_usuario" placeholder="Ingrese el apellido" required">
        </div>

        <div class="mb-4">
          <input name="email_usuario" type="email" class="form-control form-control-lg shadow-sm"
                 id="email_usuario" placeholder="ejemplo@correo.com" required">
        </div>

        <div class="form-floating mb-4">
          <select id="tipo_usuario" name="tipo_usuario" class="form-control" required>
            <option disabled selected>Seleccione el tipo de usuario</option>
          </select>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Guardar usuario</button>
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

            Swal.fire({
              title: "Registrando usuario...",
              text: "Por favor espere un momento.",
              allowOutsideClick: false,
              didOpen: () => Swal.showLoading(),
            });

            registroUsuario(); //* Hoisting
            async function registroUsuario() {
              const respuesta = await fetch(
                "assets/controladores/usuarios/registro_usuario.php",
                {
                  method: "POST",
                  body: formData,
                }
              );
              const res = await respuesta.text();
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
            }
          });
      },
    });
  });
