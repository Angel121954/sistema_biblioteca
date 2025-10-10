<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST["reservas_id_reserva"], $_POST["fecha_prestamo"], $_POST["fecha_devolucion"])
        && !empty($_POST["reservas_id_reserva"]) && !empty($_POST["fecha_prestamo"]) &&
        !empty($_POST["fecha_devolucion"])
    ) {
        //* variables
        $fk_reserva = intval($_POST["reservas_id_reserva"]);
        $fecha_prestamo = $_POST["fecha_prestamo"];
        $fecha_devolucion = $_POST["fecha_devolucion"];

        $sql->efectuarConsulta("INSERT INTO prestamos(fecha_prestamo, fecha_devolucion, reservas_id_reserva)
                        VALUES('$fecha_prestamo', '$fecha_devolucion', $fk_reserva)");
        $sql->desconectar();
        echo "ok";
    }
}
