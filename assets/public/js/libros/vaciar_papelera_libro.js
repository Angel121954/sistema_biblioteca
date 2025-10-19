document.addEventListener("DOMContentLoaded", () => {
  const btnEliminarTodos = document.querySelector("#btn_papelera_libros");

  if (!btnEliminarTodos) return;

  btnEliminarTodos.addEventListener("click", async () => {
    const confirmacion = await Swal.fire({
      title: "¿Deseas eliminar todos los libros?",
      text: "Todos los libros inactivos serán eliminados completamente del sistema.",
      icon: "question",
      showCancelButton: true,
      confirmButtonText: "Sí, vaciar papelera",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
    });

    if (confirmacion.isConfirmed) {
      try {
        Swal.fire({
          title: "Vaciando papelera...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        const respuesta = await fetch(
          "assets/controladores/libros/vaciar_papelera_libro.php",
          {
            method: "POST",
          }
        );

        const res = await respuesta.text();

        console.log(`Lo que trajo la respuesta: ${res}`);

        if (res.trim() === "ok") {
          Swal.fire({
            title: "Papelera vaciada",
            text: "Todos los libros fueron eliminados correctamente.",
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => {
            location.reload();
          });
        } else {
          Swal.fire({
            title: "Error",
            text: res || "No se pudieron eliminar los libros.",
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
