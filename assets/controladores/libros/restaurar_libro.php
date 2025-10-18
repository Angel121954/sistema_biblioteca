<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$sql->efectuarConsulta("UPDATE libros SET disponibilidad_libro = 'Disponible'
                        WHERE disponibilidad_libro = 'Inactivo' AND
                        cantidad_libro > 0");
echo "ok";
$sql->desconectar();
