<?php

require_once "../../modelos/MySQL.php";

$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_reserva"]) && !empty($_POST["id_reserva"])) {
    //* variables
    $id_reserva = intval($_POST["id_reserva"]);

    $sql->efectuarConsulta("DELETE rl FROM reservas_has_libros rl
                            INNER JOIN reservas r ON
                            rl.reservas_id_reserva = r.id_reserva
                            
                            WHERE id_reserva_has_libro = $id_reserva");
    $sql->efectuarConsulta("DELETE FROM reservas WHERE id_reserva = $id_reserva");
    echo "ok";
}
$sql->desconectar();
