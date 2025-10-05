<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_reserva"], $_POST["accion"])) {
    //* variables
    $id_reserva = intval($_POST["id_reserva"]);
    $accion = $_POST["accion"];

    $reserva = $sql->efectuarConsulta("SELECT * FROM reservas WHERE id_reserva = $id_reserva");
    $fila = $reserva->fetch_assoc();

    if ($fila) {
        $id_libro = intval($fila["libros_id_libro"]);

        if ($accion === "Aceptar") {
            
            $sql->efectuarConsulta("UPDATE libros SET cantidad_libro = cantidad_libro - 1
                                    WHERE id_libro = $id_libro"); //* Restar del inventario de los libros disponibles
            $libro = $sql->efectuarConsulta("SELECT cantidad_libro FROM libros WHERE id_libro = $id_libro");
            $libroDatos = $libro->fetch_assoc();

            if ($libroDatos["cantidad_libro"] <= 0) {
                $sql->efectuarConsulta("UPDATE libros SET disponibilidad_libro = 'No disponible'
                                            WHERE id_libro = $id_libro");
            }
            $sql->efectuarConsulta("UPDATE reservas SET estado_reserva = 'Aceptada'
                                    WHERE id_reserva = $id_reserva
            ");
        }

        if ($accion === "Cancelar") {
            $sql->efectuarConsulta("UPDATE reservas SET estado_reserva = 'Cancelada'
                                    WHERE id_reserva = $id_reserva");
        }

        echo "ok";
    } else {
        echo "Reserva no encontrada";
    }
}

$sql->desconectar();
