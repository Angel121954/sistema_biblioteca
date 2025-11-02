<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["accion"]) && $_POST["accion"] === "cerrar") {
    // Destruir sesión correctamente
    session_unset();
    session_destroy();

    // Confirmar al JS que se cerró correctamente
    echo "OK";
    exit;
} else {
    // Si alguien entra sin permiso, redirigir al login
    header("Location: login.php");
    exit;
}
