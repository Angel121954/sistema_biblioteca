<?php

require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset(
        $_POST["id_libro"],
        $_POST["titulo_libro"],
        $_POST["autor_libro"],
        $_POST["isbn_libro"],
        $_POST["categoria_libro"],
        $_POST["disponibilidad_libro"],
        $_POST["cantidad_libro"]
    )) {
        //* variables
        $id = intval($_POST["id_libro"]);
        $titulo = filter_var($_POST["titulo_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor = filter_var($_POST["autor_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $isbn = htmlspecialchars(trim($_POST["isbn_libro"]));
        $categoria = trim($_POST["categoria_libro"]);
        $disponibilidad = $_POST["disponibilidad_libro"];
        $cantidad = floatval($_POST["cantidad_libro"]);

        $sql->efectuarConsulta("UPDATE libros SET titulo_libro = '$titulo', autor_libro = '$autor',
                        isbn_libro = '$isbn', categoria_libro = '$categoria', disponibilidad_libro = '$disponibilidad',
                        cantidad_libro = $cantidad WHERE id_libro = $id");
        $sql->desconectar();
    }
}
