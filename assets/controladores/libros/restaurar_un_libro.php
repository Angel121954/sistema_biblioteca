<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["titulo_libro"]) && !empty($_POST["titulo_libro"])) {
    $titulo = filter_var($_POST["titulo_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $restaurar = $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Activo'
                            WHERE titulo_libro = '$titulo'");
    if ($restaurar) {
        echo "ok";
    } else {
        echo "No se pudo restaurar el libro pedido.";
    }
}
$sql->desconectar();
