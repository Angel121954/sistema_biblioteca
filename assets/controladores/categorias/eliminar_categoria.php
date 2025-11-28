<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (
    isset($_POST["id_categoria"], $_POST["nombre_categoria"])
    && !empty($_POST["id_categoria"] && !empty($_POST["nombre_categoria"]))
) {
    //* variables
    $id = intval($_POST["id_categoria"]);
    $categoria = filter_var($_POST["nombre_categoria"], FILTER_SANITIZE_SPECIAL_CHARS);
    $categoria_usuario = $sql->efectuarConsulta("SELECT id_categoria FROM categorias c
                                                INNER JOIN libros l ON l.fk_categoria = c.id_categoria
                                                WHERE id_categoria = $id");
    if ($categoria_usuario->num_rows > 0) {
        echo "No se puede inactivar la categoria $categoria ya que está asociado a un libro";
        $sql->desconectar();
        exit;
    }
    $inactivar = $sql->efectuarConsulta("UPDATE categorias SET estado_categoria =
                                        'Inactiva' WHERE id_categoria = $id");
    if ($inactivar) {
        echo "ok";
    } else {
        echo "No se pudo inactivar la categoria";
    }
}
$sql->desconectar();
