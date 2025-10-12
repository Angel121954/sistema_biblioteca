<?php
require_once "../../phpSpreadsheet/vendor/autoload.php";

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Prueba de PhpSpreadsheet');

$writer = new Xlsx($spreadsheet);
$writer->save('test.xlsx');

echo "✅ Archivo 'test.xlsx' generado correctamente.";
