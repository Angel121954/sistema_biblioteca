function eliminarCategoria(btn) {
  const id_categoria = btn.dataset.id;
  const categoria = btn.dataset.nombre;
  Swal.fire({
    title: "¿Eliminar categoría?",
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
      fd.append("id_categoria", id_categoria);
      fd.append("nombre_categoria", categoria);

      Swal.fire({
        title: "Eliminando categoría...",
        text: "Por favor espere un momento.",
        allowOutsideClick: false,
        didOpen: () => Swal.showLoading(),
      });

      const respuesta = await fetch(
        "assets/controladores/categorias/eliminar_categoria.php",
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
          "Categoría eliminado correctamente.",
          "success"
        ).then(() => location.reload());
      } else if (
        res.includes(
          `No se puede inactivar la categoria ${categoria} ya que está asociado a un libro`
        )
      ) {
        Swal.fire("Fallo", res, "question");
      } else {
        Swal.fire("Error", res, "error");
      }
    }
  });
}
