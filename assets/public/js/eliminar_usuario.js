function eliminarUsuario(id_usuario) {
  Swal.fire({
    title: "¿Eliminar usuario?",
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
      fd.append("id_usuario", id_usuario);

      fetch("assets/controladores/eliminar_usuario.php", {
        method: "POST",
        body: fd,
      })
        .then((r) => r.text())
        .then((res) => {
          if (res.trim() === "ok") {
            Swal.fire(
              "Eliminado",
              "Usuario eliminado correctamente.",
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
