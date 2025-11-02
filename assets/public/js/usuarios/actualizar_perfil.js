function actualizarPerfil(btn) {
  const id = btn.dataset.id;
  const nombre = btn.dataset.nombre;
  const apellido = btn.dataset.apellido;
  const email = btn.dataset.email;
  Swal.fire({
    title: "<i class='fa-solid fa-user-pen'></i> Actualizar perfil",
    html: `
    <div class="mb-4">
          <input name="nombre" type="text" class="form-control form-control-lg shadow-sm"
                 id="nombre" placeholder="Ingrese su nombre" required value="${nombre}">
        </div>
        <div class="mb-4">
          <input name="apellido" type="text" class="form-control form-control-lg shadow-sm"
                 id="apellido" placeholder="Ingrese su apellido" required value="${apellido}">
        </div>
        <div class="mb-4">
          <input name="email" type="email" class="form-control form-control-lg shadow-sm"
                 id="email" placeholder="ejemplo@correo.com" required value="${email}">
        </div>
      <hr>
      <div class="mb-4">
      <input id="pass1" name="pass1" type="password" class="form-control form-control-lg shadow-sm" 
      placeholder="Nueva contraseña (opcional)">
      </div>
      <div class="mb-4">
      <input id="pass2" name="pass2" type="password" class="form-control form-control-lg shadow-sm" 
      placeholder="Confirmar contraseña">
      </div>
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

      const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
      if (!regex.test(nombre) || !regex.test(apellido)) {
        Swal.fire(
          "Campo inválido",
          "Por favor, ingrese campos válidos para poder actualizar el perfil.",
          "warning"
        );
        return false;
      }

      if (pass1 && pass1 !== pass2) {
        Swal.showValidationMessage("Las contraseñas no coinciden");
        return false;
      }

      return { nombre, apellido, email, pass1 };
    },
  }).then(async (result) => {
    if (result.isConfirmed) {
      const datos = new FormData();
      datos.append("id_usuario", id);
      datos.append("nombre", result.value.nombre);
      datos.append("apellido", result.value.apellido);
      datos.append("email", result.value.email);
      datos.append("contrasena", result.value.pass1);

      /* Swal.fire({
        title: "Actualizando perfil...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      }); */

      const respuesta = await fetch(
        "assets/controladores/usuarios/actualizar_perfil.php",
        {
          method: "POST",
          body: datos,
        }
      );
      const res = await respuesta.text();
      if (res.trim() === "ok") {
        Swal.fire({
          icon: "success",
          title: "Perfil actualizado",
          text: "Los cambios se han guardado correctamente.",
          timer: 2000,
          showConfirmButton: false,
        }).then(() => location.reload());
      }
    }
  });
}
