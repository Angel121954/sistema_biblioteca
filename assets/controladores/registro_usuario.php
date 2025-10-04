<?php

require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset(
            $_POST["nombre_usuario"],
            $_POST["apellido_usuario"],
            $_POST["email_usuario"],
            $_POST["contrasena_usuario"],
            $_POST["tipo_usuario"]
        )
        && !empty($_POST["nombre_usuario"]) && !empty($_POST["apellido_usuario"]) &&
        !empty($_POST["email_usuario"]) && !empty($_POST["contrasena_usuario"]) &&
        !empty($_POST["tipo_usuario"])
    ) {
        //* variables
        $nombre = filter_var($_POST["nombre_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $apellido = filter_var($_POST["apellido_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST["email_usuario"], FILTER_SANITIZE_EMAIL);
        $contrasena = password_hash($_POST["contrasena_usuario"], PASSWORD_DEFAULT);
        $tipo = $_POST["tipo_usuario"];

        $sql->efectuarConsulta("INSERT INTO usuarios(nombre_usuario, apellido_usuario,
                                    email_usuario, contrasena_usuario, fk_tipo_usuario) VALUES(
                                        '$nombre', '$apellido', '$email', '$contrasena', '$tipo')");
        $sql->desconectar();

        echo "ok"; //? necesario para recibir la respuesta desde el SweetAlert del JS
        exit;
    }
}
