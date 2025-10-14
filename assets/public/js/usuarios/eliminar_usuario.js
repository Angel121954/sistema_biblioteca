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
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
