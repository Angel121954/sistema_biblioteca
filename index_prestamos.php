<?php

session_start();
require_once "assets/modelos/MySQL.php";
$sql = new MySQL();
$sql->conectar();
$id_usuario = intval($_SESSION["id_usuario"]);

//* consulta para poderlo imprimir en la tabla
$presta = $sql->efectuarConsulta("SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion,
                        r.fecha_reserva, l.titulo_libro, u.nombre_usuario
                        FROM prestamos p INNER JOIN reservas r 
                        ON p.reservas_id_reserva = r.id_reserva
                        INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
                        INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
                        INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
                        ORDER BY p.id_prestamo");

$prestamos_usuario = $sql->efectuarConsulta("SELECT p.id_prestamo, p.fecha_prestamo, p.fecha_devolucion,
                        r.fecha_reserva, l.titulo_libro, u.nombre_usuario
                        FROM prestamos p INNER JOIN reservas r 
                        ON p.reservas_id_reserva = r.id_reserva
                        INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
                        INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
                        INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
                        WHERE u.id_usuario = $id_usuario
                        ORDER BY p.id_prestamo");

$usuario_result = $sql->efectuarConsulta("SELECT * FROM usuarios WHERE id_usuario = $id_usuario");
$usuario = $usuario_result->fetch_assoc();

$reservas_result = $sql->efectuarConsulta("SELECT r.id_reserva, u.id_usuario, u.nombre_usuario,
                                        r.fecha_reserva, rl.cantidad_libros, l.titulo_libro FROM reservas r
                                        INNER JOIN usuarios u ON r.usuarios_id_usuario = u.id_usuario
                                        INNER JOIN reservas_has_libros rl ON rl.reservas_id_reserva = r.id_reserva
                                        INNER JOIN libros l ON rl.libros_id_libro = l.id_libro
                                        WHERE rl.estado_has_reserva = 'Aceptada'");

$reservas = [];

while ($valor = $reservas_result->fetch_assoc()) {
    $reservas[] = $valor;
}

//* convertir las fechas en formato JSON para poderlo leer en el public/js/prestamos/registro_prestamo.js
$reservas_json = json_encode($reservas, JSON_UNESCAPED_UNICODE);

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>Prestamos</title>

    <!--Font Awesome local-->
    <link href="assets/libs/awesome/css/all.min.css" rel="stylesheet" type="text/css">

    <!--SweetAlert local-->
    <link href="assets/libs/sweetAlert/sweetalert2.min.css" rel="stylesheet" type="text/css">

    <!--Bootstrap local-->
    <link href="assets/libs/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">

    <!-- Custom styles for this template-->
    <link href="assets/css/sb-admin-2.min.css" rel="stylesheet">

    <!--DataTable local-->
    <link href="assets/libs/datatables/datatables.min.css" rel="stylesheet">

    <!--Estilo personal-->
    <link href="assets/css/estilo_general.css" rel="stylesheet">

    <style>
        /* ======= Estilos generales ======= */
        .toast-notificacion {
            position: fixed;
            bottom: 25px;
            right: 25px;
            background: #1e1e2f;
            color: #fff;
            padding: 14px 20px;
            border-radius: 12px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: "Segoe UI", sans-serif;
            font-size: 15px;
            animation: slideIn 0.5s ease forwards;
            opacity: 0;
            z-index: 9999;
        }

        /* Ícono */
        .toast-notificacion i {
            font-size: 18px;
            color: #4ade80;
            /* verde moderno */
        }

        /* Botón de cerrar */
        .toast-notificacion button {
            background: none;
            border: none;
            color: #aaa;
            font-size: 18px;
            cursor: pointer;
            margin-left: auto;
            transition: 0.3s;
        }

        .toast-notificacion button:hover {
            color: #fff;
        }

        /* Animaciones */
        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
                opacity: 1;
            }
        }

        @keyframes slideOut {
            from {
                opacity: 1;
                transform: translateY(0);
            }

            to {
                opacity: 0;
                transform: translateY(40px);
            }
        }
    </style>
</head>

<body id="page-top">

    <!-- Page Wrapper -->
    <div id="wrapper">

        <!-- Sidebar -->
        <ul class="navbar-nav sidebar sidebar-dark accordion" id="accordionSidebar">

            <!-- Sidebar - Brand -->
            <?php switch ($_SESSION["tipo_usuario"]):
                case "1": ?>
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.php">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">Biblioteca</div>
                    </a>
                <?php break;
                default: ?>
                    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index_libros.php">
                        <div class="sidebar-brand-icon rotate-n-15">
                            <i class="fas fa-laugh-wink"></i>
                        </div>
                        <div class="sidebar-brand-text mx-3">Biblioteca</div>
                    </a>
            <?php endswitch; ?>

            <!-- Divider -->
            <hr class="sidebar-divider my-0">

            <!-- Nav Item - Dashboard -->
            <?php switch ($_SESSION["tipo_usuario"]):
                case "1": ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="index.php">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span></a>
                    </li>
                <?php break;
                default: ?>
                    <li class="nav-item active">
                        <a class="nav-link" href="index_libros.php">
                            <i class="fas fa-fw fa-tachometer-alt"></i>
                            <span>Dashboard</span></a>
                    </li>
            <?php endswitch; ?>

            <!-- Divider -->
            <hr class="sidebar-divider">

            <!-- Heading -->
            <div class="sidebar-heading">
                Funcionalidad
            </div>

            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                <!-- Enlace: usuarios -->
                <li class="nav-item">
                    <a class="nav-link" href="index.php">
                        <i class="bi bi-people-fill"></i>
                        <span>Usuarios</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Enlace: libros -->
            <li class="nav-item">
                <a class="nav-link" href="index_libros.php">
                    <i class="bi bi-journal-bookmark-fill"></i>
                    <span>Libros</span>
                </a>
            </li>

            <!-- Reservas -->
            <?php switch ($_SESSION["tipo_usuario"]):
                case "1": ?>
                    <li class="nav-item">
                        <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_reservas"
                            aria-expanded="true" aria-controls="collapsePages">
                            <i class="fas fa-fw fa-book-open"></i>
                            <span>Reservas</span>
                        </a>

                        <div id="menu_reservas" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <a class="collapse-item" href="index_reservas.php">Reservas</a>
                                <a class="collapse-item" href="assets/controladores/informes/historial_reserva.php">Historial de reservas</a>
                            </div>
                        </div>

                    </li>
                <?php break;
                default: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index_reservas.php">
                            <i class="fas fa-fw fa-book-open"></i>
                            <span>Reservas</span>
                        </a>
                    </li>
            <?php break;
            endswitch; ?>

            <!-- Enlace: prestamos -->
            <li class="nav-item">
                <a class="nav-link" href="index_prestamos.php">
                    <i class="bi bi-clock-history"></i>
                    <span>Prestamos</span>
                </a>
            </li>

            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                <!-- Select: Informes -->
                <li class="nav-item">
                    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#menu_informes"
                        aria-expanded="true" aria-controls="collapsePages">
                        <i class="fas fa-fw fa-chart-line"></i>
                        <span>Informes</span>
                    </a>
                    <div id="menu_informes" class="collapse" aria-labelledby="headingPages" data-parent="#accordionSidebar">
                        <div class="bg-white py-2 collapse-inner rounded">
                            <a class="collapse-item" href="assets/controladores/informes/libro_disponible.php">Libros disponibles</a>
                            <a class="collapse-item" href="assets/controladores/informes/libro_prestado.php">Libros prestados</a>
                            <a class="collapse-item" href="assets/controladores/informes/historial_prestamo.php">Historial prestamo</a>
                        </div>
                    </div>
                </li>

                <!-- Enlace: gráficos -->
                <li class="nav-item">
                    <a class="nav-link" href="index_graficos.php">
                        <i class="bi bi-bar-chart-fill"></i>
                        <span>Gráficos</span>
                    </a>
                </li>
            <?php endif; ?>

            <!-- Enlace: perfil -->
            <li class="nav-item">
                <a class="nav-link" href="#"
                    onclick="actualizarPerfil(
                    '<?php echo $usuario['id_usuario']; ?>',
                    '<?php echo $usuario['nombre_usuario']; ?>',
                    '<?php echo $usuario['apellido_usuario']; ?>',
                    '<?php echo $usuario['email_usuario']; ?>')">
                    <i class="fas fa-user-cog"></i>
                    <span>Perfil</span>
                </a>
            </li>

            <!-- Divider -->
            <hr class="sidebar-divider d-none d-md-block">

            <!-- Sidebar Toggler (Sidebar) -->
            <div class="text-center d-none d-md-inline">
                <button class="rounded-circle border-0" id="sidebarToggle"></button>
            </div>
        </ul>
        <!-- End of Sidebar -->

        <!-- Content Wrapper -->
        <div id="content-wrapper" class="d-flex flex-column">

            <!-- Main Content -->
            <div id="content">

                <!-- Topbar -->
                <nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">

                    <!-- Sidebar Toggle (Topbar) -->
                    <button id="sidebarToggleTop" class="btn btn-link d-md-none rounded-circle mr-3">
                        <i class="fa fa-bars"></i>
                    </button>

                    <!-- Topbar Navbar -->
                    <ul class="navbar-nav ml-auto">

                        <!-- Nav Item - Search Dropdown (Visible Only XS) -->
                        <li class="nav-item dropdown no-arrow d-sm-none">
                            <a class="nav-link dropdown-toggle" href="#" id="searchDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-search fa-fw"></i>
                            </a>
                            <!-- Parte buscador -->

                        </li>

                        <!-- Nav Item - Alerts -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="alertsDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-bell fa-fw"></i>
                                <!-- Counter - Alerts -->
                                <span class="badge badge-danger badge-counter">3+</span>
                            </a>
                            <!-- Dropdown - Alerts -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="alertsDropdown">
                                <h6 class="dropdown-header">
                                    Alerts Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-primary">
                                            <i class="fas fa-file-alt text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 12, 2019</div>
                                        <span class="font-weight-bold">A new monthly report is ready to download!</span>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-success">
                                            <i class="fas fa-donate text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 7, 2019</div>
                                        $290.29 has been deposited into your account!
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="mr-3">
                                        <div class="icon-circle bg-warning">
                                            <i class="fas fa-exclamation-triangle text-white"></i>
                                        </div>
                                    </div>
                                    <div>
                                        <div class="small text-gray-500">December 2, 2019</div>
                                        Spending Alert: We've noticed unusually high spending for your account.
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Show All Alerts</a>
                            </div>
                        </li>

                        <!-- Nav Item - Messages -->
                        <li class="nav-item dropdown no-arrow mx-1">
                            <a class="nav-link dropdown-toggle" href="#" id="messagesDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fas fa-envelope fa-fw"></i>
                                <!-- Counter - Messages -->
                                <span class="badge badge-danger badge-counter">7</span>
                            </a>
                            <!-- Dropdown - Messages -->
                            <div class="dropdown-list dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="messagesDropdown">
                                <h6 class="dropdown-header">
                                    Message Center
                                </h6>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_1.svg"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div class="font-weight-bold">
                                        <div class="text-truncate">Hi there! I am wondering if you can help me with a
                                            problem I've been having.</div>
                                        <div class="small text-gray-500">Emily Fowler · 58m</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_2.svg"
                                            alt="...">
                                        <div class="status-indicator"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">I have the photos that you ordered last month, how
                                            would you like them sent to you?</div>
                                        <div class="small text-gray-500">Jae Chun · 1d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="img/undraw_profile_3.svg"
                                            alt="...">
                                        <div class="status-indicator bg-warning"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Last month's report looks great, I am very happy with
                                            the progress so far, keep up the good work!</div>
                                        <div class="small text-gray-500">Morgan Alvarez · 2d</div>
                                    </div>
                                </a>
                                <a class="dropdown-item d-flex align-items-center" href="#">
                                    <div class="dropdown-list-image mr-3">
                                        <img class="rounded-circle" src="https://source.unsplash.com/Mv9hjnEUHR4/60x60"
                                            alt="...">
                                        <div class="status-indicator bg-success"></div>
                                    </div>
                                    <div>
                                        <div class="text-truncate">Am I a good boy? The reason I ask is because someone
                                            told me that people say this to all dogs, even if they aren't good...</div>
                                        <div class="small text-gray-500">Chicken the Dog · 2w</div>
                                    </div>
                                </a>
                                <a class="dropdown-item text-center small text-gray-500" href="#">Read More Messages</a>
                            </div>
                        </li>

                        <div class="topbar-divider d-none d-sm-block"></div>

                        <!-- Nav Item - User Information -->
                        <li class="nav-item dropdown no-arrow">
                            <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <span class="mr-2 d-none d-lg-inline text-gray-600 small"><?php echo $_SESSION["nombre_usuario"] . " " . $_SESSION["apellido_usuario"]; ?></span>
                                <img class="img-profile rounded-circle"
                                    src="img/undraw_profile.svg">
                            </a>
                            <!-- Dropdown - User Information -->
                            <div class="dropdown-menu dropdown-menu-right shadow animated--grow-in"
                                aria-labelledby="userDropdown">
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-user fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Profile
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-cogs fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Settings
                                </a>
                                <a class="dropdown-item" href="#">
                                    <i class="fas fa-list fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Activity Log
                                </a>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="#" data-toggle="modal" data-target="#logoutModal">
                                    <i class="fas fa-sign-out-alt fa-sm fa-fw mr-2 text-gray-400"></i>
                                    Logout
                                </a>
                            </div>
                        </li>

                    </ul>

                </nav>
                <!-- End of Topbar -->
                <!-- Content Wrapper -->
                <div id="content-wrapper" class="d-flex flex-column">

                    <!-- Main Content -->
                    <div id="content">

                        <!-- Begin Page Content -->
                        <div class="container-fluid">
                            <!-- Page Heading -->
                            <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
                                <div class="d-sm-flex align-items-center justify-content-between mb-4">
                                    <h1>Gestión de Prestamos</h1>
                                    <button id="btn_registro_prestamo" data-reserva="<?php echo htmlspecialchars($reservas_json, ENT_QUOTES, 'UTF-8'); ?>"><i class="bi bi-file-earmark-plus text-white-50 mx-1"></i>Realizar un prestamo</button>
                                </div>
                            <?php endif; ?>
                            <!-- DataTales Example -->
                            <div class="card shadow mb-4">
                                <div class="card-header py-3">
                                    <?php switch ($_SESSION["tipo_usuario"]):
                                        case "1": ?>
                                            <h6 class="m-0 font-weight-bold text-primary">Tabla de los prestamos</h6>
                                        <?php break;
                                        default: ?>
                                            <h6 class="m-0 font-weight-bold text-primary">Tabla de mis prestamos</h6>
                                    <?php break;
                                    endswitch; ?>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <?php if ($_SESSION["tipo_usuario"] === "1") { ?>
                                            <table class="table table-bordered tabla_dt" id="tbl_prestamos" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>ID prestamo</th>
                                                        <th>Fecha reserva</th>
                                                        <th>Fecha prestamo</th>
                                                        <th>Fecha devolución</th>
                                                        <th>Nombre usuario</th>
                                                        <th>Titulo libro</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($fila = $presta->fetch_assoc()): ?>
                                                        <tr>
                                                            <th><?php echo $fila["id_prestamo"]; ?></th>
                                                            <th><?php echo $fila["fecha_reserva"]; ?></th>
                                                            <th><?php echo $fila["fecha_prestamo"]; ?></th>
                                                            <th><?php echo $fila["fecha_devolucion"]; ?></th>
                                                            <th><?php echo $fila["nombre_usuario"]; ?></th>
                                                            <th><?php echo $fila["titulo_libro"]; ?></th>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        <?php } else { ?>
                                            <table class="table table-bordered" id="tbl_prestamos1" width="100%" cellspacing="0">
                                                <thead>
                                                    <tr>
                                                        <th>ID prestamo</th>
                                                        <th>Fecha prestamo</th>
                                                        <th>Fecha devolución</th>
                                                        <th>Nombre usuario</th>
                                                        <th>Titulo libro</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php while ($fila = $prestamos_usuario->fetch_assoc()): ?>
                                                        <tr>
                                                            <th><?php echo $fila["id_prestamo"]; ?></th>
                                                            <th><?php echo $fila["fecha_prestamo"]; ?></th>
                                                            <th><?php echo $fila["fecha_devolucion"]; ?></th>
                                                            <th><?php echo $fila["nombre_usuario"]; ?></th>
                                                            <th><?php echo $fila["titulo_libro"]; ?></th>
                                                        </tr>
                                                    <?php endwhile; ?>
                                                </tbody>
                                            </table>
                                        <?php } ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- /.container-fluid -->

                    </div>
                    <!-- End of Main Content -->

                </div>
                <!-- End of Content Wrapper -->
            </div>
            <!-- Footer -->
            <footer class="sticky-footer bg-white">
                <div class="container my-auto">
                    <div class="copyright text-center my-auto">
                        <span>Copyright &copy; Your Website 2021</span>
                    </div>
                </div>
            </footer>
            <!-- End of Footer -->

        </div>
        <!-- End of Content Wrapper -->

    </div>
    <!-- End of Page Wrapper -->

    <!-- Scroll to Top Button-->
    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    <!-- Logout Modal-->
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Ready to Leave?</h5>
                    <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">Select "Logout" below if you are ready to end your current session.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" type="button" data-dismiss="modal">Cancel</button>
                    <a class="btn btn-primary" href="login.html">Logout</a>
                </div>
            </div>
        </div>
    </div>
    <?php if ($_SESSION["tipo_usuario"] !== "1"): ?>
        <!-- ======= Contenedor del mensaje ======= -->
        <div id="toast" class="toast-notificacion" style="display:none;">
            <i class="fas fa-book-reader"></i>
            <span>Recuerde usuario <?= $_SESSION["nombre_usuario"]; ?> la fecha de devolución de libro correspondiente. Gracias</span>

            <button id="cerrarToast">&times;</button>
        </div>
    <?php endif; ?>
    <?php $sql->desconectar(); ?>

    <!-- ============================ -->
    <!-- 🔹 Librerías base y dependencias -->
    <!-- ============================ -->
    <script src="assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="assets/libs/jquery/jquery.min.js"></script>
    <script src="assets/libs/jquery-easing/jquery.easing.min.js"></script>

    <!-- ============================ -->
    <!-- 🔹 Librerías externas -->
    <!-- ============================ -->
    <script src="assets/libs/awesome/js/all.min.js"></script>
    <script src="assets/libs/sweetAlert/sweetalert2.all.min.js"></script>

    <!-- ============================ -->
    <!-- 🔹 Scripts principales -->
    <!-- ============================ -->
    <script src="assets/js/sb-admin-2.min.js"></script>

    <!-- ============================ -->
    <!-- 🔹 Scripts personalizados -->
    <!-- ============================ -->
    <?php if ($_SESSION["tipo_usuario"] === "1"): ?>
        <script src="assets/public/js/prestamos/registro_prestamo.js"></script>
    <?php endif; ?>
    <script src="assets/public/js/usuarios/actualizar_perfil.js"></script>

    <!--Funcionalidad del menú despegable-->
    <script src="assets/funcionalidad/app.js"></script>

    <!--DataTables local-->
    <script src="assets/libs/datatables/datatables.min.js"></script>
    <script src="assets/funcionalidad/tablas.js"></script>
    <?php if ($_SESSION["tipo_usuario"] !== "1"): ?>
        <script>
            document.addEventListener("DOMContentLoaded", () => {
                const toast = document.getElementById("toast");
                const btnCerrar = document.getElementById("cerrarToast");

                // Mostrar mensaje
                toast.style.display = "flex";
                setTimeout(() => toast.style.opacity = "1", 10);

                // Ocultar después de 5s
                setTimeout(() => cerrarToast(), 5000);

                // Cerrar manualmente
                btnCerrar.addEventListener("click", cerrarToast);

                function cerrarToast() {
                    toast.style.animation = "slideOut 0.5s ease forwards";
                    setTimeout(() => toast.style.display = "none", 500);
                }
            });
        </script>
    <?php endif; ?>
</body>

</html>