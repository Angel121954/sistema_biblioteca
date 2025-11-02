<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $campos = ["titulo_libro", "autor_libro", "isbn_libro", "categoria_libro", "cantidad_libro"];
    foreach ($campos as $campo) {
        if (!isset($_POST[$campo]) || trim($_POST[$campo]) === '') {
            exit("Error: faltan campos requeridos.");
        }
    }

    function limpiarTexto($texto, $conexion)
    {
        $texto = trim($texto);
        $texto = strip_tags($texto);
        $texto = htmlspecialchars($texto, ENT_QUOTES, 'UTF-8');
        $texto = mysqli_real_escape_string($conexion, $texto);
        return $texto;
    }

    $conexion = $sql->getConexion();
    $titulo    = limpiarTexto($_POST["titulo_libro"], $conexion);
    $autor     = limpiarTexto($_POST["autor_libro"], $conexion);
    $isbn      = limpiarTexto($_POST["isbn_libro"], $conexion);
    $categoria = limpiarTexto($_POST["categoria_libro"], $conexion);
    $cantidad  = filter_var($_POST["cantidad_libro"], FILTER_SANITIZE_NUMBER_INT);

    if (!is_numeric($cantidad) || $cantidad <= 0) {
        exit("Cantidad inválida");
    }

    $consulta = "
        INSERT INTO libros (
            titulo_libro, autor_libro, isbn_libro, categoria_libro, cantidad_libro, disponibilidad_libro, estado_libro
        ) VALUES (
            '$titulo', '$autor', '$isbn', '$categoria', $cantidad, 'Disponible', 'Activo'
        )
    ";

    $resultado = $sql->efectuarConsulta($consulta);

    if ($resultado) {
        echo "ok";
    } else {
        echo "Error al registrar el libro.";
    }

    $sql->desconectar();
    exit;
}
