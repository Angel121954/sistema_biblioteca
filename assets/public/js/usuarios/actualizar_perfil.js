function actualizarPerfil(id, nombre, apellido, email) {
  Swal.fire({
    title: "<i class='fa-solid fa-user-pen'></i> Actualizar perfil",
    html: `
      <input id="nombre" name="nombre" class="swal2-input" placeholder="Nombre" value="${nombre}">
      <input id="apellido" name="apellido" class="swal2-input" placeholder="Apellido" value="${apellido}">
      <input id="email" name="email" type="email" class="swal2-input" placeholder="Correo electrónico" value="${email}">
      <hr>
      <input id="pass1" name="pass1" type="password" class="swal2-input" placeholder="Nueva contraseña (opcional)">
      <input id="pass2" name="pass2" type="password" class="swal2-input" placeholder="Confirmar contraseña">
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: "Guardar cambios",
    cancelButtonText: "Cancelar",
    preConfirm: () => {
      const nombre = document.querySelector("#nombre").value.trim();
      const apellido = document.querySelector("#apellido").value.trim();
      const email = document.querySelector("#email").value.trim();
      const pass1 = document.querySelector("#pass1").value;
      const pass2 = document.querySelector("#pass2").value;

      if (!nombre || !apellido || !email) {
        Swal.showValidationMessage(
          "Por favor completa todos los campos obligatorios"
        );
        return false;
      }

      if (pass1 && pass1 !== pass2) {
        Swal.showValidationMessage("Las contraseñas no coinciden");
        return false;
      }

      return { nombre, apellido, email, pass1 };
    },
  }).then((result) => {
    if (result.isConfirmed) {
      const datos = new FormData();
      datos.append("id_usuario", id);
      datos.append("nombre", result.value.nombre);
      datos.append("apellido", result.value.apellido);
      datos.append("email", result.value.email);
      datos.append("contrasena", result.value.pass1);

      fetch("assets/controladores/usuarios/actualizar_perfil.php", {
        method: "POST",
        body: datos,
      })
        .then((res) => res.text())
        .then((respuesta) => {
          Swal.fire({
            icon: "success",
            title: "Perfil actualizado",
            text: "Los cambios se han guardado correctamente.",
            timer: 2000,
            showConfirmButton: false,
          }).then(() => location.reload());
        })
        .catch((error) => {
          Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un problema al actualizar el perfil.",
          });
        });
    }
  });
}
