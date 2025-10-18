<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT p.id_prestamo, u.nombre_usuario, l.titulo_libro, p.fecha_prestamo, p.fecha_devolucion
    FROM prestamos p
    INNER JOIN reservas_has_libros rl ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
    INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
    INNER JOIN reservas r ON rl.reservas_id_reserva = r.id_reserva
    INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
    ORDER BY p.fecha_prestamo DESC;
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Historial Préstamos');

$sheet->fromArray(['ID Préstamo', 'Usuario', 'Título Libro', 'Fecha Préstamo', 'Fecha Devolución'], null, 'A1');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->fromArray($row, null, "A$fila");
    $fila++;
}

foreach (range('A', 'E') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="historial_prestamo.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
