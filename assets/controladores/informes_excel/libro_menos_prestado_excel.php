<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT l.titulo_libro, COUNT(p.id_prestamo) AS total_prestamos
    FROM prestamos p
    INNER JOIN reservas_has_libros rl ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
    INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
    GROUP BY l.id_libro
    ORDER BY total_prestamos ASC;
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Menos Prestados');

$sheet->fromArray(['Título del Libro', 'Veces Prestado'], null, 'A1');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->fromArray([$row['titulo_libro'], $row['total_prestamos']], null, "A$fila");
    $fila++;
}

foreach (range('A', 'B') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="libros_menos_prestados.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
