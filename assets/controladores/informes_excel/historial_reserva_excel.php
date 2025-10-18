<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT r.id_reserva, u.nombre_usuario, l.titulo_libro, r.fecha_reserva, r.estado_reserva
    FROM reservas r
    INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
    INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
    INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
    WHERE rl.estado_has_reserva = 'Finalizada'
    ORDER BY r.fecha_reserva DESC;
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Historial Reservas');

$sheet->fromArray(['ID Reserva', 'Usuario', 'Título Libro', 'Fecha Reserva', 'Estado'], null, 'A1');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->fromArray($row, null, "A$fila");
    $fila++;
}

foreach (range('A', 'E') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="historial_reserva.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
