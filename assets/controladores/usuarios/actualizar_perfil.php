<?php
session_start();
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

$id_usuario = intval($_SESSION["id_usuario"]);
$nombre = filter_var($_POST["nombre"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$apellido = filter_var($_POST["apellido"], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);
$contrasena = trim($_POST["contrasena"]);

if ($contrasena != "") {
    $hash = password_hash($contrasena, PASSWORD_BCRYPT);
    $sql->efectuarConsulta("UPDATE usuarios SET 
        nombre_usuario = '$nombre',
        apellido_usuario = '$apellido',
        email_usuario = '$email',
        contrasena_usuario = '$hash'
        WHERE id_usuario = $id_usuario");
} else {
    $sql->efectuarConsulta("UPDATE usuarios SET 
        nombre_usuario = '$nombre',
        apellido_usuario = '$apellido',
        email_usuario = '$email'
        WHERE id_usuario = '$id_usuario'");
}

$_SESSION["nombre_usuario"] = $nombre;

echo "ok";
$sql->desconectar();
