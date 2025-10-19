<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Activo'");
echo "ok";
$sql->desconectar();
