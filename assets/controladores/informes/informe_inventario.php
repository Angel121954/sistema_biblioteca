<?php

//* importar las librerías
require_once "../../libs/fpdf/fpdf.php";
require_once "../../modelos/MySQL.php";

$sql = new MySQL();
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, 'Informe de Inventario / libros', 0, 1, 'C');
