<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset(
        $_POST["id_categoria"],
        $_POST["nombre_categoria"]
    )
    && !empty($_POST["id_categoria"])
    && !empty($_POST["nombre_categoria"])
) {
    //* variables
    $id = intval($_POST["id_categoria"]);
    $categoria = filter_var($_POST["nombre_categoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $categoria_repetida = $sql->efectuarConsulta("SELECT id_categoria FROM categorias
                                    WHERE nombre_categoria = '$categoria' AND
                                    id_categoria != $id");
    if ($categoria_repetida->num_rows > 0) {
        echo "Está categoría ya existe en el sistema. Por favor ingresa otra";
        $sql->desconectar();
        exit;
    }
    $editar = $sql->efectuarConsulta("UPDATE categorias SET nombre_categoria = '$categoria'
                                        WHERE id_categoria = $id");
    if ($editar) {
        echo "ok";
    }
}
$sql->desconectar();
