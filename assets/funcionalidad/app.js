$(document).ready(function () {
  // Cuando se hace clic en un enlace con data-toggle="collapse"
  $('[data-toggle="collapse"]').on("click", function (e) {
    e.preventDefault();

    const target = $(this).attr("data-target"); // Ej: #menu_despegable
    const $targetMenu = $(target);

    // Si el menú ya está abierto, lo cerramos
    if ($targetMenu.hasClass("show")) {
      $targetMenu.collapse("hide");
    } else {
      // Cerramos los demás menús abiertos
      $(".collapse.show").collapse("hide");
      // Mostramos el que se acaba de clicar
      $targetMenu.collapse("show");
    }
  });
});