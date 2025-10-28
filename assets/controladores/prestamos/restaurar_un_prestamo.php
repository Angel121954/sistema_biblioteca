<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["fecha_prestamo"]) && !empty($_POST["fecha_prestamo"])) {
    $fecha = $_POST["fecha_prestamo"];
    $sql->efectuarConsulta("UPDATE prestamos SET estado_prestamo = 'Activo'
                            WHERE DATE(fecha_prestamo) = '$fecha'");
    echo "ok";
}
$sql->desconectar();
