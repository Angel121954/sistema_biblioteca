<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$sql->efectuarConsulta("UPDATE usuarios SET estado_usuario = 'Activo'");
echo "ok";
$sql->desconectar();

?>