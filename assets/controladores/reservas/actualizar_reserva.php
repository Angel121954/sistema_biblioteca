<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id_reserva"], $_POST["accion"]) && !empty($_POST["id_reserva"])) {
        $id_reserva = intval($_POST["id_reserva"]);
        $accion = $_POST["accion"];

        //* Si la acción es Aceptar
        if ($accion === "Aceptar") {
            $resultado = $sql->efectuarConsulta("SELECT rl.libros_id_libro, rl.cantidad_libros
                                                 FROM reservas_has_libros rl
                                                 WHERE rl.id_reserva_has_libro = $id_reserva");

            while ($fila = $resultado->fetch_assoc()) {
                $id_libro = intval($fila['libros_id_libro']);
                $cantidad = intval($fila['cantidad_libros']);

                $libros_result = $sql->efectuarConsulta("SELECT COUNT(*) AS cantidad_excedida
                                    FROM libros WHERE cantidad_libro < $cantidad
                                    AND id_libro = $id_libro");
                if ($libros_result->num_rows > 0) {
                    $libros = $libros_result->fetch_assoc();
                    if ($libros['cantidad_excedida'] > 0) {
                        echo "No se puede aceptar la reserva por cantidad insuficiente del ejemplar";
                        $sql->desconectar();
                        exit;
                    }
                }
                $sql->efectuarConsulta("UPDATE reservas_has_libros rl
                                            SET rl.estado_has_reserva = 'Aceptada'
                                            WHERE rl.id_reserva_has_libro = $id_reserva");
                $sql->efectuarConsulta("UPDATE libros SET cantidad_libro = cantidad_libro - $cantidad
                                            WHERE id_libro = $id_libro");
            }
            echo "ok";
        }

        //* Si la acción es Cancelar
        if ($accion === "Cancelar") {
            $cancelar = $sql->efectuarConsulta("DELETE FROM reservas_has_libros
                            WHERE id_reserva_has_libro = $id_reserva");
            if ($cancelar) {
                echo "ok";
            } else {
                echo "No se pudo cancelar la reserva correspondiente";
            }
        }

        $sql->desconectar();
    }
}
