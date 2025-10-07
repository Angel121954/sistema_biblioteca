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

    $id_usuario = $_SESSION["id_usuario"];

    if ($resultado->num_rows > 0) {
        $tabla = "<table class='table table-bordered' id='tbl_libros' width='100%'
                    <tr>
                        <th>ID libro</th>
                        <th>Título</th>
                        <th>Autor</th>
                        <th>Categoría</th>
                        <th>Disponibilidad</th>";
        if ($id_usuario === "1") {
            $tabla .= "<th>Cantidad</th>
                        <th>ISBN</th>";
        }
        $tabla .= "</tr>";
        while ($fila = $resultado->fetch_assoc()) {
            $tabla .= "<tr>
                    <td>{$fila['id_libro']}</td>
                    <td>{$fila['titulo_libro']}</td>
                    <td>{$fila['autor_libro']}</td>
                    <td>{$fila['categoria_libro']}</td>
                    <td>{$fila['disponibilidad_libro']}</td>";
            if ($id_usuario === "1") {
                $tabla .= "<td>{$fila['cantidad_libro']}</td>
                        <td>{$fila['isbn_libro']}</td>";
            }
            $tabla .= "</tr>";
        }
        $tabla .= "</table>";
        echo $tabla;
    } else {
        echo '<tr><td colspan="7" class="text-center text-muted">No se encontraron resultados.</td></tr>';
    }
}

$sql->desconectar();
