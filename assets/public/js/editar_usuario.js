function editarUsuario(
  id,
  nombre,
  apellido,
  email,
  contrasena,
  tiposUsuariosStr
) {
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
    title: "Editar usuario",
    html: `
      <form id="frm_editar_usuario" class="needs-validation" novalidate>
        <input type="hidden" name="id_usuario" value="${id}">

        <div class="form-floating mb-3">
          <input name="nombre_usuario" type="text" class="form-control" id="nombre_usuario"
                 placeholder="Nombre" required autocomplete="off" value="${nombre}">
          <label for="nombre_usuario">Nombres</label>
        </div>

        <div class="form-floating mb-3">
          <input name="apellido_usuario" type="text" class="form-control" id="apellido_usuario"
                 placeholder="Apellido" required autocomplete="off" value="${apellido}">
          <label for="apellido_usuario">Apellidos</label>
        </div>

        <div class="form-floating mb-3">
          <input name="email_usuario" type="email" class="form-control" id="email_usuario"
                 placeholder="Correo" required autocomplete="email" value="${email}">
          <label for="email_usuario">Correo electrónico</label>
        </div>

        <div class="form-floating mb-3">
          <input id="contrasena_usuario" name="contrasena_usuario" type="password"
                 class="form-control" placeholder="Contraseña" required
                 autocomplete="new-password" value="${contrasena}">
          <label for="contrasena_usuario">Contraseña</label>
        </div>

        <div class="form-floating mb-3">
          <select id="tipo_usuario" name="tipo_usuario" class="form-select" required>
            ${opcionesTiposUsuario}
          </select>
          <label for="tipo_usuario">Tipo de usuario</label>
        </div>

        <button type="submit" class="btn btn-primary w-100 py-2">Guardar cambios</button>
      </form>
    `,
    showConfirmButton: false,
    width: 600,
    didOpen: () => {
      const form = document.querySelector("#frm_editar_usuario");

      form.addEventListener("submit", (e) => {
        e.preventDefault();
        const formData = new FormData(form);

        fetch("assets/controladores/usuarios/editar_usuario.php", {
          method: "POST",
          body: formData,
        })
          .then((r) => r.text())
          .then((res) => {
            console.log("Respuesta del servidor:", res);
            if (res.trim() === "ok") {
              Swal.fire({
                icon: "success",
                title: "Usuario actualizado correctamente",
                showConfirmButton: false,
                timer: 1500,
                position: "top-end",
              }).then(() => location.reload());
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
}
