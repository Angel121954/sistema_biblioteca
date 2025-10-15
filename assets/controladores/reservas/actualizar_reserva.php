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

            //* Cambiar el estado de la reserva a Aceptada
            $sql->efectuarConsulta("UPDATE reservas_has_libros rl
                SET rl.estado_has_reserva = 'Aceptada' 
                WHERE rl.id_reserva_has_libro = $id_reserva");

            $resultado = $sql->efectuarConsulta("SELECT rl.libros_id_libro, rl.cantidad_libros
                                                 FROM reservas_has_libros rl
                                                 WHERE rl.id_reserva_has_libro = $id_reserva");

            while ($fila = $resultado->fetch_assoc()) {
                $id_libro = intval($fila['libros_id_libro']);
                $cantidad = intval($fila['cantidad_libros']);

                $sql->efectuarConsulta("UPDATE libros 
                                        SET cantidad_libro = cantidad_libro - $cantidad 
                                        WHERE id_libro = $id_libro");
            }

            echo "ok";
        }

        //* Si la acción es Cancelar
        if ($accion === "Cancelar") {
            $sql->efectuarConsulta("DELETE FROM reservas_has_libros
                            WHERE id_reserva_has_libro = $id_reserva");
            echo "ok";
        }

        $sql->desconectar();
    }
}
