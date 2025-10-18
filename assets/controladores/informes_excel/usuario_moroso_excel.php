<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$sql = new MySQL();
$sql->conectar();

$query = $sql->efectuarConsulta("
    SELECT u.nombre_usuario, u.apellido_usuario, u.email_usuario, p.fecha_devolucion
    FROM usuarios u
    INNER JOIN reservas r ON u.id_usuario = r.usuarios_id_usuario
    INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
    INNER JOIN prestamos p ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
    WHERE NOW() > p.fecha_devolucion;
");

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setTitle('Usuarios Morosos');

$sheet->fromArray(['Nombre', 'Apellido', 'Email', 'Fecha Devolución'], null, 'A1');

$fila = 2;
while ($row = $query->fetch_assoc()) {
    $sheet->fromArray([$row['nombre_usuario'], $row['apellido_usuario'], $row['email_usuario'], $row['fecha_devolucion']], null, "A$fila");
    $fila++;
}

foreach (range('A', 'D') as $col) $sheet->getColumnDimension($col)->setAutoSize(true);

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="usuarios_morosos.xlsx"');
header('Cache-Control: max-age=0');
$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;
