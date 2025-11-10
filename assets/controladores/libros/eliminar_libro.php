<?php

require_once "../../modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();

if (isset($_POST["id_libro"]) && !empty($_POST["id_libro"])) {
    //* variables
    $id = filter_var($_POST["id_libro"], FILTER_SANITIZE_NUMBER_INT);

    $libro_result = $sql->efectuarConsulta("SELECT titulo_libro FROM libros
                                    WHERE id_libro = $id");

    if ($libro_result->num_rows > 0) {
        $libro = $libro_result->fetch_assoc();
        $titulo_libro = $libro['titulo_libro'];
        $libro_prestado = $sql->efectuarConsulta("SELECT titulo_libro FROM libros l
                                INNER JOIN reservas_has_libros rl ON rl.libros_id_libro = l.id_libro
                                INNER JOIN prestamos p ON p.fk_reserva_has_libro = rl.id_reserva_has_libro
                                WHERE l.id_libro = $id");

        if ($libro_prestado->num_rows > 0) {
            echo "No se puede inactivar el libro $titulo_libro ya que está asociado a un prestamo";
            $sql->desconectar();
            exit;
        }
        $inactivar = $sql->efectuarConsulta("UPDATE libros SET estado_libro = 'Inactivo'
                                                WHERE id_libro = $id");
        if ($inactivar) {
            echo "ok";
        } else {
            echo "No se pudo inactivar el libro";
        }
    } else {
        echo "No existe el libro";
    }
}
$sql->desconectar();
