document
  .querySelector("#btn_cerrar_session")
  .addEventListener("click", async () => {
    Swal.fire({
      title: "¿Deseas cerrar sesión?",
      text: "Tu sesión actual se cerrará.",
      icon: "question",
      showCancelButton: true,
      confirmButtonColor: "#3085d6",
      cancelButtonColor: "#d33",
      confirmButtonText: "Sí, cerrar sesión",
      cancelButtonText: "Cancelar",
    }).then(async (result) => {
      if (result.isConfirmed) {
        // Mostrar mensaje de cargando
        Swal.fire({
          title: "Cerrando sesión...",
          text: "Por favor espera un momento.",
          allowOutsideClick: false,
          didOpen: () => {
            Swal.showLoading();
          },
        });

        try {
          const respuesta = await fetch(
            "assets/controladores/usuarios/cerrar_sesion_usuario.php",
            {
              method: "POST",
              headers: { "Content-Type": "application/x-www-form-urlencoded" },
              body: "accion=cerrar",
            }
          );

          const resultado = await respuesta.text();

          if (resultado.trim() === "OK") {
            window.location.href = "login.php";
          } else {
            Swal.fire({
              icon: "error",
              title: "Error al cerrar sesión",
              text: "Inténtalo de nuevo.",
            });
            console.error(resultado);
          }
        } catch (error) {
          console.error("Error en la solicitud:", error);
          Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "Ocurrió un error al intentar cerrar sesión.",
          });
        }
      }
    });
  });
