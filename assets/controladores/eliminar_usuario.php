<?php

require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_usuario"]) && !empty($_POST["id_usuario"])) {
    //* variables
    $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);

    $sql->efectuarConsulta("DELETE FROM usuarios WHERE id_usuario = $id");
    $id_maximo_result = $sql->efectuarConsulta(
        "SELECT MAX(id_usuario) AS maximo_id FROM usuarios"
    );
    $fila = mysqli_fetch_assoc($id_maximo_result);

    $id_maximo = !empty($fila["maximo_id"]) ? intval($fila["maximo_id"]) : 0;

    $siguiente = ($id_maximo > 0) ? $id_maximo + 1 : 1;
    $sql->efectuarConsulta("ALTER TABLE usuarios AUTO_INCREMENT = $siguiente");

    echo "ok";
    $sql->desconectar();
}
