<?php
session_start();
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST['filtro']) && !empty($_POST['filtro'])) {
    $filtro = filter_var($_POST['filtro'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);

    $consulta = "SELECT id_libro, titulo_libro, autor_libro, 
                isbn_libro, categoria_libro, disponibilidad_libro,
                cantidad_libro FROM libros
                WHERE titulo_libro LIKE '%$filtro%'
                   OR autor_libro LIKE '%$filtro%'
                   OR isbn_libro LIKE '%$filtro%'
                   OR categoria_libro LIKE '%$filtro%'
                   OR disponibilidad_libro LIKE '%$filtro%'
    ";

    $resultado = $sql->efectuarConsulta($consulta);

    if ($resultado->num_rows > 0) {
        while ($fila = $resultado->fetch_assoc()) {
            echo "<tr>
                    <td>{$fila['id_libro']}</td>
                    <td>{$fila['titulo_libro']}</td>
                    <td>{$fila['autor_libro']}</td>
                    <td>{$fila['isbn_libro']}</td>
                    <td>{$fila['categoria_libro']}</td>
                    <td>{$fila['disponibilidad_libro']}</td>
                    <td>{$fila['cantidad_libro']}</td>
                </tr>";
        }
    } else {
        echo '<tr><td colspan="7" class="text-center text-muted">No se encontraron resultados.</td></tr>';
    }
}

$sql->desconectar();
