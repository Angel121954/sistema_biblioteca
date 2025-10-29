<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$restaurar = $sql->efectuarConsulta("UPDATE prestamos SET estado_prestamo = 'Activo'");
if ($restaurar) {
    echo "ok";
} else {
    echo "No se pudo restaurar el prestamo.";
}
$sql->desconectar();
exit;
