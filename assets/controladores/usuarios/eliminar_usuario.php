<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_usuario"]) && !empty($_POST["id_usuario"])) {
    //* variables
    $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);

    $usuario_result = $sql->efectuarConsulta("SELECT nombre_usuario FROM usuarios
                                    WHERE id_usuario = $id");

    if ($usuario_result->num_rows > 0) {
        $usuario = $usuario_result->fetch_assoc();
        $nombre_usuario = $usuario['nombre_usuario'];
        $usuario_reserva = $sql->efectuarConsulta("SELECT id_usuario FROM usuarios u
                                INNER JOIN reservas r ON r.usuarios_id_usuario = u.id_usuario
                                INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.fk_reserva
                                WHERE u.id_usuario = $id");

        if ($usuario_reserva->num_rows > 0) {
            echo "No se puede inactivar el usuario $nombre_usuario ya que está asociado a un prestamo";
        } else {
            $inactivar = $sql->efectuarConsulta("UPDATE usuarios SET estado_usuario = 'Inactivo'
                            WHERE id_usuario = $id");
            if ($inactivar) {
                echo "ok";
            } else {
                echo "No se pudo inactivar el usuario";
            }
        }
    } else {
        echo "No existe el usuario";
    }
}
$sql->desconectar();
