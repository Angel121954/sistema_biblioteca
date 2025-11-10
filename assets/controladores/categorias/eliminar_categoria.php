<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["id_categoria"])
    && !empty($_POST["id_categoria"])
) {
    //* variables
    $id = intval($_POST["id_categoria"]);
    $inactivar = $sql->efectuarConsulta("UPDATE categorias SET estado_categoria =
                                        'Inactiva' WHERE id_categoria = $id");
    if ($inactivar) {
        echo "ok";
    } else {
        echo "No se pudo inactivar la categoria";
    }
}
$sql->desconectar();
