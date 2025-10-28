function editarUsuario(id, nombre, apellido, email, tiposUsuariosStr) {
  const tipos_usuarios = JSON.parse(tiposUsuariosStr);

  let opcionesTiposUsuario = `
    <option disabled value="">Seleccione un tipo de usuario</option>
  `;

  tipos_usuarios.forEach((t) => {
    opcionesTiposUsuario += `
      <option value="${t.id_tipo_usuario}">${t.nombre_tipo_usuario}</option>
    `;
  });

  Swal.fire({
    title:
      '<h3 class="fw-bold mb-3 text-primary">Editar información del usuario</h3>',
    html: `
      <form id="frm_editar_usuario" class="text-start mt-3">
        <input type="hidden" name="id_usuario" value="${id}">

        <div class="mb-3">
          <label for="nombre_usuario" class="form-label fw-semibold">Nombre</label>
          <input name="nombre_usuario" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre_usuario" placeholder="Ingrese el nombre" required value="${nombre}">
        </div>

        <div class="mb-3">
          <label for="apellido_usuario" class="form-label fw-semibold">Apellido</label>
          <input name="apellido_usuario" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido_usuario" placeholder="Ingrese el apellido" required value="${apellido}">
        </div>

        <div class="mb-3">
          <label for="email_usuario" class="form-label fw-semibold">Correo electrónico</label>
          <input name="email_usuario" type="email" class="form-control form-control-lg shadow-sm"
                 id="email_usuario" placeholder="ejemplo@correo.com" required value="${email}">
        </div>

        <div class="mb-4">
          <label for="tipo_usuario" class="form-label fw-semibold">Tipo de usuario</label>
          <select id="tipo_usuario" name="tipo_usuario" class="form-control form-select-lg shadow-sm" required>
            ${opcionesTiposUsuario}
          </select>
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
      const form = document.querySelector("#frm_editar_usuario");

      form.addEventListener("submit", async (e) => {
        e.preventDefault();

        // ✅ Obtener valores y validarlos antes de enviar
        const nombre = form.nombre_usuario.value.trim();
        const apellido = form.apellido_usuario.value.trim();
        const tipo = form.tipo_usuario.value;

        // Validaciones básicas
        if (nombre.length < 2) {
          Swal.fire(
            "Campo inválido",
            "El nombre debe tener al menos 2 caracteres.",
            "warning"
          );
          return;
        }

        if (apellido.length < 2) {
          Swal.fire(
            "Campo inválido",
            "El apellido debe tener al menos 2 caracteres.",
            "warning"
          );
          return;
        }

        if (!tipo) {
          Swal.fire(
            "Campo inválido",
            "Debe seleccionar un tipo de usuario.",
            "warning"
          );
          return;
        }

        // Si todo es válido
        const formData = new FormData(form);

        Swal.fire({
          title: "Editando usuario...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        try {
          const respuesta = await fetch(
            "assets/controladores/usuarios/editar_usuario.php",
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
              text: "El usuario ha sido modificado correctamente.",
              icon: "success",
              confirmButtonText: "Aceptar",
            }).then(() => location.reload());
          } else {
            Swal.fire(
              "Error",
              res || "No se pudo actualizar el usuario.",
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
