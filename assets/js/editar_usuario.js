function editarUsuario(
  id,
  nombre,
  apellido,
  email,
  contrasena,
  tiposUsuariosStr
) {
  const tipos_usuarios = JSON.parse(tiposUsuariosStr);
  let opcionesTiposUsuario =
    '<option disabled value="">Seleccione un tipo de usuario</option>';
  tipos_usuarios.forEach((t) => {
    opcionesTiposUsuario += `<option value="${t.id_tipo_usuario}">${t.nombre_tipo_usuario}</option>`;
  });

  Swal.fire({
    title: "Editar Usuario",
    html: `
      <div class="container">
        <div class="row g-2">
          <div class="col-md-6">
            <input id="swal_nombre" type="text" class="form-control"
                   placeholder="Nombre" value="${nombre}">
          </div>
          <div class="col-md-6">
            <input id="swal_apellido" type="text" class="form-control"
                   placeholder="Apellido" value="${apellido}">
          </div>
          <div class="col-md-6">
            <input id="swal_email" type="email" class="form-control"
                   placeholder="Correo" value="${email}">
          </div>
          <div class="col-md-6">
            <input id="swal_contrasena" type="password" class="form-control"
                   placeholder="Contraseña" value="${contrasena}">
          </div>
          <div class="col-md-6">
            <select id="swal_tipo_usuario" class="form-select">
              ${opcionesTiposUsuario}
            </select>
          </div>
        </div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: "Actualizar",
    didOpen: () => {
      // Preseleccionar el tipo de usuario actual
      document.querySelector("#swal_tipo_usuario").value = tipos_usuarios.find(
        (t) => t.id_tipo_usuario == contrasena
      );
    },
    preConfirm: () => {
      // Armar los datos para enviar
      const fd = new FormData();
      fd.append("id_usuario", id);
      fd.append("nombre_usuario", document.querySelector("#swal_nombre").value);
      fd.append(
        "apellido_usuario",
        document.querySelector("#swal_apellido").value
      );
      fd.append("email_usuario", document.querySelector("#swal_email").value);
      fd.append(
        "contrasena_usuario",
        document.querySelector("#swal_contrasena").value
      );
      fd.append(
        "tipo_usuario",
        document.querySelector("#swal_tipo_usuario").value
      );

      return fd;
    },
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("assets/controladores/editar_usuario.php", {
        method: "POST",
        body: result.value,
      })
        .then((r) => r.text())
        .then((res) => {
          console.log("Respuesta del servidor:", res);
          if (res.trim() === "ok") {
            Swal.fire(
              "¡Actualizado!",
              "Usuario modificado correctamente",
              "success"
            ).then(() => location.reload());
          } else {
            Swal.fire("Error", res, "error");
          }
        })
        .catch(() =>
          Swal.fire("Error", "No se pudo conectar con el servidor", "error")
        );
    }
  });
}
