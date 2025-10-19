<?php

require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST["nombre_usuario"], $_POST["contrasena_usuario"]) &&
        !empty($_POST["nombre_usuario"]) && !empty($_POST["contrasena_usuario"])
    ) {
        //* variables
        $nombre = htmlspecialchars(trim($_POST["nombre_usuario"]));
        $contrasena = trim($_POST["contrasena_usuario"]);

        $usuarios = $sql->efectuarConsulta("SELECT * FROM usuarios
                    WHERE nombre_usuario = '$nombre' AND estado_usuario = 'Activo'");

        $fila = mysqli_fetch_assoc($usuarios);
        if ($fila && password_verify($contrasena, $fila["contrasena_usuario"])) {
            session_start();
            $_SESSION["tipo_usuario"] = $fila["fk_tipo_usuario"];
            $_SESSION["id_usuario"] = $fila["id_usuario"];
            $_SESSION["nombre_usuario"] = $fila["nombre_usuario"];
            $_SESSION["apellido_usuario"] = $fila["apellido_usuario"];
            $_SESSION["email_usuario"] = $fila["email_usuario"];
            $_SESSION["acceso"] = true;

            if ($_SESSION["tipo_usuario"] === "1") {
                header("Location: ../../index.php");
            } else {
                header("Location: ../../index_libros.php");
            }
            /* switch ($fila["fk_tipo_usuario"]) {
                case "1":
                    header("Location: ../../index.php");
                    break;
                case "2":
                    header("Location: ../../.php");
                    break; */
        } else {
            header("Location: ../../login.php?error=credenciales");
        }
        exit;
    } else {
        header("Location: ../../login.php?error=credenciales");
        exit;
    }
    $sql->desconectar();
}
