<?php

require_once "../../modelos/MySQL.php"; //* Librería
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_prestamo"]) && !empty($_POST["id_prestamo"])) {
    //* variables
    $id_prestamo = intval($_POST["id_prestamo"]);

    $sql->efectuarConsulta("UPDATE prestamos p SET p.estado_prestamo = 'Inactivo'
                            WHERE p.id_prestamo = $id_prestamo");
    echo "ok";
}
$sql->desconectar();
