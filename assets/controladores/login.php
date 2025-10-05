<?php

require_once "../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (
        isset($_POST["email_usuario"], $_POST["contrasena_usuario"]) &&
        !empty($_POST["email_usuario"]) && !empty($_POST["contrasena_usuario"])
    ) {
        //* variables
        $email = htmlspecialchars(trim($_POST["email_usuario"]));
        $contrasena = trim($_POST["contrasena_usuario"]);

        $usuarios = $sql->efectuarConsulta("SELECT * FROM usuarios
                    WHERE email_usuario = '$email'");

        echo "SELECT * FROM usuarios
                    WHERE email_usuario = '$email'";
        $fila = mysqli_fetch_assoc($usuarios);
        if ($fila && password_verify($contrasena, $fila["contrasena_usuario"])) {
            session_start();
            $_SESSION["tipo_usuario"] = $fila["fk_tipo_usuario"];
            $_SESSION["id_usuario"] = $fila["id_usuario"];
            $_SESSION["nombre_usuario"] = $fila["nombre_usuario"];
            $_SESSION["apellido_usuario"] = $fila["apellido_usuario"];
            $_SESSION["email_usuario"] = $fila["email_usuario"];
            $_SESSION["acceso"] = true;

            header("Location: ../../index.php");
            /* switch ($fila["fk_tipo_usuario"]) {
                case "1":
                    header("Location: ../../index.php");
                    break;
                case "2":
                    header("Location: ../../.php");
                    break; */
        } else {
            header("Location: ../../login.html?error=credenciales");
        }
        exit;
    } else {
        header("Location: ../../login.html?error=credenciales");
        exit;
    }
    $sql->desconectar();
}
