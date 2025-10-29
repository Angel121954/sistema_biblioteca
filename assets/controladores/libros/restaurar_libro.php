<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$restaurar = $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Activo'");
if ($restaurar) {
    echo "ok";
} else {
    echo "No se pudo restaurar los libros.";
}
$sql->desconectar();
