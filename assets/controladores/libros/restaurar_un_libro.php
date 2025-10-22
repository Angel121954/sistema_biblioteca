<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["titulo_libro"]) && !empty($_POST["titulo_libro"])) {
    $titulo = filter_var($_POST["titulo_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Activo'
                            WHERE titulo_libro = '$titulo'");
    echo "ok";
}
$sql->desconectar();
