<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;

//* Conexión a la base de datos
$sql = new MySQL();
$sql->conectar();

//* Consulta de usuarios
$consulta = "
    SELECT 
        u.nombre_usuario, 
        u.apellido_usuario,
        u.email_usuario, 
        tu.nombre_tipo_usuario 
    FROM usuarios u
    INNER JOIN tipos_usuarios tu ON u.fk_tipo_usuario = tu.id_tipo_usuario
    WHERE u.estado_usuario = 'Activo'
";

$resultado = $sql->efectuarConsulta($consulta);

//* Crear el archivo Excel
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

//* Título del reporte
$sheet->setCellValue('A1', 'Historial de Usuarios');
$sheet->mergeCells('A1:D1');
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//* Encabezados de la tabla
$sheet->setCellValue('A3', 'Nombre y Apellido');
$sheet->setCellValue('B3', 'Correo');
$sheet->setCellValue('C3', 'Tipo de Usuario');

//* Estilo de encabezados
$headerStyle = [
    'font' => [
        'bold' => true,
        'color' => ['rgb' => 'FFFFFF'],
        'size' => 11
    ],
    'fill' => [
        'fillType' => Fill::FILL_SOLID,
        'startColor' => ['rgb' => '34495E']
    ],
    'alignment' => [
        'horizontal' => Alignment::HORIZONTAL_CENTER,
        'vertical' => Alignment::VERTICAL_CENTER
    ],
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
            'color' => ['rgb' => '2980B9']
        ]
    ]
];

$sheet->getStyle('A3:C3')->applyFromArray($headerStyle);

//* Ajustar ancho de columnas
$sheet->getColumnDimension('A')->setWidth(30);
$sheet->getColumnDimension('B')->setWidth(35);
$sheet->getColumnDimension('C')->setWidth(25);

//* Llenar datos
$fila = 4;
if ($resultado && $resultado->num_rows > 0) {
    while ($row = $resultado->fetch_assoc()) {
        $sheet->setCellValue('A' . $fila, $row['nombre_usuario'] . ' ' . $row['apellido_usuario']);
        $sheet->setCellValue('B' . $fila, $row['email_usuario']);
        $sheet->setCellValue('C' . $fila, $row['nombre_tipo_usuario']);

        //* Estilo de celdas de datos
        $sheet->getStyle('A' . $fila . ':C' . $fila)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => 'CCCCCC']
                ]
            ]
        ]);

        $fila++;
    }
} else {
    $sheet->setCellValue('A4', 'No se encontraron usuarios activos.');
    $sheet->mergeCells('A4:C4');
    $sheet->getStyle('A4')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
}

//* Pie de página
$ultimaFila = $fila + 1;
$sheet->setCellValue('A' . $ultimaFila, 'Biblioteca - Informe generado el ' . date('d/m/Y'));
$sheet->mergeCells('A' . $ultimaFila . ':C' . $ultimaFila);
$sheet->getStyle('A' . $ultimaFila)->getFont()->setItalic(true)->setSize(9);
$sheet->getStyle('A' . $ultimaFila)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

//* Generar y descargar el archivo
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="historial_usuarios.xlsx"');
header('Cache-Control: max-age=0');

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');

//! Cierre de conexión
$sql->desconectar();
