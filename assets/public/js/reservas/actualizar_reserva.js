function actualizarReserva(id_reserva, accion) {
  Swal.fire({
    title: `¿Seguro que deseas ${accion.toLowerCase()} esta reserva?`,
    icon: "question",
    showCancelButton: true,
    confirmButtonText: `Sí, ${accion}`,
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      const formData = new FormData();
      formData.append("id_reserva", id_reserva);
      formData.append("accion", accion);

      fetch("assets/controladores/reservas/actualizar_reserva.php", {
        method: "POST",
        body: formData,
      })
        .then((r) => r.text())
        .then((res) => {
          console.log("Respuesta del servidor:", res);

          if (res.trim() === "ok") {
            Swal.fire(
              "Éxito",
              `La reserva fue ${accion.toLowerCase()} correctamente`,
              "success"
            ).then(() => location.reload());
          } else {
            Swal.fire("Error", res, "error");
          }
        })
        .catch(() => {
          Swal.fire("Error", "Hubo un problema con la petición", "error");
        });
    }
  });
}
