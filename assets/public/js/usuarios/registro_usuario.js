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

        <div class="mb-4">
          <input name="contrasena_usuario" type="password" class="form-control form-control-lg shadow-sm"
                 id="contrasena_usuario" placeholder="Ingrese la contraseña" required">
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
            const nombre = document
              .querySelector("#nombre_usuario")
              .value.trim();
            const apellido = document
              .querySelector("#apellido_usuario")
              .value.trim();

            const email = document.querySelector("#email_usuario").value.trim();

            const contrasena = document
              .querySelector("#contrasena_usuario")
              .value.trim();

            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            if (!regex.test(nombre)) {
              Swal.showValidationMessage("El nombre debe ser válido.");
              return;
            }

            if (!regex.test(apellido)) {
              Swal.showValidationMessage("El apellido debe ser válido.");
              return;
            }

            if (!email || email.includes("'")) {
              Swal.fire(
                "Campo inválido",
                "El correo debe ser válido y no debe de contener comilla simple por cuestiones de seguridad.",
                "warning"
              );
              return;
            }

            if (contrasena.length < 4) {
              Swal.showValidationMessage(
                "La contrasña debe de tener un minimo de 4 caracteres."
              );
              return;
            }

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
              } else if (
                res ==
                "Este correo ya existe en la base de datos. Intenta con otro"
              ) {
                Swal.fire("Fallo", res, "question");
              } else {
                Swal.fire("Error", res, "error");
              }
            }
          });
      },
    });
  });
