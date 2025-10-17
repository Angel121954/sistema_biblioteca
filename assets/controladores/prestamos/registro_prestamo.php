<?php
require_once "../../modelos/MySQL.php"; //* Libreria
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id_reserva_has_libro"]) && !empty($_POST["id_reserva_has_libro"])) {
        //* variable
        $id_reserva_has_libro = intval($_POST["id_reserva_has_libro"]);

        if ($id_reserva_has_libro > 0) {
            $sql->efectuarConsulta("INSERT INTO prestamos (fecha_prestamo, 
                                    fecha_devolucion, fk_reserva_has_libro, estado_prestamo)
                                    VALUES (NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), $id_reserva_has_libro,
                                    'Activo')");

            $sql->efectuarConsulta("UPDATE reservas_has_libros SET estado_has_reserva = 'Finalizada'
                                    WHERE id_reserva_has_libro = $id_reserva_has_libro");

            echo "ok";
        }

        $sql->desconectar();
    }
}
