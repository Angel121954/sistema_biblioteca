document.addEventListener("DOMContentLoaded", () => {
  const btnRestaurarUno = document.querySelector("#btn_restaurar_uno");

  if (!btnRestaurarUno) return;

  btnRestaurarUno.addEventListener("click", async () => {
    const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;

    // Pedir nombre con validación
    const { value: nombre } = await Swal.fire({
      title: "Restaurar usuario",
      text: "Ingrese el nombre del usuario que desea restaurar:",
      input: "text",
      inputPlaceholder: "Nombre",
      showCancelButton: true,
      confirmButtonText: "Continuar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      inputValidator: (value) => {
        if (!value) {
          return "Debes ingresar el nombre del usuario.";
        }
        if (!regex.test(value) || value.length < 2) {
          return "El nombre debe ser válido y contar con 2 o más caracteres.";
        }
      },
    });

    if (!nombre) return;

    // Pedir apellido con validación
    const { value: apellido } = await Swal.fire({
      title: "Restaurar usuario",
      text: "Ingrese el apellido del usuario:",
      input: "text",
      inputPlaceholder: "Apellido",
      showCancelButton: true,
      confirmButtonText: "Restaurar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      inputValidator: (value) => {
        if (!value) {
          return "Debes ingresar el apellido del usuario.";
        }
        if (!regex.test(value) || value.length < 2) {
          return "El apellido debe ser válido y contar con 2 o más caracteres.";
        }
      },
    });

    if (!apellido) return;

    // Confirmación final
    const confirmacion = await Swal.fire({
      title: `¿Restaurar a ${nombre} ${apellido}?`,
      text: "El usuario volverá a estar activo en el sistema.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, restaurar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
    });

    if (confirmacion.isConfirmed) {
      try {
        Swal.fire({
          title: "Restaurando usuario...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        const respuesta = await fetch(
          "assets/controladores/usuarios/restaurar_un_usuario.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `nombre_usuario=${encodeURIComponent(
              nombre
            )}&apellido_usuario=${encodeURIComponent(apellido)}`,
          }
        );

        const resultado = await respuesta.text();

        if (resultado.trim() === "ok") {
          Swal.fire({
            title: "Usuario restaurado",
            text: `${nombre} ${apellido} ha sido activado correctamente.`,
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => location.reload());
        } else if (resultado.includes("No existe el usuario")) {
          Swal.fire("Fallo", resultado, "question");
        } else {
          Swal.fire({
            title: "Error",
            text: resultado || "No se pudo restaurar el usuario.",
            icon: "error",
          });
        }
      } catch (error) {
        console.error(error);
        Swal.fire({
          title: "Error",
          text: "No se pudo conectar con el servidor.",
          icon: "error",
        });
      }
    }
  });
});
