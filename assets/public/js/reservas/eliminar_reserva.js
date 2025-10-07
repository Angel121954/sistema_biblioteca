function eliminarReserva(id) {
  Swal.fire({
    title: "¿Estás seguro?",
    text: "Esta acción eliminará la reserva permanentemente.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      fetch(
        `assets/controladores/reservas/eliminar_reserva.php?id_reserva=${id}`,
        {
          method: "GET",
        }
      )
        .then((response) => response.text())
        .then((data) => {
          Swal.fire({
            title: "¡Eliminado!",
            text: "La reserva ha sido eliminada correctamente.",
            icon: "success",
            confirmButtonColor: "#3085d6",
          }).then(() => {
            location.reload();
          });
        })
        .catch((error) => {
          Swal.fire({
            title: "Error",
            text: "Ocurrió un problema al eliminar la reserva.",
            icon: "error",
            confirmButtonColor: "#3085d6",
          });
        });
    }
  });
}
