<?php
session_start();
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset(
        $_SESSION["id_usuario"],
        $_POST["id_libro"]
    ) && !empty($_SESSION["id_usuario"])
    && !empty($_POST["id_libro"])
) {
    //* variables
    $id_usuario = filter_var($_SESSION["id_usuario"], FILTER_SANITIZE_NUMBER_INT);
    $id_libro = filter_var($_POST["id_libro"], FILTER_SANITIZE_NUMBER_INT);

    $sql->efectuarConsulta("INSERT INTO reservas(fecha_reserva,
                        estado_reserva, usuarios_id_usuario, libros_id_libro)
                        VALUES(NOW(), 'Pendiente', $id_usuario, $id_libro)");

    echo "ok";
}

$sql->desconectar();
