<?php

require_once "../../modelos/MySQL.php";

$sql = new MySQL();
$sql->conectar();

if (isset($_GET["id_reserva"]) && !empty($_GET["id_reserva"])) {
    //* variables
    $id_reserva = intval($_GET["id_reserva"]);

    $sql->efectuarConsulta("DELETE FROM reservas WHERE id_reserva = $id_reserva");
}
$sql->desconectar();
