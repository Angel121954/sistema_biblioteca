<?php
require_once "../../modelos/MySQL.php"; //* Libreria
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["reservas_id_reserva"]) && !empty($_POST["reservas_id_reserva"])) {
        //* variable
        $id_reserva = intval($_POST["reservas_id_reserva"]);

        if ($id_reserva > 0) {
            $sql->efectuarConsulta("INSERT INTO prestamos (fecha_prestamo, 
                                    fecha_devolucion, reservas_id_reserva)
                                    VALUES (NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), $id_reserva)");

            echo "ok";
        }

        $sql->desconectar();
    }
}
