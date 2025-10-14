<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_usuario"]) && !empty($_POST["id_usuario"])) {
    //* variables
    $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);

    $sql->efectuarConsulta("DELETE FROM reservas WHERE usuarios_id_usuario = $id");
    $sql->efectuarConsulta("DELETE FROM usuarios WHERE id_usuario = $id");
    echo "ok";
}
$sql->desconectar();