<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT id_libro, titulo_libro, autor_libro, isbn_libro, categoria_libro
    FROM libros
    WHERE disponibilidad_libro = 'Sin ejemplares';
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Sin Ejemplares');

$sheet->fromArray(['ID', 'Título', 'Autor', 'ISBN', 'Categoría'], null, 'A1');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->fromArray($row, null, "A$fila");
    $fila++;
}

foreach (range('A', 'E') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="libros_sin_ejemplares.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
