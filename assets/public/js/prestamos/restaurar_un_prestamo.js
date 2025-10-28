document.addEventListener("DOMContentLoaded", () => {
  const btnRestaurarPrestamo = document.querySelector(
    "#btn_restaurar_un_prestamo"
  );

  if (!btnRestaurarPrestamo) return;

  btnRestaurarPrestamo.addEventListener("click", async () => {
    // Pedir la fecha del préstamo al usuario
    const { value: fecha_prestamo } = await Swal.fire({
      title: "Restaurar préstamo",
      text: "Ingrese la fecha del préstamo que desea restaurar:",
      input: "date",
      inputPlaceholder: "Fecha del préstamo",
      showCancelButton: true,
      confirmButtonText: "Restaurar",
      cancelButtonText: "Cancelar",
      confirmButtonColor: "#28a745",
      cancelButtonColor: "#6c757d",
      inputValidator: (value) => {
        if (!value) return "Debes ingresar la fecha del préstamo.";
      },
    });

    if (!fecha_prestamo) return;

    // Confirmación final
    const confirmacion = await Swal.fire({
      title: `¿Restaurar préstamo con fecha "${fecha_prestamo}"?`,
      text: "El préstamo volverá a estar disponible en el sistema.",
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
          title: "Restaurando préstamo...",
          text: "Por favor espere un momento.",
          allowOutsideClick: false,
          didOpen: () => Swal.showLoading(),
        });

        // Enviar la fecha al backend
        const respuesta = await fetch(
          "assets/controladores/prestamos/restaurar_un_prestamo.php",
          {
            method: "POST",
            headers: {
              "Content-Type": "application/x-www-form-urlencoded",
            },
            body: `fecha_prestamo=${encodeURIComponent(fecha_prestamo)}`,
          }
        );

        const resultado = await respuesta.text();

        if (resultado.trim() === "ok") {
          Swal.fire({
            title: "Préstamo restaurado",
            text: `El préstamo del día "${fecha_prestamo}" ha sido activado correctamente.`,
            icon: "success",
            confirmButtonColor: "#28a745",
          }).then(() => location.reload());
        } else {
          Swal.fire({
            title: "Error",
            text: resultado || "No se pudo restaurar el préstamo.",
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
