<?php
require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST['filtro']) && !empty($_POST['filtro'])) {
    $filtro = $_POST['filtro'];

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
                    <td>
                        <button class='btn btn-warning btn-sm'><i class='fas fa-edit'></i></button>
                        <button class='btn btn-danger btn-sm'><i class='fas fa-trash-alt'></i></button>
                    </td>
                </tr>";
        }
    } else {
        echo '<tr><td colspan="8" class="text-center text-muted">No se encontraron resultados.</td></tr>';
    }
}

$sql->desconectar();
