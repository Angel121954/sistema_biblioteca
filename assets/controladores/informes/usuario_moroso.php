<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/fpdf/fpdf.php";

$sql = new MySQL();
$sql->conectar();

$libros_result = $sql->efectuarConsulta("SELECT u.id_usuario, u.nombre_usuario, u.apellido_usuario,
                                        u.email_usuario, p.fecha_devolucion FROM usuarios u
                                        INNER JOIN reservas r ON r.usuarios_id_usuario = u.id_usuario
                                        INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
                                        INNER JOIN prestamos p ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
                                        WHERE NOW() > p.fecha_devolucion");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, utf8_decode('Informe de Usuarios Morosos'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(52, 73, 94);
$pdf->SetDrawColor(41, 128, 185);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(10, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(65, 8, 'Nombres y apellidos', 1, 0, 'C', true);
$pdf->Cell(60, 8, 'Email', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Fecha de devolucion', 1, 1, 'C', true);

$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

if ($libros_result->num_rows > 0) {
    while ($libro = $libros_result->fetch_assoc()) {
        $pdf->Cell(10, 8, utf8_decode($libro['id_usuario']), 1, 0, 'C');
        $pdf->Cell(65, 8, utf8_decode($libro['nombre_usuario'] . ' ' . $libro['apellido_usuario']), 1, 0, 'L');
        $pdf->Cell(60, 8, utf8_decode($libro['email_usuario']), 1, 0, 'L');
        $pdf->Cell(50, 8, date('d/m/Y', strtotime($libro['fecha_devolucion'])), 1, 1, 'C');
    }
} else {
    $pdf->Cell(185, 10, utf8_decode('No hay usuarios morosos actualmente.'), 1, 1, 'C');
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode('Biblioteca - Informe generado el ' . date('d/m/Y')), 0, 0, 'C');

$pdf->Output('I', 'informe_usuarios_morosos.pdf');

$sql->desconectar();
