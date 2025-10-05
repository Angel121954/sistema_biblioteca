document.addEventListener("DOMContentLoaded", () => {
  const btnFiltrar = document.querySelector("#btn_filtrar_libros");
  const inputFiltro = document.querySelector("#filtrar_libro");
  const contenedor = document.querySelector("#dataTable tbody"); // solo el cuerpo de la tabla

  btnFiltrar.addEventListener("click", () => {
    const filtro = inputFiltro.value.trim();

    if (filtro === "") {
      Swal.fire({
        position: "top-end",
        icon: "warning",
        title: "Campo vacío",
        text: "Por favor ingresa un criterio de búsqueda",
        showConfirmButton: false,
        timer: 2000,
      });
      return;
    }

    fetch("assets/controladores/libros/filtrar_libro.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/x-www-form-urlencoded",
      },
      body: `filtro=${encodeURIComponent(filtro)}`,
    })
      .then((response) => response.text())
      .then((data) => {
        contenedor.innerHTML = data;

        Swal.fire({
          position: "top-end",
          icon: "success",
          title: "Libros filtrados correctamente",
          showConfirmButton: false,
          timer: 1500,
        });
      })
      .catch(() => {
        Swal.fire({
          position: "top-end",
          icon: "error",
          title: "Error",
          text: "No se pudo filtrar los libros",
          showConfirmButton: false,
          timer: 2000,
        });
      });
  });
});
