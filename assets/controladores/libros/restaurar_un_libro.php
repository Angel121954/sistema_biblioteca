<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["titulo_libro"]) && !empty($_POST["titulo_libro"])) {
    $titulo = filter_var($_POST["titulo_libro"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $libro_result = $sql->efectuarConsulta("SELECT id_libro FROM libros WHERE
                                        titulo_libro = '$titulo' AND estado_libro = 'Inactivo'");
    if ($libro_result->num_rows > 0) {
        $restaurar = $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Activo'
                            WHERE titulo_libro = '$titulo'");
        if ($restaurar) {
            echo "ok";
        } else {
            echo "No se pudo restaurar el libro pedido.";
        }
    } else {
        echo "No existe el libro $titulo en estado inactivo";
    }
}
$sql->desconectar();
