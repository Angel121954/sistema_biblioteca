<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$libros_inactivos = $sql->efectuarConsulta("SELECT id_libro FROM libros
                                        WHERE estado_libro = 'Inactivo'");
if ($libros_inactivos->num_rows > 0) {
    $restaurar = $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Activo'");
    if ($restaurar) {
        echo "ok";
    } else {
        echo "No se pudo restaurar los libros.";
    }
} else {
    echo "No hay libros para restaurar";
}
$sql->desconectar();
