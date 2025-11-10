<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $campos = ["id_libro", "titulo_libro", "autor_libro", "fk_categoria", "cantidad_libro"];
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
    $id        = intval($_POST["id_libro"]);
    $titulo    = limpiarTexto($_POST["titulo_libro"], $conexion);
    $autor     = limpiarTexto($_POST["autor_libro"], $conexion);
    $categoria = filter_var($_POST["fk_categoria"], FILTER_SANITIZE_NUMBER_INT);
    $cantidad  = filter_var($_POST["cantidad_libro"], FILTER_SANITIZE_NUMBER_INT);

    if (!is_numeric($cantidad) || $cantidad < 0) {
        exit("No se puede editar con una cantidad negativa.");
    }

    $consulta = "
        UPDATE libros SET
            titulo_libro = '$titulo',
            autor_libro = '$autor',
            fk_categoria = $categoria,
            disponibilidad_libro = 'Disponible',
            cantidad_libro = $cantidad
        WHERE id_libro = $id
    ";

    $resultado = $sql->efectuarConsulta($consulta);

    if ($resultado) {
        echo "ok";
    } else {
        echo "No se pudo editar el libro correctamente.";
    }

    $sql->desconectar();
    exit;
}
