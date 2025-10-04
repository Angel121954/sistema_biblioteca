<?php

require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset(
            $_POST["id_usuario"],
            $_POST["nombre_usuario"],
            $_POST["apellido_usuario"],
            $_POST["email_usuario"],
            $_POST["contrasena_usuario"]
        ) && !empty($_POST["id_usuario"])
        && !empty($_POST["nombre_usuario"]) && !empty($_POST["apellido_usuario"])
        && !empty($_POST["email_usuario"]) && !empty($_POST["contrasena_usuario"])
    ) {
        //* variables
        $id = filter_var($_POST["id_usuario"], FILTER_SANITIZE_NUMBER_INT);
        $nombre = filter_var($_POST["nombre_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST["apellido_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST["email_usuario"], FILTER_SANITIZE_EMAIL);
        $contrasena = password_hash($_POST["contrasena_usuario"], PASSWORD_DEFAULT);

        $sql->efectuarConsulta("UPDATE usuarios SET nombre_usuario = '$nombre', 
                            apellido_usuario = '$apellido', email_usuario = '$email',
                            contrasena_usuario = '$contrasena' WHERE id_usuario = $id");
        echo "ok";
        $sql->desconectar();
    }
}
