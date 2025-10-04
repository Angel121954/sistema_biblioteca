<?php
require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset(
            $_POST["titulo_libro"],
            $_POST["autor_libro"],
            $_POST["isbn_libro"],
            $_POST["categoria_libro"],
            $_POST["disponibilidad_libro"],
            $_POST["cantidad_libro"]
        ) &&
        !empty($_POST["titulo_libro"]) &&
        !empty($_POST["isbn_libro"]) &&
        !empty($_POST["categoria_libro"]) &&
        !empty($_POST["disponibilidad_libro"]) &&
        !empty($_POST["cantidad_libro"])
    ) {
        $titulo         = filter_var($_POST["titulo_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $autor          = filter_var($_POST["autor_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $isbn           = htmlspecialchars(trim($_POST["isbn_libro"]));
        $categoria      = filter_var($_POST["categoria_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $disponibilidad = $_POST["disponibilidad_libro"];
        $cantidad       = floatval($_POST["cantidad_libro"]);
        $sql->efectuarConsulta("INSERT INTO libros
                (titulo_libro, autor_libro, isbn_libro, categoria_libro, disponibilidad_libro, cantidad_libro)
            VALUES
                ('$titulo', '$autor', '$isbn', '$categoria', '$disponibilidad', $cantidad)");

        echo "ok";
        $sql->desconectar();
    }
}
