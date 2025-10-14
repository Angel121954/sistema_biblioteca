function eliminarReserva(id_reserva) {
  Swal.fire({
    title: "¿Eliminar reserva?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {
      const fd = new FormData();
      fd.append("id_reserva", id_reserva);

      Swal.fire({
        title: "Eliminando reserva...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "assets/controladores/reservas/eliminar_reserva.php",
        {
          method: "POST",
          body: fd,
        }
      );
      const res = await respuesta.text();
      if (res.trim() === "ok") {
        Swal.fire(
          "Eliminado",
          "Reserva eliminada correctamente.",
          "success"
        ).then(() => location.reload());
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
