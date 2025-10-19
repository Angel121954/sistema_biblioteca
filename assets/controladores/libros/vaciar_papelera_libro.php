<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$resultado = $sql->efectuarConsulta("SELECT id_libro FROM libros");

if ($resultado->num_rows > 0) {
    while ($libro = $resultado->fetch_assoc()) {
        //* variable
        $id_libro = intval($libro['id_libro']);

        $sql->efectuarConsulta("
        DELETE p FROM prestamos p
        INNER JOIN reservas_has_libros rl
        ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
        WHERE rl.libros_id_libro = $id_libro");

        $sql->efectuarConsulta("
        DELETE FROM reservas_has_libros rl
        WHERE rl.libros_id_libro = $id_libro");

        $sql->efectuarConsulta("
        DELETE r FROM reservas r INNER JOIN reservas_has_libros rl
        ON rl.reservas_id_reserva = r.id_reserva
        WHERE rl.libros_id_libro = $id_libro");

        $sql->efectuarConsulta("
        DELETE FROM libros
        WHERE id_libro = $id_libro AND estado_libro = 'Inactivo'");
    }
    echo "ok";
}

$sql->desconectar();
