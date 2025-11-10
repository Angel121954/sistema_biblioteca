<?php
require_once "../../modelos/MySQL.php"; //* Libreria
require_once "../../libs/PHPMailer/src/PHPMailer.php";
require_once "../../libs/PHPMailer/src/SMTP.php";
require_once "../../libs/PHPMailer/src/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$sql = new MySQL();
$sql->conectar();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["id_reserva_has_libro"]) && !empty($_POST["id_reserva_has_libro"])) {
        //* variable
        $id_reserva_has_libro = intval($_POST["id_reserva_has_libro"]);

        if ($id_reserva_has_libro > 0) {
            $registrar = $sql->efectuarConsulta("INSERT INTO prestamos (fecha_prestamo, 
                                    fecha_devolucion, fk_reserva_has_libro, estado_prestamo)
                                    VALUES (NOW(), DATE_ADD(NOW(), INTERVAL 15 DAY), $id_reserva_has_libro,
                                    'Activo')");
            if ($registrar) {
                $sql->efectuarConsulta("UPDATE reservas_has_libros SET estado_has_reserva = 'Finalizada'
                                    WHERE id_reserva_has_libro = $id_reserva_has_libro");
            }
        }

        $usuarios_result = $sql->efectuarConsulta("SELECT * FROM usuarios u INNER JOIN
                                            reservas r ON r.usuarios_id_usuario = u.id_usuario
                                            INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva
                                            = r.id_reserva INNER JOIN prestamos p ON
                                            p.fk_reserva_has_libro = rl.id_reserva_has_libro
                                            WHERE rl.id_reserva_has_libro = $id_reserva_has_libro");

        if ($usuarios_result) {
            $usuario = $usuarios_result->fetch_assoc();

            $mail = new PHPMailer(true);

            try {
                $mail->isSMTP();
                $mail->Host       = 'smtp.gmail.com';
                $mail->SMTPAuth   = true;
                $mail->Username   = 'angeldavidagudelocuartas13@gmail.com';
                $mail->Password   = 'jhvm chgt zkyd xzlx';
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port       = 587;

                $mail->setFrom('angeldavidagudelocuartas13@gmail.com', 'contrasena_phpmailer_angel');
                $mail->addAddress($usuario["email_usuario"], $usuario["nombre_usuario"]);

                $mail->isHTML(true);
                $mail->Subject = 'Correo de prueba con PHPMailer';
                $mail->Body    = '<h3>¡Hola!</h3> Este es un mensaje de prueba.';
                $mail->AltBody = 'Este es un mensaje de prueba.';
                $mail->CharSet = 'UTF-8';

                $mail->send();
                echo "ok";
            } catch (Exception $e) {
                echo "Error al enviar el correo: {$mail->ErrorInfo}";
            }
        }
    }
}
$sql->desconectar();
