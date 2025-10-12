<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/fpdf/fpdf.php";

$sql = new MySQL();
$sql->conectar();

$libros_result = $sql->efectuarConsulta("SELECT p.id_prestamo, l.titulo_libro, l.autor_libro,
                                l.isbn_libro, l.categoria_libro, l.disponibilidad_libro,
                                rl.cantidad_libros FROM prestamos p INNER JOIN reservas r ON
                                p.reservas_id_reserva = r.id_reserva INNER JOIN 
                                reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
                                INNER JOIN libros l ON
                                rl.libros_id_libro = l.id_libro");

$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);

$pdf->Cell(0, 10, utf8_decode('Informe de Libros Prestados'), 0, 1, 'C');
$pdf->Ln(5);

$pdf->SetFont('Arial', 'B', 10);
$pdf->SetFillColor(41, 128, 185);
$pdf->SetTextColor(255, 255, 255);
$pdf->Cell(10, 8, 'ID', 1, 0, 'C', true);
$pdf->Cell(50, 8, 'Titulo', 1, 0, 'C', true);
$pdf->Cell(52, 8, 'Autor', 1, 0, 'C', true);
$pdf->Cell(28, 8, 'ISBN', 1, 0, 'C', true);
$pdf->Cell(30, 8, 'Categoria', 1, 0, 'C', true);
$pdf->Cell(20, 8, 'Cantidad', 1, 1, 'C', true);

// Datos
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

if ($libros_result->num_rows > 0) {
    while ($libro = $libros_result->fetch_assoc()) {
        $pdf->Cell(10, 8, $libro['id_prestamo'], 1, 0, 'C');
        $pdf->Cell(50, 8, utf8_decode($libro['titulo_libro']), 1, 0, 'L');
        $pdf->Cell(52, 8, utf8_decode($libro['autor_libro']), 1, 0, 'L');
        $pdf->Cell(28, 8, $libro['isbn_libro'], 1, 0, 'C');
        $pdf->Cell(30, 8, utf8_decode($libro['categoria_libro']), 1, 0, 'C');
        $pdf->Cell(20, 8, $libro['cantidad_libros'], 1, 1, 'C');
    }
} else {
    $pdf->Cell(0, 10, utf8_decode('No hay libros prestados actualmente.'), 1, 1, 'C');
}

$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 8);
$pdf->Cell(0, 10, utf8_decode('Biblioteca - Informe generado el ' . date('d/m/Y')), 0, 0, 'C');

$pdf->Output('I', 'informe_libros_prestados.pdf');

$sql->desconectar();
