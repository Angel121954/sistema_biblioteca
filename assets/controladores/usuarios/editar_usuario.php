<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset(
            $_POST["id_usuario"],
            $_POST["nombre_usuario"],
            $_POST["apellido_usuario"],
            $_POST["email_usuario"],
            $_POST["tipo_usuario"]
        ) && !empty($_POST["id_usuario"])
        && !empty($_POST["nombre_usuario"]) && !empty($_POST["apellido_usuario"])
        && !empty($_POST["email_usuario"]) && !empty($_POST["tipo_usuario"])
    ) {
        //* variables
        $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_var($_POST["nombre_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST["apellido_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST["email_usuario"], FILTER_SANITIZE_EMAIL);
        $tipo_usuario = filter_var($_POST["tipo_usuario"], FILTER_SANITIZE_NUMBER_INT);

        $sql->efectuarConsulta("UPDATE usuarios SET nombre_usuario = '$nombre', 
                            apellido_usuario = '$apellido', email_usuario = '$email',
                            fk_tipo_usuario = $tipo_usuario WHERE id_usuario = $id");
        echo "ok";
        $sql->desconectar();
    }
}
