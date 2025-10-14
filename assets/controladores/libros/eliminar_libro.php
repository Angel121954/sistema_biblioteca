<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_libro"]) && !empty($_POST["id_libro"])) {
    //* variables
    $id_libro = filter_var($_POST["id_libro"], FILTER_SANITIZE_NUMBER_INT);

    $sql->efectuarConsulta("DELETE FROM reservas_has_libros rl
                            WHERE rl.libros_id_libro = $id_libro");
    $sql->efectuarConsulta("DELETE r FROM reservas r INNER JOIN 
                            reservas_has_libros rl ON
                            rl.reservas_id_reserva = r.id_reserva
                            WHERE rl.libros_id_libro = $id_libro");
    $sql->efectuarConsulta("DELETE FROM libros WHERE id_libro = $id_libro");
    echo "ok";
}
$sql->desconectar();
