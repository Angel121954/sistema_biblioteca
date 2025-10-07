function editarLibro(id, titulo, autor, isbn, categoria, cantidad) {
  Swal.fire({
    title: "Editar Libro",
    html: `
      <form id="frm_editar_libro" class="text-start mt-3">
      <div class="container">
        <div class="row g-3">
          <div class="col-md-6">
            <label for="swal_titulo" class="form-label fw-semibold">Título</label>
            <input id="swal_titulo" name="titulo" type="text"
                   class="form-control form-control-lg shadow-sm"
                   placeholder="Ingrese el título" required value="${titulo}">
          </div>

          <div class="col-md-6">
            <label for="swal_autor" class="form-label fw-semibold">Autor</label>
            <input id="swal_autor" name="autor" type="text"
                   class="form-control form-control-lg shadow-sm"
                   placeholder="Ingrese el autor" required value="${autor}">
          </div>

          <div class="col-md-6">
            <label for="swal_isbn" class="form-label fw-semibold">ISBN</label>
            <input id="swal_isbn" name="isbn" type="text"
                   class="form-control form-control-lg shadow-sm"
                   placeholder="Ingrese el ISBN" required value="${isbn}">
          </div>

          <div class="col-md-6">
            <label for="swal_categoria" class="form-label fw-semibold">Categoría</label>
            <input id="swal_categoria" name="categoria" type="text"
                   class="form-control form-control-lg shadow-sm"
                   placeholder="Ingrese la categoría" required value="${categoria}">
          </div>

          <div class="col-md-6">
            <label for="swal_cantidad" class="form-label fw-semibold">Cantidad</label>
            <input id="swal_cantidad" name="cantidad" type="number"
                   class="form-control form-control-lg shadow-sm"
                   placeholder="Ingrese la cantidad" required value="${cantidad}">
          </div>
        </div>
      </div>
    `,
    focusConfirm: false,
    showCancelButton: true,
    confirmButtonText: "Actualizar",
    preConfirm: () => {
      // Armar los datos para enviar
      const fd = new FormData();
      fd.append("id_libro", id);
      fd.append("titulo_libro", document.querySelector("#swal_titulo").value);
      fd.append("autor_libro", document.querySelector("#swal_autor").value);
      fd.append("isbn_libro", document.querySelector("#swal_isbn").value);
      fd.append(
        "categoria_libro",
        document.querySelector("#swal_categoria").value
      );
      fd.append(
        "cantidad_libro",
        document.querySelector("#swal_cantidad").value
      );

      return fd;
    },
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("assets/controladores/libros/editar_libro.php", {
        method: "POST",
        body: result.value,
      })
        .then((r) => r.text())
        .then((res) => {
          console.log("Respuesta del servidor:", res);
          if (res.trim() === "ok") {
            Swal.fire(
              "¡Actualizado!",
              "Libro modificado correctamente",
              "success"
            ).then(() => location.reload());
          } else {
            Swal.fire("Error", res, "error");
          }
        })
        .catch(() =>
          Swal.fire("Error", "No se pudo conectar con el servidor", "error")
        );
    }
  });
}
