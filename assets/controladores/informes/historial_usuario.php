<?php
require_once "../../modelos/MySQL.php";
require_once "../../libs/fpdf/fpdf.php";

//* Conexión a la base de datos
$sql = new MySQL();
$sql->conectar();

//* Consulta de reservas
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

//* Configuración del PDF
$pdf = new FPDF();
$pdf->AddPage();
$pdf->SetMargins(10, 10, 10);

// Título del reporte
$pdf->SetFont('Arial', 'B', 16);
$pdf->Cell(0, 10, utf8_decode('Historial de Usuarios'), 0, 1, 'C');
$pdf->Ln(5);

//* Encabezado de la tabla
$pdf->SetFont('Arial', 'B', 11);
$pdf->SetFillColor(52, 73, 94);
$pdf->SetDrawColor(41, 128, 185);
$pdf->SetTextColor(255, 255, 255);

$pdf->Cell(60, 8, utf8_decode('Nombre y Apellido'), 1, 0, 'C', true);
$pdf->Cell(65, 8, utf8_decode('Correo'), 1, 0, 'C', true);
$pdf->Cell(60, 8, utf8_decode('Tipo de usuario'), 1, 1, 'C', true);

//* Cuerpo de la tabla
$pdf->SetFont('Arial', '', 10);
$pdf->SetTextColor(0, 0, 0);

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $usuario = utf8_decode($fila['nombre_usuario'] . ' ' . $fila['apellido_usuario']);
        $email = utf8_decode($fila['email_usuario']);
        $tipo_usuario = utf8_decode($fila['nombre_tipo_usuario']);

        $pdf->Cell(60, 8, $usuario, 1, 0, 'L');
        $pdf->Cell(65, 8, $email, 1, 0, 'L');
        $pdf->Cell(60, 8, $tipo_usuario, 1, 1, 'L');
    }
} else {
    $pdf->Cell(190, 10, utf8_decode('No se encontraron usuarios activos.'), 1, 1, 'C');
}

//* Pie de página
$pdf->Ln(10);
$pdf->SetFont('Arial', 'I', 9);
$pdf->Cell(0, 10, utf8_decode('Biblioteca - Informe generado el ' . date('d/m/Y')), 0, 0, 'C');

//* Salida del PDF
$pdf->Output('I', 'historial_usuarios.pdf');

//! Cierre de conexión
$sql->desconectar();
