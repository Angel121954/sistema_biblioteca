document.addEventListener("DOMContentLoaded", () => {
  const btnRestaurarTodos = document.getElementById("btn_restaurar_libros");

  if (!btnRestaurarTodos) return;

  btnRestaurarTodos.addEventListener("click", async () => {
    const confirmacion = await Swal.fire({
      title: "¿Deseas restaurar todos los libros?",
      text: "Todos los libros inactivos volverán a estar activos en el sistema.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, restaurar todos",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
    });

    if (confirmacion.isConfirmed) {
      try {
        Swal.fire({
          title: "Restaurando usuarios...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        const respuesta = await fetch(
          "assets/controladores/libros/restaurar_libro.php",
          {
            method: "POST",
          }
        );

        const resultado = await respuesta.text();

        if (resultado.trim() === "ok") {
          Swal.fire({
            title: "Libros restaurados",
            text: "Todos los libros fueron activados correctamente.",
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            title: "Error",
            text: resultado || "No se pudieron restaurar los libros.",
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
