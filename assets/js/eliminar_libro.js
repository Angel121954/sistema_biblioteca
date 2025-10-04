function eliminarLibro(id_libro) {
  Swal.fire({
    title: "¿Eliminar libro?",
    text: "Esta acción no se puede deshacer.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then((result) => {
    if (result.isConfirmed) {
      const fd = new FormData();
      fd.append("id_libro", id_libro);

      fetch("assets/controladores/eliminar_libro.php", {
        method: "POST",
        body: fd,
      })
        .then((r) => r.text())
        .then((res) => {
          if (res.trim() === "ok") {
            Swal.fire(
              "Eliminado",
              "Libro eliminado correctamente.",
              "success"
            ).then(() => location.reload());
          } else {
            Swal.fire("Error", res, "error");
          }
        })
        .catch(() =>
          Swal.fire("Error", "Hubo un problema con la petición", "error")
        );
    }
  });
}
