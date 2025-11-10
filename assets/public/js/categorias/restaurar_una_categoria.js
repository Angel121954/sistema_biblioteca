document.addEventListener("DOMContentLoaded", () => {
  const btnRestaurarLibro = document.querySelector(
    "#btn_restaurar_una_categoria"
  );
  if (!btnRestaurarLibro) return;

  btnRestaurarLibro.addEventListener("click", async () => {
    // Pedir título del libro con validación
    const { value: categoria } = await Swal.fire({
      title: "Restaurar categoría",
      text: "Ingrese el nombre de la categoría que desea restaurar:",
      input: "text",
      inputPlaceholder: "Nombre de la categoría",
      showCancelButton: true,
      confirmButtonText: "Restaurar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      inputValidator: (value) => {
        // Validación básica
        if (!value) {
          return "Debes ingresar el nombre de la categoría.";
        }

        // Validación de caracteres y longitud
        const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
        if (!regex.test(value) || value.length < 2) {
          return "La categoría debe ser válida.";
        }
      },
    });

    if (!categoria) return;

    // Confirmación final
    const confirmacion = await Swal.fire({
      title: `¿Restaurar "${categoria}"?`,
      text: "La categoría volverá a estar disponible en el sistema.",
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
          title: "Restaurando categoría...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        const respuesta = await fetch(
          "assets/controladores/categorias/restaurar_una_categoria.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `nombre_categoria=${encodeURIComponent(categoria)}`,
          }
        );

        const resultado = await respuesta.text();

        if (resultado.trim() === "ok") {
          Swal.fire({
            title: "Categoría restaurada",
            text: `"${categoria}" ha sido activado correctamente.`,
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => location.reload());
        } else if (
          resultado.trim() ===
          `No existe la categoría ${categoria} en estado inactivo`
        ) {
          Swal.fire("Fallo", resultado, "question");
        } else {
          Swal.fire({
            title: "Error",
            text: resultado || "No se pudo restaurar la categoría.",
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
