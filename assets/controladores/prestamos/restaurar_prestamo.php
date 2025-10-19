<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$sql->efectuarConsulta("UPDATE prestamos SET estado_prestamo = 'Activo'");
echo "ok";
$sql->desconectar();
