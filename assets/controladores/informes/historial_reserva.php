<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/fpdf/fpdf.php";

//* Conexión a la base de datos
$sql = new MySQL();
$sql->conectar();

//* Consulta de reservas
$consulta = "
    SELECT 
        u.nombre_usuario, 
        u.apellido_usuario,
        l.titulo_libro, 
        l.autor_libro, 
        r.fecha_reserva
    FROM reservas r
    INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
    INNER JOIN reservas_has_libros rl 
    ON rl.reservas_id_reserva = r.id_reserva
    INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
    WHERE rl.estado_has_reserva = 'Finalizada'
    ORDER BY r.fecha_reserva DESC
";

$resultado = $sql->efectuarConsulta($consulta);

//* Configuración del PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);

// Título del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Historial de Reservas'), 0, 1, 'C');
$pdf->Ln(5);

//* Encabezado de la tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(52, 73, 94);
$pdf->SetDrawColor(41, 128, 185);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(50, 8, utf8_decode('Nombre y Apellido'), 1, 0, 'C', true);
$pdf->Cell(50, 8, utf8_decode('Título del libro'), 1, 0, 'C', true);
$pdf->Cell(55, 8, utf8_decode('Autor'), 1, 0, 'C', true);
$pdf->Cell(35, 8, utf8_decode('Fecha reserva'), 1, 1, 'C', true);

//* Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuario = utf8_decode($fila['nombre_usuario'] . ' ' . $fila['apellido_usuario']);
        $libro = utf8_decode($fila['titulo_libro']);
        $autor = utf8_decode($fila['autor_libro']);
        $fecha = utf8_decode(date('d/m/Y', strtotime($fila['fecha_reserva'])));

        $pdf->Cell(50, 8, $usuario, 1, 0, 'L');
        $pdf->Cell(50, 8, $libro, 1, 0, 'L');
        $pdf->Cell(55, 8, $autor, 1, 0, 'L');
        $pdf->Cell(35, 8, $fecha, 1, 1, 'C');
    }
} else {
    $pdf->Cell(190, 10, utf8_decode('No se encontraron reservas realizadas.'), 1, 1, 'C');
}

//* Pie de página
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, utf8_decode('Biblioteca - Informe generado el ' . date('d/m/Y')), 0, 0, 'C');

//* Salida del PDF
$pdf->Output('I', 'informe_reservas.pdf');

//! Cierre de conexión
$sql->desconectar();
