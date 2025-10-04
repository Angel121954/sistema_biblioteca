function editarLibro(id, titulo, autor, isbn, categoria, cantidad) {
  Swal.fire({
    title: "Editar Libro",
    html: `
      <div class="container">
        <div class="row g-2">
          <div class="col-md-6">
            <input id="swal_titulo" type="text" class="form-control"
                   placeholder="Titulo" value="${titulo}">
          </div>
          <div class="col-md-6">
            <input id="swal_autor" type="text" class="form-control"
                   placeholder="Autor" value="${autor}">
          </div>
          <div class="col-md-6">
            <input id="swal_isbn" type="text" class="form-control"
                   placeholder="ISBN" value="${isbn}">
          </div>
          <div class="col-md-6">
            <input id="swal_categoria" type="text" class="form-control"
                   placeholder="Categoria" value="${categoria}">
          </div>
          <div class="col-md-6">
            <select id="swal_disponibilidad" class="form-select">
              <option value="Disponible">Disponible</option>
              <option value="No disponible">No disponible</option>
            </select>
          </div>
          <div class="col-md-6">
            <input id="swal_cantidad" type="number" class="form-control"
                   placeholder="Cantidad" value="${cantidad}">
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
        "disponibilidad_libro",
        document.querySelector("#swal_disponibilidad").value
      );
      fd.append(
        "cantidad_libro",
        document.querySelector("#swal_cantidad").value
      );

      return fd;
    },
  }).then((result) => {
    if (result.isConfirmed) {
      fetch("assets/controladores/editar_libro.php", {
        method: "POST",
        body: result.value,
      })
        .then((r) => r.text())
        .then((res) => {
          console.log("Respuesta del servidor:", res);
          if (res.trim() === "ok") {
            Swal.fire(
              "¡Actualizado!",
              "Usuario modificado correctamente",
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
