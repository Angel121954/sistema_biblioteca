function eliminarPrestamo(id_prestamo) {
  Swal.fire({
    title: "¿Eliminar prestamo?",
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
      fd.append("id_prestamo", id_prestamo);

      Swal.fire({
        title: "Eliminando prestamo...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "assets/controladores/prestamos/eliminar_prestamo.php",
        {
          method: "POST",
          body: fd,
        }
      );
      const res = await respuesta.text();
      console.log(res);
      if (res.trim() === "ok") {
        Swal.fire(
          "Eliminado",
          "Prestamo eliminado correctamente.",
          "success"
        ).then(() => location.reload());
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
