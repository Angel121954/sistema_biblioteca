<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_libro"]) && !empty($_POST["id_libro"])) {
    //* variables
    $id_libro = filter_var($_POST["id_libro"], FILTER_SANITIZE_NUMBER_INT);

    $eliminar = $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Inactivo'
                            WHERE id_libro = $id_libro");
    if ($eliminar) {
        echo "ok";
    } else {
        echo "No se pudo eliminar el libro correctamente";
    }
}
$sql->desconectar();
