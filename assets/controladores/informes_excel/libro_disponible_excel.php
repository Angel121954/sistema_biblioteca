<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT id_libro, titulo_libro, autor_libro, isbn_libro, categoria_libro, cantidad_libro
    FROM libros
    WHERE disponibilidad_libro = 'Disponible';
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Libros Disponibles');

$sheet->setCellValue('A1', 'ID');
$sheet->setCellValue('B1', 'Título');
$sheet->setCellValue('C1', 'Autor');
$sheet->setCellValue('D1', 'ISBN');
$sheet->setCellValue('E1', 'Categoría');
$sheet->setCellValue('F1', 'Cantidad');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->fromArray($row, null, "A$fila");
    $fila++;
}

foreach (range('A', 'F') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="libros_disponibles.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
