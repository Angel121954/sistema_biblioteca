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
  }).then(async (result) => {
    if (result.isConfirmed) {
      const fd = new FormData();
      fd.append("id_libro", id_libro);

      Swal.fire({
        title: "Eliminando libro...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "assets/controladores/libros/eliminar_libro.php",
        {
          method: "POST",
          body: fd,
        }
      );
      const res = await respuesta.text();
      if (res.trim() === "ok") {
        Swal.fire(
          "Eliminado",
          "Libro eliminado correctamente.",
          "success"
        ).then(() => location.reload());
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
