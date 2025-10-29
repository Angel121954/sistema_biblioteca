<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_usuario"]) && !empty($_POST["id_usuario"])) {
    //* variables
    $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);

    $eliminar = $sql->efectuarConsulta("UPDATE usuarios SET estado_usuario = 'Inactivo'
                            WHERE id_usuario = $id");
    if ($eliminar) {
        echo "ok";
    } else {
        echo "No se pudo eliminar el usuario";
    }
}
$sql->desconectar();
