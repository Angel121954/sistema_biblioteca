<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$categorias_inactivas = $sql->efectuarConsulta("SELECT id_categoria FROM categorias
                                                WHERE estado_categoria = 'Inactiva'");
if ($categorias_inactivas->num_rows > 0) {
    $restaurar = $sql->efectuarConsulta("UPDATE categorias SET estado_categoria = 'Activa'");
    if ($restaurar) {
        echo "ok";
    } else {
        echo "No se pudo restaurar las categorías inactivas";
    }
} else {
    echo "No hay categorías para restaurar";
}
$sql->desconectar();
