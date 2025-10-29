<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$resultado = $sql->efectuarConsulta("SELECT id_prestamo FROM prestamos");

if ($resultado->num_rows > 0) {
    while ($prestamo = $resultado->fetch_assoc()) {
        //* variable
        $id_prestamo = filter_var($prestamo['id_prestamo'], FILTER_SANITIZE_NUMBER_INT);

        $sql->efectuarConsulta("DELETE FROM prestamos
                            WHERE id_prestamo = $id_prestamo 
                            AND estado_prestamo = 'Inactivo'");
    }
    echo "ok";
}

$sql->desconectar();
