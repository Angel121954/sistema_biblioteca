<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$restaurar = $sql->efectuarConsulta("UPDATE usuarios SET estado_usuario = 'Activo'");
if ($restaurar) {
    echo "ok";
} else {
    echo "No se pudo restaurar los usuarios";
}
$sql->desconectar();
