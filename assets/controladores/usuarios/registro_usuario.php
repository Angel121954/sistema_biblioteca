<?php

require_once "../../modelos/MySQL.php";
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
        $nombre = filter_var($_POST["nombre_usuario"], FILTER_SANITIZE_SPECIAL_CHARS);
        $apellido = filter_var($_POST["apellido_usuario"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $email = filter_var($_POST["email_usuario"], FILTER_SANITIZE_EMAIL);
        $contrasena = password_hash($_POST["contrasena_usuario"], PASSWORD_DEFAULT);
        $tipo = $_POST["tipo_usuario"];

        $correo_repetido_result = $sql->efectuarConsulta("SELECT COUNT(*) AS cantidad_repetida
                                                FROM usuarios WHERE email_usuario = '$email'");

        $correo_repetido = $correo_repetido_result->fetch_assoc();
        if ($correo_repetido["cantidad_repetida"] > 0) {
            echo "Este correo ya existe en la base de datos. Intenta con otro";
        } else {
            $registrar = $sql->efectuarConsulta("INSERT INTO usuarios(nombre_usuario, apellido_usuario,
                                    email_usuario, contrasena_usuario, estado_usuario, fk_tipo_usuario) VALUES(
                                        '$nombre', '$apellido', '$email', '$contrasena', 'Activo', '$tipo')");
            if ($registrar) {
                echo "ok";
            } else {
                echo "Por favor llene los campos correctamente";
            }
        }
        $sql->desconectar();
        exit;
    } else {
        echo "Por favor, completar todos los campos correctamente";
        exit;
    }
}
