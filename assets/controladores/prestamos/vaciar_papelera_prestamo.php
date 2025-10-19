<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$resultado = $sql->efectuarConsulta("SELECT id_prestamo FROM prestamos");

if ($resultado->num_rows > 0) {
    while ($prestamo = $resultado->fetch_assoc()) {
        //* variable
        $id_prestamo = intval($prestamo['id_prestamo']);

        $sql->efectuarConsulta("
        DELETE FROM prestamos
        WHERE id_prestamo = $id_prestamo AND estado_prestamo = 'Inactivo'");
    }
    echo "ok";
}

$sql->desconectar();
