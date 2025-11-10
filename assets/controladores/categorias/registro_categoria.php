<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["nombre_categoria"])
    && !empty($_POST["nombre_categoria"])
) {
    //* variables
    $nombre = filter_var($_POST["nombre_categoria"], FILTER_SANITIZE_FULL_SPECIAL_CHARS) ?? "";
    $categoria_repetida = $sql->efectuarConsulta("SELECT id_categoria FROM categorias
                                                WHERE nombre_categoria = '$nombre'");
    if ($categoria_repetida->num_rows > 0) {
        echo "La categoría $nombre ya existe en el aplicativo. Intenta con otro";
        $sql->desconectar();
        exit;
    }
    $registrar = $sql->efectuarConsulta("INSERT INTO categorias(nombre_categoria, estado_categoria)
                                    VALUES('$nombre', 'Activa')");
    if ($registrar) {
        echo "ok";
    }
}
$sql->desconectar();
