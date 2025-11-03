<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["fecha_prestamo"]) && !empty($_POST["fecha_prestamo"])) {
    $fecha = $_POST["fecha_prestamo"];
    $fecha_result = $sql->efectuarConsulta("SELECT * FROM prestamos
                                    WHERE DATE(fecha_prestamo) = '$fecha'
                                    AND estado_prestamo = 'Inactivo'");
    if ($fecha_result->num_rows > 0) {
        $restaurar = $sql->efectuarConsulta("UPDATE prestamos SET estado_prestamo = 'Activo'
                            WHERE DATE(fecha_prestamo) = '$fecha'");
        if ($restaurar) {
            echo "ok";
        } else if (!$restaurar) {
            echo "No se pudo restaurar el prestamo pedido.";
        }
    } else {
        echo "No existe un prestamo con la fecha: " . $fecha;
    }
}
$sql->desconectar();
