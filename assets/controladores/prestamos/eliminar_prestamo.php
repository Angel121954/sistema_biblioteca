<?php

require_once "../../modelos/MySQL.php"; //* Librería
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_prestamo"]) && !empty($_POST["id_prestamo"])) {
    //* variables
    $id_prestamo = filter_var($_POST["id_prestamo"], FILTER_SANITIZE_NUMBER_INT) ?? 0;

    $eliminar = $sql->efectuarConsulta("UPDATE prestamos p SET p.estado_prestamo = 'Inactivo'
                            WHERE p.id_prestamo = $id_prestamo");
    if ($eliminar) {
        echo "ok";
    } else {
        echo "No se pudo eliminar correctamente el prestamo.";
    }
}
$sql->desconectar();
