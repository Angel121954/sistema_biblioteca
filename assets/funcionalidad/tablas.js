$(document).ready(function () {
  $(".tabla_dt").DataTable({
    responsive: true, // 📱 Hace que la tabla se adapte a pantallas pequeñas
    autoWidth: false, // ⚙️ Evita que las columnas se deformen
    pageLength: 10, // 🔢 Número de filas por página
    lengthMenu: [5, 10, 25, 50, 100], // 🎚️ Opciones de cantidad de registros a mostrar
    order: [[0, "asc"]], // 🔡 Ordena automáticamente por la primera columna
    searching: true, // 🔍 Activa el buscador
    paging: true, // 📄 Paginación
    info: true, // ℹ️ Muestra el texto de "Mostrando X de Y registros"
    language: {
      url: "assets/lang/es-ES.json", // 🌎 Traducción al español
    },
    dom:
      // 🎨 Estructura visual de los controles (orden y posición)
      "<'row mb-3'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>>" +
      "<'row'<'col-sm-12'tr>>" +
      "<'row mt-3'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
  });
});
