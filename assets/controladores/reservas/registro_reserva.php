<?php
session_start();
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id_usuario"], $_POST["id_libro"], $_POST["cantidad"])) {
        //* variables
        $id_usuario = intval($_SESSION['id_usuario'] ?? 0);
        $libros = $_POST['id_libro'];
        $cantidades = $_POST['cantidad'];

        if ($id_usuario > 0 && !empty($libros) && !empty($cantidades)) {

            //* Insertar la reserva principal
            $sql->efectuarConsulta("INSERT INTO reservas (fecha_reserva, usuarios_id_usuario)
                                    VALUES (NOW(), $id_usuario)");
            $id_reserva = $sql->ultimoIdInsertado();

            //* Insertar en la tabla pivote de reservas_has_libros
            for ($i = 0; $i < count($libros); $i++) {
                $id_libro = intval($libros[$i]);
                $cantidad = intval($cantidades[$i]);

                $sql->efectuarConsulta("INSERT INTO reservas_has_libros
                    (reservas_id_reserva, libros_id_libro, cantidad_libros, estado_has_reserva)
                    VALUES ($id_reserva, $id_libro, $cantidad, 'Activa')");
            }
            echo "ok";
            exit;
        } else {
            echo json_encode(["status" => "error", "mensaje" => "Datos incompletos o usuario no autenticado"]);
            exit;
        }
    } else {
        echo json_encode(["status" => "error", "mensaje" => "Parámetros no válidos"]);
        exit;
    }
}
