<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_libro"]) && !empty($_POST["id_libro"])) {
    //* variables
    $id_libro = filter_var($_POST["id_libro"], FILTER_SANITIZE_NUMBER_INT);

    $sql->efectuarConsulta("UPDATE libros SET disponibilidad_libro = 'Inactivo'
                            WHERE id_libro = $id_libro");
    echo "ok";
}
$sql->desconectar();
