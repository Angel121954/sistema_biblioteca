document.addEventListener("DOMContentLoaded", () => {
  const btnRestaurarLibro = document.querySelector("#btn_restaurar_un_libro");

  if (!btnRestaurarLibro) return;

  btnRestaurarLibro.addEventListener("click", async () => {
    // Pedir título del libro
    const { value: titulo } = await Swal.fire({
      title: "Restaurar libro",
      text: "Ingrese el título del libro que desea restaurar:",
      input: "text",
      inputPlaceholder: "Título del libro",
      showCancelButton: true,
      confirmButtonText: "Restaurar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      inputValidator: (value) => {
        if (!value) return "Debes ingresar el título del libro.";
      },
    });

    if (!titulo) return;

    // Confirmación final
    const confirmacion = await Swal.fire({
      title: `¿Restaurar "${titulo}"?`,
      text: "El libro volverá a estar disponible en el sistema.",
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
          title: "Restaurando libro...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        const respuesta = await fetch(
          "assets/controladores/libros/restaurar_un_libro.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `titulo_libro=${encodeURIComponent(titulo)}`,
          }
        );

        const resultado = await respuesta.text();

        if (resultado.trim() === "ok") {
          Swal.fire({
            title: "Libro restaurado",
            text: `"${titulo}" ha sido activado correctamente.`,
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => location.reload());
        } else {
          Swal.fire({
            title: "Error",
            text: resultado || "No se pudo restaurar el libro.",
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
