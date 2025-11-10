<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["nombre_categoria"])
    && !empty($_POST["nombre_categoria"])
) {
    $categoria = filter_var($_POST["nombre_categoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $categoria_inactiva = $sql->efectuarConsulta("SELECT id_categoria FROM categorias
                                        WHERE nombre_categoria = '$categoria'
                                        AND estado_categoria = 'Inactiva'");
    if ($categoria_inactiva->num_rows > 0) {
        $restaurar = $sql->efectuarConsulta("UPDATE categorias SET estado_categoria = 'Activa'
                                            WHERE nombre_categoria = '$categoria'");
        if ($restaurar) {
            echo "ok";
        } else {
            echo "No se pudo restaurar la categoría";
        }
    } else {
        echo "No existe la categoría $categoria en estado inactivo";
    }
}
