function eliminarUsuario(btn) {
  const id_usuario = btn.dataset.id;
  const nombre_usuario = btn.dataset.nombre;
  Swal.fire({
    title: "¿Eliminar usuario?",
    text: "Esta acción inactiva el usuario del sistema.",
    icon: "warning",
    showCancelButton: true,
    confirmButtonColor: "#d33",
    cancelButtonColor: "#6c757d",
    confirmButtonText: "Sí, eliminar",
    cancelButtonText: "Cancelar",
  }).then(async (result) => {
    if (result.isConfirmed) {
      const fd = new FormData();
      fd.append("id_usuario", id_usuario);

      Swal.fire({
        title: "Eliminando usuario...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "assets/controladores/usuarios/eliminar_usuario.php",
        {
          method: "POST",
          body: fd,
        }
      );
      const res = await respuesta.text();
      if (res.trim() === "ok") {
        Swal.fire(
          "Eliminado",
          "Usuario eliminado correctamente.",
          "success"
        ).then(() => location.reload());
      } else if (
        res.trim() ==
        `No se puede inactivar el usuario ${nombre_usuario} ya que está asociado a una reserva`
      ) {
        Swal.fire("Fallo", res, "question");
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
