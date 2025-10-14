document.addEventListener("DOMContentLoaded", () => {
  const btnFiltrar = document.querySelector("#btn_filtrar_libros");
  const inputFiltro = document.querySelector("#filtrar_libro");
  const contenedor = document.querySelector("#tbl_libros");

  btnFiltrar.addEventListener("click", async () => {
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

    Swal.fire({
      position: "top-end",
      title: "Filtrando libros...",
      text: "Por favor espere un momento.",
      allowOutsideClick: false,
      didOpen: () => Swal.showLoading(),
    });

    const respuesta = await fetch(
      "assets/controladores/libros/filtrar_libro.php",
      {
        method: "POST",
        headers: {
          "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `filtro=${encodeURIComponent(filtro)}`,
      }
    );
    const data = await respuesta.text();
    contenedor.innerHTML = data;

    Swal.fire({
      position: "top-end",
      icon: "success",
      title: "Libros filtrados correctamente",
      showConfirmButton: false,
      timer: 1500,
    });
  });
});
