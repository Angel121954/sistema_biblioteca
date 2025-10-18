<?php
session_start();
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id_usuario"], $_POST["id_libro"], $_POST["cantidad"])) {

        //* Variables
        $id_usuario = intval($_SESSION['id_usuario'] ?? 0);
        $libros = $_POST['id_libro'];
        $cantidades = $_POST['cantidad'];

        if ($id_usuario > 0 && !empty($libros) && !empty($cantidades)) {

            //* Insertar la reserva principal
            $sql->efectuarConsulta("
                INSERT INTO reservas (fecha_reserva, usuarios_id_usuario)
                VALUES (NOW(), $id_usuario)
            ");
            $id_reserva = $sql->ultimoIdInsertado();

            //* Insertar en la tabla pivote reservas_has_libros
            for ($i = 0; $i < count($libros); $i++) {
                $id_libro = intval($libros[$i]);
                $cantidad = intval($cantidades[$i]);

                $cantidad_libro_result = $sql->efectuarConsulta("
                    SELECT cantidad_libro, titulo_libro FROM libros WHERE id_libro = $id_libro
                ");

                if ($cantidad_libro_result && $cantidad_libro_result->num_rows > 0) {
                    $fila = $cantidad_libro_result->fetch_assoc();
                    $cantidad_libro = intval($fila['cantidad_libro']);
                    $titulo_libro = filter_var($fila['titulo_libro'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

                    if ($cantidad_libro > 0 && $cantidad_libro >= $cantidad) {
                        $sql->efectuarConsulta("
                            INSERT INTO reservas_has_libros
                            (reservas_id_reserva, libros_id_libro, cantidad_libros, estado_has_reserva)
                            VALUES ($id_reserva, $id_libro, $cantidad, 'Activa')
                        ");

                        $nueva_cantidad = $cantidad_libro - $cantidad;

                        $sql->efectuarConsulta("
                            UPDATE libros 
                            SET cantidad_libro = $nueva_cantidad,
                            disponibilidad_libro = IF($nueva_cantidad = 0, 'Sin ejemplares', 'Disponible')
                            WHERE id_libro = $id_libro
                        ");
                    } else {
                        echo "No hay suficientes ejemplares del libro $titulo_libro";
                        exit;
                    }
                } else {
                    echo "El libro con ID $id_libro no existe.";
                    exit;
                }
            }

            echo "ok";
        }
    }
}
