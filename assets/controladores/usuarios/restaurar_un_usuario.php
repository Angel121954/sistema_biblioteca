<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["nombre_usuario"], $_POST["apellido_usuario"])
    && !empty($_POST["nombre_usuario"] && !empty($_POST["apellido_usuario"]))
) {
    //* variable
    $nombre_usuario = filter_var($_POST["nombre_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $apellido_usuario = filter_var($_POST["apellido_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";

    $sql->efectuarConsulta("UPDATE usuarios SET estado_usuario = 'Activo'
                            WHERE nombre_usuario = '$nombre_usuario' AND
                            apellido_usuario = '$apellido_usuario'");
    echo "ok";
}
$sql->desconectar();
