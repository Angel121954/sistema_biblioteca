<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["fecha_prestamo"]) && !empty($_POST["fecha_prestamo"])) {
    $fecha = $_POST["fecha_prestamo"];
    $restaurar = $sql->efectuarConsulta("UPDATE prestamos SET estado_prestamo = 'Activo'
                            WHERE DATE(fecha_prestamo) = '$fecha'");
    if ($restaurar) {
        echo "ok";
    } else {
        echo "No se pudo restaurar el prestamo pedido.";
    }
}
$sql->desconectar();
