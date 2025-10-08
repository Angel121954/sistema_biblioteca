<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/fpdf/fpdf.php";

$sql = new MySQL();
$sql->conectar();

$resultado = $sql->efectuarConsulta("SELECT u.nombre_usuario, u.apellido_usuario,
        l.titulo_libro, l.autor_libro, p.fecha_prestamo, p.fecha_devolucion
        FROM prestamos p INNER JOIN reservas r ON p.reservas_id_reserva = r.id_reserva
        INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
        INNER JOIN libros l ON r.libros_id_libro = l.id_libro
        ORDER BY p.fecha_prestamo DESC");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 14);
$pdf->Cell(0, 10, utf8_decode('Historial de prestamos'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(41, 128, 185);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(37, 8, 'Nombre y Apellido', 1, 0, 'C', true);
$pdf->Cell(43, 8, 'Titulo del libro', 1, 0, 'C', true);
$pdf->Cell(45, 8, 'Autor', 1, 0, 'C', true);
$pdf->Cell(32, 8, 'Prestamo', 1, 0, 'C', true);
$pdf->Cell(32, 8, 'Devolucion', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 9);
$pdf->SetTextColor(0, 0, 0);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuario = utf8_decode($fila['nombre_usuario'] . ' ' . $fila['apellido_usuario']);
        $libro = utf8_decode($fila['titulo_libro']);
        $autor = utf8_decode($fila['autor_libro']);
        $prestamo = $fila['fecha_prestamo'];
        $devolucion = $fila['fecha_devolucion'];

        $pdf->Cell(37, 8, $usuario, 1, 0, 'L');
        $pdf->Cell(43, 8, $libro, 1, 0, 'L');
        $pdf->Cell(45, 8, $autor, 1, 0, 'L');
        $pdf->Cell(32, 8, $prestamo, 1, 0, 'C');
        $pdf->Cell(32, 8, $devolucion, 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, utf8_decode("No se encontraron préstamos registrados."), 1, 1, "C");
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode('Biblioteca - Informe generado el ' . date('d/m/Y')), 0, 0, 'C');

$pdf->Output("I", "informe_prestamos.pdf");

$sql->desconectar();
