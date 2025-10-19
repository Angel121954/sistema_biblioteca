<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$resultado = $sql->efectuarConsulta("SELECT id_usuario FROM usuarios");

if ($resultado->num_rows > 0) {
    while ($usuario = $resultado->fetch_assoc()) {
        $id_usuario = intval($usuario['id_usuario']);

        //* Eliminamos primero de la tabla prestamos que contiene la foreign key
        //* de la tabla pivote
        $sql->efectuarConsulta("DELETE p FROM prestamos p INNER JOIN reservas_has_libros rl
                                ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
                                INNER JOIN reservas r ON rl.reservas_id_reserva = r.id_reserva
                                WHERE r.usuarios_id_usuario = $id_usuario");

        $sql->efectuarConsulta("DELETE rl FROM reservas_has_libros rl INNER JOIN
                                reservas r ON rl.reservas_id_reserva = r.id_reserva
                                WHERE r.usuarios_id_usuario = $id_usuario");

        $sql->efectuarConsulta("DELETE FROM reservas r WHERE r.usuarios_id_usuario = $id_usuario");

        $sql->efectuarConsulta("DELETE FROM usuarios WHERE id_usuario = $id_usuario
                                    AND estado_usuario = 'Inactivo'");
    }
    echo "ok";
}

$sql->desconectar();
