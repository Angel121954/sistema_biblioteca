<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT p.id_prestamo, l.titulo_libro, u.nombre_usuario, p.fecha_prestamo, p.fecha_devolucion
    FROM prestamos p
    INNER JOIN reservas_has_libros rl ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
    INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
    INNER JOIN reservas r ON rl.reservas_id_reserva = r.id_reserva
    INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario;
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Libros Prestados');

$sheet->fromArray(['ID Préstamo', 'Título', 'Usuario', 'Fecha Préstamo', 'Fecha Devolución'], null, 'A1');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->setCellValue("A$fila", $row['id_prestamo']);
    $sheet->setCellValue("B$fila", $row['titulo_libro']);
    $sheet->setCellValue("C$fila", $row['nombre_usuario']);
    $sheet->setCellValue("D$fila", $row['fecha_prestamo']);
    $sheet->setCellValue("E$fila", $row['fecha_devolucion']);
    $fila++;
}

foreach (range('A', 'E') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="libros_prestados.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
