document.addEventListener("DOMContentLoaded", () => {
  const btnRestaurarTodos = document.getElementById("btn_restaurar_categorias");

  if (!btnRestaurarTodos) return;

  btnRestaurarTodos.addEventListener("click", async () => {
    const confirmacion = await Swal.fire({
      title: "¿Deseas restaurar todas las categorías?",
      text: "Todos las categorías inactivas volverán a estar activos en el sistema.",
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
          title: "Restaurando categorías...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        const respuesta = await fetch(
          "assets/controladores/categorias/restaurar_categoria.php",
          {
            method: "POST",
          }
        );

        const resultado = await respuesta.text();

        if (resultado.trim() === "ok") {
          Swal.fire({
            title: "Categorías restauradas",
            text: "Todas las categorías fueron activadas correctamente.",
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => {
            location.reload();
          });
        } else if (resultado.includes("No hay categorías para restaurar")) {
          Swal.fire("Fallo", resultado, "question");
        } else {
          Swal.fire({
            title: "Error",
            text: resultado || "No se pudieron restaurar las categorías.",
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
