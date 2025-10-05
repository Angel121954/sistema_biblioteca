<?php
session_start();
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset(
        $_SESSION["id_usuario"],
        $_POST["fecha"],
        $_POST["id_libro"]
    ) && !empty($_POST["fecha"])
    && !empty($_POST["id_libro"])
) {
    //* variables
    $id_usuario = intval($_SESSION["id_usuario"]);
    $fecha = $_POST["fecha"];
    $id_libro = filter_var($_POST["id_libro"], FILTER_SANITIZE_NUMBER_INT);

    $sql->efectuarConsulta("INSERT INTO reservas(fecha_reserva,
                        estado_reserva, usuarios_id_usuario, libros_id_libro)
                        VALUES('$fecha', 'Pendiente', $id_usuario, $id_libro)");

    echo "ok";
}

$sql->desconectar();
