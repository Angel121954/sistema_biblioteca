<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$resultado = $sql->efectuarConsulta("
    SELECT 
    COALESCE(COUNT(DISTINCT l.id_libro), 0) AS total_libros, 
    COALESCE(COUNT(DISTINCT rl.id_reserva_has_libro), 0) AS total_reservas,
    COALESCE(COUNT(DISTINCT p.id_prestamo), 0) AS total_prestamos,
    COALESCE(COUNT(DISTINCT u.id_usuario), 0) AS total_usuarios
    FROM usuarios u
    LEFT JOIN reservas r ON r.usuarios_id_usuario = u.id_usuario
    LEFT JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva 
    LEFT JOIN prestamos p ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
    LEFT JOIN libros l ON rl.libros_id_libro = l.id_libro
    WHERE estado_usuario != 'Inactivo'
");

$datos = $resultado->fetch_assoc();

header("Content-Type: application/json; charset=UTF-8");
echo json_encode($datos, JSON_UNESCAPED_UNICODE);

$sql->desconectar();
